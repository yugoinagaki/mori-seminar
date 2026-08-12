@auth
{{-- 新規作成モーダル --}}
<div id="create-modal" class="fixed inset-0 z-[100000] hidden" aria-modal="true">
    {{-- オーバーレイ --}}
    <div id="create-modal-overlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-200"></div>

    {{-- モーダル本体 --}}
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div id="create-modal-panel"
             class="relative bg-white w-full max-w-xl rounded-xl shadow-2xl opacity-0 translate-y-4 transition-all duration-200 max-h-[90vh] overflow-y-auto">

            {{-- ヘッダー --}}
            <div class="sticky top-0 flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white rounded-t-xl">
                <h2 class="text-base font-bold text-gray-900">新規作成</h2>
                <button id="create-modal-close" type="button" class="text-gray-400 hover:text-gray-700 p-1 rounded transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- コンテンツ種別セレクター --}}
            <div class="px-6 pt-5 pb-3">
                <p class="text-xs font-semibold tracking-[0.15em] uppercase text-gray-400 mb-3">作成する種類</p>
                <div class="grid grid-cols-3 gap-2" id="create-type-selector">
                    @foreach([
                        ['post',       '投稿',               'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 12h6m-6-4h.01'],
                        ['case-study', 'ケーススタディ',      'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['member',     'メンバー',            'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ] as [$type, $label, $icon])
                    <button
                        type="button"
                        data-type="{{ $type }}"
                        class="create-type-btn flex flex-col items-center gap-2 px-3 py-4 rounded-lg border-2 border-gray-200 hover:border-primary-400 hover:bg-primary-50 transition-all duration-150 text-center"
                    >
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-600">{{ $label }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- フォームエリア --}}
            <div id="create-form-area" class="hidden px-6 pb-6">
                <div class="w-full h-px bg-gray-100 mb-5"></div>

                {{-- 投稿フォーム --}}
                <form id="form-post" class="create-form hidden space-y-4" method="POST" action="{{ route('create.post') }}">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">種別</label>
                        <select name="type" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                            <option value="news">NEWS</option>
                            <option value="activity">活動報告</option>
                            <option value="admission">入ゼミ</option>
                            <option value="blog">Blog</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">タイトル <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required placeholder="記事タイトルを入力" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">抜粋（任意）</label>
                        <textarea name="excerpt" rows="2" placeholder="記事の要約（一覧ページに表示）" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">本文</label>
                        <textarea name="content" rows="6" placeholder="本文を入力（あとからページ上で直接編集できます）" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 resize-y"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">公開日</label>
                        <input type="date" name="published_at" value="{{ now()->format('Y-m-d') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                    </div>
                    <button type="submit" class="w-full bg-primary-700 text-white text-sm font-medium py-2.5 rounded-lg hover:bg-primary-600 transition-colors duration-150">
                        作成して投稿ページへ
                    </button>
                </form>

                {{-- ケーススタディフォーム --}}
                <form id="form-case-study" class="create-form hidden space-y-4" method="POST" action="{{ route('create.case-study') }}">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">タイトル <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required placeholder="ケーススタディのタイトル" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">概要</label>
                        <textarea name="description" rows="3" placeholder="概要説明（一覧に表示）" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 resize-none"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">本文</label>
                        <textarea name="content" rows="6" placeholder="本文（あとから直接編集できます）" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 resize-y"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">公開日</label>
                        <input type="date" name="published_at" value="{{ now()->format('Y-m-d') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                    </div>
                    <button type="submit" class="w-full bg-primary-700 text-white text-sm font-medium py-2.5 rounded-lg hover:bg-primary-600 transition-colors duration-150">
                        作成してページへ
                    </button>
                </form>

                {{-- メンバーフォーム --}}
                <form id="form-member" class="create-form hidden space-y-4" method="POST" action="{{ route('create.member') }}">
                    @csrf
                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">名前 <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required placeholder="山田 太郎" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">よみがな</label>
                            <input type="text" name="name_kana" placeholder="やまだ たろう" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">期</label>
                            <input type="number" name="generation" placeholder="例: 5" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">学年</label>
                            <select name="university_year" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                                @foreach(['B1','B2','B3','B4','M1','M2','D1','D2'] as $yr)
                                <option value="{{ $yr }}">{{ $yr }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">専攻</label>
                            <input type="text" name="major" placeholder="法学部 政治学科" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">自己紹介</label>
                            <textarea name="bio" rows="3" placeholder="一言自己紹介" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 resize-none"></textarea>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 mb-1.5">ステータス</label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 text-sm cursor-pointer">
                                    <input type="radio" name="status" value="active" checked class="accent-primary-700"> 現役
                                </label>
                                <label class="flex items-center gap-2 text-sm cursor-pointer">
                                    <input type="radio" name="status" value="alumni" class="accent-primary-700"> OB/OG
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-primary-700 text-white text-sm font-medium py-2.5 rounded-lg hover:bg-primary-600 transition-colors duration-150">
                        メンバーを追加
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endauth
