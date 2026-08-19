<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    /**
     * Menampilkan daftar riwayat laporan milik user yang sedang login (Pembeli)
     */
    public function index()
    {
        $reports = Report::with(['product', 'reportedUser', 'user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('pembeli.laporan-saya', compact('reports'));
    }

    /**
     * Menampilkan halaman/form untuk membuat laporan baru (Mengarah ke pembeli.laporkan)
     */
    public function create()
    {
        // Mengambil daftar produk beserta seller untuk dropdown
        $products = Product::with('seller')->get();

        // Mengambil daftar pengguna/penjual lain (kecuali user yang sedang login)
        $users = User::with('role')
            ->where('id_user', '!=', Auth::id())
            ->get();

        return view('pembeli.laporkan', compact('products', 'users'));
    }

    /**
     * Menyimpan data laporan baru ke database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'target_type'      => 'required|in:produk,pengguna,lainnya',
            'product_id'       => 'nullable|required_if:target_type,produk|exists:products,id_product',
            'reported_user_id' => 'nullable|required_if:target_type,pengguna|exists:users,id_user',
            'reason'           => 'required|string|max:255',
            'description'      => 'nullable|string',
        ]);

        $productId = null;
        $reportedUserId = null;

        if ($validated['target_type'] === 'produk') {
            $productId = $validated['product_id'];
            // Opsional: otomatis set reported_user_id berdasarkan pemilik produk
            $product = Product::find($productId);
            if ($product && isset($product->user_id)) {
                $reportedUserId = $product->user_id;
            }
        } elseif ($validated['target_type'] === 'pengguna') {
            $reportedUserId = $validated['reported_user_id'];
        }

        Report::create([
            'user_id'          => Auth::id(),
            'product_id'       => $productId,
            'reported_user_id' => $reportedUserId,
            'reason'           => $validated['reason'],
            'description'      => $validated['description'] ?? null,
            'status'           => 'pending',
        ]);

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dikirim! Tim admin akan meninjau laporan kamu.');
    }

    /**
     * Method khusus Admin untuk melihat SELURUH laporan dari semua user
     */
    public function adminIndex()
    {
        $reports = Report::with(['user', 'product', 'reportedUser'])
            ->latest()
            ->paginate(15);

        return view('admin.pelanggaran', compact('reports'));
    }
}
