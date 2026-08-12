@extends('layouts.app')

@section('title', 'お問い合わせ | 森聡研究会')

@section('content')
<div>
    <div class="pt-32 pb-16 relative" style="background: linear-gradient(155deg, #041c33 0%, #0d5189 100%)">
        <div class="absolute inset-0 opacity-[0.05]"
             style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;"></div>
        <div class="relative max-w-7xl mx-auto px-6 md:px-14">
            <p class="text-white/35 text-[10px] tracking-[0.4em] uppercase mb-4">Contact Us</p>
            <h1 class="text-white text-4xl md:text-5xl font-bold tracking-tight">お問い合わせ</h1>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-6 md:px-14 py-20">
        <div class="space-y-10">
            <div>
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-4">Email</p>
                <a href="mailto:morisemi2020@gmail.com"
                   class="text-gray-800 text-lg font-medium hover:text-primary-700 transition-colors">
                    morisemi2020@gmail.com
                </a>
                <p class="text-gray-400 text-sm mt-2">※ 森先生個人への連絡先ではありません</p>
            </div>

            <div>
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-4">SNS</p>
                <div class="flex gap-5">
                    <a href="https://twitter.com/morisemi_keio" target="_blank" rel="noopener"
                       class="flex items-center gap-2 text-gray-600 hover:text-primary-700 transition-colors text-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                        @morisemi_keio
                    </a>
                    <a href="https://instagram.com/keio.mori" target="_blank" rel="noopener"
                       class="flex items-center gap-2 text-gray-600 hover:text-primary-700 transition-colors text-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                        @keio.mori
                    </a>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-8">
                <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-4">FAQ</p>
                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                    入ゼミに関するよくある質問はFAQページをご確認ください。
                </p>
                <a href="/faq" class="inline-flex items-center gap-2 text-primary-700 text-sm font-medium hover:gap-3 transition-all">
                    FAQを見る
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
