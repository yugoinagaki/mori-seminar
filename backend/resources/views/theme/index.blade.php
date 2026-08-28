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
            <div class="fade-in text-white/[0.08] font-bold leading-none select-none font-mincho"
                 style="font-size: clamp(5rem, 14vw, 10rem)">
                {{ $latestYear ?? date('Y') }}
            </div>
            @if($currentThemes->count() === 1 && !$currentThemes->first()->semester)
                {{-- Single year-wide theme: keep original hero with title --}}
                <h1 class="fade-in text-white text-3xl md:text-4xl font-bold tracking-tight -mt-3 leading-tight"
                    data-editable data-model="annual_theme" data-id="{{ $currentThemes->first()->id }}" data-field="title">
                    {{ $currentThemes->first()->title }}
                </h1>
            @elseif($currentThemes->isEmpty())
                <h1 class="fade-in text-white text-3xl md:text-4xl font-bold tracking-tight -mt-3 leading-tight">
                    変動する国際秩序と日本の外交戦略
                </h1>
            @endif
        </div>
    </div>

    {{-- ─── Current year: one photo + one section per theme ──────────────────── --}}
    @php $currentPhoto = $latestYear ? $themeYears[$latestYear]?->photo_url : null; @endphp

    @if($currentPhoto)
    <div class="fade-in relative w-full overflow-hidden" style="height: 60vh; min-height: 360px; max-height: 640px">
        <img src="{{ asset('storage/' . $currentPhoto) }}"
             alt="{{ $latestYear }}年 森聡ゼミ 集合写真"
             loading="lazy" decoding="async"
             class="w-full h-full object-cover object-center">
        <div class="absolute inset-x-0 top-0 h-16 pointer-events-none"
             style="background: linear-gradient(to bottom, #0d5189, transparent)"></div>
        <div class="absolute inset-x-0 bottom-0 h-24 pointer-events-none"
             style="background: linear-gradient(to top, #fff, transparent)"></div>
        <div class="absolute bottom-6 right-6 text-white/60 text-[10px] tracking-[0.3em] uppercase">
            {{ $latestYear }} Seminar
        </div>
    </div>
    @endif

    @foreach($currentThemes as $index => $theme)
    <section class="{{ $index > 0 ? 'border-t border-gray-100' : '' }}">
        <div class="max-w-3xl mx-auto px-6 md:px-14 {{ ($index === 0 && $currentPhoto) ? 'pt-8 pb-16' : 'pt-16 pb-16' }}">
            @if($theme->semester)
            <div class="fade-in mb-6 flex items-center gap-3">
                <span class="text-[11px] px-3 py-1 border border-primary-700 text-primary-700 tracking-widest font-medium">
                    {{ $theme->semesterLabel() }}
                </span>
            </div>
            @endif

            @if($currentThemes->count() > 1 || $theme->semester)
            <h2 class="fade-in text-2xl md:text-3xl font-bold text-gray-900 tracking-tight leading-tight mb-8 font-mincho"
                data-editable data-model="annual_theme" data-id="{{ $theme->id }}" data-field="title">
                {{ $theme->title }}
            </h2>
            @endif

            @if($theme->content)
            <div class="fade-in prose prose-lg prose-gray max-w-none prose-headings:font-bold prose-a:text-primary-700"
                 data-editable data-model="annual_theme" data-id="{{ $theme->id }}" data-field="content">
                {!! $theme->content !!}
            </div>
            @endif
        </div>
    </section>
    @endforeach

    @if($currentThemes->isEmpty())
    <div class="max-w-3xl mx-auto px-6 md:px-14 py-16">
        <div class="fade-in text-gray-600 text-sm leading-[2] space-y-6">
            <p>
                トランプ政権の復帰、米中対立の激化、そしてAIをはじめとする先端技術が安全保障に与える影響——
                変動する国際秩序の中で、日本はいかなる外交戦略を採るべきか。
                本ゼミでは多角的な視点からこのテーマを深く探求します。
            </p>
        </div>
    </div>
    @endif

    {{-- ─── Archive ─────────────────────────────────────────────────────────── --}}
    @if($archive->isNotEmpty())
    <section class="relative border-t border-gray-100"
             style="background: linear-gradient(180deg, #fafafa 0%, #f4f4f5 100%)">
        <div class="max-w-5xl mx-auto px-6 md:px-14 py-24 md:py-32">
            <div class="fade-in mb-16 md:mb-20">
                <p class="text-[10px] tracking-[0.4em] uppercase text-gray-400 mb-3">Archive</p>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 font-mincho tracking-tight">過去のテーマ</h2>
                <p class="text-gray-500 text-sm mt-3">これまでのゼミが取り組んできた研究テーマ</p>
            </div>

            <div class="space-y-16 md:space-y-24">
                @foreach($archive as $year => $themes)
                @php $yearPhoto = $themeYears[$year]?->photo_url ?? null; @endphp
                <div class="fade-in group relative">
                    <div class="grid grid-cols-1 md:grid-cols-[auto_1fr] gap-6 md:gap-14">
                        {{-- Year + photo --}}
                        <div class="md:sticky md:top-24 self-start md:w-56">
                            <p class="text-[10px] tracking-[0.4em] uppercase text-gray-400 mb-1">Year</p>
                            <div class="text-5xl md:text-6xl font-bold text-gray-300 group-hover:text-primary-700 font-mincho leading-none tracking-tight transition-colors duration-500">
                                {{ $year }}
                            </div>
                            <div class="w-8 h-px bg-gray-200 group-hover:bg-primary-700 group-hover:w-16 mt-4 transition-all duration-500"></div>

                            @if($yearPhoto)
                            <div class="mt-6 aspect-[4/3] overflow-hidden bg-gray-100">
                                <img src="{{ asset('storage/' . $yearPhoto) }}"
                                     alt="{{ $year }}年 集合写真"
                                     loading="lazy" decoding="async"
                                     class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-105">
                            </div>
                            @endif
                        </div>

                        {{-- Themes for this year --}}
                        <div class="space-y-10 pt-1">
                            @foreach($themes as $t)
                            <article>
                                @if($t->semester)
                                <span class="text-[10px] px-2.5 py-0.5 border border-primary-700 text-primary-700 tracking-widest font-medium inline-block mb-3">
                                    {{ $t->semesterLabel() }}
                                </span>
                                @endif
                                <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3 leading-snug font-mincho tracking-wide">
                                    {{ $t->title }}
                                </h3>
                                @if($t->content)
                                <p class="text-sm text-gray-500 leading-relaxed line-clamp-3">
                                    {{ Str::limit(strip_tags($t->content), 200) }}
                                </p>
                                @endif
                            </article>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

</div>
@endsection
