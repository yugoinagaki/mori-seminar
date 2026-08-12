<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', '森聡研究会 | 慶應義塾大学')</title>
    <meta name="description" content="@yield('description', '慶應義塾大学 森聡研究会の公式サイト。国際政治学・現代アメリカ外交・先端技術を研究しています。')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+JP:wght@300;400;500;700&family=Shippori+Mincho:wght@400;500;600;700;800&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

{{-- A. ページ遷移オーバーレイ（対象ページのみ出力） --}}
@if ($showWipe ?? false)
<div id="wipe-overlay" class="wipe-wrapper">
    <div class="wipe-forest"></div>
</div>
@endif

{{-- F. ナビバー --}}
<nav
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-transparent"
    data-navbar
>
    <div class="w-full px-4 flex items-center justify-between py-2">
        {{-- ロゴ --}}
        <a href="/" class="text-white flex items-center gap-3">
            <img src="/keio-logo.png" alt="慶應義塾大学" class="h-8 w-auto object-contain shrink-0" />
            <img src="/seminar-logo.png" alt="森聡研究会" class="h-20 w-auto object-contain" />
        </a>

        {{-- デスクトップナビ --}}
        <ul class="hidden lg:flex items-center gap-8">
            @foreach([
                ['Home',         '/'],
                ['Theme',        '/theme'],
                ['News',         '/news'],
                ['Professor',    '/professor'],
                ['Blog',         '/blog'],
                ['Members',      '/members'],
                ['Case Studies', '/case-studies'],
                ['FAQ',          '/faq'],
                ['Contact',      '/contact'],
            ] as [$label, $href])
            <li>
                <a
                    href="{{ $href }}"
                    class="text-white/65 hover:text-white text-[13px] font-medium tracking-wide transition-colors duration-150
                           {{ ($href === '/' ? request()->is('/') : request()->is(ltrim($href, '/').'*')) ? '!text-white' : '' }}"
                >{{ $label }}</a>
            </li>
            @endforeach
        </ul>

        {{-- モバイルトグル --}}
        <button
            class="lg:hidden text-white p-1"
            data-mobile-toggle
            aria-expanded="false"
            aria-label="メニューを開く"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-icon-hamburger>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-icon-close style="display:none">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- モバイルメニュー --}}
    <div
        class="lg:hidden bg-primary-900 border-t border-white/10 opacity-0 -translate-y-3"
        data-mobile-menu
        style="display:none"
    >
        <ul class="px-6 py-5 space-y-4">
            @foreach([
                ['Home',         '/'],
                ['Theme',        '/theme'],
                ['News',         '/news'],
                ['Professor',    '/professor'],
                ['Blog',         '/blog'],
                ['Members',      '/members'],
                ['Case Studies', '/case-studies'],
                ['FAQ',          '/faq'],
                ['Contact',      '/contact'],
            ] as [$label, $href])
            <li>
                <a href="{{ $href }}" class="text-white/70 hover:text-white block text-sm font-medium">
                    {{ $label }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</nav>

<main>
    @yield('content')
</main>

{{-- フッター --}}
<footer class="bg-primary-950 text-white">
    <div class="max-w-7xl mx-auto px-6 py-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-14">
            {{-- ブランド --}}
            <div>
                <p class="text-[9px] tracking-[0.3em] uppercase text-white/35 mb-2">Keio University</p>
                <h3 class="text-2xl font-bold mb-5 tracking-wide">森聡研究会</h3>
                <p class="text-white/40 text-sm leading-relaxed mb-8">
                    国際政治学・現代アメリカ外交・<br>先端技術を研究するゼミナール
                </p>
                <div class="flex gap-5">
                    <a href="https://twitter.com/morisemi_keio" target="_blank" rel="noopener"
                       class="text-white/35 hover:text-white transition-colors" aria-label="X (Twitter)">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    <a href="https://instagram.com/keio.mori" target="_blank" rel="noopener"
                       class="text-white/35 hover:text-white transition-colors" aria-label="Instagram">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- リンク --}}
            <div>
                <h4 class="text-[10px] tracking-[0.25em] uppercase text-white/35 font-semibold mb-6">Menu</h4>
                <ul class="grid grid-cols-2 gap-x-6 gap-y-3">
                    @foreach([
                        ['Annual Theme',  '/theme'],
                        ['News',          '/news'],
                        ['Professor',     '/professor'],
                        ['Blog',          '/blog'],
                        ['Members',       '/members'],
                        ['Case Studies',  '/case-studies'],
                        ['FAQ',           '/faq'],
                        ['Contact',       '/contact'],
                    ] as [$label, $href])
                    <li><a href="{{ $href }}" class="text-white/55 hover:text-white text-sm transition-colors">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </div>

            {{-- コンタクト --}}
            <div>
                <h4 class="text-[10px] tracking-[0.25em] uppercase text-white/35 font-semibold mb-6">Contact</h4>
                <a href="mailto:morisemi2020@gmail.com"
                   class="text-white/55 hover:text-white text-sm transition-colors block mb-2">
                    morisemi2020@gmail.com
                </a>
                <p class="text-white/25 text-xs leading-relaxed">
                    ※ 森先生個人への連絡先ではありません
                </p>
            </div>
        </div>

        <div class="border-t border-white/10 mt-14 pt-8 flex flex-col md:flex-row justify-between items-center gap-3">
            <p class="text-white/25 text-xs">© 2026 Mori Seminar, Keio University. All rights reserved.</p>
            <p class="text-white/20 text-xs">慶應義塾大学 法学部</p>
        </div>
    </div>
</footer>

</body>
</html>
