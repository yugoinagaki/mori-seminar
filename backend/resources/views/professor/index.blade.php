@extends('layouts.app')

@section('title', '教員紹介')

@section('content')
<div>
    <div class="pt-32 pb-16 relative" style="background: linear-gradient(155deg, #041c33 0%, #0d5189 100%)">
        <div class="absolute inset-0 opacity-[0.05]"
             style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;"></div>
        <div class="relative max-w-7xl mx-auto px-6 md:px-14">
            <p class="text-white/35 text-[10px] tracking-[0.4em] uppercase mb-4">Professor</p>
            <h1 class="text-white text-4xl md:text-5xl font-bold tracking-tight">
                {{ $professor ? $professor->name . ' 教授' : '森 聡 教授' }}
            </h1>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 md:px-14 py-16">
        <div class="grid md:grid-cols-3 gap-16">
            {{-- Photo --}}
            <div class="md:col-span-1">
                <div class="aspect-[4/5] overflow-hidden bg-primary-50 relative">
                    <img src="{{ storage_url($professor->profile_image_url) ?? 'https://picsum.photos/seed/professor/400/500' }}"
                         alt="{{ $professor->name ?? '教授' }}"
                         class="w-full h-full object-cover">
                </div>
                <div class="mt-6 space-y-2">
                    <h2 class="text-2xl font-bold text-gray-900"
                        data-editable data-model="professor" data-id="{{ $professor?->id }}" data-field="name">
                        {{ $professor->name ?? '森 聡' }}
                    </h2>
                    <p class="text-gray-500 text-sm"
                       data-editable data-model="professor" data-id="{{ $professor?->id }}" data-field="name_en">
                        {{ $professor->name_en ?? 'Satoshi Mori' }}
                    </p>
                    <p class="text-gray-500 text-sm"
                       data-editable data-model="professor" data-id="{{ $professor?->id }}" data-field="title">
                        {{ $professor->title ?? '慶應義塾大学 法学部 教授' }}
                    </p>
                </div>
                @if($professor?->research_themes_body)
                <div class="mt-6">
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-3">研究テーマ</p>
                    <div class="text-gray-600 text-sm leading-[1.9] break-words [&_p]:whitespace-pre-wrap [&_li]:whitespace-pre-wrap [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:list-decimal [&_ol]:pl-5 [&_a]:text-primary-700 [&_a]:underline">
                        {!! $professor->research_themes_body !!}
                    </div>
                </div>
                @elseif($professor?->research_themes)
                <div class="mt-6">
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-3">研究テーマ</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($professor->research_themes as $theme)
                        <span class="text-xs px-3 py-1.5 bg-primary-50 text-primary-700 font-medium">{{ $theme }}</span>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach(['国際政治学', '現代アメリカ外交', '冷戦史', 'インド太平洋戦略', 'AI・安全保障'] as $tag)
                    <span class="text-xs px-3 py-1.5 bg-primary-50 text-primary-700 font-medium">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif

                @if(!empty($professor?->achievements_pdf_url))
                <div class="mt-6">
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-3">業績一覧</p>
                    <a href="{{ storage_url($professor->achievements_pdf_url) }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 text-sm text-primary-700 hover:text-primary-900 font-medium border-b border-primary-700/40 hover:border-primary-900 pb-1 transition-colors">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        業績一覧 (PDF)
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    @if($professor->achievements_pdf_note)
                    <p class="text-[11px] text-gray-400 mt-1.5 font-mono tracking-wide">{{ $professor->achievements_pdf_note }}</p>
                    @endif
                </div>
                @endif

                @if(!empty($professor?->gallery_photo_urls))
                <div class="mt-16">
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-4">ギャラリー</p>
                    <div class="grid grid-cols-3 md:grid-cols-1 gap-2 md:gap-4">
                        @foreach($professor->gallery_photo_urls as $photo)
                        @php $photoPath = is_array($photo) ? (reset($photo) ?: null) : $photo; @endphp
                        @if($photoPath)
                        <div class="aspect-square overflow-hidden bg-gray-100">
                            <img src="{{ storage_url($photoPath) }}"
                                 alt="ギャラリー"
                                 loading="lazy"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Text --}}
            <div class="md:col-span-2 space-y-12">
                @php
                    $blocks = collect($professor?->bio_blocks ?? [])
                        ->filter(fn ($b) => !empty($b['heading']) || !empty($b['body']));
                @endphp
                @if($blocks->isNotEmpty())
                <div class="space-y-10">
                    @foreach($blocks as $block)
                    <div>
                        @if(!empty($block['heading']))
                        <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-4">{{ $block['heading'] }}</p>
                        @endif
                        @if(!empty($block['body']))
                        <div class="text-gray-600 text-sm leading-[2] break-words [&_p]:whitespace-pre-wrap [&_li]:whitespace-pre-wrap [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:list-decimal [&_ol]:pl-5 [&_h2]:text-base [&_h2]:font-bold [&_h2]:text-gray-900 [&_h2]:mt-6 [&_h3]:text-sm [&_h3]:font-bold [&_h3]:text-gray-800 [&_h3]:mt-4 [&_a]:text-primary-700 [&_a]:underline">
                            {!! $block['body'] !!}
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @elseif($professor?->bio)
                <div>
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-4">プロフィール</p>
                    <div class="text-gray-600 text-sm leading-[2] break-words [&_p]:whitespace-pre-wrap [&_li]:whitespace-pre-wrap"
                         data-editable data-model="professor" data-id="{{ $professor->id }}" data-field="bio">
                        {!! $professor->bio !!}
                    </div>
                </div>
                @else
                <div>
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-4">プロフィール</p>
                    <div class="text-gray-600 text-sm leading-[2] space-y-4">
                        <p>専門は国際政治学、現代アメリカ外交、冷戦史。外務省勤務後、コロンビア大学ロースクール修了。東京大学にて博士号取得。現在、慶應義塾大学法学部教授。</p>
                        <p>現在の研究領域は国際秩序の動態分析、アメリカのインド太平洋戦略、人工知能などの先端技術と安全保障の関係。</p>
                    </div>
                </div>
                @endif

                @if($professor?->career)
                <div>
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-4">経歴</p>
                    <dl class="space-y-3">
                        @foreach($professor->career as $item)
                        <div class="flex gap-4">
                            <dt class="text-gray-400 text-sm font-mono w-16 shrink-0">
                                {{ is_array($item) ? ($item['year'] ?? '') : '' }}
                            </dt>
                            <dd class="text-gray-600 text-sm leading-relaxed">
                                {{ is_array($item) ? ($item['description'] ?? $item) : $item }}
                            </dd>
                        </div>
                        @endforeach
                    </dl>
                </div>
                @endif

                @if($professor?->awards)
                <div>
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-4">受賞歴</p>
                    <dl class="space-y-3">
                        @foreach($professor->awards as $item)
                        <div class="flex gap-4">
                            <dt class="text-gray-400 text-sm font-mono w-16 shrink-0">
                                {{ is_array($item) ? ($item['year'] ?? '') : '' }}
                            </dt>
                            <dd class="text-gray-600 text-sm">
                                {{ is_array($item) ? ($item['name'] ?? $item) : $item }}
                            </dd>
                        </div>
                        @endforeach
                    </dl>
                </div>
                @endif

                @if(!empty($professor?->papers))
                <div>
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-6">論文</p>
                    <ul class="divide-y divide-gray-100">
                        @foreach($professor->papers as $paper)
                        <li class="py-5 flex gap-4 md:gap-6">
                            <span class="text-gray-400 text-xs font-mono w-14 shrink-0 pt-0.5">
                                {{ $paper['year'] ?? '' }}
                            </span>
                            <div class="flex-1 min-w-0">
                                @if(!empty($paper['url']))
                                <a href="{{ $paper['url'] }}" target="_blank" rel="noopener"
                                   class="group text-sm text-gray-800 font-medium hover:text-primary-700 transition-colors leading-snug inline-flex items-start gap-1.5">
                                    <span>{{ $paper['title'] ?? '' }}</span>
                                    <svg class="w-3 h-3 mt-1 shrink-0 opacity-40 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                                @else
                                <p class="text-sm text-gray-800 font-medium leading-snug">{{ $paper['title'] ?? '' }}</p>
                                @endif
                                @if(!empty($paper['journal']))
                                <p class="text-xs text-gray-500 mt-1.5">{{ $paper['journal'] }}</p>
                                @endif
                                @if(!empty($paper['description']))
                                <p class="text-xs text-gray-400 mt-2 leading-relaxed">{{ $paper['description'] }}</p>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(!empty($professor?->books))
                <div>
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-6">著書</p>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                        @foreach($professor->books as $book)
                        @php
                            $cover = is_array($book['cover_url'] ?? null)
                                ? (reset($book['cover_url']) ?: null)
                                : ($book['cover_url'] ?? null);
                        @endphp
                        <div class="group flex flex-col">
                            @if($cover)
                            <a href="{{ $book['url'] ?? '#' }}" target="{{ $book['url'] ? '_blank' : '_self' }}"
                               class="{{ $book['url'] ? '' : 'pointer-events-none' }}">
                                <div class="aspect-[2/3] overflow-hidden bg-gray-100 mb-3 shadow-sm">
                                    <img src="{{ storage_url($cover) }}"
                                         alt="{{ $book['title'] ?? '' }}"
                                         loading="lazy"
                                         decoding="async"
                                         class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-300">
                                </div>
                            </a>
                            @endif
                            <p class="text-sm font-semibold text-gray-900 leading-snug">{{ $book['title'] ?? '' }}</p>
                            @if(!empty($book['year']) || !empty($book['publisher']))
                            <p class="text-xs text-gray-400 mt-1">
                                {{ collect([$book['year'] ?? null, $book['publisher'] ?? null])->filter()->implode(' · ') }}
                            </p>
                            @endif
                            @if(!empty($book['description']))
                            <p class="text-xs text-gray-500 mt-2 leading-relaxed">{{ $book['description'] }}</p>
                            @endif
                            @if(!empty($book['url']))
                            <a href="{{ $book['url'] }}" target="_blank"
                               class="inline-block mt-2 text-xs text-primary-600 hover:underline">詳細を見る →</a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
