@extends('layouts.app')

@section('title', '年間テーマ')

@section('content')
<div>

    {{-- ─── Hero ──────────────────────────────────────────────────────────────── --}}
    <div class="pt-32 pb-16 relative overflow-hidden"
         style="background: linear-gradient(155deg, #041c33 0%, #0d5189 100%)">

        <div class="absolute inset-0 opacity-[0.05]"
             style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;"></div>
        <div class="absolute -right-40 -top-40 w-[500px] h-[500px] rounded-full border border-white/8 pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-6 md:px-14">
            <p class="fade-in text-white/35 text-[10px] tracking-[0.4em] uppercase mb-4">Annual Theme</p>
            <div class="fade-in text-white/[0.08] font-bold leading-none select-none"
                 style="font-size: clamp(5rem, 14vw, 10rem)"
                 @if($theme) data-editable data-model="annual_theme" data-id="{{ $theme->id }}" data-field="year" @endif>
                {{ $theme?->year ?? '2025' }}
            </div>
            <h1 class="fade-in text-white text-3xl md:text-4xl font-bold tracking-tight -mt-3 leading-tight"
                @if($theme) data-editable data-model="annual_theme" data-id="{{ $theme->id }}" data-field="title" @endif>
                {{ $theme?->title ?? '変動する国際秩序と日本の外交戦略' }}
            </h1>
        </div>
    </div>

    {{-- ─── Full-width photo ────────────────────────────────────────────────── --}}
    @if($theme?->photo_url)
    <div class="fade-in relative w-full overflow-hidden" style="height: 70vh; min-height: 380px; max-height: 680px">
        <img src="{{ asset('storage/' . $theme->photo_url) }}"
             alt="{{ $theme->year }}年 森聡ゼミ 集合写真"
             loading="lazy" decoding="async"
             class="w-full h-full object-cover object-center">
        {{-- top fade to blend with hero --}}
        <div class="absolute inset-x-0 top-0 h-16 pointer-events-none"
             style="background: linear-gradient(to bottom, #0d5189, transparent)"></div>
        {{-- bottom fade to blend into content --}}
        <div class="absolute inset-x-0 bottom-0 h-24 pointer-events-none"
             style="background: linear-gradient(to top, #fff, transparent)"></div>
        <div class="absolute bottom-6 right-6 text-white/50 text-[10px] tracking-[0.3em] uppercase">
            {{ $theme->year }} Seminar
        </div>
    </div>
    @endif

    {{-- ─── Content ────────────────────────────────────────────────────────── --}}
    <div class="max-w-3xl mx-auto px-6 md:px-14 {{ $theme?->photo_url ? 'pt-4 pb-16' : 'py-16' }}">
        @if($theme?->content)
        <div class="fade-in prose prose-lg prose-gray max-w-none prose-headings:font-bold prose-a:text-primary-700"
             data-editable data-model="annual_theme" data-id="{{ $theme->id }}" data-field="content">
            {!! $theme->content !!}
        </div>
        @else
        <div class="fade-in text-gray-600 text-sm leading-[2] space-y-6">
            <p>
                トランプ政権の復帰、米中対立の激化、そしてAIをはじめとする先端技術が安全保障に与える影響——
                変動する国際秩序の中で、日本はいかなる外交戦略を採るべきか。
                本ゼミでは多角的な視点からこのテーマを深く探求します。
            </p>
        </div>
        @endif
    </div>

</div>
@endsection
