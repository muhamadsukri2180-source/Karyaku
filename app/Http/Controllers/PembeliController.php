<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembeliController extends Controller
{
    // ================= DASHBOARD =================
    public function dashboard()
    {
        $userId = Auth::id();

        $totalPesanan   = Order::where('buyer_id', $userId)->count();
        $totalSelesai   = Order::where('buyer_id', $userId)->where('status', 'selesai')->count();
        $totalBelanja   = Order::where('buyer_id', $userId)->where('payment_status', 'paid')->sum('total_price');
        $totalWishlist  = Wishlist::where('user_id', $userId)->count();
        $totalKeranjang = Cart::where('user_id', $userId)->count();

        $recentOrders = Order::with('items.product')
            ->where('buyer_id', $userId)
            ->latest('id_order')
            ->take(5)
            ->get();

        $rekomendasi = Product::with(['category', 'seller'])
            ->where('status', 'active')
            ->orderByDesc('sold_count')
            ->take(4)
            ->get();

        return view('pembeli.dashboard', compact(
            'totalPesanan', 'totalSelesai', 'totalBelanja', 'totalWishlist', 'totalKeranjang', 'recentOrders', 'rekomendasi'
        ));
    }

    // ================= MARKETPLACE =================
    public function marketplace(Request $request)
    {
        $query = Product::with(['category', 'seller'])->where('status', 'active');

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
                $query->orderBy('price');
                break;
            case 'termahal':
                $query->orderByDesc('price');
                break;
            case 'terlaris':
                $query->orderByDesc('sold_count');
                break;
            default:
                $query->orderByDesc('sold_count');
        }

        // Menggunakan appends($request->query()) agar aman dari linter IDE
        $products   = $query->paginate(12)->appends($request->query());
        $categories = Category::orderBy('name')->get();
        $wishlistIds = Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray();

        return view('pembeli.marketplace', compact('products', 'categories', 'wishlistIds'));
    }

    // ================= DETAIL PRODUK =================
    public function produkDetail(int|string $id)
    {
        $product = Product::with(['category', 'seller'])->findOrFail($id);

        $product->increment('view_count');

        $produkLain = Product::where('seller_id', $product->seller_id)
            ->where('id_product', '!=', $product->id_product)
            ->where('status', 'active')
            ->take(4)
            ->get();

        $isWishlisted = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id_product)
            ->exists();

        return view('pembeli.produk', compact('product', 'produkLain', 'isWishlisted'));
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

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function keranjangUpdate(Request $request, int|string $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->quantity = $request->quantity;
        $cart->save();

        return back()->with('success', 'Jumlah item diperbarui.');
    }

    public function keranjangDestroy(int|string $id)
    {
        Cart::where('user_id', Auth::id())->where('id_cart', $id)->delete();

        return back()->with('success', 'Item dihapus dari keranjang.');
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
    public function wishlistToggle(int|string $productId)
    {
        $product = Product::find($productId);

        if (! $product) {
            if (request()->wantsJson()) {
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

        if (request()->wantsJson()) {
            return response()->json(['status' => $status]);
        }

        return back()->with('success', $status === 'added' ? 'Ditambahkan ke wishlist.' : 'Dihapus dari wishlist.');
    }

    public function wishlistIndex()
    {
        $items = Wishlist::with('product.seller', 'product.category')
            ->where('user_id', Auth::id())
            ->latest('id_wishlist')
            ->get();

        return view('pembeli.wishlist', compact('items'));
    }

    // ================= PESANAN =================
    public function pesananIndex()
    {
        $orders = Order::with('items.product')
            ->where('buyer_id', Auth::id())
            ->latest('id_order')
            ->paginate(10);

        return view('pembeli.pesanan', compact('orders'));
    }

    public function pesananDetail(int|string $id)
    {
        $order = Order::with('items.product.seller')
            ->where('buyer_id', Auth::id())
            ->findOrFail($id);

        return view('pembeli.pesanan-detail', compact('order'));
    }

    // ================= DOWNLOAD =================
    public function downloadIndex()
    {
        $orderItems = OrderItem::with('product.seller', 'order')
            ->whereHas('order', function ($q) {
                $q->where('buyer_id', Auth::id())->where('payment_status', 'paid');
            })
            ->latest('id_order_item')
            ->get();

        return view('pembeli.download', compact('orderItems'));
    }

    // ================= PROFILE =================
    public function profile()
    {
        return view('pembeli.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id_user . ',id_user',
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}