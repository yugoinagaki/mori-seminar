@extends('layouts.app')

@section('title', $post->title . ' | 森聡研究会')
@section('description', $post->excerpt ?? '慶應義塾大学 森聡研究会の公式サイト。国際政治学・現代アメリカ外交・先端技術を研究しています。')

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
        <div class="relative max-w-4xl mx-auto px-6 md:px-14">
            <a href="/news" class="fade-in inline-flex items-center gap-2 text-white/40 hover:text-white text-xs tracking-widest uppercase mb-6 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                ニュース一覧
            </a>
            <div class="fade-in flex items-center gap-3 mb-5">
                <span class="text-[10px] font-bold tracking-[0.15em] px-2.5 py-1
                             {{ $typeStyle[$post->type] ?? 'bg-gray-600 text-white' }}">
                    {{ $typeLabel[$post->type] ?? $post->type }}
                </span>
                <time class="text-white/40 text-sm font-mono">
                    {{ $post->published_at?->format('Y.m.d') }}
                </time>
            </div>
            <h1 class="fade-in text-white text-2xl md:text-4xl font-bold leading-tight"
                data-editable data-model="post" data-id="{{ $post->id }}" data-field="title">{{ $post->title }}</h1>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-6 md:px-14 py-16">
        @if($post->content)
        <div
            class="fade-in prose prose-lg prose-gray max-w-none
                   prose-headings:font-bold prose-headings:text-gray-900
                   prose-a:text-primary-700 prose-a:no-underline hover:prose-a:underline
                   prose-img:rounded prose-img:w-full"
            data-editable data-multiline data-model="post" data-id="{{ $post->id }}" data-field="content"
        >{!! $post->content !!}</div>
        @else
        <p class="fade-in text-gray-400">本文がありません。</p>
        @endif

        @if($post->tags->count())
        <div class="fade-in flex flex-wrap gap-2 mt-12 pt-8 border-t border-gray-100">
            @foreach($post->tags as $tag)
            <span class="text-xs px-3 py-1.5 bg-primary-50 text-primary-700 font-medium">{{ $tag->name }}</span>
            @endforeach
        </div>
        @endif

        <div class="fade-in mt-12 pt-8 border-t border-gray-100">
            <a href="/news" class="inline-flex items-center gap-2 text-primary-700 text-sm font-medium hover:gap-3 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
                </svg>
                ニュース一覧に戻る
            </a>
        </div>
    </div>
</div>
@endsection
