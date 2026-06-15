<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja (client-side state).
     */
    public function index()
    {
        return view('keranjang');
    }
}
