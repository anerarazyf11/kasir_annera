<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Cart;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::name($request->user()->id);
        
        $cart->applyTax([
            'id' => 1,
            'rate' => 10,
            'title' => 'Pajak PPN 10%'
        ]);

        return response()->json($cart->getDetails());
    }

    public function store(Request $request)
    {
        $id = $request->id ?? $request->produk_id;

        $produk = Produk::find($id);
        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }

        $cart = Cart::name($request->user()->id);

        $cart->addItem([
            'id' => $produk->id,
            'title' => $produk->nama_produk,
            'quantity' => 1,
            'price' => $produk->harga
        ]);

        return response()->json(['status' => 'success', 'message' => 'Berhasil ditambahkan.']);
    }

    public function update(Request $request, $hash)
    {
        $cart = Cart::name($request->user()->id);
        $item = $cart->getItem($hash);

        if (!$item) {
            return response()->json(['message' => 'Item tidak ditemukan.'], 404);
        }

        $cart->updateItem($item->getHash(), [
            'quantity' => $request->qty
        ]);

        return response()->json(['status' => 'success', 'message' => 'Berhasil diupdate.']);
    }

    public function destroy(Request $request, $hash)
    {
        $cart = Cart::name($request->user()->id);
        $cart->removeItem($hash);

        return response()->json(['status' => 'success', 'message' => 'Berhasil dihapus.']);
    }

    public function clear(Request $request)
    {
        $cart = Cart::name($request->user()->id);
        $cart->destroy();

        return back();
    }
}