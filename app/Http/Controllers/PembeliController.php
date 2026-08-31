<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Membership;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Review;
use App\Models\IdentityVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PembeliController extends Controller
{
    // =========================================================
    // DASHBOARD
    // =========================================================
    public function dashboard()
    {
        $userId = Auth::id();

        $rawStats = Order::where('buyer_id', $userId)
            ->selectRaw("
                COUNT(*) as total_pesanan,
                SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as total_selesai,
                SUM(CASE WHEN payment_status = 'unpaid' AND status != 'dibatalkan' THEN 1 ELSE 0 END) as total_belum_bayar,
                COALESCE(SUM(CASE WHEN payment_status = 'paid' THEN total_price ELSE 0 END), 0) as total_belanja
            ")
            ->first();

        $totalPesanan = (int) ($rawStats->total_pesanan ?? 0);
        $totalSelesai = (int) ($rawStats->total_selesai ?? 0);
        $totalBelumBayar = (int) ($rawStats->total_belum_bayar ?? 0);
        $totalBelanja = (float) ($rawStats->total_belanja ?? 0);
        $totalWishlist = Wishlist::where('user_id', $userId)->count();
        $totalKeranjang = Cart::where('user_id', $userId)->count();

        $recentOrders = Order::with(['items.product'])
            ->where('buyer_id', $userId)
            ->latest('id_order')
            ->take(5)
            ->get();

        $rekomendasi = Product::with(['category', 'seller'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->whereIn('status', ['active', 'pending'])
            ->orderByDesc('id_product')
            ->take(8)
            ->get();

        $promotedProducts = Product::with(['category', 'seller'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->whereIn('status', ['active', 'pending'])
            ->where('is_promoted', true)
            ->where(fn ($q) => $q->whereNull('promoted_until')->orWhere('promoted_until', '>=', now()))
            ->latest('id_product')
            ->take(6)
            ->get();

        $categories = Category::where('status', 'aktif')->orderBy('name')->take(8)->get();
        $wishlistIds = Wishlist::where('user_id', $userId)->pluck('product_id')->toArray();

        return view('pembeli.dashboard', compact(
            'totalPesanan', 'totalSelesai', 'totalBelumBayar', 'totalBelanja',
            'totalWishlist', 'totalKeranjang', 'recentOrders', 'rekomendasi',
            'promotedProducts', 'categories', 'wishlistIds'
        ));
    }

    // =========================================================
    // MARKETPLACE
    // =========================================================
    public function marketplace(Request $request)
    {
        $userId = Auth::id();

        $query = Product::with(['category', 'seller'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('status', 'active');

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . trim($request->q) . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        match ($request->get('sort')) {
            'terbaru'  => $query->orderByDesc('id_product'),
            'termurah' => $query->orderBy('price', 'asc'),
            'termahal' => $query->orderBy('price', 'desc'),
            'terlaris' => $query->orderByDesc('sold_count'),
            default    => $query->orderByDesc('sold_count'),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::where('status', 'aktif')->orderBy('name')->get();
        $wishlistIds = Wishlist::where('user_id', $userId)->pluck('product_id')->toArray();

        return view('pembeli.marketplace', compact('products', 'categories', 'wishlistIds'));
    }

    // =========================================================
    // DETAIL PRODUK
    // =========================================================
    public function produkDetail($id)
    {
        $userId = Auth::id();
        $product = Product::with(['category', 'seller', 'reviews.user'])->findOrFail($id);

        $product->increment('view_count');

        $produkLain = Product::where('seller_id', $product->seller_id)
            ->where('id_product', '!=', $product->id_product)
            ->where('status', 'active')
            ->latest('id_product')
            ->take(4)
            ->get();

        $isWishlisted = Wishlist::where('user_id', $userId)->where('product_id', $product->id_product)->exists();

        $reviews = $product->reviews()->with('user')->latest('id_review')->get();
        $avgRating = round($reviews->avg('rating') ?: 5, 1);
        $totalReviews = $reviews->count();

        $hasBought = OrderItem::where('product_id', $product->id_product)
            ->whereHas('order', fn ($q) => $q->where('buyer_id', $userId)->where('payment_status', 'paid'))
            ->exists();

        $userReview = Review::where('product_id', $product->id_product)->where('user_id', $userId)->first();

        return view('pembeli.produk', compact(
            'product', 'produkLain', 'isWishlisted', 'reviews',
            'avgRating', 'totalReviews', 'hasBought', 'userReview'
        ));
    }

    // =========================================================
    // REVIEW
    // =========================================================
    public function reviewStore(Request $request, $id)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();
        Product::findOrFail($id);

        $hasBought = OrderItem::where('product_id', $id)
            ->whereHas('order', fn ($q) => $q->where('buyer_id', $userId)->where('payment_status', 'paid'))
            ->exists();

        if (!$hasBought) {
            return back()->with('error', 'Anda hanya dapat memberikan ulasan untuk produk yang sudah Anda beli dan bayar.');
        }

        Review::updateOrCreate(
            ['product_id' => $id, 'user_id' => $userId],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        return back()->with('success', 'Terima kasih! Ulasan dan rating Anda berhasil disimpan.');
    }

    // =========================================================
    // KERANJANG
    // =========================================================
    public function keranjangIndex()
    {
        $items = Cart::with(['product.seller'])->where('user_id', Auth::id())->latest('id_cart')->get();
        return view('pembeli.keranjang', compact('items'));
    }

    public function keranjangStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id_product',
            'quantity'   => 'nullable|integer|min:1',
        ]);

        $userId = Auth::id();
        $quantity = (int) ($request->quantity ?? 1);

        $cart = Cart::firstOrNew(['user_id' => $userId, 'product_id' => $request->product_id]);
        $cart->quantity = ($cart->quantity ?? 0) + $quantity;
        $cart->save();

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function keranjangUpdate(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        Cart::where('user_id', Auth::id())->findOrFail($id)->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Jumlah item berhasil diperbarui.');
    }

    public function keranjangDestroy($id)
    {
        Cart::where('user_id', Auth::id())->where('id_cart', $id)->delete();
        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    // =========================================================
    // CHECKOUT
    // =========================================================
    public function checkout(Request $request)
    {
        $request->validate([
            'cart_ids'   => 'required|array|min:1',
            'cart_ids.*' => 'exists:carts,id_cart',
        ]);

        $userId = Auth::id();
        $cartIds = collect($request->cart_ids)->map(fn ($id) => (int) $id)->unique()->values();

        $carts = Cart::with('product')->where('user_id', $userId)->whereIn('id_cart', $cartIds)->get();

        if ($carts->isEmpty()) {
            return back()->withErrors(['cart_ids' => 'Tidak ada item yang dipilih.']);
        }

        foreach ($carts as $cart) {
            if (!$cart->product || $cart->product->status !== 'active') {
                return back()->withErrors(['cart_ids' => 'Salah satu produk di keranjang sudah tidak tersedia.']);
            }
        }

        $order = DB::transaction(function () use ($carts, $userId) {
            $total = $carts->sum(fn ($cart) => $cart->product->price * $cart->quantity);

            $order = Order::create([
                'buyer_id'       => $userId,
                'total_price'    => $total,
                'status'         => 'diproses',
                'payment_status' => 'unpaid',
            ]);

            foreach ($carts as $cart) {
                $subtotal = $cart->product->price * $cart->quantity;

                OrderItem::create([
                    'order_id'   => $order->id_order,
                    'product_id' => $cart->product_id,
                    'price'      => $cart->product->price,
                    'quantity'   => $cart->quantity,
                    'subtotal'   => $subtotal,
                ]);

                $cart->product->increment('sold_count', $cart->quantity);
            }

            Cart::whereIn('id_cart', $carts->pluck('id_cart'))->where('user_id', $userId)->delete();

            return $order;
        });

        return redirect()->route('pembeli.pesanan.detail', $order->id_order)
            ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
    }

    // =========================================================
    // WISHLIST
    // =========================================================
    public function wishlistToggle($productId)
    {
        $userId = Auth::id();

        if (!Product::where('id_product', $productId)->exists()) {
            if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Produk tidak ditemukan.'], 404);
            }
            return back()->withErrors(['wishlist' => 'Produk tidak ditemukan.']);
        }

        $existing = Wishlist::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
        } else {
            Wishlist::create(['user_id' => $userId, 'product_id' => $productId]);
            $status = 'added';
        }

        if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
            return response()->json(['status' => $status]);
        }

        return back()->with('success', $status === 'added' ? 'Produk berhasil ditambahkan ke wishlist.' : 'Produk dihapus dari wishlist.');
    }

    public function wishlistIndex()
    {
        $userId = Auth::id();
        $wishlists = Wishlist::with(['product.seller', 'product.category'])
            ->where('user_id', $userId)
            ->latest('id_wishlist')
            ->paginate(12);

        $items = $wishlists;
        return view('pembeli.wishlist', compact('wishlists', 'items'));
    }

    // =========================================================
    // PESANAN
    // =========================================================
    public function pesananIndex(Request $request)
    {
        $userId = Auth::id();
        $tab = $request->get('tab', 'semua');

        $query = Order::with(['items.product.seller'])->where('buyer_id', $userId);

        if ($tab === 'diproses') {
            $query->whereIn('status', ['diproses', 'pending']);
        } elseif ($tab === 'selesai') {
            $query->where('status', 'selesai');
        } elseif ($tab === 'dibatalkan') {
            $query->where('status', 'dibatalkan');
        }

        $orders = $query->latest('id_order')->paginate(10)->withQueryString();

        $rawCounts = Order::where('buyer_id', $userId)
            ->selectRaw("
                COUNT(*) as semua,
                SUM(CASE WHEN status IN ('diproses', 'pending') THEN 1 ELSE 0 END) as diproses,
                SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai,
                SUM(CASE WHEN status = 'dibatalkan' THEN 1 ELSE 0 END) as dibatalkan
            ")
            ->first();

        $counts = [
            'semua'      => (int) ($rawCounts->semua ?? 0),
            'diproses'   => (int) ($rawCounts->diproses ?? 0),
            'selesai'    => (int) ($rawCounts->selesai ?? 0),
            'dibatalkan' => (int) ($rawCounts->dibatalkan ?? 0),
        ];

        return view('pembeli.pesanan', compact('orders', 'tab', 'counts'));
    }

    public function pesananDetail($id)
    {
        $order = Order::with(['items.product.seller', 'items.product.category'])
            ->where('buyer_id', Auth::id())
            ->findOrFail($id);

        return view('pembeli.pesanan-detail', compact('order'));
    }

    // =========================================================
    // DOWNLOAD
    // =========================================================
    public function downloadIndex()
    {
        $userId = Auth::id();
        $orderItems = OrderItem::with(['product.seller', 'product.category', 'order'])
            ->whereHas('order', fn ($q) => $q->where('buyer_id', $userId)->where('payment_status', 'paid'))
            ->latest('id_order_item')
            ->paginate(12);

        return view('pembeli.download', compact('orderItems'));
    }

    public function downloadFile($id_order_item)
    {
        $userId = Auth::id();
        $orderItem = OrderItem::with(['order', 'product'])
            ->where('id_order_item', $id_order_item)
            ->whereHas('order', fn ($q) => $q->where('buyer_id', $userId)->where('payment_status', 'paid'))
            ->firstOrFail();

        if (!$orderItem->product || !$orderItem->product->file) {
            return back()->with('error', 'File karya digital belum diunggah atau tidak ditemukan.');
        }

        $file = $orderItem->product->file;

        if (Storage::disk('public')->exists($file)) {
            return Storage::disk('public')->download($file);
        }

        $filePath = storage_path('app/public/' . $file);
        if (file_exists($filePath)) {
            return response()->download($filePath);
        }

        return back()->with('error', 'File berkas tidak ditemukan di server penyimpanan.');
    }

    // =========================================================
    // PROFILE
    // =========================================================
    public function profile()
    {
        return view('pembeli.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    // =========================================================
    // MEMBERSHIP
    // =========================================================
    public function membershipIndex()
    {
        $userId = Auth::id();
        $memberships = Membership::orderBy('price')->get();
        $user = Auth::user();
        $user->loadMissing('role');

        $isPenjual = ($user->role->role_name ?? null) === 'penjual';
        $pending = IdentityVerification::where('user_id', $userId)->where('status', 'pending')->latest('id_identity_verification')->first();

        return view('pembeli.membership', compact('memberships', 'user', 'isPenjual', 'pending'));
    }

    public function membershipPurchase(Request $request, $id)
    {
        $membership = Membership::findOrFail($id);
        return redirect()->route('pembeli.seller.registration.create', ['membership' => $membership->id_membership]);
    }

    // =========================================================
    // NOTIFIKASI & PERINGATAN
    // =========================================================
    public function notificationsIndex()
    {
        $userId = Auth::id();
        $notifications = Notification::where(fn ($q) => $q->where('user_id', $userId)->orWhereNull('user_id'))
            ->latest()
            ->paginate(10);

        return view('pembeli.notifications', compact('notifications'));
    }

    public function peringatanIndex()
    {
        $userId = Auth::id();
        $peringatan = \App\Models\Report::where('reported_user_id', $userId)
            ->whereIn('status', ['reviewed', 'escalated'])
            ->whereNotNull('admin_note')
            ->latest('reviewed_at')
            ->paginate(10);

        return view('pembeli.peringatan', compact('peringatan'));
    }
}