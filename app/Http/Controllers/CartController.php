<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja (client-side state).
     */
    public function index()
    {
        return view('keranjang', [
            'serverCart' => $this->cartPayload($this->queryCart()->get()),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'grind_size' => ['nullable', 'string', 'max:50'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
        ]);

        $product = Product::findOrFail($data['product_id']);
        $quantity = (int) ($data['quantity'] ?? 1);
        $grindSize = $data['grind_size'] ?? (($product->sizes[0]['size'] ?? '100gr'));

        $cartItem = $this->queryCart()
            ->where('product_id', $product->id)
            ->where('grind_size', $grindSize)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            CartItem::create($this->identity() + [
                'product_id' => $product->id,
                'grind_size' => $grindSize,
                'quantity' => $quantity,
            ]);
        }

        return response()->json(['cart' => $this->cartPayload($this->queryCart()->get())]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'grind_size' => ['required', 'string', 'max:50'],
            'quantity' => ['required', 'integer', 'min:0', 'max:99'],
        ]);

        $cartItem = $this->queryCart()
            ->where('product_id', $data['product_id'])
            ->where('grind_size', $data['grind_size'])
            ->first();

        if ($cartItem) {
            if ((int) $data['quantity'] === 0) {
                $cartItem->delete();
            } else {
                $cartItem->update(['quantity' => (int) $data['quantity']]);
            }
        }

        return response()->json(['cart' => $this->cartPayload($this->queryCart()->get())]);
    }

    public function destroy(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'grind_size' => ['required', 'string', 'max:50'],
        ]);

        $this->queryCart()
            ->where('product_id', $data['product_id'])
            ->where('grind_size', $data['grind_size'])
            ->delete();

        return response()->json(['cart' => $this->cartPayload($this->queryCart()->get())]);
    }

    public function sync(Request $request)
    {
        $data = $request->validate([
            'cart' => ['required', 'array'],
            'cart.*.id' => ['required', 'integer', 'exists:products,id'],
            'cart.*.grind_size' => ['nullable', 'string', 'max:50'],
            'cart.*.quantity' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        foreach ($data['cart'] as $item) {
            $product = Product::find($item['id']);
            if (!$product) {
                continue;
            }

            $grindSize = $item['grind_size'] ?? ($product->sizes[0]['size'] ?? '100gr');
            $cartItem = $this->queryCart()
                ->where('product_id', $product->id)
                ->where('grind_size', $grindSize)
                ->first();

            if ($cartItem) {
                $cartItem->update(['quantity' => (int) $item['quantity']]);
            } else {
                CartItem::create($this->identity() + [
                    'product_id' => $product->id,
                    'grind_size' => $grindSize,
                    'quantity' => (int) $item['quantity'],
                ]);
            }
        }

        return response()->json(['cart' => $this->cartPayload($this->queryCart()->get())]);
    }

    private function queryCart()
    {
        return CartItem::with('product')
            ->when(Auth::check(), fn ($query) => $query->where('user_id', Auth::id()))
            ->unless(Auth::check(), fn ($query) => $query->where('session_id', session()->getId()));
    }

    private function identity(): array
    {
        return Auth::check()
            ? ['user_id' => Auth::id(), 'session_id' => null]
            : ['user_id' => null, 'session_id' => session()->getId()];
    }

    private function cartPayload($items): array
    {
        return $items->filter(fn ($item) => $item->product)
            ->map(function ($item) {
                $product = $item->product;
                $price = (float) $product->price;
                foreach (($product->sizes ?? []) as $size) {
                    if (($size['size'] ?? null) === $item->grind_size) {
                        $price = (float) $size['price'];
                    }
                }

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $price,
                    'grind_size' => $item->grind_size,
                    'quantity' => $item->quantity,
                    'image' => $product->image_path ? asset($product->image_path) : '',
                ];
            })->values()->all();
    }
}
