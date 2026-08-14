@auth
@if(in_array(auth()->user()->role ?? '', ['super_admin', 'editor']))
<div id="edit-bar"
     class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[9990] flex items-center gap-2
            bg-primary-950/95 backdrop-blur-md text-white text-sm px-4 py-2.5 rounded-full shadow-xl border border-white/10">

    {{-- Status indicator --}}
    <span id="edit-bar-dot" class="w-2 h-2 rounded-full bg-white/30 transition-colors duration-200"></span>
    <span id="edit-bar-status" class="text-xs font-medium text-white/60">プレビュー</span>

    <div class="w-px h-4 bg-white/20"></div>

    {{-- Toggle edit mode --}}
    <button id="edit-bar-toggle"
            class="px-3 py-1 rounded-full text-xs font-medium transition-all duration-200 bg-amber-500 text-white hover:bg-amber-400">
        編集開始
    </button>

    {{-- New content button (visible in edit mode only) --}}
    <button id="edit-bar-new"
            class="hidden flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium hover:bg-white/10 transition-all duration-200">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        新規作成
    </button>

    {{-- Actions area (hidden until edit mode is active) --}}
    <div id="edit-bar-actions" class="hidden flex items-center gap-2">
        <div id="edit-bar-sep" class="w-px h-4 bg-white/20"></div>
        <span id="edit-bar-count" class="text-xs text-amber-300 font-medium"></span>

        {{-- Apply drafts --}}
        <form method="POST" action="{{ route('drafts.apply') }}" class="contents">
            @csrf
            <button type="submit" id="edit-bar-apply"
                    class="px-3 py-1 rounded-full text-xs font-medium bg-emerald-600 hover:bg-emerald-500 transition-colors duration-200 disabled:opacity-40"
                    disabled>
                反映する
            </button>
        </form>

        {{-- Discard drafts --}}
        <form method="POST" action="{{ route('drafts.discard') }}" class="contents"
              onsubmit="return confirm('下書きを破棄してもよいですか？')">
            @csrf
            <button type="submit" id="edit-bar-discard"
                    class="px-3 py-1 rounded-full text-xs font-medium text-white/60 hover:text-white hover:bg-white/10 transition-all duration-200 disabled:opacity-40"
                    disabled>
                破棄
            </button>
        </form>
    </div>
</div>
@endif
@endauth
