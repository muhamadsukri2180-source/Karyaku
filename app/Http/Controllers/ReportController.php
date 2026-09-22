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
    public function index()
    {
        $userId = Auth::id();

        $products = Product::select('id_product', 'title', 'seller_id')
            ->with(['seller:id_user,name'])
            ->where('status', 'active')
            ->where('seller_id', '!=', $userId)
            ->orderBy('title')
            ->get();

        $users = User::select('id_user', 'name', 'id_role')
            ->with(['role:id_role,role_name'])
            ->where('id_user', '!=', $userId)
            ->whereHas('role', function ($q) {
                $q->whereIn('role_name', ['penjual', 'pembeli']);
            })
            ->orderBy('name')
            ->get();

        $reports = Report::with([
                'product:id_product,title,seller_id',
                'reportedUser:id_user,name',
                'reviewer:id_user,name',
            ])
            ->where('user_id', $userId)
            ->latest('id_report')
            ->paginate(10, ['*'], 'page_saya')
            ->withQueryString();

        $incomingReports = Report::with([
                'product:id_product,title,seller_id',
                'reporter:id_user,name',
                'reviewer:id_user,name',
            ])
            ->where('reported_user_id', $userId)
            ->latest('id_report')
            ->paginate(10, ['*'], 'page_masuk')
            ->withQueryString();

        $pendingIncomingCount = Report::where('reported_user_id', $userId)
            ->where('status', 'pending')
            ->count();

        return view('pembeli.laporan-saya', compact('products', 'users', 'reports', 'incomingReports', 'pendingIncomingCount'));
    }

    public function create()
    {
        return $this->index();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'target_type'      => 'required|in:produk,pengguna,lainnya',
            'product_id'       => 'nullable|required_if:target_type,produk|integer|exists:products,id_product',
            'reported_user_id' => 'nullable|required_if:target_type,pengguna|integer|exists:users,id_user',
            'reason'           => 'required|string|max:255',
            'description'      => 'nullable|string|max:2000',
        ]);

        $userId = Auth::id();
        $productId = null;
        $reportedUserId = null;

        if ($validated['target_type'] === 'produk') {
            $productId = $validated['product_id'];
            $product = Product::select('id_product', 'seller_id')->where('id_product', $productId)->first();

            if (!$product) {
                return back()->withInput()->with('error', 'Produk yang ingin dilaporkan tidak ditemukan.');
            }

            $reportedUserId = $product->seller_id;
            if ($reportedUserId == $userId) {
                return back()->withInput()->with('error', 'Kamu tidak dapat melaporkan produk milik akun sendiri.');
            }
        } elseif ($validated['target_type'] === 'pengguna') {
            $reportedUserId = $validated['reported_user_id'];
            if ($reportedUserId == $userId) {
                return back()->withInput()->with('error', 'Kamu tidak dapat melaporkan akun sendiri.');
            }

            $targetUser = User::with('role')->find($reportedUserId);
            if ($targetUser && !in_array(strtolower($targetUser->role->role_name ?? ''), ['penjual', 'pembeli'])) {
                return back()->withInput()->with('error', 'Akun pengurus platform (Admin, Verifikator, Customer Service) tidak dapat dilaporkan. Hanya akun Penjual dan Pembeli yang dapat dilaporkan.');
            }
        }

        DB::transaction(fn () => Report::create([
            'user_id'          => $userId,
            'product_id'       => $productId,
            'reported_user_id' => $reportedUserId,
            'reason'           => $validated['reason'],
            'description'      => $validated['description'] ?? null,
            'status'           => 'pending',
        ]));

        return redirect()->back()
            ->with('success', 'Laporan berhasil dikirim! Tim admin akan meninjau laporan kamu.');
    }

    public function adminIndex()
    {
        $reports = Report::with([
                'user:id_user,name,email',
                'product:id_product,title,seller_id',
                'reportedUser:id_user,name,email',
            ])
            ->latest('id_report')
            ->paginate(15)
            ->withQueryString();

        return view('admin.pelanggaran', compact('reports'));
    }
}