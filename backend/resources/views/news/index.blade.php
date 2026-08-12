@extends('layouts.app')

@section('title', 'ニュース | 森聡研究会')

@section('content')
@php
$typeStyle = [
    'news'      => 'bg-primary-700 text-white',
    'blog'      => 'bg-emerald-700 text-white',
    'activity'  => 'bg-amber-600 text-white',
    'admission' => 'bg-rose-700 text-white',
];
$typeLabel = [
    'news'      => 'NEWS',
    'blog'      => 'Blog',
    'activity'  => '活動報告',
    'admission' => '入ゼミ',
];
@endphp
<div>
    <div class="pt-32 pb-16 relative" style="background: linear-gradient(155deg, #041c33 0%, #0d5189 100%)">
        <div class="absolute inset-0 opacity-[0.05]"
             style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;"></div>
        <div class="relative max-w-7xl mx-auto px-6 md:px-14">
            <p class="fade-in text-white/35 text-[10px] tracking-[0.4em] uppercase mb-4">Latest Updates</p>
            <h1 class="fade-in text-white text-4xl md:text-5xl font-bold tracking-tight">ニュース</h1>
        </div>
    </div>

    {{-- フィルタータブ --}}
    <div class="bg-white border-b border-gray-100 sticky top-[60px] z-40">
        <div class="max-w-7xl mx-auto px-6 md:px-14">
            <div class="flex gap-0 overflow-x-auto">
                @foreach($typeOptions as $val => $label)
                <a
                    href="{{ url('/news') . ($val !== 'all' ? '?type=' . $val : '') }}"
                    class="px-5 py-4 text-sm font-medium whitespace-nowrap border-b-2 transition-colors
                           {{ $activeType === $val ? 'border-primary-700 text-primary-700' : 'border-transparent text-gray-400 hover:text-gray-700' }}"
                >{{ $label }}</a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- リスト --}}
    <div class="max-w-7xl mx-auto px-6 md:px-14 py-16">
        <ul class="divide-y divide-gray-100">
            @forelse($posts as $item)
            <li class="fade-in">
                <a href="/news/{{ $item->slug }}" class="flex flex-col sm:flex-row sm:items-center gap-3 py-5 group">
                    <time class="text-gray-400 text-sm font-mono w-28 shrink-0">
                        {{ $item->published_at?->format('Y.m.d') }}
                    </time>
                    <span class="text-[10px] font-bold tracking-[0.15em] px-2.5 py-1 w-fit shrink-0
                                 {{ $typeStyle[$item->type] ?? 'bg-gray-200 text-gray-600' }}">
                        {{ $typeLabel[$item->type] ?? $item->type }}
                    </span>
                    <span class="flex-1 text-gray-700 text-sm md:text-[15px] font-medium group-hover:text-primary-700 transition-colors">
                        {{ $item->title }}
                    </span>
                    <svg class="w-4 h-4 text-gray-300 group-hover:text-primary-700 group-hover:translate-x-1 transition-all shrink-0 hidden sm:block"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </li>
            @empty
            <li class="py-16 text-center text-gray-400">記事がありません</li>
            @endforelse
        </ul>

        {{ $posts->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection
