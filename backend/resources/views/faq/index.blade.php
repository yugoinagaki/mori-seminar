@extends('layouts.app')

@section('title', 'よくある質問 | 森聡研究会')

@section('content')
<div>
    <div class="pt-32 pb-16 relative" style="background: linear-gradient(155deg, #041c33 0%, #0d5189 100%)">
        <div class="absolute inset-0 opacity-[0.05]"
             style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;"></div>
        <div class="relative max-w-7xl mx-auto px-6 md:px-14">
            <p class="fade-in text-white/35 text-[10px] tracking-[0.4em] uppercase mb-4">FAQ</p>
            <h1 class="fade-in text-white text-4xl md:text-5xl font-bold tracking-tight">よくある質問</h1>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-6 md:px-14 py-16">
        @if($faqs->count())

        {{-- カテゴリありのFAQ --}}
        @foreach($categories as $cat)
        @php $catFaqs = $faqs->where('category', $cat)->values(); @endphp
        @if($catFaqs->count())
        <div class="fade-in mb-12">
            <h2 class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-6">{{ $cat }}</h2>
            <dl class="divide-y divide-gray-100">
                @foreach($catFaqs as $faq)
                <div>
                    <dt>
                        <button
                            class="w-full flex items-start justify-between gap-4 py-5 text-left"
                            data-faq-button="{{ $faq->id }}"
                        >
                            <span class="text-gray-800 font-medium text-sm md:text-base leading-relaxed">{{ $faq->question }}</span>
                            <svg class="w-5 h-5 text-primary-700 shrink-0 mt-0.5 transition-transform duration-200"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 data-faq-arrow>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </dt>
                    <dd class="pb-5 text-gray-500 text-sm leading-relaxed" data-faq-answer="{{ $faq->id }}" style="display:none">
                        {{ $faq->answer }}
                    </dd>
                </div>
                @endforeach
            </dl>
        </div>
        @endif
        @endforeach

        {{-- カテゴリなしのFAQ --}}
        @if($uncategorized->count())
        <div class="fade-in mb-12">
            <dl class="divide-y divide-gray-100">
                @foreach($uncategorized as $faq)
                <div>
                    <dt>
                        <button
                            class="w-full flex items-start justify-between gap-4 py-5 text-left"
                            data-faq-button="{{ $faq->id }}"
                        >
                            <span class="text-gray-800 font-medium text-sm md:text-base leading-relaxed">{{ $faq->question }}</span>
                            <svg class="w-5 h-5 text-primary-700 shrink-0 mt-0.5 transition-transform duration-200"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 data-faq-arrow>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </dt>
                    <dd class="pb-5 text-gray-500 text-sm leading-relaxed" data-faq-answer="{{ $faq->id }}" style="display:none">
                        {{ $faq->answer }}
                    </dd>
                </div>
                @endforeach
            </dl>
        </div>
        @endif

        @else
        <p class="fade-in py-16 text-center text-gray-400">FAQはまだ登録されていません</p>
        @endif

        <div class="fade-in mt-12 pt-8 border-t border-gray-100 text-center">
            <p class="text-gray-500 text-sm mb-6">解決しない場合はお気軽にお問い合わせください</p>
            <a href="/contact" class="btn-primary">お問い合わせ</a>
        </div>
    </div>
</div>
@endsection
