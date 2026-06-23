@extends('layouts.app')

@section('title', 'Wholesale B2B | Toko Kopi Sembilan')

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
    input[type="text"], input[type="email"], select, textarea {
        background: transparent !important;
        border: 1px solid #d1d5db !important;
        color: #121212 !important;
        border-radius: 0px !important;
    }
    input[type="text"]:focus, input[type="email"]:focus, select:focus, textarea:focus {
        outline: none !important;
        box-shadow: none !important;
        border-color: #121212 !important;
    }
    .btn-dark {
        background-color: #121212;
        color: #ffffff;
        border: 1px solid #121212;
        transition: all 0.3s ease;
        font-weight: 700;
    }
    .btn-dark:hover {
        background-color: transparent;
        border-color: #121212;
        color: #121212;
    }
</style>
@endsection

@section('content')
<main class="pt-32 min-h-screen bg-white">
    <div class="max-w-2xl mx-auto px-margin-mobile py-16">
        <div class="mb-12 text-center space-y-4">
            <p class="label-tiny text-neutral-400">Kemitraan B2B</p>
            <h1 class="font-display text-4xl md:text-5xl italic font-bold text-[#121212]">Wholesale Coffee.</h1>
            <p class="text-sm text-neutral-500 font-light leading-relaxed max-w-lg mx-auto">
                Tertarik menyajikan kopi spesial kami di cafe, kantor, atau hotel Anda? Isi formulir di bawah ini untuk mengajukan proposal kerjasama, tim kami akan segera menghubungi Anda.
            </p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 mb-8 text-xs font-bold uppercase tracking-widest text-center">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ url('/wholesale') }}" method="POST" class="space-y-6 font-sans">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Contact Name -->
                <div class="flex flex-col gap-2">
                    <label class="label-tiny text-[10px] opacity-60">Nama Kontak</label>
                    <input name="contact_name" value="{{ old('contact_name') }}" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-40" placeholder="Contoh: Budi Santoso" required type="text">
                    @error('contact_name')
                        <span class="text-xs text-red-500 uppercase tracking-widest">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Business Name -->
                <div class="flex flex-col gap-2">
                    <label class="label-tiny text-[10px] opacity-60">Nama Bisnis / Toko</label>
                    <input name="business_name" value="{{ old('business_name') }}" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-40" placeholder="Contoh: Sembilan Coffee Shop" required type="text">
                    @error('business_name')
                        <span class="text-xs text-red-500 uppercase tracking-widest">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email -->
                <div class="flex flex-col gap-2">
                    <label class="label-tiny text-[10px] opacity-60">Alamat Email</label>
                    <input name="email" value="{{ old('email') }}" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-40" placeholder="email@domain.com" required type="email">
                    @error('email')
                        <span class="text-xs text-red-500 uppercase tracking-widest">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="flex flex-col gap-2">
                    <label class="label-tiny text-[10px] opacity-60">Nomor Telepon / HP</label>
                    <input name="phone" value="{{ old('phone') }}" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-40" placeholder="08XXXXXXXXXX" required type="text">
                    @error('phone')
                        <span class="text-xs text-red-500 uppercase tracking-widest">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Message -->
            <div class="flex flex-col gap-2">
                <label class="label-tiny text-[10px] opacity-60">Pesan Kerjasama & Kebutuhan Kopi</label>
                <textarea name="message" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-40 resize-none min-h-[120px]" placeholder="Jelaskan kebutuhan volume kopi bulanan Anda atau profil rasa kopi yang Anda inginkan..." required>{{ old('message') }}</textarea>
                @error('message')
                    <span class="text-xs text-red-500 uppercase tracking-widest">{{ $message }}</span>
                @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-4 btn-dark label-tiny tracking-wider">
                    KIRIM PENGAJUAN KERJASAMA
                </button>
            </div>
        </form>
    </div>
</main>
@endsection
