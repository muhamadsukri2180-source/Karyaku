<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * ================================================================
     * RIWAYAT LAPORAN PEMBELI
     * ================================================================
     *
     * Menampilkan laporan milik user yang sedang login.
     * Menggunakan pagination agar RAM server tetap hemat.
     */
    public function index()
    {
        $userId = Auth::id();

        $reports = Report::with([
                'product:id_product,title,seller_id',
                'reportedUser:id_user,name',
            ])
            ->where('user_id', $userId)
            ->latest('id_report') // Menggunakan primary key id_report
            ->paginate(10)
            ->withQueryString();

        return view(
            'pembeli.laporan-saya',
            compact('reports')
        );
    }

    /**
     * ================================================================
     * FORM BUAT LAPORAN
     * ================================================================
     */
    public function create()
    {
        $userId = Auth::id();

        $products = Product::select(
                'id_product',
                'title',
                'seller_id'
            )
            ->with([
                'seller:id_user,name'
            ])
            ->where('status', 'active')
            ->orderBy('title')
            ->get();

        $users = User::select(
                'id_user',
                'name',
                'id_role'
            )
            ->with([
                'role:id_role,role_name'
            ])
            ->where('id_user', '!=', $userId)
            ->orderBy('name')
            ->get();

        return view(
            'pembeli.laporkan',
            compact('products', 'users')
        );
    }

    /**
     * ================================================================
     * SIMPAN LAPORAN
     * ================================================================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'target_type' => [
                'required',
                'in:produk,pengguna,lainnya',
            ],

            'product_id' => [
                'nullable',
                'required_if:target_type,produk',
                'integer',
                'exists:products,id_product',
            ],

            'reported_user_id' => [
                'nullable',
                'required_if:target_type,pengguna',
                'integer',
                'exists:users,id_user',
            ],

            'reason' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $userId = Auth::id();

        $productId = null;
        $reportedUserId = null;

        if ($validated['target_type'] === 'produk') {
            $productId = $validated['product_id'];

            $product = Product::select(
                    'id_product',
                    'seller_id'
                )
                ->where('id_product', $productId)
                ->first();

            if (!$product) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Produk yang ingin dilaporkan tidak ditemukan.'
                    );
            }

            $reportedUserId = $product->seller_id;

            if ($reportedUserId == $userId) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Kamu tidak dapat melaporkan produk milik akun sendiri.'
                    );
            }
        } elseif ($validated['target_type'] === 'pengguna') {
            $reportedUserId = $validated['reported_user_id'];

            if ($reportedUserId == $userId) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Kamu tidak dapat melaporkan akun sendiri.'
                    );
            }
        }

        DB::transaction(function () use (
            $userId,
            $productId,
            $reportedUserId,
            $validated
        ) {
            Report::create([
                'user_id'          => $userId,
                'product_id'       => $productId,
                'reported_user_id' => $reportedUserId,
                'reason'           => $validated['reason'],
                'description'      => $validated['description'] ?? null,
                'status'           => 'pending',
            ]);
        });

        return redirect()
            ->route('reports.index')
            ->with(
                'success',
                'Laporan berhasil dikirim! Tim admin akan meninjau laporan kamu.'
            );
    }

    /**
     * ================================================================
     * ADMIN - SEMUA LAPORAN
     * ================================================================
     */
    public function adminIndex()
    {
        $reports = Report::with([
                'user:id_user,name,email',
                'product:id_product,title,seller_id',
                'reportedUser:id_user,name,email',
            ])
            ->latest('id_report') // Menggunakan primary key id_report
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.pelanggaran',
            compact('reports')
        );
    }
}
