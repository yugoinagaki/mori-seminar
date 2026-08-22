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
                @if($professor?->research_themes)
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
            </div>

            {{-- Text --}}
            <div class="md:col-span-2 space-y-12">
                <div>
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-4">プロフィール</p>
                    @if($professor?->bio)
                    <div class="text-gray-600 text-sm leading-[2]"
                         data-editable data-model="professor" data-id="{{ $professor->id }}" data-field="bio">
                        {!! $professor->bio !!}
                    </div>
                    @else
                    <div class="text-gray-600 text-sm leading-[2] space-y-4">
                        <p>専門は国際政治学、現代アメリカ外交、冷戦史。外務省勤務後、コロンビア大学ロースクール修了。東京大学にて博士号取得。現在、慶應義塾大学法学部教授。</p>
                        <p>現在の研究領域は国際秩序の動態分析、アメリカのインド太平洋戦略、人工知能などの先端技術と安全保障の関係。</p>
                    </div>
                    @endif
                </div>

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

                @if(!empty($professor?->books))
                <div>
                    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-6">著書・論文</p>
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

        {{-- Gallery --}}
        @if(!empty($professor?->gallery_photo_urls))
        <div class="mt-16">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-6">ギャラリー</p>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
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
</div>
@endsection
