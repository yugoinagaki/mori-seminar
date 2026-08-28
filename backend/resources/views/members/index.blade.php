@extends('layouts.app')

@section('title', 'ゼミ生紹介')

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

    {{-- Tabs --}}
    <div class="bg-white border-b border-gray-100 sticky top-[60px] z-40">
        <div class="max-w-7xl mx-auto px-6 md:px-14 flex gap-0 overflow-x-auto">
            <button type="button"
                    class="members-tab-btn px-6 py-4 text-sm font-medium border-b-2 transition-colors whitespace-nowrap border-primary-700 text-primary-700"
                    data-tab="all">
                全員
            </button>
            @foreach($cohorts as $cohort)
            <button type="button"
                    class="members-tab-btn px-6 py-4 text-sm font-medium border-b-2 transition-colors whitespace-nowrap border-transparent text-gray-400 hover:text-gray-700"
                    data-tab="cohort-{{ $cohort->id }}">
                {{ $cohort->generation }}期
            </button>
            @endforeach
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 md:px-14 py-16">
        @if($members->isNotEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($members as $member)
            <div class="fade-in group"
                 data-member-cohort="{{ $member->cohort_id ? 'cohort-' . $member->cohort_id : '' }}">
                <button type="button"
                        class="member-photo-trigger relative block w-full mb-4 cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-700 focus-visible:ring-offset-2"
                        data-open-member-modal
                        data-name="{{ $member->name }}"
                        data-cohort="{{ $member->cohort?->generation }}"
                        data-position="{{ $member->position }}"
                        data-image-url="{{ $member->profile_image_url ? storage_url($member->profile_image_url) : '' }}"
                        data-initial="{{ mb_substr($member->name, 0, 1) }}"
                        aria-label="{{ $member->name }} の詳細を見る">
                    <div class="p-3 bg-primary-950 border-2 border-primary-800 group-hover:border-amber-400 shadow-md group-hover:shadow-xl transition-all duration-500">
                        <div class="aspect-square overflow-hidden bg-gray-100">
                            @if($member->profile_image_url)
                            <img src="{{ storage_url($member->profile_image_url) }}" alt="{{ $member->name }}"
                                 class="w-full h-full object-cover grayscale group-hover:grayscale-0 scale-[1.04] group-hover:scale-100 transition-all duration-500">
                            @else
                            <div class="w-full h-full bg-primary-50 flex items-center justify-center">
                                <span class="text-primary-300 text-3xl font-bold">{{ mb_substr($member->name, 0, 1) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    {{-- Bio HTML held here so JS can copy it into the modal --}}
                    <template class="member-bio-template">{!! $member->bio !!}</template>
                </button>

                @if($member->cohort?->generation || $member->position)
                <p class="text-center text-[10px] tracking-[0.3em] uppercase text-primary-700/80 font-medium mb-1.5">
                    {{ $member->cohort?->generation ? $member->cohort->generation . '期' : '' }}{{ $member->position ? ($member->cohort?->generation ? ' · ' : '') . $member->position : '' }}
                </p>
                @endif

                <p class="text-center font-mincho text-base md:text-lg font-bold text-gray-900 tracking-wide leading-tight group-hover:text-primary-700 transition-colors duration-300"
                   data-editable data-model="member" data-id="{{ $member->id }}" data-field="name">
                    {{ $member->name }}
                </p>

                @if($member->bio)
                <p class="text-xs text-gray-500 mt-3 leading-relaxed line-clamp-3"
                   data-editable data-model="member" data-id="{{ $member->id }}" data-field="bio">
                    {{ strip_tags($member->bio) }}
                </p>
                @endif
            </div>
            @endforeach
        </div>
        @else
        <p class="py-16 text-center text-gray-400">メンバーが登録されていません</p>
        @endif
    </div>
</div>

{{-- Member detail modal --}}
<div id="member-modal" class="hidden fixed inset-0 z-[80]" aria-hidden="true" role="dialog" aria-modal="true">
    <div id="member-modal-overlay"
         class="absolute inset-0 bg-primary-950/85 backdrop-blur-md opacity-0 transition-opacity duration-300"></div>

    <div class="absolute inset-0 flex items-center justify-center p-4 md:p-8 pointer-events-none">
        <div id="member-modal-panel"
             class="relative w-full max-w-4xl h-[90vh] md:h-[85vh] md:max-h-[680px] bg-white overflow-hidden flex flex-col opacity-0 translate-y-6 scale-[0.98] transition-all duration-500 ease-out pointer-events-auto border-[6px] md:border-[10px] border-primary-950"
             style="box-shadow: 0 30px 90px -20px rgba(4, 28, 51, 0.55), 0 8px 30px -10px rgba(4, 28, 51, 0.35);">
            <button type="button" id="member-modal-close"
                    class="absolute top-4 right-4 z-10 w-10 h-10 flex items-center justify-center text-white bg-black/40 hover:bg-black/70 backdrop-blur-sm rounded-full transition-all"
                    aria-label="閉じる">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="flex flex-col md:flex-row flex-1 min-h-0">
                {{-- Framed photo (amber border at modal edge, no mat) --}}
                <div class="relative aspect-square md:aspect-auto md:h-full md:w-3/5 bg-primary-950 overflow-hidden shrink-0 border-b-2 md:border-b-0 md:border-r-2 border-amber-400">
                    <img id="member-modal-image" src="" alt="" class="absolute inset-0 w-full h-full object-cover hidden">
                    <div id="member-modal-initial" class="absolute inset-0 hidden items-center justify-center bg-gradient-to-br from-primary-800 via-primary-900 to-primary-950">
                        <span class="text-white/25 text-[10rem] font-bold font-mincho leading-none"></span>
                    </div>
                    <div class="pointer-events-none absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-primary-950/80 to-transparent"></div>
                    <p class="absolute bottom-5 left-6 text-white/50 text-[9px] tracking-[0.4em] uppercase">Mori Seminar</p>
                </div>

                {{-- Text (scrolls independently) --}}
                <div class="relative flex-1 min-h-0 min-w-0 flex flex-col overflow-hidden">
                    <div class="flex-1 min-h-0 overflow-y-auto p-8 md:p-10 member-modal-scroll">
                        <p class="text-[10px] tracking-[0.4em] uppercase text-primary-700 font-semibold mb-4">Member</p>
                        <h2 id="member-modal-name" class="text-3xl md:text-[2.15rem] font-bold text-gray-900 font-mincho tracking-wide mb-5 leading-tight break-words"></h2>

                        <div class="flex flex-wrap gap-2 mb-8">
                            <span id="member-modal-cohort"
                                  class="hidden text-[11px] px-3 py-1 border border-primary-700 text-primary-700 tracking-wider font-medium"></span>
                            <span id="member-modal-position"
                                  class="hidden text-[11px] px-3 py-1 bg-primary-900 text-white tracking-wider font-medium"></span>
                        </div>

                        <div class="border-t border-gray-100 pt-6">
                            <p class="text-[10px] tracking-[0.35em] uppercase text-gray-400 mb-4">About</p>
                            <div id="member-modal-bio" class="text-[13.5px] text-gray-700 leading-loose whitespace-pre-line break-words"></div>
                            <p id="member-modal-bio-empty" class="hidden text-sm text-gray-300 italic">自己紹介は登録されていません</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
