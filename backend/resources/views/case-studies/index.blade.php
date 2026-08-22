@extends('layouts.app')

@section('title', 'ケーススタディ')

@section('content')
<div>
    <div class="pt-32 pb-16 relative" style="background: linear-gradient(155deg, #041c33 0%, #0d5189 100%)">
        <div class="absolute inset-0 opacity-[0.05]"
             style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;"></div>
        <div class="relative max-w-7xl mx-auto px-6 md:px-14">
            <p class="text-white/35 text-[10px] tracking-[0.4em] uppercase mb-4">Case Studies</p>
            <h1 class="text-white text-4xl md:text-5xl font-bold tracking-tight">ケーススタディ</h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 md:px-14 py-16">
        @if($caseStudies->isNotEmpty())
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($caseStudies as $cs)
            <a href="/case-studies/{{ $cs->slug }}" class="group block" data-wipe-link>
                <div class="aspect-video bg-primary-50 overflow-hidden mb-4">
                    @if($cs->thumbnail_url)
                    <img src="{{ storage_url($cs->thumbnail_url) }}" alt="{{ $cs->title }}"
                         loading="lazy" decoding="async"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div class="w-full h-full bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
                        <span class="text-primary-400 text-3xl font-bold opacity-30">Case</span>
                    </div>
                    @endif
                </div>
                <time class="text-gray-400 text-xs font-mono">{{ $cs->published_at?->format('Y.m.d') }}</time>
                <h2 class="text-gray-800 font-bold mt-1 group-hover:text-primary-700 transition-colors leading-snug"
                    data-editable data-model="case_study" data-id="{{ $cs->id }}" data-field="title">
                    {{ $cs->title }}
                </h2>
                @if($cs->description)
                <p class="text-gray-500 text-sm mt-2 leading-relaxed line-clamp-2">{{ strip_tags($cs->description) }}</p>
                @endif
                @if($cs->author)
                <p class="text-gray-400 text-xs mt-2">{{ $cs->author->name }}</p>
                @endif
            </a>
            @endforeach
        </div>
        @if($caseStudies->hasPages())
        <div class="flex justify-center gap-2 mt-12">
            {{ $caseStudies->links() }}
        </div>
        @endif
        @else
        <p class="py-16 text-center text-gray-400">まだケーススタディがありません</p>
        @endif
    </div>
</div>
@endsection
