@extends('layouts.app')

@section('title', $cs->title)
@section('description', strip_tags($cs->description ?? ''))

@section('content')
<div>
    <div class="pt-32 pb-16 relative" style="background: linear-gradient(155deg, #041c33 0%, #0d5189 100%)">
        <div class="absolute inset-0 opacity-[0.05]"
             style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;"></div>
        <div class="relative max-w-4xl mx-auto px-6 md:px-14">
            <a href="/case-studies" class="inline-flex items-center gap-2 text-white/40 hover:text-white text-xs tracking-widest uppercase mb-6 transition-colors" data-wipe-link>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                ケーススタディ一覧
            </a>
            <div class="flex items-center gap-3 mb-5">
                <time class="text-white/40 text-sm font-mono">{{ $cs->published_at?->format('Y.m.d') }}</time>
                @if($cs->author)
                <span class="text-white/40 text-sm">{{ $cs->author->name }}</span>
                @endif
            </div>
            <h1 class="text-white text-2xl md:text-4xl font-bold leading-tight"
                data-editable data-model="case_study" data-id="{{ $cs->id }}" data-field="title">
                {{ $cs->title }}
            </h1>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-6 md:px-14 py-16">
        @if($cs->content)
        <div class="prose prose-lg prose-gray max-w-none prose-headings:font-bold prose-a:text-primary-700"
             data-editable data-model="case_study" data-id="{{ $cs->id }}" data-field="content">
            {!! $cs->content !!}
        </div>
        @else
        <p class="text-gray-400">本文がありません。</p>
        @endif

        <div class="mt-12 pt-8 border-t border-gray-100">
            <a href="/case-studies" class="inline-flex items-center gap-2 text-primary-700 text-sm font-medium hover:gap-3 transition-all" data-wipe-link>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
                </svg>
                一覧に戻る
            </a>
        </div>
    </div>
</div>
@endsection
