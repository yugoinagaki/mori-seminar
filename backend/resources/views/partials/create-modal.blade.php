@auth
@if(in_array(auth()->user()->role ?? '', ['super_admin', 'editor']))
<div id="create-modal" class="fixed inset-0 z-[9995] hidden" role="dialog" aria-modal="true">
    {{-- Overlay --}}
    <div id="create-modal-overlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    {{-- Panel --}}
    <div class="relative flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-lg font-bold text-gray-900">新規作成</h2>
                <button id="create-modal-close" class="p-1 text-gray-400 hover:text-gray-600 transition-colors" aria-label="閉じる">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Type selector --}}
            <div class="flex gap-2 mb-5" id="create-type-selector">
                <button type="button" class="create-type-btn active px-4 py-2 rounded-full text-sm font-medium border-2 border-primary-700 text-primary-700 bg-primary-50" data-type="post">投稿</button>
                <button type="button" class="create-type-btn px-4 py-2 rounded-full text-sm font-medium border-2 border-transparent text-gray-500 hover:border-gray-300 transition-colors" data-type="case-study">研究事例</button>
                <button type="button" class="create-type-btn px-4 py-2 rounded-full text-sm font-medium border-2 border-transparent text-gray-500 hover:border-gray-300 transition-colors" data-type="member">メンバー</button>
            </div>

            {{-- Post form --}}
            <form id="form-post" method="POST" action="{{ route('create.post') }}">
                @csrf
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">種別</label>
                        <select name="type" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="news">NEWS</option>
                            <option value="activity">活動報告</option>
                            <option value="admission">入ゼミ</option>
                            <option value="blog">ブログ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">タイトル <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="記事タイトル">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">概要</label>
                        <input type="text" name="excerpt" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="一行概要（省略可）">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">本文</label>
                        <textarea name="content" rows="4" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none" placeholder="本文（省略可・後から編集可）"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">公開日</label>
                        <input type="date" name="published_at" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" id="create-cancel-post" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">キャンセル</button>
                    <button type="submit" class="btn-primary !py-2 !px-5 text-sm">作成</button>
                </div>
            </form>

            {{-- Case study form --}}
            <form id="form-case-study" method="POST" action="{{ route('create.case-study') }}" class="hidden">
                @csrf
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">タイトル <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="研究事例タイトル">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">概要</label>
                        <textarea name="description" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none" placeholder="概要（省略可）"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">本文</label>
                        <textarea name="content" rows="4" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none" placeholder="本文（省略可）"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">公開日</label>
                        <input type="date" name="published_at" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" id="create-cancel-cs" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">キャンセル</button>
                    <button type="submit" class="btn-primary !py-2 !px-5 text-sm">作成</button>
                </div>
            </form>

            {{-- Member form --}}
            <form id="form-member" method="POST" action="{{ route('create.member') }}" class="hidden">
                @csrf
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">氏名 <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="山田 太郎">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">よみがな</label>
                            <input type="text" name="name_kana" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="やまだ たろう">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">期</label>
                            <input type="number" name="generation" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="1">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">学年</label>
                            <input type="text" name="university_year" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="3年">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">専攻</label>
                        <input type="text" name="major" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="商学部 マーケティング学科">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">自己紹介</label>
                        <textarea name="bio" rows="2" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none" placeholder="（省略可）"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">ステータス <span class="text-rose-500">*</span></label>
                        <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="active">在籍中</option>
                            <option value="alumni">OB/OG</option>
                        </select>
                    </div>
                </div>
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" id="create-cancel-member" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">キャンセル</button>
                    <button type="submit" class="btn-primary !py-2 !px-5 text-sm">作成</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endif
@endauth
