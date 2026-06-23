@extends('layouts.app')

@section('title', 'Sign up | Toko Kopi Sembilan')

@section('content')
<main class="min-h-[85vh] bg-white pt-20 border-b border-neutral-100 flex flex-col md:grid md:grid-cols-2">
    <!-- Kolom Kiri: Asset Gambar Kopi Premium (Hanya tampil di desktop) -->
    <div class="hidden md:block w-full h-full bg-neutral-50 overflow-hidden relative">
        <img src="{{ asset('images/register_pouring_beans.png') }}" alt="Toko Kopi Sembilan Register" class="w-full h-full object-cover object-center absolute inset-0">
    </div>

    <!-- Kolom Kanan: Form Sign up -->
    <div class="flex flex-col justify-center items-center px-6 sm:px-12 lg:px-24 py-16 md:py-24 bg-white w-full">
        <div class="w-full max-w-[400px]">
            <header class="mb-8 text-center md:text-left">
                <h1 class="font-sans font-semibold text-3xl text-[#121212] mb-2">Sign up</h1>
                <p class="font-sans text-sm text-neutral-500">Hello and thank you for signing up.</p>
            </header>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-xs uppercase tracking-wider mb-6">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" id="register-form" class="space-y-5">
                @csrf
                
                <!-- Hidden fields for backend compatibility -->
                <input type="hidden" name="name" id="hidden-name">
                <input type="hidden" name="password_confirmation" id="hidden-password-confirmation">

                <!-- First Name Input -->
                <div class="flex flex-col gap-1.5">
                    <input type="text" id="first_name" placeholder="First Name" class="w-full py-3.5 px-5 text-sm border border-neutral-300 rounded-none outline-none focus:ring-0 focus:border-[#121212] text-[#121212] bg-[#FFFFFF] placeholder:text-neutral-400 font-medium transition-colors" required>
                </div>

                <!-- Last Name Input -->
                <div class="flex flex-col gap-1.5">
                    <input type="text" id="last_name" placeholder="Last Name" class="w-full py-3.5 px-5 text-sm border border-neutral-300 rounded-none outline-none focus:ring-0 focus:border-[#121212] text-[#121212] bg-[#FFFFFF] placeholder:text-neutral-400 font-medium transition-colors" required>
                </div>

                <!-- Email Input -->
                <div class="flex flex-col gap-1.5">
                    <input id="email" name="email" value="{{ old('email') }}" type="email" placeholder="Email" class="w-full py-3.5 px-5 text-sm border border-neutral-300 rounded-none outline-none focus:ring-0 focus:border-[#121212] text-[#121212] bg-[#FFFFFF] placeholder:text-neutral-400 font-medium transition-colors" required>
                </div>

                <!-- Password Input -->
                <div class="flex flex-col gap-1.5">
                    <input id="password" name="password" type="password" placeholder="Password" class="w-full py-3.5 px-5 text-sm border border-neutral-300 rounded-none outline-none focus:ring-0 focus:border-[#121212] text-[#121212] bg-[#FFFFFF] placeholder:text-neutral-400 font-medium transition-colors" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-[#121212] hover:bg-neutral-800 text-white font-semibold text-sm py-4 rounded-full tracking-wide transition-colors mt-6 block text-center cursor-pointer">
                    Create Account
                </button>
            </form>

            <div class="mt-8 text-center pt-4 text-xs text-neutral-500">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-semibold text-[#121212] underline underline-offset-4 hover:opacity-75 transition-opacity ml-1">Login Here</a>
            </div>
        </div>
    </div>
</main>

<script>
    document.getElementById('register-form').addEventListener('submit', function(e) {
        const firstName = document.getElementById('first_name').value.trim();
        const lastName = document.getElementById('last_name').value.trim();
        const password = document.getElementById('password').value;

        // Gabungkan First Name & Last Name ke hidden input 'name'
        document.getElementById('hidden-name').value = firstName + ' ' + lastName;

        // Salin password ke hidden input 'password_confirmation'
        document.getElementById('hidden-password-confirmation').value = password;
    });
</script>
@endsection
