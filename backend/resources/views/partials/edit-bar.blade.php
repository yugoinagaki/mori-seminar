@auth
@if(in_array(auth()->user()->role ?? '', ['super_admin', 'editor']))
<div id="edit-bar"
     class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[9990] flex items-center gap-2
            bg-primary-950/95 backdrop-blur-md text-white text-sm px-4 py-2.5 rounded-full shadow-xl border border-white/10">

    {{-- Toggle edit mode --}}
    <button id="edit-bar-toggle"
            class="flex items-center gap-1.5 px-3 py-1 rounded-full font-medium transition-all duration-200
                   hover:bg-white/10">
        <span id="edit-bar-icon" class="w-2 h-2 rounded-full bg-gray-400 transition-colors duration-200"></span>
        <span id="edit-bar-label">編集モード</span>
    </button>

    <div class="w-px h-4 bg-white/20"></div>

    {{-- New content (visible in edit mode only) --}}
    <button id="edit-bar-new"
            class="hidden items-center gap-1.5 px-3 py-1 rounded-full font-medium hover:bg-white/10 transition-all duration-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        新規作成
    </button>

    <div id="edit-bar-draft-divider" class="hidden w-px h-4 bg-white/20"></div>

    {{-- Apply drafts --}}
    <form method="POST" action="{{ route('drafts.apply') }}" id="edit-bar-apply-form" class="hidden">
        @csrf
        <button type="submit"
                class="flex items-center gap-1.5 px-3 py-1 rounded-full font-medium bg-emerald-600 hover:bg-emerald-500 transition-colors duration-200">
            <span id="edit-bar-draft-count"
                  class="bg-white text-emerald-700 text-xs font-bold w-4 h-4 flex items-center justify-center rounded-full"></span>
            反映
        </button>
    </form>

    {{-- Discard drafts --}}
    <form method="POST" action="{{ route('drafts.discard') }}" id="edit-bar-discard-form" class="hidden">
        @csrf
        <button type="submit"
                class="px-3 py-1 rounded-full font-medium text-white/60 hover:text-white hover:bg-white/10 transition-all duration-200"
                onclick="return confirm('下書きを破棄してもよいですか？')">
            破棄
        </button>
    </form>
</div>
@endif
@endauth
