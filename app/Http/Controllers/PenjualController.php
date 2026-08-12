<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjualController extends Controller
{
    // ================= DASHBOARD PENJUAL =================
    public function dashboard()
    {
        $user = Auth::user()->load('membership', 'role');
        $membership = $user->membership;

        // Nama paket membership
        $membershipName = $membership->name ?? 'Gratis';
        $lowerName = strtolower($membershipName);

        // Tentukan limit kuota produk berdasarkan paket
        $maxProducts = $membership ? ($membership->product_limit ?? 5) : 5;
        
        // Total produk yang dimiliki penjual
        $totalProduk = Product::where('seller_id', $user->id_user)->count();

        // Cek apakah kuota upload produk sudah penuh
        $batasTercapai = $totalProduk >= $maxProducts;

        // Cek apakah paket mendukung fitur iklan (Khusus Diamond / Flag allow_ads)
        $bisaIklan = $lowerName === 'diamond' || (isset($membership->allow_ads) && $membership->allow_ads);

        // Map styling dinamis berdasarkan nama paket
        if (str_contains($lowerName, 'diamond')) {
            $bg = 'bg-gradient-to-r from-sky-500/10 via-indigo-500/10 to-purple-500/10';
            $border = 'border-sky-300/40';
            $warna = 'sky-500';
            $icon = 'fa-gem';
        } elseif (str_contains($lowerName, 'gold') || str_contains($lowerName, 'platinum')) {
            $bg = 'bg-amber-500/10';
            $border = 'border-amber-300/40';
            $warna = 'amber-500';
            $icon = 'fa-crown';
        } elseif (str_contains($lowerName, 'silver')) {
            $bg = 'bg-slate-400/10';
            $border = 'border-slate-300/40';
            $warna = 'slate-500';
            $icon = 'fa-medal';
        } else {
            $bg = 'bg-blue-500/10';
            $border = 'border-blue-200';
            $warna = 'blue-600';
            $icon = 'fa-box-open';
        }

        // Struct Array $p LENGKAP untuk kebutuhan View Blade Penjual
        $p = [
            'label'  => $membershipName,
            'limit'  => $maxProducts,
            'total'  => $totalProduk,
            'iklan'  => $bisaIklan,
            'bg'     => $bg,       // FIXED: Mencegah 'Undefined array key "bg"'
            'border' => $border,   // FIXED: Mencegah 'Undefined array key "border"'
            'warna'  => $warna,    // FIXED: Mencegah 'Undefined array key "warna"'
            'icon'   => $icon,     // FIXED: Mencegah 'Undefined array key "icon"'
            'batas'  => $maxProducts,
        ];

        // Statistik Penjual
        $totalPesanan = OrderItem::whereHas('product', function ($q) use ($user) {
            $q->where('seller_id', $user->id_user);
        })->count();

        $totalPendapatan = OrderItem::whereHas('product', function ($q) use ($user) {
            $q->where('seller_id', $user->id_user);
        })->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->sum('subtotal');

        $products = Product::where('seller_id', $user->id_user)
            ->latest('id_product')
            ->take(5)
            ->get();

        return view('penjual.dashboard', compact(
            'batasTercapai',
            'p',
            'totalProduk',
            'totalPesanan',
            'totalPendapatan',
            'products'
        ));
    }
}

