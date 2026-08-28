@extends('layouts.app')

@section('title', 'ニュース')

@section('content')
<div>
    {{-- Page Hero --}}
    <div class="pt-32 pb-16 relative" style="background: linear-gradient(155deg, #041c33 0%, #0d5189 100%)">
        <div class="absolute inset-0 opacity-[0.05]"
             style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;"></div>
        <div class="relative max-w-7xl mx-auto px-6 md:px-14">
            <p class="text-white/35 text-[10px] tracking-[0.4em] uppercase mb-4">Latest Updates</p>
            <h1 class="text-white text-4xl md:text-5xl font-bold tracking-tight">ニュース</h1>
        </div>
    </div>

    {{-- Filter tabs --}}
    <div class="bg-white border-b border-gray-100 sticky top-[60px] z-40">
        <div class="max-w-7xl mx-auto px-6 md:px-14">
            <div class="flex gap-0 overflow-x-auto">
                @foreach($typeOptions as $key => $label)
                <a href="{{ $key === 'all' ? '/news' : '/news?type=' . $key }}"
                   class="px-5 py-4 text-sm font-medium whitespace-nowrap border-b-2 transition-colors
                          {{ $activeType === $key ? 'border-primary-700 text-primary-700' : 'border-transparent text-gray-400 hover:text-gray-700' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- List --}}
    <div class="max-w-7xl mx-auto px-6 md:px-14 py-16">
        @php
            $typeLabel = ['news'=>'NEWS','blog'=>'Blog','activity'=>'活動報告','admission'=>'入ゼミ'];
            $typeStyle = ['news'=>'bg-primary-700 text-white','blog'=>'bg-emerald-700 text-white','activity'=>'bg-amber-600 text-white','admission'=>'bg-rose-700 text-white'];
        @endphp
        <ul class="divide-y divide-gray-100">
            @forelse($posts as $item)
            <li>
                <a href="/news/{{ $item->slug }}"
                   class="relative flex flex-col sm:flex-row sm:items-center gap-3 py-5 px-4 sm:px-6 -mx-4 sm:-mx-6 group overflow-hidden" data-wipe-link>
                    {{-- Ink fill layer --}}
                    <span aria-hidden="true"
                          class="absolute inset-0 bg-primary-700 -translate-x-full group-hover:translate-x-0 transition-transform duration-500 ease-out motion-reduce:transition-none motion-reduce:translate-x-0 motion-reduce:opacity-0 motion-reduce:group-hover:opacity-100"></span>

                    <time class="relative z-10 text-gray-400 group-hover:text-white/70 text-sm font-mono w-28 shrink-0 transition-colors duration-300 delay-100">
                        {{ $item->published_at?->format('Y.m.d') }}
                    </time>
                    <span class="relative z-10 text-[10px] font-bold tracking-[0.15em] px-2.5 py-1 w-fit shrink-0 border border-transparent transition-all duration-300 delay-100 {{ $typeStyle[$item->type] ?? 'bg-gray-200 text-gray-600' }} group-hover:!bg-transparent group-hover:!text-white group-hover:border-white/60">
                        {{ $typeLabel[$item->type] ?? $item->type }}
                    </span>
                    <span class="relative z-10 flex-1 text-gray-700 group-hover:text-white text-sm md:text-[15px] font-medium transition-colors duration-300 delay-100">
                        {{ $item->title }}
                    </span>
                    <svg class="relative z-10 w-4 h-4 text-gray-300 group-hover:text-white group-hover:translate-x-1 transition-all duration-300 delay-100 shrink-0 hidden sm:block"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </li>
            @empty
            <li class="py-16 text-center text-gray-400">記事がありません</li>
            @endforelse
        </ul>

        @if($posts->hasPages())
        <div class="flex justify-center gap-2 mt-12">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
