@extends('layouts.app')

@section('title', 'Register | Toko Kopi Sembilan')

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
    input[type="text"], input[type="email"], input[type="password"] {
        background: transparent !important;
        border: 1px solid #444444 !important;
        color: #F9F9F9 !important;
        border-radius: 0px !important;
    }
    input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
        outline: none !important;
        box-shadow: none !important;
        border-color: #F9F9F9 !important;
    }
</style>
@endsection

@section('content')
<main class="mt-32 min-h-[70vh] flex items-center justify-center px-margin-mobile md:px-margin-desktop py-stack-xl max-w-md mx-auto w-full">
    <div class="w-full border border-[#F9F9F9]/10 p-8 bg-[#1a1a1a]/40 backdrop-blur-xl">
        <header class="mb-8 text-center">
            <h1 class="font-display text-4xl uppercase italic mb-2">Register</h1>
            <p class="label-tiny opacity-60 text-xs">Create your customer account</p>
        </header>

        @if ($errors->any())
            <div class="bg-red-900/40 border border-red-500 text-red-200 px-4 py-3 mb-6 text-xs uppercase tracking-wider">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="flex flex-col gap-2">
                <label class="label-tiny text-[10px] opacity-60" for="name">Full Name</label>
                <input id="name" name="name" value="{{ old('name') }}" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-20" placeholder="ENTER YOUR NAME" required type="text">
            </div>

            <div class="flex flex-col gap-2">
                <label class="label-tiny text-[10px] opacity-60" for="email">Email Address</label>
                <input id="email" name="email" value="{{ old('email') }}" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-20" placeholder="EMAIL@DOMAIN.COM" required type="email">
            </div>

            <div class="flex flex-col gap-2">
                <label class="label-tiny text-[10px] opacity-60" for="password">Password</label>
                <input id="password" name="password" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-20" placeholder="MINIMUM 8 CHARACTERS" required type="password">
            </div>

            <div class="flex flex-col gap-2">
                <label class="label-tiny text-[10px] opacity-60" for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" class="w-full py-3 px-4 outline-none text-sm placeholder:opacity-20" placeholder="RE-ENTER PASSWORD" required type="password">
            </div>

            <button type="submit" class="w-full mt-4 bg-[#F9F9F9] text-[#121212] font-bold text-center py-5 uppercase tracking-widest hover:bg-transparent hover:text-[#F9F9F9] border border-transparent hover:border-[#F9F9F9]/25 transition-all duration-350 active:scale-[0.98] label-tiny">
                Create Account
            </button>
        </form>

        <footer class="mt-8 text-center border-t border-[#F9F9F9]/10 pt-6">
            <p class="label-tiny text-[10px] opacity-60 mb-2">Already have an account?</p>
            <a href="{{ route('login') }}" class="label-tiny text-[10px] underline underline-offset-4 hover:opacity-100 opacity-80 transition-opacity">Login Here</a>
        </footer>
    </div>
</main>
@endsection
