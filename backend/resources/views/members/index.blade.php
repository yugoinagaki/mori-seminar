@extends('layouts.app')

@section('title', 'ゼミ生紹介 | 森聡研究会')

@section('content')
<div>
    <div class="pt-32 pb-16 relative" style="background: linear-gradient(155deg, #041c33 0%, #0d5189 100%)">
        <div class="absolute inset-0 opacity-[0.05]"
             style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;"></div>
        <div class="relative max-w-7xl mx-auto px-6 md:px-14">
            <p class="fade-in text-white/35 text-[10px] tracking-[0.4em] uppercase mb-4">Members</p>
            <h1 class="fade-in text-white text-4xl md:text-5xl font-bold tracking-tight">ゼミ生紹介</h1>
        </div>
    </div>

    {{-- G. タブ --}}
    <div class="bg-white border-b border-gray-100 sticky top-[60px] z-40">
        <div class="max-w-7xl mx-auto px-6 md:px-14 flex gap-0">
            <button
                class="px-6 py-4 text-sm font-medium border-b-2 transition-colors border-primary-700 text-primary-700"
                data-tab="active"
            >現役メンバー</button>
            <button
                class="px-6 py-4 text-sm font-medium border-b-2 transition-colors border-transparent text-gray-400 hover:text-gray-700"
                data-tab="ob_og"
            >OB・OG</button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 md:px-14 py-16">
        {{-- 現役メンバー --}}
        @if($active->count())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6" id="members-active">
            @foreach($active as $member)
            <div class="fade-in group" data-member-status="active">
                <div class="aspect-square overflow-hidden mb-3 bg-gray-100">
                    @if($member->profile_image_url)
                    <img
                        src="{{ storage_url($member->profile_image_url) }}"
                        alt="{{ $member->name }}"
                        class="w-full h-full object-cover grayscale group-hover:grayscale-0 scale-[1.04] group-hover:scale-100 transition-all duration-500"
                    />
                    @else
                    <div class="w-full h-full bg-primary-50 flex items-center justify-center">
                        <span class="text-primary-300 text-3xl font-bold">{{ mb_substr($member->name, 0, 1) }}</span>
                    </div>
                    @endif
                </div>
                <p class="text-sm font-bold text-gray-800">{{ $member->name }}</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $member->generation ? "{$member->generation}期" : '' }}
                    {{ $member->university_year ? "・{$member->university_year}年" : '' }}
                    {{ $member->graduated_year ? "({$member->graduated_year}年卒)" : '' }}
                </p>
                @if($member->major)
                <p class="text-xs text-gray-400">{{ $member->major }}</p>
                @endif
                @if($member->bio)
                <p class="text-xs text-gray-500 mt-2 leading-relaxed line-clamp-3">{{ $member->bio }}</p>
                @endif
                <div class="flex gap-3 mt-2">
                    @if($member->twitter_url)
                    <a href="{{ $member->twitter_url }}" target="_blank" rel="noopener"
                       class="text-gray-300 hover:text-primary-700 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    @endif
                    @if($member->instagram_url)
                    <a href="{{ $member->instagram_url }}" target="_blank" rel="noopener"
                       class="text-gray-300 hover:text-primary-700 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- OB・OG (初期は非表示) --}}
        @if($obog->count())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6" id="members-obog" style="display:none">
            @foreach($obog as $member)
            <div class="fade-in group" data-member-status="ob_og">
                <div class="aspect-square overflow-hidden mb-3 bg-gray-100">
                    @if($member->profile_image_url)
                    <img
                        src="{{ storage_url($member->profile_image_url) }}"
                        alt="{{ $member->name }}"
                        class="w-full h-full object-cover grayscale group-hover:grayscale-0 scale-[1.04] group-hover:scale-100 transition-all duration-500"
                    />
                    @else
                    <div class="w-full h-full bg-primary-50 flex items-center justify-center">
                        <span class="text-primary-300 text-3xl font-bold">{{ mb_substr($member->name, 0, 1) }}</span>
                    </div>
                    @endif
                </div>
                <p class="text-sm font-bold text-gray-800">{{ $member->name }}</p>
                <p class="text-xs text-gray-400 mt-0.5">
                    {{ $member->generation ? "{$member->generation}期" : '' }}
                    {{ $member->university_year ? "・{$member->university_year}年" : '' }}
                    {{ $member->graduated_year ? "({$member->graduated_year}年卒)" : '' }}
                </p>
                @if($member->major)
                <p class="text-xs text-gray-400">{{ $member->major }}</p>
                @endif
                @if($member->bio)
                <p class="text-xs text-gray-500 mt-2 leading-relaxed line-clamp-3">{{ $member->bio }}</p>
                @endif
                <div class="flex gap-3 mt-2">
                    @if($member->twitter_url)
                    <a href="{{ $member->twitter_url }}" target="_blank" rel="noopener"
                       class="text-gray-300 hover:text-primary-700 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    @endif
                    @if($member->instagram_url)
                    <a href="{{ $member->instagram_url }}" target="_blank" rel="noopener"
                       class="text-gray-300 hover:text-primary-700 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @if($active->isEmpty() && $obog->isEmpty())
        <p class="py-16 text-center text-gray-400">メンバーが登録されていません</p>
        @endif
    </div>
</div>

<script>
// G. タブの初期化 — active/ob_og の両グリッドを同一の [data-member-status] で管理する
document.addEventListener('DOMContentLoaded', () => {
    const tabs    = document.querySelectorAll('[data-tab]');
    const grids   = { active: document.getElementById('members-active'), ob_og: document.getElementById('members-obog') };

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const status = tab.dataset.tab;

            tabs.forEach((t) => {
                const active = t.dataset.tab === status;
                t.classList.toggle('border-primary-700', active);
                t.classList.toggle('text-primary-700', active);
                t.classList.toggle('border-transparent', !active);
                t.classList.toggle('text-gray-400', !active);
                t.classList.toggle('hover:text-gray-700', !active);
            });

            Object.entries(grids).forEach(([key, grid]) => {
                if (grid) grid.style.display = key === status ? '' : 'none';
            });

            setTimeout(() => window.initScrollObserver(), 0);
        });
    });
});
</script>
@endsection
