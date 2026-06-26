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
        $rules = [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:25'],
            'payment' => ['required', 'string', 'in:Bank Transfer,QRIS,Transfer BCA,Transfer BRI'],
            'cart_data' => ['nullable', 'string'],
            'order_notes' => ['nullable', 'string', 'max:500'],
            'delivery_method' => ['required', 'string', 'in:shipping,pickup'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
        ];

        if ($this->input('delivery_method') === 'pickup') {
            $rules['address'] = ['nullable', 'string'];
            $rules['city'] = ['nullable', 'string', 'max:100'];
            $rules['postal_code'] = ['nullable', 'string', 'max:15'];
            $rules['courier'] = ['nullable', 'string'];
            $rules['shipping_service'] = ['nullable', 'string', 'max:100'];
            $rules['shipping_cost'] = ['nullable', 'numeric', 'min:0'];
            $rules['biteship_area_id'] = ['nullable', 'string', 'max:100'];
            $rules['biteship_area_name'] = ['nullable', 'string', 'max:255'];
        } else {
            $rules['address'] = ['required', 'string'];
            $rules['city'] = ['required', 'string', 'max:100'];
            $rules['postal_code'] = ['required', 'string', 'max:15'];
            $rules['courier'] = ['required', 'string', 'in:jne,jnt,sicepat,anteraja,tiki,pos,lion,idexpress,wahana,sap,ninja,grab,gojek,rpx,paxel,deliveree,lalamove,borzo,sentralcargo,tlx,dash_express,pickup'];
            $rules['shipping_service'] = ['required', 'string', 'max:100'];
            $rules['shipping_cost'] = ['required', 'numeric', 'min:0'];
            $rules['biteship_area_id'] = ['nullable', 'string', 'max:100'];
            $rules['biteship_area_name'] = ['nullable', 'string', 'max:255'];
        }

        return $rules;
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
            'phone.required' => 'Nomor HP/Telepon wajib diisi.',
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
