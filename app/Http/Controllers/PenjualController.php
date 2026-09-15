<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\IdentityVerification;
use App\Models\Membership;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Report;
use App\Models\User;
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
                    'name'        => 'Peringatan Perpanjangan Paket',
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

        if (!$user->isMembershipActive() && $user->id_membership) {
            return redirect()->route('penjual.membership.index')
                ->with('error', 'Masa aktif paket membership Anda telah berakhir. Silakan perpanjang paket membership Anda untuk dapat menambah produk baru.');
        }

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

        if (!$user->isMembershipActive() && $user->id_membership) {
            return redirect()->route('penjual.membership.index')
                ->with('error', 'Masa aktif paket membership Anda telah berakhir. Silakan perpanjang paket membership Anda untuk mengunggah produk baru.');
        }

        if (!$user->canUploadProduct()) {
            return redirect()->route('penjual.produk.index')
                ->with('error', 'Gagal mengunggah. Batas kuota upload produk paket Anda (' . $user->getMaxUploadLimit() . ' produk) telah tercapai.');
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
            ->with('success', 'Produk berhasil diunggah! Produk Anda saat ini berada dalam antrean verifikasi oleh Verifikator sebelum diterbitkan di marketplace.');
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

        return redirect()->route('penjual.produk.index')->with('success', 'Data produk berhasil diperbarui dan diajukan ulang untuk verifikasi.');
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
        $promotedProducts = Product::where('seller_id', $user->id_user)->where('is_promoted', true)->latest('id_product')->get();

        return view('penjual.iklan.index', compact('user', 'bisaIklan', 'activeProducts', 'promotedProducts'));
    }

    public function iklanStore(Request $request, $id = null)
    {
        $user = Auth::user();

        if (!$user->canUseAds()) {
            return redirect()->route('penjual.membership.index')
                ->with('error', 'Fitur promosi iklan hanya tersedia untuk penjual dengan paket membership aktif. Silakan perpanjang atau beli paket membership terlebih dahulu.');
        }

        $productId = $id ?: $request->input('product_id');

        if (!$productId) {
            return back()->with('error', 'Silakan pilih produk yang ingin diiklankan.');
        }

        $product = Product::where('seller_id', $user->id_user)->findOrFail($productId);
        if ($product->status !== 'active') {
            return back()->with('error', 'Hanya produk berstatus aktif yang dapat diiklankan.');
        }

        $request->validate([
            'ad_video' => 'nullable|file|mimes:mp4,webm,ogg,mov,qt|max:10240',
        ], [
            'ad_video.mimes' => 'Video iklan harus berformat MP4, WebM, OGG, atau MOV (ukuran landscape 16:9, maksimal 10 detik).',
            'ad_video.max'   => 'Ukuran file video iklan tidak boleh lebih dari 10 MB (maksimal durasi 10 detik).',
        ]);

        $updateData = [
            'is_promoted'    => true,
            'promoted_until' => now()->addDays(30),
        ];

        if ($request->hasFile('ad_video')) {
            $videoPath = $request->file('ad_video')->store('products/videos', 'public');
            $updateData['video'] = $videoPath;
        }

        $product->update($updateData);

        return back()->with('success', 'Iklan produk "' . $product->title . '" berhasil dipublikasikan!');
    }

    public function iklanCancel($id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        $product->update([
            'is_promoted' => false,
            'promoted_until' => null
        ]);

        return back()->with('success', 'Promosi iklan untuk produk ini telah dihentikan.');
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

        // Cek apakah penjual memiliki pengajuan pembayaran paket yang sedang pending
        $pendingPayment = IdentityVerification::with('membership')
            ->where('user_id', $user->id_user)
            ->where('status', 'pending')
            ->latest('id_identity_verification')
            ->first();

        // Cek jika ada penolakan pembayaran sebelumnya (dalam 7 hari terakhir)
        $lastRejectedPayment = IdentityVerification::with('membership')
            ->where('user_id', $user->id_user)
            ->where('status', 'rejected')
            ->where('updated_at', '>=', now()->subDays(7))
            ->latest('id_identity_verification')
            ->first();

        // Data rekening dan metode pembayaran resmi platform
        $paymentMethods = [
            'BCA' => [
                'name'           => 'Bank Central Asia (BCA)',
                'account_number' => '0862398284994',
                'account_name'   => 'PT Karyaku Digital Kreatif',
                'type'           => 'Transfer Bank',
                'badge'          => 'BCA',
                'color'          => '#005baa',
                'icon'           => 'bi-bank',
            ],
            'BNI' => [
                'name'           => 'Bank Negara Indonesia (BNI)',
                'account_number' => '8820192019',
                'account_name'   => 'PT Karyaku Digital Kreatif',
                'type'           => 'Transfer Bank',
                'badge'          => 'BNI',
                'color'          => '#f15a24',
                'icon'           => 'bi-bank',
            ],
            'Mandiri' => [
                'name'           => 'Bank Mandiri',
                'account_number' => '137001928301',
                'account_name'   => 'PT Karyaku Digital Kreatif',
                'type'           => 'Transfer Bank',
                'badge'          => 'Mandiri',
                'color'          => '#003366',
                'icon'           => 'bi-bank',
            ],
            'BRI' => [
                'name'           => 'Bank Rakyat Indonesia (BRI)',
                'account_number' => '0192019283019',
                'account_name'   => 'PT Karyaku Digital Kreatif',
                'type'           => 'Transfer Bank',
                'badge'          => 'BRI',
                'color'          => '#00529c',
                'icon'           => 'bi-bank',
            ],
            'QRIS' => [
                'name'           => 'QRIS / Semua Bank & E-Wallet',
                'account_number' => 'NMID: ID102003920192',
                'account_name'   => 'KARYAKU QRIS RESMI',
                'type'           => 'Scan QR',
                'badge'          => 'QRIS',
                'color'          => '#dc2626',
                'icon'           => 'bi-qr-code-scan',
            ],
            'GOPAY' => [
                'name'           => 'GoPay',
                'account_number' => '081234567890',
                'account_name'   => 'KARYAKU OFFICIAL',
                'type'           => 'E-Wallet',
                'badge'          => 'GoPay',
                'color'          => '#00aed6',
                'icon'           => 'bi-wallet2',
            ],
            'DANA' => [
                'name'           => 'DANA',
                'account_number' => '081234567890',
                'account_name'   => 'KARYAKU OFFICIAL',
                'type'           => 'E-Wallet',
                'badge'          => 'DANA',
                'color'          => '#118eea',
                'icon'           => 'bi-wallet2',
            ],
            'OVO' => [
                'name'           => 'OVO',
                'account_number' => '081234567890',
                'account_name'   => 'KARYAKU OFFICIAL',
                'type'           => 'E-Wallet',
                'badge'          => 'OVO',
                'color'          => '#4c2a86',
                'icon'           => 'bi-wallet2',
            ],
        ];

        return view('penjual.membership.index', compact(
            'user', 'memberships', 'currentMembership', 'maxUpload', 'totalUploaded',
            'remainingDays', 'countdown', 'showWarning', 'isExpired',
            'pendingPayment', 'lastRejectedPayment', 'paymentMethods'
        ));
    }

    public function membershipPurchase(Request $request, $id)
    {
        $user = Auth::user();
        $membership = Membership::findOrFail($id);

        // Cek jika penjual masih memiliki pembayaran pending
        if (IdentityVerification::where('user_id', $user->id_user)->where('status', 'pending')->exists()) {
            return redirect()->route('penjual.membership.index')
                ->with('error', 'Anda masih memiliki transaksi perpanjangan/upgrade paket yang sedang diproses oleh admin.');
        }

        $validated = $request->validate([
            'payment_method' => 'required|string|max:100',
            'payment_proof'  => 'required|image|mimes:jpg,jpeg,png,webp|max:3072',
            'sender_bank'    => 'nullable|string|max:100',
            'sender_name'    => 'nullable|string|max:150',
            'sender_account' => 'nullable|string|max:100',
        ], [
            'payment_method.required' => 'Silakan pilih metode pembayaran yang Anda gunakan.',
            'payment_proof.required'  => 'Foto bukti transfer pembayaran wajib dilampirkan.',
            'payment_proof.image'     => 'Bukti pembayaran harus berupa berkas gambar.',
            'payment_proof.mimes'     => 'Format gambar bukti transfer harus JPG, JPEG, PNG, atau WEBP.',
            'payment_proof.max'       => 'Ukuran foto bukti transfer tidak boleh lebih dari 3 MB.',
        ]);

        $proofPath = $request->file('payment_proof')->store('identity-verifications/payment', 'public');

        // Ambil data verifikasi sebelumnya (NIK, alamat, rekening awal) jika ada
        $lastVerif = IdentityVerification::where('user_id', $user->id_user)->latest('id_identity_verification')->first();

        $nik = $lastVerif->nik ?? null;
        $address = $lastVerif->address ?? null;
        $identityDoc = $lastVerif->identity_document ?? null;
        $bankName = !empty($validated['sender_bank']) ? $validated['sender_bank'] : ($lastVerif->bank_name ?? $validated['payment_method']);
        $accountName = !empty($validated['sender_name']) ? $validated['sender_name'] : ($lastVerif->account_name ?? $user->name);
        $accountNumber = !empty($validated['sender_account']) ? $validated['sender_account'] : ($lastVerif->account_number ?? '-');

        // Buat record pengajuan verifikasi pembayaran baru
        IdentityVerification::create([
            'user_id'              => $user->id_user,
            'identity_document'    => $identityDoc,
            'nik'                  => $nik,
            'address'              => $address,
            'bank_name'            => $bankName,
            'account_name'         => $accountName,
            'account_number'       => $accountNumber,
            'membership_id'        => $membership->id_membership,
            'payment_method'       => $validated['payment_method'],
            'payment_proof'        => $proofPath,
            'payment_amount'       => $membership->price,
            'payment_submitted_at' => now(),
            'submitted_at'         => now(),
            'status'               => 'pending',
            'notes'                => null,
        ]);

        Notification::create([
            'user_id'     => $user->id_user,
            'name'        => 'Pembayaran Paket Terkirim',
            'description' => 'Bukti transfer pembayaran paket ' . $membership->name . ' sebesar Rp ' . number_format($membership->price, 0, ',', '.') . ' telah dikirimkan. Menunggu verifikasi admin.',
            'is_read'     => false,
        ]);

        return redirect()->route('penjual.membership.index')
            ->with('success', 'Bukti pembayaran paket ' . $membership->name . ' berhasil dikirim! Verifikator kami akan memproses aktivasi paket Anda secepatnya.');
    }

    public function membershipCancelPayment()
    {
        $user = Auth::user();
        $pending = IdentityVerification::where('user_id', $user->id_user)
            ->where('status', 'pending')
            ->latest('id_identity_verification')
            ->first();

        if ($pending) {
            if ($pending->payment_proof && Storage::disk('public')->exists($pending->payment_proof)) {
                Storage::disk('public')->delete($pending->payment_proof);
            }
            $pending->delete();

            return redirect()->route('penjual.membership.index')
                ->with('success', 'Pengajuan pembayaran paket telah dibatalkan.');
        }

        return back()->with('error', 'Tidak ada pengajuan pembayaran pending yang dapat dibatalkan.');
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
        return back()->with('info', 'Pemeriksaan dan verifikasi bukti pembayaran dilakukan oleh tim Verifikator platform untuk menjamin keamanan transaksi.');
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



        // ================= LAPORAN DARI PENJUAL =================
    // Fitur ini memungkinkan penjual mengirim laporan (mis. laporan terhadap
    // pembeli/pengguna lain atau produk bermasalah). Laporan yang masuk akan
    // otomatis terhubung & bisa ditindaklanjuti oleh role verifikator, admin,
    // dan customer service, karena semuanya membaca dari tabel `reports` yang sama.
    public function laporanIndex(Request $request)
    {
        $user = Auth::user();

        $products = Product::select('id_product', 'title', 'seller_id')
            ->with(['seller:id_user,name'])
            ->where('status', 'active')
            ->where('seller_id', '!=', $user->id_user)
            ->orderBy('title')
            ->get();

        $users = User::select('id_user', 'name', 'id_role')
            ->with(['role:id_role,role_name'])
            ->where('id_user', '!=', $user->id_user)
            ->whereHas('role', function ($q) {
                $q->whereIn('role_name', ['penjual', 'pembeli']);
            })
            ->orderBy('name')
            ->get();

        $reports = Report::with([
                'product:id_product,title,seller_id',
                'reportedUser:id_user,name',
            ])
            ->where('user_id', $user->id_user)
            ->latest('id_report')
            ->paginate(8)
            ->withQueryString();

        return view('penjual.laporan.index', compact('products', 'users', 'reports'));
    }

    public function laporanStore(Request $request)
    {
        $validated = $request->validate([
            'target_type'      => 'required|in:produk,pengguna,lainnya',
            'product_id'       => 'nullable|required_if:target_type,produk|integer|exists:products,id_product',
            'reported_user_id' => 'nullable|required_if:target_type,pengguna|integer|exists:users,id_user',
            'reason'           => 'required|string|max:255',
            'description'      => 'nullable|string|max:2000',
        ]);

        $user = Auth::user();
        $productId = null;
        $reportedUserId = null;

        if ($validated['target_type'] === 'produk') {
            $productId = $validated['product_id'];
            $product = Product::select('id_product', 'seller_id')->where('id_product', $productId)->first();

            if (!$product) {
                return back()->withInput()->with('error', 'Produk yang ingin dilaporkan tidak ditemukan.');
            }

            $reportedUserId = $product->seller_id;
            if ($reportedUserId == $user->id_user) {
                return back()->withInput()->with('error', 'Kamu tidak dapat melaporkan produk milik akun sendiri.');
            }
        } elseif ($validated['target_type'] === 'pengguna') {
            $reportedUserId = $validated['reported_user_id'];
            if ($reportedUserId == $user->id_user) {
                return back()->withInput()->with('error', 'Kamu tidak dapat melaporkan akun sendiri.');
            }

            $targetUser = User::with('role')->find($reportedUserId);
            if ($targetUser && !in_array(strtolower($targetUser->role->role_name ?? ''), ['penjual', 'pembeli'])) {
                return back()->withInput()->with('error', 'Akun pengurus platform (Admin, Verifikator, Customer Service) tidak dapat dilaporkan. Hanya akun Penjual dan Pembeli yang dapat dilaporkan.');
            }
        }

        Report::create([
            'user_id'          => $user->id_user,
            'product_id'       => $productId,
            'reported_user_id' => $reportedUserId,
            'reason'           => $validated['reason'],
            'description'      => $validated['description'] ?? null,
            'status'           => 'pending',
        ]);

        return redirect()->route('penjual.laporan.index')
            ->with('success', 'Laporan berhasil dikirim! Tim verifikator, admin, dan CS akan meninjau laporan kamu.');
    }

    public function peringatanIndex()
    {
        $userId = Auth::id();
        $peringatan = \App\Models\Report::with(['product', 'reporter'])
            ->where('reported_user_id', $userId)
            ->whereIn('status', ['reviewed', 'resolved', 'escalated'])
            ->whereNotNull('admin_note')
            ->latest('updated_at')
            ->paginate(10);

        return view('penjual.peringatan', compact('peringatan'));
    }

}
