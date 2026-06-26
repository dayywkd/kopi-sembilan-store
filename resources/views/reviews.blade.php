@extends('layouts.app')

@section('title', 'Ulasan Pelanggan | Toko Kopi Sembilan')

@section('styles')
<style>
    .label-tiny {
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.2em;
        text-transform: uppercase;
    }
    .review-card {
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid #E5E7EB;
    }
    .review-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px -10px rgba(18, 18, 18, 0.08);
        border-color: #121212;
    }
    .avatar-circle {
        background-color: #D7D5D5; /* SOFT MIST */
        color: #000000; /* DEEP BLACK */
    }
</style>
@endsection

@section('content')
<main class="mt-32 min-h-screen pb-24 bg-white">
    
    {{-- Hero Header --}}
    <section class="px-margin-mobile md:px-margin-desktop py-16 text-center max-w-container-max mx-auto border-b border-[#E5E7EB] mb-16 reveal">
        <span class="label-tiny text-[#5B5B5B] block mb-3">TESTIMONI</span>
        <h1 class="font-display text-4xl md:text-6xl uppercase italic text-brand-dark leading-tight">Ulasan Pelanggan</h1>
        <p class="font-sans text-sm text-neutral-500 max-w-md mx-auto mt-4 leading-relaxed">
            Temukan pendapat tulus dari para pelanggan setia kami tentang cita rasa kopi terbaik dari Roastery Toko Kopi Sembilan.
        </p>
    </section>

    {{-- Review Cards Grid --}}
    <section class="px-margin-mobile md:px-margin-desktop max-w-container-max mx-auto reveal">
        @if($reviews->isEmpty())
            <div class="text-center py-20 border border-dashed border-[#E5E7EB] bg-brand-cream/10">
                <span class="material-symbols-outlined text-neutral-300 text-5xl">rate_review</span>
                <p class="font-sans text-sm text-neutral-500 mt-4">Belum ada ulasan terverifikasi saat ini.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($reviews as $review)
                    <div class="review-card bg-white p-8 flex flex-col justify-between h-full">
                        <div class="space-y-6">
                            
                            {{-- Rating Stars --}}
                            <div class="flex items-center gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="material-symbols-outlined text-[18px] {{ $i <= $review->rating ? 'text-[#000000]' : 'text-[#D7D5D5]' }}" style="font-variation-settings: 'FILL' 1, 'wght' 400">
                                        star
                                    </span>
                                @endfor
                            </div>
                            
                            {{-- Comment --}}
                            <p class="font-sans text-sm text-neutral-700 leading-relaxed italic">
                                "{{ $review->comment ?? 'Tidak ada komentar tertulis.' }}"
                            </p>
                        </div>
                        
                        {{-- User & Product Info --}}
                        <div class="mt-8 pt-6 border-t border-[#E5E7EB] flex items-center gap-4">
                            {{-- Avatar Initial --}}
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm avatar-circle flex-shrink-0">
                                {{ strtoupper(substr($review->customer_name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-sans text-sm font-bold text-neutral-800 truncate leading-tight">{{ $review->customer_name }}</h4>
                                <span class="font-sans text-[10px] text-neutral-400 block mt-0.5">{{ $review->created_at->diffForHumans() }}</span>
                                
                                @if($review->product)
                                    <a href="{{ route('product.show', $review->product->slug) }}" 
                                       class="inline-flex items-center gap-1 text-[11px] font-semibold text-[#000000] hover:text-[#5B5B5B] transition-colors mt-2 uppercase tracking-wider">
                                        <span class="material-symbols-outlined text-[12px]">local_cafe</span>
                                        {{ $review->product->name }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{-- Pagination Links --}}
            <div class="mt-16 flex justify-center">
                {{ $reviews->links() }}
            </div>
        @endif
    </section>

</main>
@endsection
