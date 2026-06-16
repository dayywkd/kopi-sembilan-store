<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Tentukan apakah pengguna diizinkan untuk membuat request ini.
     */
    public function authorize(): bool
    {
        return true; // Set ke true agar request diproses
    }

    /**
     * Dapatkan aturan validasi yang berlaku untuk request.
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'courier' => ['required', 'string', 'in:jne,jnt,sicepat,anteraja,tiki,pos,lion,idexpress,wahana,sap,ninja,grab,gojek,rpx,paxel,deliveree,lalamove,borzo,sentralcargo,tlx,dash_express'],
            'shipping_service' => ['required', 'string', 'max:100'],
            'shipping_cost' => ['required', 'numeric', 'min:0'],
            'postal_code' => ['required', 'string', 'max:15'],
            'biteship_area_id' => ['nullable', 'string', 'max:100'],
            'biteship_area_name' => ['nullable', 'string', 'max:255'],
            'payment' => ['required', 'string', 'in:Bank Transfer,QRIS'],
            'cart_data' => ['required', 'string'], // JSON payload dari local storage
            'order_notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Kustomisasi pesan kesalahan validasi.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'Nama depan wajib diisi.',
            'last_name.required' => 'Nama belakang wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'address.required' => 'Alamat pengiriman wajib diisi.',
            'city.required' => 'Kota pengiriman wajib diisi.',
            'postal_code.required' => 'Kode pos wajib diisi.',
            'biteship_area_id.required' => 'Wilayah/Kecamatan pengiriman wajib dipilih.',
            'biteship_area_name.required' => 'Wilayah/Kecamatan pengiriman wajib dipilih.',
            'payment.required' => 'Metode pembayaran wajib dipilih.',
            'payment.in' => 'Metode pembayaran tidak valid.',
            'cart_data.required' => 'Data keranjang belanja kosong.',
        ];
    }
}
