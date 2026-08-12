@extends('layouts.app')

@section('title', ($theme?->title ?? '年間テーマ') . ' | 森聡研究会')

@section('content')
<div>
    <div class="pt-32 pb-16 relative overflow-hidden" style="background: linear-gradient(155deg, #041c33 0%, #0d5189 100%)">
        <div class="absolute inset-0 opacity-[0.05]"
             style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;"></div>
        <div class="absolute -right-40 -top-40 w-[500px] h-[500px] rounded-full border border-white/8"></div>
        <div class="relative max-w-7xl mx-auto px-6 md:px-14">
            <p class="fade-in text-white/35 text-[10px] tracking-[0.4em] uppercase mb-4">Annual Theme</p>
            <div class="fade-in text-white/12 font-bold leading-none select-none" style="font-size: clamp(4rem, 12vw, 8rem)">
                {{ $theme?->year ?? '2025' }}
            </div>
            <h1 class="fade-in text-white text-3xl md:text-4xl font-bold tracking-tight -mt-2 leading-tight"
                @if($theme) data-editable data-model="annual_theme" data-id="{{ $theme->id }}" data-field="title" @endif>
                {{ $theme?->title ?? '変動する国際秩序と日本の外交戦略' }}
            </h1>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-6 md:px-14 py-16">
        @if($theme?->content)
        <div
            class="fade-in prose prose-lg prose-gray max-w-none prose-headings:font-bold prose-a:text-primary-700"
            @if($theme) data-editable data-multiline data-model="annual_theme" data-id="{{ $theme->id }}" data-field="content" @endif
        >{!! $theme->content !!}</div>
        @else
        <div class="fade-in text-gray-600 text-sm leading-[2] space-y-6">
            <p>
                トランプ政権の復帰、米中対立の激化、そしてAIをはじめとする先端技術が安全保障に与える影響——
                変容する国際秩序の中で、日本はいかなる外交戦略を採るべきか。
                本ゼミでは多角的な視点からこのテーマを深く探求します。
            </p>
            <p>
                2025年の森聡研究会では、国際政治学の視点から現代の安全保障環境を分析し、
                日本外交の課題と展望について議論を深めます。
            </p>
        </div>
        @endif
    </div>
</div>
@endsection
