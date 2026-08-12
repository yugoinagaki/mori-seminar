@auth
@php $draftCount = \App\Models\Draft::where('user_id', auth()->id())->count(); @endphp
<div
    id="edit-bar"
    class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[99999] flex items-center gap-3 px-4 py-2.5 rounded-full shadow-2xl border border-white/20 backdrop-blur-md transition-all duration-300"
    style="background: rgba(4, 28, 51, 0.92)"
>
    {{-- Edit mode indicator --}}
    <span id="edit-bar-status" class="flex items-center gap-2 text-xs font-medium {{ session('edit_mode') ? 'text-amber-300' : 'text-white/60' }}">
        <span id="edit-bar-dot" class="inline-block w-2 h-2 rounded-full {{ session('edit_mode') ? 'bg-amber-400' : 'bg-white/30' }}"></span>
        <span id="edit-bar-label">{{ session('edit_mode') ? '編集モード' : 'プレビュー' }}</span>
    </span>

    <span class="w-px h-4 bg-white/20"></span>

    {{-- Toggle button --}}
    <button
        id="edit-bar-toggle"
        type="button"
        class="text-xs font-medium px-3 py-1.5 rounded-full transition-all duration-200 {{ session('edit_mode') ? 'bg-white/15 text-white hover:bg-white/25' : 'bg-amber-500 text-white hover:bg-amber-400' }}"
    >{{ session('edit_mode') ? '終了' : '編集開始' }}</button>

    {{-- New create button (shown in edit mode) --}}
    <button
        id="edit-bar-new"
        type="button"
        class="{{ session('edit_mode') ? '' : 'hidden' }} text-xs font-medium px-3 py-1.5 rounded-full bg-white/10 text-white/80 hover:bg-white/20 transition-colors duration-200 flex items-center gap-1.5"
    >
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        新規作成
    </button>

    {{-- Draft count + apply/discard (shown in edit mode) --}}
    <div id="edit-bar-actions" class="{{ session('edit_mode') ? '' : 'hidden' }} flex items-center gap-3">
        <span id="edit-bar-count" class="text-xs text-amber-300 font-medium {{ $draftCount > 0 ? '' : 'hidden' }}">変更 {{ $draftCount }} 件</span>
        <span class="w-px h-4 bg-white/20 {{ $draftCount > 0 ? '' : 'hidden' }}" id="edit-bar-sep"></span>

        <form id="edit-bar-apply-form" method="POST" action="{{ route('drafts.apply') }}">
            @csrf
            <button
                id="edit-bar-apply"
                type="submit"
                class="text-xs font-medium px-3 py-1.5 rounded-full bg-emerald-600 text-white hover:bg-emerald-500 transition-colors duration-200 disabled:opacity-50"
                {{ $draftCount === 0 ? 'disabled' : '' }}
            >反映する</button>
        </form>

        <form id="edit-bar-discard-form" method="POST" action="{{ route('drafts.discard') }}">
            @csrf
            <button
                id="edit-bar-discard"
                type="submit"
                class="text-xs font-medium px-3 py-1.5 rounded-full bg-white/10 text-white/70 hover:bg-white/20 transition-colors duration-200 disabled:opacity-50"
                {{ $draftCount === 0 ? 'disabled' : '' }}
                onclick="return confirm('すべての下書きを破棄しますか？')"
            >破棄</button>
        </form>
    </div>
</div>
@endauth
