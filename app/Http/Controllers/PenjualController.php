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
use Illuminate\Support\Facades\DB;
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
        $remainingDays = $user->remaining_days;

        // Statistik Penjualan
        $totalPesanan = OrderItem::whereHas('product', function ($q) use ($user) {
            $q->where('seller_id', $user->id_user);
        })->count();

        $totalPendapatan = OrderItem::whereHas('product', function ($q) use ($user) {
            $q->where('seller_id', $user->id_user);
        })->whereHas('order', function ($q) {
            $q->where('payment_status', 'paid');
        })->sum('subtotal');

        $statsProduct = Product::where('seller_id', $user->id_user)
            ->selectRaw("
                SUM(sold_count) as total_terjual,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as produk_aktif,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as produk_pending,
                SUM(CASE WHEN status IN ('rejected', 'inactive', 'blocked') THEN 1 ELSE 0 END) as produk_buked
            ")
            ->first();

        $totalTerjual = (int) ($statsProduct->total_terjual ?? 0);
        $produkAktif = (int) ($statsProduct->produk_aktif ?? 0);
        $produkPending = (int) ($statsProduct->produk_pending ?? 0);
        $produkBuked = (int) ($statsProduct->produk_buked ?? 0);

        $recentProducts = Product::where('seller_id', $user->id_user)
            ->with('category')
            ->latest('id_product')
            ->take(5)
            ->get();

        $recentOrders = OrderItem::with(['product', 'order.buyer'])
            ->whereHas('product', function ($q) use ($user) {
                $q->where('seller_id', $user->id_user);
            })
            ->latest('id_order_item')
            ->take(5)
            ->get();

        return view('penjual.dashboard', compact(
            'user',
            'membership',
            'membershipName',
            'maxProducts',
            'totalProduk',
            'quotaSisa',
            'batasTercapai',
            'bisaIklan',
            'isExpired',
            'remainingDays',
            'totalPesanan',
            'totalPendapatan',
            'totalTerjual',
            'produkAktif',
            'produkPending',
            'produkBuked',
            'recentProducts',
            'recentOrders'
        ));
    }

    // ================= 2. MANAJEMEN PRODUK (DAFTAR) =================
    public function produkIndex(Request $request)
    {
        $user = Auth::user();
        $tab = $request->get('tab', 'semua');
        $q = $request->get('q');

        $query = Product::with(['category', 'reviews'])
            ->where('seller_id', $user->id_user);

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

        // Validasi kuota upload produk
        if (!$user->canUploadProduct()) {
            return redirect()->route('penjual.produk.index')
                ->with('error', 'Kuota upload produk Anda sudah penuh (' . $user->getMaxUploadLimit() . ' produk). Silakan tingkatkan paket membership Anda untuk menambah kuota.');
        }

        $categories = Category::where('status', 'aktif')->orderBy('name')->get();

        return view('penjual.produk.create', compact('categories', 'user'));
    }

    public function produkStore(Request $request)
    {
        $user = Auth::user();

        // Cek kembali kuota upload
        if (!$user->canUploadProduct()) {
            return redirect()->route('penjual.produk.index')
                ->with('error', 'Gagal mengunggah. Batas kuota upload produk paket Anda (' . $user->getMaxUploadLimit() . ' produk) telah tercapai. Silakan perpanjang / tingkatkan paket membership Anda.');
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
            'video'       => 'nullable|file|mimes:mp4,webm,ogg,mov,avi|max:51200', // max 50MB opsional
            'file'        => 'required|file|max:51200', // max 50MB
        ], [
            'title.required'       => 'Nama produk wajib diisi.',
            'category_id.required' => 'Pilih kategori produk.',
            'price.required'       => 'Harga produk wajib diisi (minimal Rp 1.000).',
            'stock.required'       => 'Jumlah stok produk wajib diisi.',
            'description.required' => 'Deskripsi produk wajib diisi.',
            'thumbnail.required'   => 'Unggah foto/gambar sampul produk utama.',
            'images.max'           => 'Foto pendukung maksimal 5 foto.',
            'video.max'            => 'Ukuran berkas video maksimal 50MB.',
            'video.mimes'          => 'Format berkas video harus MP4, WEBM, OGG, MOV, atau AVI.',
            'file.required'        => 'Unggah berkas digital produk untuk pembeli.',
        ]);

        // Upload Sampul Utama (Thumbnail)
        $thumbPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbPath = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        // Upload Gallery Photos (hingga 5 foto total)
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

        // Upload Video (Opsional 1 video)
        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('products/videos', 'public');
        }

        // Upload Berkas Produk
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('products/files', 'public');
        }

        Product::create([
            'seller_id'       => $user->id_user,
            'category_id'     => $validated['category_id'],
            'title'           => $validated['title'],
            'description'     => $validated['description'],
            'price'           => $validated['price'],
            'stock'           => $validated['stock'],
            'thumbnail'       => $thumbPath,
            'images'          => $galleryPaths,
            'video'           => $videoPath,
            'file'            => $filePath,
            'status'          => 'pending', // Menunggu verifikasi
            'rejection_note'  => null,
            'is_promoted'     => false,
            'view_count'      => 0,
            'sold_count'      => 0,
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

        $product->title = $validated['title'];
        $product->category_id = $validated['category_id'];
        $product->price = $validated['price'];
        $product->stock = $validated['stock'];
        $product->description = $validated['description'];

        // Jika sebelumnya diblokir/ditolak, kembalikan ke status pending untuk ditinjau ulang
        if (in_array($product->status, ['rejected', 'inactive', 'blocked'])) {
            $product->status = 'pending';
            $product->rejection_note = null;
        }

        $product->save();

        return redirect()->route('penjual.produk.index')
            ->with('success', 'Data produk berhasil diperbarui.');
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

        return redirect()->route('penjual.produk.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    // ================= 6. FITUR IKLAN & PROMOSI PRODUK =================
    public function iklanIndex()
    {
        $user = Auth::user();
        $bisaIklan = $user->canUseAds();

        $activeProducts = Product::where('seller_id', $user->id_user)
            ->where('status', 'active')
            ->orderBy('title')
            ->get();

        $promotedProducts = Product::where('seller_id', $user->id_user)
            ->where('is_promoted', true)
            ->latest('promoted_until')
            ->get();

        return view('penjual.iklan.index', compact('user', 'bisaIklan', 'activeProducts', 'promotedProducts'));
    }

    public function iklanStore(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user->canUseAds()) {
            return redirect()->route('penjual.membership.index')
                ->with('error', 'Fitur pasang iklan hanya tersedia untuk paket membership Gold & Diamond. Silakan tingkatkan paket Anda.');
        }

        $product = Product::where('seller_id', $user->id_user)->findOrFail($id);

        if ($product->status !== 'active') {
            return back()->with('error', 'Hanya produk yang sudah berstatus aktif/disetujui yang dapat diiklankan.');
        }

        $product->is_promoted = true;
        $product->promoted_until = now()->addDays(7); // Iklan aktif selama 7 hari
        $product->save();

        return back()->with('success', 'Iklan produk "' . $product->title . '" berhasil diaktifkan selama 7 hari!');
    }

    public function iklanCancel($id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        $product->is_promoted = false;
        $product->promoted_until = null;
        $product->save();

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
        $remainingDays = $user->remaining_days;
        $isExpired = $user->membership_expires_at ? $user->membership_expires_at->isPast() : false;

        return view('penjual.membership.index', compact(
            'user', 'memberships', 'currentMembership', 'maxUpload', 'totalUploaded', 'remainingDays', 'isExpired'
        ));
    }

    public function membershipPurchase(Request $request, $id)
    {
        $membership = Membership::findOrFail($id);
        $user = Auth::user();

        // Perpanjang atau ganti paket membership
        $user->id_membership = $membership->id_membership;
        $user->membership_expires_at = now()->addDays($membership->duration_days ?? 30);
        $user->save();

        Notification::create([
            'user_id'     => $user->id_user,
            'name'        => '💎 Paket Membership Aktif',
            'description' => 'Paket ' . $membership->name . ' Anda telah aktif hingga ' . $user->membership_expires_at->translatedFormat('d F Y') . '. Kuota upload: ' . $membership->max_upload . ' produk.',
            'is_read'     => false,
        ]);

        return redirect()->route('penjual.membership.index')
            ->with('success', 'Selamat! Paket membership ' . $membership->name . ' berhasil diaktifkan/diperpanjang.');
    }

    // ================= 8. PESANAN MASUK (PENJUALAN) =================
    public function pesananIndex(Request $request)
    {
        $user = Auth::user();
        $tab = $request->get('tab', 'semua');

        $query = OrderItem::with(['product', 'order.buyer'])
            ->whereHas('product', function ($q) use ($user) {
                $q->where('seller_id', $user->id_user);
            });

        if ($tab === 'diproses') {
            $query->whereHas('order', function ($q) {
                $q->whereIn('status', ['diproses', 'pending']);
            });
        } elseif ($tab === 'selesai') {
            $query->whereHas('order', function ($q) {
                $q->where('status', 'selesai');
            });
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
            ->whereHas('product', function ($q) {
                $q->where('seller_id', Auth::id());
            })
            ->findOrFail($id);

        return view('penjual.pesanan.detail', compact('orderItem'));
    }

    public function pesananKonfirmasi($id)
    {
        $user = Auth::user();

        $orderItem = OrderItem::with(['product', 'order'])
            ->whereHas('product', function ($q) use ($user) {
                $q->where('seller_id', $user->id_user);
            })
            ->findOrFail($id);

        $order = $orderItem->order;

        if ($order) {
            $order->payment_status = 'paid';
            $order->status = 'selesai';
            $order->save();

            // Kirim notifikasi ke pembeli
            Notification::create([
                'user_id'     => $order->buyer_id,
                'name'        => '✅ Pesanan Dikonfirmasi Penjual',
                'description' => 'Pesanan #' . $order->id_order . ' (' . ($orderItem->product->title ?? 'Produk Digital') . ') telah dikonfirmasi oleh penjual. Anda kini dapat mengunduh berkasnya!',
                'is_read'     => false,
            ]);
        }

        return back()->with('success', 'Pesanan pembelian berhasil dikonfirmasi! Status pesanan kini Lunas & Selesai. Pembeli dapat langsung mengunduh berkas digital karya Anda.');
    }

    // ================= 9. KEUANGAN & PENARIKAN SALDO =================
    public function keuanganIndex()
    {
        $user = Auth::user();

        // Total seluruh omset penjualan dari pesanan yang lunas (paid)
        $totalPendapatan = OrderItem::whereHas('product', fn($q) => $q->where('seller_id', $user->id_user))
            ->whereHas('order', fn($q) => $q->where('payment_status', 'paid'))
            ->sum('subtotal');

        // Total dana yang sudah berhasil ditarik atau sedang diproses
        $totalDitarik = Withdrawal::where('user_id', $user->id_user)
            ->whereIn('status', ['completed', 'pending'])
            ->sum('amount');

        $saldoTersedia = max(0, $totalPendapatan - $totalDitarik);

        $withdrawals = Withdrawal::where('user_id', $user->id_user)
            ->latest('id_withdrawal')
            ->paginate(10);

        return view('penjual.keuangan.index', compact(
            'totalPendapatan', 'totalDitarik', 'saldoTersedia', 'withdrawals'
        ));
    }

    public function penarikanStore(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'bank_name'           => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name'   => 'required|string|max:100',
            'amount'              => 'required|numeric|min:20000',
        ], [
            'bank_name.required'           => 'Nama bank/e-wallet wajib dipilih/diisi.',
            'bank_account_number.required' => 'Nomor rekening wajib diisi.',
            'bank_account_name.required'   => 'Nama pemilik rekening wajib diisi.',
            'amount.required'              => 'Nominal penarikan wajib diisi.',
            'amount.min'                   => 'Minimal penarikan saldo adalah Rp 20.000.',
        ]);

        // Cek saldo
        $totalPendapatan = OrderItem::whereHas('product', fn($q) => $q->where('seller_id', $user->id_user))
            ->whereHas('order', fn($q) => $q->where('payment_status', 'paid'))
            ->sum('subtotal');

        $totalDitarik = Withdrawal::where('user_id', $user->id_user)
            ->whereIn('status', ['completed', 'pending'])
            ->sum('amount');

        $saldoTersedia = max(0, $totalPendapatan - $totalDitarik);

        if ($validated['amount'] > $saldoTersedia) {
            return back()->with('error', 'Saldo tidak mencukupi untuk melakukan penarikan sebesar Rp ' . number_format($validated['amount'], 0, ',', '.') . '. Saldo tersedia: Rp ' . number_format($saldoTersedia, 0, ',', '.'));
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

        return back()->with('success', 'Permintaan penarikan saldo sebesar Rp ' . number_format($validated['amount'], 0, ',', '.') . ' berhasil diajukan dan sedang menunggu proses transfer oleh Admin.');
    }
}