<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Membership;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PenjualController extends Controller
{
    // ================= 1. DASHBOARD PENJUAL =================
    public function dashboard()
    {
        $user = Auth::user()->load('membership', 'role');
        $membership = $user->membership;
        $membershipName = $membership->name ?? 'Gratis / Standar';
        
        $maxProducts = $user->getMaxUploadLimit();
        $totalProduk = Product::where('seller_id', $user->id_user)->count();
        $quotaSisa = max(0, $maxProducts - $totalProduk);
        $batasTercapai = $totalProduk >= $maxProducts;
        $bisaIklan = $user->canUseAds();
        
        $isExpired = $user->membership_expires_at ? $user->membership_expires_at->isPast() : false;
        $remainingDays = $user->remainingDays;
        $countdown = $user->membershipCountdown;
        $showWarning = $user->needsMembershipRenewalWarning(3);

        if ($showWarning) {
            $today = now()->format('Y-m-d');
            $hasNotifiedToday = Notification::where('user_id', $user->id_user)
                ->where('name', 'LIKE', '%Peringatan Perpanjangan%')
                ->whereDate('created_at', $today)
                ->exists();

            if (!$hasNotifiedToday) {
                Notification::create([
                    'user_id'     => $user->id_user,
                    'name'        => '⚠️ Peringatan Perpanjangan Paket',
                    'description' => "Masa aktif paket membership {$membershipName} Anda akan segera berakhir dalam {$remainingDays} hari lagi. Segera perpanjang paket Anda agar kuota dan fitur toko tidak terbatasi.",
                    'is_read'     => false,
                ]);
            }
        }

        $totalPesanan = OrderItem::whereHas('product', fn($q) => $q->where('seller_id', $user->id_user))->count();
        $totalPendapatan = OrderItem::whereHas('product', fn($q) => $q->where('seller_id', $user->id_user))
            ->whereHas('order', fn($q) => $q->where('payment_status', 'paid'))->sum('subtotal');

        $statsProduct = Product::where('seller_id', $user->id_user)
            ->selectRaw("
                SUM(sold_count) as total_terjual,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as produk_aktif,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as produk_pending,
                SUM(CASE WHEN status IN ('rejected', 'inactive', 'blocked') THEN 1 ELSE 0 END) as produk_buked
            ")->first();

        $totalTerjual = (int) ($statsProduct->total_terjual ?? 0);
        $produkAktif = (int) ($statsProduct->produk_aktif ?? 0);
        $produkPending = (int) ($statsProduct->produk_pending ?? 0);
        $produkBuked = (int) ($statsProduct->produk_buked ?? 0);

        $recentProducts = Product::where('seller_id', $user->id_user)->with('category')->latest('id_product')->take(5)->get();
        $recentOrders = OrderItem::with(['product', 'order.buyer'])->whereHas('product', fn($q) => $q->where('seller_id', $user->id_user))->latest('id_order_item')->take(5)->get();

        return view('penjual.dashboard', compact(
            'user', 'membership', 'membershipName', 'maxProducts', 'totalProduk', 'quotaSisa', 'batasTercapai', 
            'bisaIklan', 'isExpired', 'remainingDays', 'countdown', 'showWarning', 'totalPesanan', 'totalPendapatan', 
            'totalTerjual', 'produkAktif', 'produkPending', 'produkBuked', 'recentProducts', 'recentOrders'
        ));
    }

    // ================= 2. MANAJEMEN PRODUK (DAFTAR) =================
    public function produkIndex(Request $request)
    {
        $user = Auth::user();
        $tab = $request->get('tab', 'semua');
        $q = $request->get('q');

        $query = Product::with(['category', 'reviews'])->where('seller_id', $user->id_user);

        if ($q) {
            $query->where('title', 'like', '%' . $q . '%');
        }

        if ($tab === 'aktif') {
            $query->where('status', 'active');
        } elseif ($tab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($tab === 'diblokir') {
            $query->whereIn('status', ['rejected', 'inactive', 'blocked']);
        }

        $products = $query->latest('id_product')->paginate(10)->withQueryString();

        $counts = [
            'semua'    => Product::where('seller_id', $user->id_user)->count(),
            'aktif'    => Product::where('seller_id', $user->id_user)->where('status', 'active')->count(),
            'pending'  => Product::where('seller_id', $user->id_user)->where('status', 'pending')->count(),
            'diblokir' => Product::where('seller_id', $user->id_user)->whereIn('status', ['rejected', 'inactive', 'blocked'])->count(),
        ];

        $canUpload = $user->canUploadProduct();
        $maxUpload = $user->getMaxUploadLimit();

        return view('penjual.produk.index', compact('products', 'tab', 'counts', 'canUpload', 'maxUpload'));
    }

    // ================= 3. TAMBAH PRODUK =================
    public function produkCreate()
    {
        $user = Auth::user();

        if (!$user->canUploadProduct()) {
            return redirect()->route('penjual.produk.index')
                ->with('error', 'Kuota upload produk Anda sudah penuh (' . $user->getMaxUploadLimit() . ' produk). Silakan tingkatkan paket membership Anda.');
        }

        $categories = Category::where('status', 'aktif')->orderBy('name')->get();
        return view('penjual.produk.create', compact('categories', 'user'));
    }

    public function produkStore(Request $request)
    {
        $user = Auth::user();

        if (!$user->canUploadProduct()) {
            return redirect()->route('penjual.produk.index')
                ->with('error', 'Gagal mengunggah. Batas kuota upload produk paket Anda telah tercapai.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id_category',
            'price'       => 'required|numeric|min:1000',
            'stock'       => 'required|integer|min:1',
            'description' => 'required|string',
            'thumbnail'   => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
            'images'      => 'nullable|array|max:5',
            'images.*'    => 'image|mimes:jpeg,png,jpg,webp|max:4096',
            'video'       => 'nullable|file|mimes:mp4,webm,ogg,mov,avi|max:51200',
            'file'        => 'required|file|max:51200',
        ]);

        $thumbPath = $request->hasFile('thumbnail') ? $request->file('thumbnail')->store('products/thumbnails', 'public') : null;
        
        $galleryPaths = [];
        if ($thumbPath) {
            $galleryPaths[] = $thumbPath;
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imgFile) {
                if (count($galleryPaths) < 5) {
                    $galleryPaths[] = $imgFile->store('products/gallery', 'public');
                }
            }
        }

        $videoPath = $request->hasFile('video') ? $request->file('video')->store('products/videos', 'public') : null;
        $filePath = $request->hasFile('file') ? $request->file('file')->store('products/files', 'public') : null;

        Product::create([
            'seller_id'      => $user->id_user,
            'category_id'    => $validated['category_id'],
            'title'          => $validated['title'],
            'description'    => $validated['description'],
            'price'          => $validated['price'],
            'stock'          => $validated['stock'],
            'thumbnail'      => $thumbPath,
            'images'         => $galleryPaths,
            'video'          => $videoPath,
            'file'           => $filePath,
            'status'         => 'pending',
            'rejection_note' => null,
            'is_promoted'    => false,
            'view_count'     => 0,
            'sold_count'     => 0,
        ]);

        return redirect()->route('penjual.produk.index')
            ->with('success', 'Produk berhasil diunggah! Saat ini sedang dalam tahap verifikasi oleh Tim Admin.');
    }

    // ================= 4. EDIT & UPDATE PRODUK =================
    public function produkEdit($id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        $categories = Category::where('status', 'aktif')->orderBy('name')->get();

        return view('penjual.produk.edit', compact('product', 'categories'));
    }

    public function produkUpdate(Request $request, $id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id_category',
            'price'       => 'required|numeric|min:1000',
            'stock'       => 'required|integer|min:1',
            'description' => 'required|string',
            'thumbnail'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'images'      => 'nullable|array|max:5',
            'images.*'    => 'image|mimes:jpeg,png,jpg,webp|max:4096',
            'video'       => 'nullable|file|mimes:mp4,webm,ogg,mov,avi|max:51200',
            'file'        => 'nullable|file|max:51200',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $product->thumbnail = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        $galleryPaths = is_array($product->images) ? $product->images : [];
        if ($product->thumbnail && !in_array($product->thumbnail, $galleryPaths)) {
            array_unshift($galleryPaths, $product->thumbnail);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imgFile) {
                if (count($galleryPaths) < 5) {
                    $galleryPaths[] = $imgFile->store('products/gallery', 'public');
                }
            }
        }
        $product->images = array_slice($galleryPaths, 0, 5);

        if ($request->hasFile('video')) {
            if ($product->video && Storage::disk('public')->exists($product->video)) {
                Storage::disk('public')->delete($product->video);
            }
            $product->video = $request->file('video')->store('products/videos', 'public');
        }

        if ($request->hasFile('file')) {
            if ($product->file && Storage::disk('public')->exists($product->file)) {
                Storage::disk('public')->delete($product->file);
            }
            $product->file = $request->file('file')->store('products/files', 'public');
        }

        $product->fill($validated);
        if (in_array($product->status, ['rejected', 'inactive', 'blocked'])) {
            $product->status = 'pending';
            $product->rejection_note = null;
        }
        $product->save();

        return redirect()->route('penjual.produk.index')->with('success', 'Data produk berhasil diperbarui.');
    }

    // ================= 5. HAPUS PRODUK =================
    public function produkDestroy($id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);

        if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
            Storage::disk('public')->delete($product->thumbnail);
        }
        if ($product->file && Storage::disk('public')->exists($product->file)) {
            Storage::disk('public')->delete($product->file);
        }

        $product->delete();
        return redirect()->route('penjual.produk.index')->with('success', 'Produk berhasil dihapus.');
    }

    // ================= 6. FITUR IKLAN & PROMOSI PRODUK =================
    public function iklanIndex()
    {
        $user = Auth::user();
        $bisaIklan = $user->canUseAds();
        $activeProducts = Product::where('seller_id', $user->id_user)->where('status', 'active')->orderBy('title')->get();
        $promotedProducts = Product::where('seller_id', $user->id_user)->where('is_promoted', true)->latest('promoted_until')->get();

        return view('penjual.iklan.index', compact('user', 'bisaIklan', 'activeProducts', 'promotedProducts'));
    }

    public function iklanStore(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user->canUseAds()) {
            return redirect()->route('penjual.membership.index')->with('error', 'Fitur pasang iklan hanya tersedia untuk paket Gold & Diamond.');
        }

        $product = Product::where('seller_id', $user->id_user)->findOrFail($id);
        if ($product->status !== 'active') {
            return back()->with('error', 'Hanya produk berstatus aktif yang dapat diiklankan.');
        }

        $product->update([
            'is_promoted' => true,
            'promoted_until' => now()->addDays(7)
        ]);

        return back()->with('success', 'Iklan produk "' . $product->title . '" berhasil diaktifkan selama 7 hari!');
    }

    public function iklanCancel($id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        $product->update([
            'is_promoted' => false,
            'promoted_until' => null
        ]);

        return back()->with('success', 'Promosi iklan untuk produk ini telah dinonaktifkan.');
    }

    // ================= 7. MEMBERSHIP PENJUAL & PEMBELIAN =================
    public function membershipIndex()
    {
        $user = Auth::user()->load('membership');
        $memberships = Membership::orderBy('price', 'asc')->get();
        $currentMembership = $user->membership;
        $maxUpload = $user->getMaxUploadLimit();
        $totalUploaded = Product::where('seller_id', $user->id_user)->count();
        $remainingDays = $user->remainingDays;
        $countdown = $user->membershipCountdown;
        $showWarning = $user->needsMembershipRenewalWarning(3);
        $isExpired = $user->membership_expires_at ? $user->membership_expires_at->isPast() : false;

        return view('penjual.membership.index', compact(
            'user', 'memberships', 'currentMembership', 'maxUpload', 'totalUploaded', 'remainingDays', 'countdown', 'showWarning', 'isExpired'
        ));
    }

    public function membershipPurchase(Request $request, $id)
    {
        $membership = Membership::findOrFail($id);
        $user = Auth::user();
        $durationDays = $membership->duration_days ?? 30;

        $newExpiresAt = ($user->membership_expires_at && $user->membership_expires_at->isFuture()) 
            ? $user->membership_expires_at->copy()->addDays($durationDays) 
            : now()->addDays($durationDays);

        $user->update([
            'id_membership' => $membership->id_membership,
            'membership_expires_at' => $newExpiresAt
        ]);

        Notification::create([
            'user_id'     => $user->id_user,
            'name'        => '💎 Paket Membership Aktif',
            'description' => 'Paket ' . $membership->name . ' Anda telah aktif hingga ' . $user->membership_expires_at->translatedFormat('d F Y H:i') . '.',
            'is_read'     => false,
        ]);

        return redirect()->route('penjual.membership.index')->with('success', 'Paket membership berhasil diaktifkan/diperpanjang.');
    }

    // ================= 8. PESANAN MASUK (PENJUALAN) =================
    public function pesananIndex(Request $request)
    {
        $user = Auth::user();
        $tab = $request->get('tab', 'semua');

        $query = OrderItem::with(['product', 'order.buyer'])->whereHas('product', fn($q) => $q->where('seller_id', $user->id_user));

        if ($tab === 'diproses') {
            $query->whereHas('order', fn($q) => $q->whereIn('status', ['diproses', 'pending']));
        } elseif ($tab === 'selesai') {
            $query->whereHas('order', fn($q) => $q->where('status', 'selesai'));
        }

        $orderItems = $query->latest('id_order_item')->paginate(10)->withQueryString();

        $counts = [
            'semua'    => OrderItem::whereHas('product', fn($q) => $q->where('seller_id', $user->id_user))->count(),
            'diproses' => OrderItem::whereHas('product', fn($q) => $q->where('seller_id', $user->id_user))
                ->whereHas('order', fn($q) => $q->whereIn('status', ['diproses', 'pending']))->count(),
            'selesai'  => OrderItem::whereHas('product', fn($q) => $q->where('seller_id', $user->id_user))
                ->whereHas('order', fn($q) => $q->where('status', 'selesai'))->count(),
        ];

        return view('penjual.pesanan.index', compact('orderItems', 'tab', 'counts'));
    }

    public function pesananDetail($id)
    {
        $orderItem = OrderItem::with(['product.category', 'order.buyer'])
            ->whereHas('product', fn($q) => $q->where('seller_id', Auth::id()))
            ->findOrFail($id);

        return view('penjual.pesanan.detail', compact('orderItem'));
    }

    public function pesananKonfirmasi($id)
    {
        $orderItem = OrderItem::with(['product', 'order'])
            ->whereHas('product', fn($q) => $q->where('seller_id', Auth::id()))
            ->findOrFail($id);

        if ($order = $orderItem->order) {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'selesai'
            ]);

            Notification::create([
                'user_id'     => $order->buyer_id,
                'name'        => '✅ Pesanan Dikonfirmasi Penjual',
                'description' => 'Pesanan #' . $order->id_order . ' telah dikonfirmasi oleh penjual. Anda kini dapat mengunduh berkasnya!',
                'is_read'     => false,
            ]);
        }

        return back()->with('success', 'Pesanan pembelian berhasil dikonfirmasi dan selesai.');
    }

    // ================= 9. KEUANGAN & PENARIKAN SALDO =================
    public function keuanganIndex()
    {
        $user = Auth::user();
        $totalPendapatan = OrderItem::whereHas('product', fn($q) => $q->where('seller_id', $user->id_user))
            ->whereHas('order', fn($q) => $q->where('payment_status', 'paid'))->sum('subtotal');

        $totalDitarik = Withdrawal::where('user_id', $user->id_user)->whereIn('status', ['completed', 'pending'])->sum('amount');
        $saldoTersedia = max(0, $totalPendapatan - $totalDitarik);
        $withdrawals = Withdrawal::where('user_id', $user->id_user)->latest('id_withdrawal')->paginate(10);

        return view('penjual.keuangan.index', compact('totalPendapatan', 'totalDitarik', 'saldoTersedia', 'withdrawals'));
    }

    public function penarikanStore(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'bank_name'           => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name'   => 'required|string|max:100',
            'amount'              => 'required|numeric|min:20000',
        ]);

        $totalPendapatan = OrderItem::whereHas('product', fn($q) => $q->where('seller_id', $user->id_user))
            ->whereHas('order', fn($q) => $q->where('payment_status', 'paid'))->sum('subtotal');
        $totalDitarik = Withdrawal::where('user_id', $user->id_user)->whereIn('status', ['completed', 'pending'])->sum('amount');
        $saldoTersedia = max(0, $totalPendapatan - $totalDitarik);

        if ($validated['amount'] > $saldoTersedia) {
            return back()->with('error', 'Saldo tidak mencukupi untuk melakukan penarikan.');
        }

        Withdrawal::create([
            'user_id'             => $user->id_user,
            'bank_name'           => $validated['bank_name'],
            'bank_account_number' => $validated['bank_account_number'],
            'bank_account_name'   => $validated['bank_account_name'],
            'amount'              => $validated['amount'],
            'status'              => 'pending',
            'notes'               => 'Pengajuan penarikan dana oleh penjual',
        ]);

        return back()->with('success', 'Permintaan penarikan saldo berhasil diajukan.');
    }
}