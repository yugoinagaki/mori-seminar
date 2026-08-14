@extends('layouts.app')

@section('title', 'ホーム')

@section('content')
<div class="overflow-x-hidden">

{{-- HERO --}}
<section class="relative min-h-screen flex flex-col justify-center overflow-hidden">
    <div class="absolute inset-0">
        @if($setting->hero_image_url)
            <img src="{{ storage_url($setting->hero_image_url) }}" alt="" class="w-full h-full object-cover object-center">
        @else
            <div class="w-full h-full" style="background: linear-gradient(155deg, #041c33 0%, #083a63 45%, #0d5189 100%)"></div>
        @endif
    </div>
    <div class="absolute inset-0 bg-primary-950/60"></div>
    <div class="absolute inset-0 opacity-[0.04]"
         style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;"></div>
    @if(!$setting->hero_image_url)
    <div class="absolute right-0 top-0 bottom-0 w-1/2 opacity-20 pointer-events-none"
         style="background: radial-gradient(ellipse at 80% 40%, #1a79c0, transparent 70%)"></div>
    @endif
    <div class="absolute top-0 left-0 right-0 h-36 pointer-events-none"
         style="background: linear-gradient(to bottom, rgba(4,28,51,0.75) 0%, transparent 100%)"></div>
    <div class="absolute bottom-0 left-0 right-0 h-40"
         style="background: linear-gradient(to bottom, transparent, rgba(4,28,51,0.7))"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-14 pt-36 pb-28">
        <div class="text-center md:text-left">
            <h1 class="text-white font-bold mb-6 leading-none"
                id="hero-title"
                style="font-size: clamp(2.8rem, 8vw, 6rem); letter-spacing: 0.1em; opacity: 0">
            </h1>

            <div id="hero-buttons"
                 class="flex flex-wrap justify-center md:justify-start gap-4"
                 style="opacity: 0; transform: translateY(10px)">
                <a href="/theme" class="btn-outline-white" data-wipe-link>
                    研究テーマについて
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
                <a href="/professor" class="btn-outline-white" data-wipe-link>
                    森聡教授について
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    {{-- 右上ニュースカード（デスクトップのみ） --}}
    @if(count($worldNews) > 0)
    <div class="hidden lg:block fixed top-28 right-4 w-64 z-40 hero-sub" style="animation-delay: 2.6s"
         id="news-widget">
        <div id="news-widget-header" class="flex justify-end mb-1">
            <button id="news-widget-close"
                    class="flex items-center justify-center w-5 h-5 rounded-full bg-primary-900/80 hover:bg-primary-700 text-white/80 hover:text-white transition-all border border-white/20"
                    aria-label="閉じる">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                </svg>
            </button>
        </div>
        <a id="news-card-container"
           href="{{ $worldNews[0]['link'] ?? '#' }}" target="_blank" rel="noopener"
           class="block border border-white/15 backdrop-blur-sm overflow-hidden hover:border-white/30 transition-colors"
           style="background: rgba(4, 18, 35, 0.78)">
            <div class="flex items-center justify-between px-4 py-2.5 border-b border-white/10">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-1.5 w-1.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-white/50 text-[9px] tracking-[0.25em] uppercase">国際情勢 · NHK</span>
                </div>
                <span id="news-counter" class="text-white/25 text-[9px] font-mono">1 / {{ count($worldNews) }}</span>
            </div>
            <div class="px-4 py-4">
                <p id="news-title" class="text-white/85 text-sm leading-relaxed font-medium">
                    {{ $worldNews[0]['title'] ?? '' }}
                </p>
                <p id="news-time" class="text-white/30 text-[10px] font-mono mt-3"></p>
            </div>
            <div class="h-px bg-white/10">
                <div id="news-progress" class="h-full bg-primary-400 transition-none" style="width:0%"></div>
            </div>
            <div class="flex items-center justify-between px-4 py-2.5">
                <div id="news-dots" class="flex gap-1">
                    @foreach($worldNews as $i => $item)
                    <span class="block rounded-full transition-all duration-300 {{ $i === 0 ? 'w-3 h-1 bg-white/60' : 'w-1 h-1 bg-white/20' }}"></span>
                    @endforeach
                </div>
                <svg class="w-3 h-3 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </div>
        </a>
        <button id="news-widget-show"
                class="hidden ml-auto mt-1 items-center gap-1.5 px-3 py-1.5 text-white/70 hover:text-white text-xs border border-white/20 backdrop-blur-sm transition-colors"
                style="background: rgba(4, 18, 35, 0.7)">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Newsを表示
        </button>
    </div>
    @endif
</section>

{{-- TICKER --}}
@if(count($recentPosts) > 0)
<div class="bg-primary-700 py-2.5 overflow-hidden select-none">
    <div class="ticker-track">
        @php
            $tickerItems = $recentPosts->map(fn($p) => [
                'slug'  => ($p->type === 'blog' ? '/blog/' : '/news/') . $p->slug,
                'label' => ($p->published_at?->format('Y.m.d') ?? '') . ' ── ' . $p->title,
            ]);
        @endphp
        @foreach([$tickerItems, $tickerItems] as $pass)
            @foreach($pass as $item)
            <a href="{{ $item['slug'] }}"
               class="text-white/80 hover:text-white text-xs tracking-wide font-light mx-10 whitespace-nowrap transition-colors"
               data-wipe-link>
                <span class="text-white/30 mr-6">◆</span>{{ $item['label'] }}
            </a>
            @endforeach
        @endforeach
    </div>
</div>
@endif

{{-- ANNUAL THEME --}}
@if($theme)
@php $slideshowPhotos = array_values(array_filter((array)($theme->slideshow_photo_urls ?? []))); @endphp
<section class="py-28 relative overflow-hidden"
         style="background: linear-gradient(140deg, #0d5189 0%, #1a79c0 100%)">
    {{-- decorative circles (only when no slideshow) --}}
    @if(empty($slideshowPhotos))
    <div class="absolute -right-40 -top-40 w-[500px] h-[500px] rounded-full border border-white/8 pointer-events-none"></div>
    <div class="absolute -right-20 -top-20 w-[360px] h-[360px] rounded-full border border-white/6 pointer-events-none"></div>
    @endif

    <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-14">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center fade-in">

            {{-- LEFT: text --}}
            <div>
                <p class="text-white/35 text-[10px] tracking-[0.35em] uppercase font-light mb-8">Annual Theme</p>
                <div class="text-white/12 font-bold leading-none mb-2 select-none"
                     style="font-size: clamp(5rem, 15vw, 10rem)">{{ $theme->year }}</div>
                <h2 class="text-white text-2xl md:text-3xl font-bold mb-10 leading-tight -mt-2"
                    data-editable data-model="annual_theme" data-id="{{ $theme->id }}" data-field="title">
                    {{ $theme->title }}
                </h2>
                <a href="/theme"
                   class="inline-flex items-center gap-3 text-white text-sm font-medium border-b border-white/35 pb-0.5 hover:border-white hover:gap-4 transition-all"
                   data-wipe-link>
                    テーマ詳細を読む
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            {{-- RIGHT: slideshow --}}
            @if(!empty($slideshowPhotos))
            <div id="home-slideshow"
                 class="relative overflow-hidden select-none"
                 style="aspect-ratio: 4/3">
                {{-- slides --}}
                @foreach($slideshowPhotos as $i => $photo)
                <div class="kb-slide absolute inset-0 {{ $i === 0 ? 'is-active' : '' }}"
                     style="opacity: {{ $i === 0 ? 1 : 0 }}; transition: opacity 1.2s ease">
                    <div class="kb-inner absolute inset-0 bg-cover bg-center"
                         @if($i === 0)
                         style="background-image: url('{{ storage_url($photo) }}')"
                         @else
                         data-bg="{{ storage_url($photo) }}"
                         @endif
                    ></div>
                    <div class="absolute inset-0" style="background: rgba(4,28,51,0.18)"></div>
                </div>
                @endforeach

                {{-- dots --}}
                @if(count($slideshowPhotos) > 1)
                <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-10">
                    @foreach($slideshowPhotos as $i => $photo)
                    <button class="kb-dot transition-all duration-300 rounded-full {{ $i === 0 ? 'w-5 h-1.5 bg-white' : 'w-1.5 h-1.5 bg-white/40' }}"
                            data-index="{{ $i }}" aria-label="スライド {{ $i + 1 }}"></button>
                    @endforeach
                </div>
                @endif
            </div>
            @endif

        </div>
    </div>
</section>
@endif

{{-- NEWS --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 md:px-14">
        <div class="flex items-end justify-between mb-12">
            <div class="fade-in">
                <p class="section-label">Latest Updates</p>
                <h2 class="section-title">ニュース</h2>
            </div>
            <a href="/news" class="hidden md:inline-flex items-center gap-2 text-primary-700 text-sm font-medium hover:gap-3 transition-all fade-in" data-wipe-link>
                一覧を見る
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
        @php
            $typeLabel = ['news'=>'NEWS','blog'=>'Blog','activity'=>'活動報告','admission'=>'入ゼミ'];
            $typeStyle = ['news'=>'bg-primary-700 text-white','blog'=>'bg-emerald-700 text-white','activity'=>'bg-amber-600 text-white','admission'=>'bg-rose-700 text-white'];
        @endphp
        <ul class="divide-y divide-gray-100">
            @forelse($recentPosts as $item)
            <li class="fade-in">
                <a href="{{ $item->type === 'blog' ? '/blog/' : '/news/' }}{{ $item->slug }}"
                   class="flex flex-col sm:flex-row sm:items-center gap-3 py-5 group" data-wipe-link>
                    <time class="text-gray-400 text-sm font-mono w-28 shrink-0">
                        {{ $item->published_at?->format('Y.m.d') }}
                    </time>
                    <span class="text-[10px] font-bold tracking-[0.15em] px-2.5 py-1 w-fit shrink-0 {{ $typeStyle[$item->type] ?? 'bg-gray-200 text-gray-600' }}">
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
            <li class="py-10 text-center text-gray-400 text-sm">まだ記事がありません</li>
            @endforelse
        </ul>
    </div>
</section>

{{-- PROFESSOR --}}
@if($professor)
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 md:px-14">
        <div class="grid md:grid-cols-2 gap-16 lg:gap-24 items-center">
            <div class="fade-in relative">
                <div class="aspect-[4/5] overflow-hidden bg-primary-100">
                    <img src="{{ storage_url($professor->profile_image_url) ?? 'https://picsum.photos/seed/professor/600/750' }}"
                         alt="{{ $professor->name }}"
                         class="w-full h-full object-cover object-center">
                </div>
                <div class="absolute -bottom-5 -right-5 w-28 h-28 border border-primary-200 pointer-events-none" style="z-index:-1"></div>
            </div>
            <div class="fade-in">
                <p class="section-label">Professor</p>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2 tracking-tight"
                    data-editable data-model="professor" data-id="{{ $professor->id }}" data-field="name">
                    {{ $professor->name }}
                </h2>
                <p class="text-gray-400 text-sm mb-8 tracking-wide"
                   data-editable data-model="professor" data-id="{{ $professor->id }}" data-field="name_en">
                    {{ $professor->name_en }} &nbsp;|&nbsp;
                    <span data-editable data-model="professor" data-id="{{ $professor->id }}" data-field="title">{{ $professor->title }}</span>
                </p>
                @if($professor->bio)
                <div class="text-gray-500 text-sm leading-[2] mb-5 line-clamp-4"
                     data-editable data-model="professor" data-id="{{ $professor->id }}" data-field="bio">
                    {!! $professor->bio !!}
                </div>
                @endif
                @if($professor->research_themes)
                <div class="flex flex-wrap gap-2 mb-10">
                    @foreach($professor->research_themes as $tag)
                    <span class="text-xs px-3 py-1.5 bg-primary-50 text-primary-700 font-medium tracking-wide">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif
                <a href="/professor" class="btn-primary" data-wipe-link>
                    プロフィールを見る
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>
@endif

{{-- MEMBERS --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6 md:px-14">
        <div class="flex items-end justify-between mb-12">
            <div class="fade-in">
                <p class="section-label">Members</p>
                <h2 class="section-title">ゼミ生紹介</h2>
            </div>
            <a href="/members" class="hidden md:inline-flex items-center gap-2 text-primary-700 text-sm font-medium hover:gap-3 transition-all fade-in" data-wipe-link>
                全員を見る
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
        @php
            $previewMembers = \App\Models\Member::where('status', 'active')->orderBy('order_index')->limit(6)->get();
        @endphp
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-5">
            @forelse($previewMembers as $member)
            <div class="fade-in group cursor-pointer">
                <div class="aspect-square overflow-hidden mb-3 bg-gray-100">
                    @if($member->profile_image_url)
                    <img src="{{ storage_url($member->profile_image_url) }}" alt="{{ $member->name }}"
                         class="w-full h-full object-cover grayscale group-hover:grayscale-0 scale-[1.04] group-hover:scale-100 transition-all duration-500">
                    @else
                    <div class="w-full h-full bg-primary-100 flex items-center justify-center">
                        <span class="text-primary-300 text-2xl font-bold">{{ mb_substr($member->name, 0, 1) }}</span>
                    </div>
                    @endif
                </div>
                <p class="text-sm font-medium text-gray-800">{{ $member->name }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $member->university_year ? $member->university_year . '年' : '' }}</p>
            </div>
            @empty
            @for($i = 0; $i < 6; $i++)
            <div class="fade-in">
                <div class="aspect-square bg-gray-100 mb-3"></div>
                <div class="h-3 bg-gray-100 rounded w-2/3 mb-1"></div>
                <div class="h-2.5 bg-gray-100 rounded w-1/3"></div>
            </div>
            @endfor
            @endforelse
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-28 relative overflow-hidden"
         style="background: linear-gradient(160deg, #041c33 0%, #083a63 100%)">
    <div class="absolute inset-0 opacity-[0.04]"
         style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-14 text-center fade-in">
        <p class="text-white/30 text-[10px] tracking-[0.4em] uppercase mb-8">Contact Us</p>
        <h2 class="text-white text-3xl md:text-4xl font-bold mb-6 leading-tight">お問い合わせ</h2>
        <p class="text-white/45 text-sm md:text-base leading-relaxed mb-12 max-w-md mx-auto">
            森聡研究会へのお問い合わせは<br>下記よりご連絡ください。
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/contact" class="btn-outline-white" data-wipe-link>
                お問い合わせ
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </a>
            <a href="/faq" class="inline-flex items-center justify-center gap-2 px-7 py-3.5 text-white/40 hover:text-white text-sm font-medium transition-colors tracking-wide" data-wipe-link>
                FAQ を見る
            </a>
        </div>
    </div>
</section>

</div>

<script>
window.__worldNews = {!! json_encode($worldNews, JSON_HEX_TAG | JSON_HEX_AMP) !!};
</script>
@endsection
