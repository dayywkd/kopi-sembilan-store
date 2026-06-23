@extends('layouts.app')

@section('title', 'Reset Password | Toko Kopi Sembilan')

@section('content')
<main class="min-h-[85vh] bg-white pt-20 border-b border-neutral-100 flex flex-col md:grid md:grid-cols-2">
    <!-- Kolom Kiri: Asset Gambar Kopi Premium (Hanya tampil di desktop) -->
    <div class="hidden md:block w-full h-full bg-neutral-50 overflow-hidden relative">
        <img src="{{ asset('images/login_latte_art.png') }}" alt="Toko Kopi Sembilan Reset Password" class="w-full h-full object-cover object-center absolute inset-0">
    </div>

    <!-- Kolom Kanan: Form Reset Password -->
    <div class="flex flex-col justify-center items-center px-6 sm:px-12 lg:px-24 py-16 md:py-24 bg-white w-full">
        <div class="w-full max-w-[400px]">
            <header class="mb-8 text-center md:text-left">
                <h1 class="font-sans font-semibold text-3xl text-[#121212] mb-2">Reset Password</h1>
                <p class="font-sans text-sm text-neutral-500">Enter your new password to update your account access.</p>
            </header>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-xs uppercase tracking-wider mb-6">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                @csrf
                
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- Password Input -->
                <div class="flex flex-col gap-1.5">
                    <input id="password" name="password" type="password" placeholder="New Password" class="w-full py-3.5 px-5 text-sm border border-neutral-300 rounded-none outline-none focus:ring-0 focus:border-[#121212] text-[#121212] bg-[#FFFFFF] placeholder:text-neutral-400 font-medium transition-colors" required>
                </div>

                <!-- Confirm Password Input -->
                <div class="flex flex-col gap-1.5">
                    <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirm New Password" class="w-full py-3.5 px-5 text-sm border border-neutral-300 rounded-none outline-none focus:ring-0 focus:border-[#121212] text-[#121212] bg-[#FFFFFF] placeholder:text-neutral-400 font-medium transition-colors" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-[#121212] hover:bg-neutral-800 text-white font-semibold text-sm py-4 rounded-full tracking-wide transition-colors mt-6 block text-center cursor-pointer">
                    Reset Password
                </button>
            </form>

            <div class="mt-8 text-center pt-4 text-xs text-neutral-500">
                Cancel resetting? 
                <a href="{{ route('login') }}" class="font-semibold text-[#121212] underline underline-offset-4 hover:opacity-75 transition-opacity ml-1">Back to Login</a>
            </div>
        </div>
    </div>
</main>
@endsection
