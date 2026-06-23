@extends('layouts.app')

@section('title', 'Log in | Toko Kopi Sembilan')

@section('content')
<main class="min-h-[85vh] bg-white pt-20 border-b border-neutral-100 flex flex-col md:grid md:grid-cols-2">
    <!-- Kolom Kiri: Asset Gambar Kopi Premium (Hanya tampil di desktop) -->
    <div class="hidden md:block w-full h-full bg-neutral-50 overflow-hidden relative">
        <img src="{{ asset('images/login_latte_art.png') }}" alt="Toko Kopi Sembilan Login" class="w-full h-full object-cover object-center absolute inset-0">
    </div>

    <!-- Kolom Kanan: Form Log in -->
    <div class="flex flex-col justify-center items-center px-6 sm:px-12 lg:px-24 py-16 md:py-24 bg-white w-full">
        <div class="w-full max-w-[400px]">
            <header class="mb-8 text-center md:text-left">
                <h1 class="font-sans font-semibold text-3xl text-[#121212] mb-2">Log in</h1>
                <p class="font-sans text-sm text-neutral-500">Welcome back, log in if you already have an account</p>
            </header>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-xs uppercase tracking-wider mb-6">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Email Input -->
                <div class="flex flex-col gap-1.5">
                    <input id="email" name="email" value="{{ old('email') }}" type="email" placeholder="Email" class="w-full py-3.5 px-5 text-sm border border-neutral-300 rounded-none outline-none focus:ring-0 focus:border-[#121212] text-[#121212] bg-[#FFFFFF] placeholder:text-neutral-400 font-medium transition-colors" required>
                </div>

                <!-- Password Input -->
                <div class="flex flex-col gap-1.5">
                    <input id="password" name="password" type="password" placeholder="Password" class="w-full py-3.5 px-5 text-sm border border-neutral-300 rounded-none outline-none focus:ring-0 focus:border-[#121212] text-[#121212] bg-[#FFFFFF] placeholder:text-neutral-400 font-medium transition-colors" required>
                </div>

                <!-- Forgotten Password Link -->
                <div class="flex justify-end pt-1">
                    <a href="{{ route('password.request') }}" class="text-[11px] font-semibold text-[#121212] underline underline-offset-4 hover:opacity-75 transition-opacity">Forgotten password?</a>
                </div>

                <!-- Remember Me Checkbox (Optional, styled minimal) -->
                <div class="flex items-center space-x-2 pt-2">
                    <input type="checkbox" name="remember" id="remember" class="border-neutral-300 text-[#121212] focus:ring-0 rounded-none w-4 h-4">
                    <label for="remember" class="font-sans text-xs text-neutral-500 cursor-pointer select-none">Keep me logged in</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-[#121212] hover:bg-neutral-800 text-white font-semibold text-sm py-4 rounded-full tracking-wide transition-colors mt-6 block text-center cursor-pointer">
                    Log In
                </button>
            </form>

            <div class="mt-8 text-center space-y-4">
                <div class="pt-4 text-xs text-neutral-500">
                    Don't have an account yet? 
                    <a href="{{ route('register') }}" class="font-semibold text-[#121212] underline underline-offset-4 hover:opacity-75 transition-opacity ml-1">Create account</a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
