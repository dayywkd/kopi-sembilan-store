@extends('layouts.app')

@section('title', 'Lacak Pesanan | Toko Kopi Sembilan')

@section('styles')
<style>
    .label-tiny {
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.15em;
        text-transform: uppercase;
    }
    @media (min-width: 768px) {
        .label-tiny {
            font-size: 12px;
            letter-spacing: 0.25em;
        }
    }
    .btn-dark {
        background-color: #121212;
        color: #ffffff;
        border: 1px solid #121212;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        font-weight: 700;
    }
    .btn-dark:hover {
        background-color: transparent;
        color: #121212;
    }
</style>
@endsection

@section('content')
<main class="min-h-[80vh] flex items-center justify-center pt-32 pb-16 bg-white px-margin-mobile md:px-margin-desktop">
    <div class="w-full max-w-md border border-[#E5E7EB] p-8 md:p-10 shadow-sm space-y-8 bg-[#FFFFFF] reveal">
        <div class="text-center space-y-2">
            <p class="label-tiny text-neutral-400">Order Tracking</p>
            <h1 class="font-display text-3xl font-bold italic text-brand-dark">Lacak Pesanan</h1>
            <p class="font-sans text-xs text-neutral-400 max-w-xs mx-auto leading-relaxed">
                Masukkan Kode Pesanan Anda dan Email/Nomor Telepon untuk melihat status pengiriman pesanan Anda.
            </p>
        </div>

        @if ($errors->has('tracking_error'))
            <div class="bg-brand-cream border border-brand-accent/20 text-brand-accent p-4 text-xs uppercase tracking-wider font-bold text-center">
                {{ $errors->first('tracking_error') }}
            </div>
        @endif

        <form action="{{ route('order.tracking.search') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label for="transaction_id" class="label-tiny text-[10px] opacity-60 block font-bold text-neutral-500">Kode Pesanan</label>
                <input type="text" name="transaction_id" id="transaction_id" required 
                       class="w-full py-3 px-4 outline-none text-sm bg-white border border-[#E5E7EB] text-brand-dark focus:border-[#121212] transition-colors"
                       placeholder="KS9-XXXXXX" value="{{ old('transaction_id') }}">
                @error('transaction_id')
                    <p class="text-red-500 text-[10px] uppercase font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="email_or_phone" class="label-tiny text-[10px] opacity-60 block font-bold text-neutral-500">Email atau No. Telepon</label>
                <input type="text" name="email_or_phone" id="email_or_phone" required 
                       class="w-full py-3 px-4 outline-none text-sm bg-white border border-[#E5E7EB] text-brand-dark focus:border-[#121212] transition-colors"
                       placeholder="email@example.com / 08123xxxx" value="{{ old('email_or_phone') }}">
                @error('email_or_phone')
                    <p class="text-red-500 text-[10px] uppercase font-bold">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full btn-dark py-4 text-xs uppercase tracking-widest font-bold font-sans">
                Cari Pesanan
            </button>
        </form>
    </div>
</main>
@endsection
