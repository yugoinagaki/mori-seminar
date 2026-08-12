@auth
<div
    class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[99999] flex items-center gap-3 px-4 py-2.5 rounded-full shadow-2xl border border-white/20 backdrop-blur-md transition-all duration-300"
    style="background: rgba(4, 28, 51, 0.92)"
>
    {{-- Edit mode indicator --}}
    <span class="flex items-center gap-2 text-xs font-medium {{ $editMode ? 'text-amber-300' : 'text-white/60' }}">
        <span class="inline-block w-2 h-2 rounded-full {{ $editMode ? 'bg-amber-400 animate-pulse' : 'bg-white/30' }}"></span>
        {{ $editMode ? '編集モード' : 'プレビュー' }}
    </span>

    <span class="w-px h-4 bg-white/20"></span>

    {{-- Toggle button --}}
    <button
        wire:click="toggle"
        class="text-xs font-medium px-3 py-1.5 rounded-full transition-all duration-200 {{ $editMode ? 'bg-white/15 text-white hover:bg-white/25' : 'bg-amber-500 text-white hover:bg-amber-400' }}"
    >
        {{ $editMode ? '終了' : '編集開始' }}
    </button>

    @if($editMode)
    {{-- Draft count badge --}}
    @if($draftCount > 0)
    <span class="text-xs text-amber-300 font-medium">変更 {{ $draftCount }} 件</span>
    <span class="w-px h-4 bg-white/20"></span>
    @endif

    {{-- Apply button --}}
    <button
        wire:click="apply"
        wire:confirm="変更を本番に反映しますか？"
        class="text-xs font-medium px-3 py-1.5 rounded-full bg-emerald-600 text-white hover:bg-emerald-500 transition-colors duration-200 disabled:opacity-50"
        @if($draftCount === 0) disabled @endif
    >反映する</button>

    {{-- Discard button --}}
    <button
        wire:click="discard"
        wire:confirm="すべての下書きを破棄しますか？"
        class="text-xs font-medium px-3 py-1.5 rounded-full bg-white/10 text-white/70 hover:bg-white/20 transition-colors duration-200 disabled:opacity-50"
        @if($draftCount === 0) disabled @endif
    >破棄</button>
    @endif
</div>
@endauth
