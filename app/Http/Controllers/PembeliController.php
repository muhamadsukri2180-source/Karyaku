<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Membership;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Role;
use App\Models\Wishlist;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PembeliController extends Controller
{
    // ================= DASHBOARD =================
    public function dashboard()
    {
        $userId = Auth::id();

        $totalPesanan    = Order::where('buyer_id', $userId)->count();
        $totalSelesai    = Order::where('buyer_id', $userId)->where('status', 'selesai')->count();
        $totalBelumBayar = Order::where('buyer_id', $userId)->where('payment_status', 'unpaid')->where('status', '!=', 'dibatalkan')->count();
        $totalBelanja    = Order::where('buyer_id', $userId)->where('payment_status', 'paid')->sum('total_price');
        $totalWishlist   = Wishlist::where('user_id', $userId)->count();
        $totalKeranjang  = Cart::where('user_id', $userId)->count();

        $recentOrders = Order::with('items.product')
            ->where('buyer_id', $userId)
            ->latest('id_order')
            ->take(5)
            ->get();

        $rekomendasi = Product::with(['category', 'seller', 'reviews'])
            ->where('status', 'active')
            ->orderByDesc('sold_count')
            ->take(8)
            ->get();

        $categories = Category::where('status', 'aktif')->orderBy('name')->take(8)->get();
        $wishlistIds = Wishlist::where('user_id', $userId)->pluck('product_id')->toArray();

        return view('pembeli.dashboard', compact(
            'totalPesanan', 'totalSelesai', 'totalBelumBayar', 'totalBelanja', 
            'totalWishlist', 'totalKeranjang', 'recentOrders', 'rekomendasi', 'categories', 'wishlistIds'
        ));
    }

    // ================= MARKETPLACE =================
    public function marketplace(Request $request)
    {
        $query = Product::with(['category', 'seller', 'reviews'])->where('status', 'active');

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        switch ($request->get('sort')) {
            case 'terbaru':
                $query->orderByDesc('id_product');
                break;
            case 'termurah':
                $query->orderBy('price', 'asc');
                break;
            case 'termahal':
                $query->orderBy('price', 'desc');
                break;
            case 'terlaris':
                $query->orderByDesc('sold_count');
                break;
            default:
                $query->orderByDesc('sold_count');
        }

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::where('status', 'aktif')->orderBy('name')->get();

        $wishlistIds = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();

        return view('pembeli.marketplace', compact('products', 'categories', 'wishlistIds'));
    }

    // ================= DETAIL PRODUK =================
    public function produkDetail($id)
    {
        $product = Product::with(['category', 'seller', 'reviews.user'])->findOrFail($id);

        $product->increment('view_count');

        $produkLain = Product::where('seller_id', $product->seller_id)
            ->where('id_product', '!=', $product->id_product)
            ->where('status', 'active')
            ->take(4)
            ->get();

        $isWishlisted = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id_product)
            ->exists();

        $reviews = $product->reviews()->with('user')->latest('id_review')->get();
        $avgRating = round($reviews->avg('rating') ?: 5, 1);
        $totalReviews = $reviews->count();

        // Cek apakah user pernah membeli produk ini dan pesanan sudah lunas/selesai
        $hasBought = OrderItem::where('product_id', $product->id_product)
            ->whereHas('order', function ($q) {
                $q->where('buyer_id', Auth::id())->where('payment_status', 'paid');
            })
            ->exists();

        $userReview = Review::where('product_id', $product->id_product)
            ->where('user_id', Auth::id())
            ->first();

        return view('pembeli.produk', compact(
            'product', 'produkLain', 'isWishlisted', 'reviews', 'avgRating', 'totalReviews', 'hasBought', 'userReview'
        ));
    }

    // ================= REVIEW / RATING & KOMENTAR =================
    public function reviewStore(Request $request, $id)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($id);

        // Validasi apakah pembeli pernah membeli produk ini
        $hasBought = OrderItem::where('product_id', $id)
            ->whereHas('order', function ($q) {
                $q->where('buyer_id', Auth::id())->where('payment_status', 'paid');
            })
            ->exists();

        if (! $hasBought) {
            return back()->with('error', 'Anda hanya dapat memberikan ulasan untuk produk yang sudah Anda beli dan bayar.');
        }

        Review::updateOrCreate(
            [
                'product_id' => $id,
                'user_id'    => Auth::id(),
            ],
            [
                'rating'     => $request->rating,
                'comment'    => $request->comment,
            ]
        );

        return back()->with('success', 'Terima kasih! Ulasan dan rating Anda berhasil disimpan.');
    }

    // ================= KERANJANG =================
    public function keranjangIndex()
    {
        $items = Cart::with('product.seller')->where('user_id', Auth::id())->latest('id_cart')->get();

        return view('pembeli.keranjang', compact('items'));
    }

    public function keranjangStore(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id_product',
            'quantity'   => 'nullable|integer|min:1',
        ]);

        $cart = Cart::firstOrNew([
            'user_id'    => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        $cart->quantity = ($cart->quantity ?? 0) + ($request->quantity ?? 1);
        $cart->save();

        return back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function keranjangUpdate(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Jumlah item berhasil diperbarui.');
    }

    public function keranjangDestroy($id)
    {
        Cart::where('user_id', Auth::id())->where('id_cart', $id)->delete();

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    // ================= CHECKOUT =================
    public function checkout(Request $request)
    {
        $request->validate([
            'cart_ids'   => 'required|array|min:1',
            'cart_ids.*' => 'exists:carts,id_cart',
        ]);

        $carts = Cart::with('product')
            ->where('user_id', Auth::id())
            ->whereIn('id_cart', $request->cart_ids)
            ->get();

        if ($carts->isEmpty()) {
            return back()->withErrors(['cart_ids' => 'Tidak ada item yang dipilih.']);
        }

        if ($carts->contains(fn ($c) => ! $c->product || $c->product->status !== 'active')) {
            return back()->withErrors(['cart_ids' => 'Salah satu produk di keranjang sudah tidak tersedia. Silakan hapus item tersebut.']);
        }

        $order = DB::transaction(function () use ($carts) {
            $total = $carts->sum(fn ($c) => $c->product->price * $c->quantity);

            $order = Order::create([
                'buyer_id'       => Auth::id(),
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

            Cart::whereIn('id_cart', $carts->pluck('id_cart'))->delete();

            return $order;
        });

        return redirect()->route('pembeli.pesanan.detail', $order->id_order)
            ->with('success', 'Pesanan berhasil dibuat. Silakan lakukan pembayaran.');
    }

    // ================= WISHLIST =================
    public function wishlistToggle($productId)
    {
        $product = Product::find($productId);

        if (! $product) {
            if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Produk tidak ditemukan.'], 404);
            }
            return back()->withErrors(['wishlist' => 'Produk tidak ditemukan.']);
        }

        $existing = Wishlist::where('user_id', Auth::id())->where('product_id', $productId)->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
        } else {
            Wishlist::create(['user_id' => Auth::id(), 'product_id' => $productId]);
            $status = 'added';
        }

        if (request()->expectsJson() || request()->wantsJson() || request()->ajax()) {
            return response()->json(['status' => $status]);
        }

        return back()->with('success', $status === 'added' ? 'Produk berhasil ditambahkan ke wishlist.' : 'Produk dihapus dari wishlist.');
    }

    public function wishlistIndex()
    {
        $wishlists = Wishlist::with(['product.seller', 'product.category'])
            ->where('user_id', Auth::id())
            ->latest('id_wishlist')
            ->paginate(12);

        $items = $wishlists;

        return view('pembeli.wishlist', compact('wishlists', 'items'));
    }

    // ================= PESANAN =================
    public function pesananIndex(Request $request)
    {
        $tab = $request->get('tab', 'semua');
        $query = Order::with(['items.product.seller'])
            ->where('buyer_id', Auth::id());

        if ($tab === 'diproses') {
            $query->whereIn('status', ['diproses', 'pending']);
        } elseif ($tab === 'selesai') {
            $query->where('status', 'selesai');
        } elseif ($tab === 'dibatalkan') {
            $query->where('status', 'dibatalkan');
        }

        $orders = $query->latest('id_order')->paginate(10)->withQueryString();

        $counts = [
            'semua'      => Order::where('buyer_id', Auth::id())->count(),
            'diproses'   => Order::where('buyer_id', Auth::id())->whereIn('status', ['diproses', 'pending'])->count(),
            'selesai'    => Order::where('buyer_id', Auth::id())->where('status', 'selesai')->count(),
            'dibatalkan' => Order::where('buyer_id', Auth::id())->where('status', 'dibatalkan')->count(),
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

    // ================= DOWNLOAD =================
    public function downloadIndex()
    {
        $orderItems = OrderItem::with(['product.seller', 'product.category', 'order'])
            ->whereHas('order', function ($q) {
                $q->where('buyer_id', Auth::id())->where('payment_status', 'paid');
            })
            ->latest('id_order_item')
            ->paginate(12);

        return view('pembeli.download', compact('orderItems'));
    }

    public function downloadFile($id_order_item)
    {
        $orderItem = OrderItem::with(['order', 'product'])
            ->where('id_order_item', $id_order_item)
            ->whereHas('order', function ($q) {
                $q->where('buyer_id', Auth::id())->where('payment_status', 'paid');
            })
            ->firstOrFail();

        if (! $orderItem->product || ! $orderItem->product->file) {
            return back()->with('error', 'File karya digital belum diunggah atau tidak ditemukan.');
        }

        $filePath = storage_path('app/public/' . $orderItem->product->file);
        if (! file_exists($filePath)) {
            // Cek di public disk langsung
            if (Storage::disk('public')->exists($orderItem->product->file)) {
                return Storage::disk('public')->download($orderItem->product->file);
            }
            return back()->with('error', 'File berkas tidak ditemukan di server penyimpanan.');
        }

        return response()->download($filePath);
    }

    // ================= PROFILE =================
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

    // ================= MEMBERSHIP (UPGRADE JADI PENJUAL) =================
    public function membershipIndex()
    {
        $memberships = Membership::orderBy('price')->get();
        $user        = Auth::user();
        $isPenjual   = ($user->role->role_name ?? null) === 'penjual';
        $pending     = \App\Models\IdentityVerification::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->latest('id_identity_verification')
            ->first();

        return view('pembeli.membership', compact('memberships', 'user', 'isPenjual', 'pending'));
    }

    public function membershipPurchase(Request $request, $id)
    {
        $membership = Membership::findOrFail($id);

        return redirect()->route(
            'pembeli.seller.registration.create',
            [
                'membership' => $membership->id_membership,
            ]
        );
    }

    // ================= NOTIFIKASI (dari Admin) =================
    public function notificationsIndex()
    {
        $notifications = Notification::where(function ($q) {
                $q->where('user_id', Auth::id())
                  ->orWhereNull('user_id');
            })
            ->latest()
            ->paginate(10);

        return view('pembeli.notifications', compact('notifications'));
    }

    // ================= PERINGATAN DITERIMA (dari Admin/CS) =================
    public function peringatanIndex()
    {
        $peringatan = \App\Models\Report::where('reported_user_id', Auth::id())
            ->whereIn('status', ['reviewed', 'escalated'])
            ->whereNotNull('admin_note')
            ->latest('reviewed_at')
            ->paginate(10);

        return view('pembeli.peringatan', compact('peringatan'));
    }
}   