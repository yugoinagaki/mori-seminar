<script setup lang="ts">
// layout: default を使用

const { get } = useApi()

const { data: postsData }   = await get<{ data: Post[] }>('/posts', { per_page: 5 })
const { data: tickerData }  = await get<{ data: Post[] }>('/posts', { type: 'news', per_page: 20 })
const { data: membersData } = await get<Member[]>('/members', { status: 'active' })
const { data: settings }    = await get<{ hero_image_url: string | null }>('/settings')
const { data: professor }   = await get<any>('/professor')
const { data: theme }       = await get<any>('/annual-themes/latest')
const { data: worldNews }   = await useFetch<{ results: any[] }>('/api/world-news')

const newsArticles = computed(() => worldNews.value?.results ?? [])

const currentNewsIndex = ref(0)
const newsProgress = ref(0)
const INTERVAL = 7000

const currentNews = computed(() => newsArticles.value[currentNewsIndex.value] ?? null)

let timer: ReturnType<typeof setInterval> | null = null
let progressTimer: ReturnType<typeof setInterval> | null = null

const startRotation = () => {
  timer = setInterval(() => {
    currentNewsIndex.value = (currentNewsIndex.value + 1) % Math.max(newsArticles.value.length, 1)
    newsProgress.value = 0
  }, INTERVAL)
  progressTimer = setInterval(() => {
    newsProgress.value = Math.min(newsProgress.value + (100 / (INTERVAL / 100)), 100)
  }, 100)
}

onMounted(() => { if (newsArticles.value.length) startRotation() })
onUnmounted(() => {
  if (timer) clearInterval(timer)
  if (progressTimer) clearInterval(progressTimer)
})
watch(newsArticles, (v) => { if (v.length && !timer) startRotation() })

const timeAgo = (dateStr: string) => {
  if (!dateStr) return ''
  const diff = Date.now() - new Date(dateStr).getTime()
  const h = Math.floor(diff / 3600000)
  const d = Math.floor(h / 24)
  if (d > 0) return `${d}日前`
  if (h > 0) return `${h}時間前`
  return 'たった今'
}

const heroImageUrl = computed(() => useStorageUrl(settings.value?.hero_image_url ?? null))

const newsItems = computed(() => postsData.value?.data ?? [])
const members   = computed(() => (membersData.value ?? []).slice(0, 6))

const typeLabel: Record<string, string> = {
  news: 'NEWS', blog: 'Blog', activity: '活動報告', admission: '入ゼミ',
}
const typeStyle: Record<string, string> = {
  news: 'bg-primary-700 text-white',
  blog: 'bg-emerald-700 text-white',
  activity: 'bg-amber-600 text-white',
  admission: 'bg-rose-700 text-white',
}

const tickerItems = computed(() =>
  (tickerData.value?.data ?? []).map(p => ({
    slug:  p.slug,
    label: `${p.published_at?.slice(0, 10).replace(/-/g, '.')} ── ${p.title}`,
  }))
)

const newsCardVisible = ref(true)
</script>

<template>
  <div class="overflow-x-hidden">

    <!-- HERO -->
    <section class="relative min-h-screen flex flex-col justify-center overflow-hidden">
      <!-- 背景: 写真 or グラデーション -->
      <div class="absolute inset-0">
        <img
          v-if="heroImageUrl"
          :src="heroImageUrl"
          alt=""
          class="w-full h-full object-cover object-center"
        />
        <div
          v-else
          class="w-full h-full"
          style="background: linear-gradient(155deg, #041c33 0%, #083a63 45%, #0d5189 100%)"
        />
      </div>
      <!-- 写真の上にかぶせるオーバーレイ -->
      <div class="absolute inset-0 bg-primary-950/60" />
      <!-- ドットパターン -->
      <div class="absolute inset-0 opacity-[0.04]"
        style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;" />
      <!-- 右側グロー（グラデーション背景のときだけ自然に見える） -->
      <div v-if="!heroImageUrl" class="absolute right-0 top-0 bottom-0 w-1/2 opacity-20 pointer-events-none"
        style="background: radial-gradient(ellipse at 80% 40%, #1a79c0, transparent 70%)" />
      <!-- 上フェード（ヘッダーと背景を自然に馴染ませる） -->
      <div class="absolute top-0 left-0 right-0 h-36 pointer-events-none"
        style="background: linear-gradient(to bottom, rgba(4,28,51,0.75) 0%, transparent 100%)" />
      <!-- 下フェード -->
      <div class="absolute bottom-0 left-0 right-0 h-40"
        style="background: linear-gradient(to bottom, transparent, rgba(4,28,51,0.7))" />

      <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-14 pt-36 pb-28">
        <div class="text-center md:text-left">
        <!-- ラベル -->
        <p class="hero-sub text-white/70 text-[10px] tracking-[0.45em] uppercase font-light mb-10"
          style="animation-delay: 0.05s">
          Keio University
        </p>

        <!-- メインタイトル: 1文字ずつ登場 -->
        <h1 class="text-white font-bold mb-4 leading-none font-mincho"
          style="font-size: clamp(2.8rem, 8vw, 6rem); letter-spacing: 0.12em">
          <span
            v-for="(char, i) in '森聡研究会'.split('')"
            :key="i"
            class="hero-char"
            :style="`animation-delay: ${0.2 + i * 0.2}s`"
          >{{ char }}</span>
        </h1>

        <!-- 英語サブタイトル -->
        <p class="hero-sub text-white/75 text-sm md:text-base font-light tracking-[0.25em] uppercase mb-10"
          style="animation-delay: 1.6s">
          mori satoru seminar
        </p>

        <!-- 区切り + キーワード -->
        <div class="hero-sub flex items-center justify-center md:justify-start gap-5 mb-10" style="animation-delay: 1.9s">
          <div class="w-10 h-px bg-white/60" />
          <p class="text-white text-xs tracking-widest font-medium"
            style="text-shadow: 0 1px 8px rgba(0,0,0,0.8)">
            現代国際政治
          </p>
        </div>

        <!-- CTA -->
        <div class="hero-sub flex flex-wrap justify-center md:justify-start gap-4" style="animation-delay: 2.1s">
          <NuxtLink to="/theme" class="btn-outline-white">
            研究テーマについて
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </NuxtLink>
          <NuxtLink to="/professor" class="btn-outline-white">
            森聡教授について
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </NuxtLink>
        </div>
        </div>

      </div>

      <!-- 右上ニュースカード（デスクトップのみ） -->
      <div class="hidden lg:block fixed top-28 right-4 w-64 z-40 hero-sub" style="animation-delay: 2.6s">

        <!-- 非表示時の「Newsを表示」ボタン -->
        <Transition name="news-card" mode="out-in">
          <button
            v-if="!newsCardVisible"
            @click="newsCardVisible = true"
            class="ml-auto flex items-center gap-1.5 px-3 py-1.5 text-white/70 hover:text-white text-xs border border-white/20 backdrop-blur-sm transition-colors"
            style="background: rgba(4, 18, 35, 0.7)"
          >
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Newsを表示
          </button>
        </Transition>

        <!-- カード上のマイナスボタン -->
        <div v-if="newsCardVisible" class="flex justify-end mb-1">
          <button
            @click="newsCardVisible = false"
            class="flex items-center justify-center w-5 h-5 rounded-full bg-primary-900/80 hover:bg-primary-700 text-white/80 hover:text-white transition-all border border-white/20"
            aria-label="閉じる"
          >
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
            </svg>
          </button>
        </div>

        <Transition name="news-card" mode="out-in">
          <a
            v-if="currentNews && newsCardVisible"
            :key="currentNewsIndex"
            :href="currentNews.link"
            target="_blank"
            rel="noopener"
            class="block border border-white/15 backdrop-blur-sm overflow-hidden group"
            style="background: rgba(4, 18, 35, 0.78)"
          >
            <!-- ヘッダー -->
            <div class="flex items-center justify-between px-4 py-2.5 border-b border-white/10">
              <div class="flex items-center gap-2">
                <span class="relative flex h-1.5 w-1.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75" />
                  <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-emerald-500" />
                </span>
                <span class="text-white/50 text-[9px] tracking-[0.25em] uppercase">国際情勢 · NHK</span>
              </div>
              <span class="text-white/25 text-[9px] font-mono">{{ currentNewsIndex + 1 }} / {{ newsArticles.length }}</span>
            </div>

            <!-- 本文 -->
            <div class="px-4 py-4">
              <p class="text-white/85 text-sm leading-relaxed group-hover:text-white transition-colors font-medium">
                {{ currentNews.title }}
              </p>
              <p class="text-white/30 text-[10px] font-mono mt-3">
                {{ timeAgo(currentNews.pubDate) }}
              </p>
            </div>

            <!-- プログレスバー -->
            <div class="h-px bg-white/10">
              <div
                class="h-full bg-primary-400 transition-none"
                :style="{ width: `${newsProgress}%` }"
              />
            </div>

            <!-- ドットインジケーター -->
            <div class="flex items-center justify-between px-4 py-2.5">
              <div class="flex gap-1">
                <span
                  v-for="(_, i) in newsArticles"
                  :key="i"
                  class="block rounded-full transition-all duration-300"
                  :class="i === currentNewsIndex
                    ? 'w-3 h-1 bg-white/60'
                    : 'w-1 h-1 bg-white/20'"
                />
              </div>
              <svg class="w-3 h-3 text-white/30 group-hover:text-white/60 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
              </svg>
            </div>
          </a>
        </Transition>
      </div>

    </section>

    <!-- TICKER -->
    <div class="bg-primary-700 py-2.5 overflow-hidden select-none">
      <div class="ticker-track">
        <template v-for="pass in 2" :key="pass">
          <NuxtLink
            v-for="(item, i) in tickerItems"
            :key="`${pass}-${i}`"
            :to="`/news/${item.slug}`"
            class="text-white/80 hover:text-white text-xs tracking-wide font-light mx-10 whitespace-nowrap transition-colors"
          >
            <span class="text-white/30 mr-6">◆</span>{{ item.label }}
          </NuxtLink>
        </template>
      </div>
    </div>

    <!-- NEWS -->
    <section class="py-24 bg-white">
      <div class="max-w-7xl mx-auto px-6 md:px-14">
        <div class="flex items-end justify-between mb-12">
          <div class="fade-in">
            <p class="section-label">Latest Updates</p>
            <h2 class="section-title">ニュース</h2>
          </div>
          <NuxtLink to="/news" class="hidden md:inline-flex items-center gap-2 text-primary-700 text-sm font-medium hover:gap-3 transition-all fade-in">
            一覧を見る
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </NuxtLink>
        </div>
        <ul class="divide-y divide-gray-100">
          <li v-for="item in newsItems" :key="item.id" class="fade-in">
            <NuxtLink :to="`/news/${item.slug}`" class="flex flex-col sm:flex-row sm:items-center gap-3 py-5 group">
              <time class="text-gray-400 text-sm font-mono w-28 shrink-0">
                {{ item.published_at?.slice(0, 10).replace(/-/g, '.') }}
              </time>
              <span class="text-[10px] font-bold tracking-[0.15em] px-2.5 py-1 w-fit shrink-0"
                :class="typeStyle[item.type] ?? 'bg-gray-200 text-gray-600'">
                {{ typeLabel[item.type] ?? item.type }}
              </span>
              <span class="flex-1 text-gray-700 text-sm md:text-[15px] font-medium group-hover:text-primary-700 transition-colors">
                {{ item.title }}
              </span>
              <svg class="w-4 h-4 text-gray-300 group-hover:text-primary-700 group-hover:translate-x-1 transition-all shrink-0 hidden sm:block"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
              </svg>
            </NuxtLink>
          </li>
          <li v-if="!newsItems.length" class="py-10 text-center text-gray-400 text-sm">
            まだ記事がありません
          </li>
        </ul>
      </div>
    </section>

    <!-- ANNUAL THEME -->
    <section v-if="theme" class="py-28 relative overflow-hidden"
      style="background: linear-gradient(140deg, #0d5189 0%, #1a79c0 100%)">
      <div class="absolute -right-40 -top-40 w-[500px] h-[500px] rounded-full border border-white/8 pointer-events-none" />
      <div class="absolute -right-20 -top-20 w-[360px] h-[360px] rounded-full border border-white/6 pointer-events-none" />
      <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-14 fade-in">
        <div class="max-w-2xl">
          <p class="text-white/35 text-[10px] tracking-[0.35em] uppercase font-light mb-8">Annual Theme</p>
          <div class="text-white/12 font-bold leading-none mb-2 select-none"
            style="font-size: clamp(5rem, 15vw, 10rem)">{{ theme.year }}</div>
          <h2 class="text-white text-2xl md:text-3xl font-bold mb-7 leading-tight -mt-2">
            {{ theme.title }}
          </h2>
          <div v-if="theme.content"
            class="text-white/65 text-sm md:text-base leading-[2] mb-10 line-clamp-3"
            v-html="theme.content" />
          <NuxtLink to="/theme"
            class="inline-flex items-center gap-3 text-white text-sm font-medium border-b border-white/35 pb-0.5 hover:border-white hover:gap-4 transition-all">
            テーマ詳細を読む
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </NuxtLink>
        </div>
      </div>
    </section>

    <!-- PROFESSOR -->
    <section v-if="professor" class="py-24 bg-gray-50">
      <div class="max-w-7xl mx-auto px-6 md:px-14">
        <div class="grid md:grid-cols-2 gap-16 lg:gap-24 items-center">
          <div class="fade-in relative">
            <div class="aspect-[4/5] overflow-hidden bg-primary-100">
              <img
                :src="useStorageUrl(professor.profile_image_url) ?? 'https://picsum.photos/seed/professor/600/750'"
                :alt="professor.name"
                class="w-full h-full object-cover object-center"
              />
            </div>
            <div class="absolute -bottom-5 -right-5 w-28 h-28 border border-primary-200 pointer-events-none" style="z-index: -1" />
          </div>
          <div class="fade-in">
            <p class="section-label">Professor</p>
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-2 tracking-tight">{{ professor.name }}</h2>
            <p class="text-gray-400 text-sm mb-8 tracking-wide">
              {{ professor.name_en }} &nbsp;|&nbsp; {{ professor.title }}
            </p>
            <div v-if="professor.bio"
              class="text-gray-500 text-sm leading-[2] mb-5 line-clamp-4"
              v-html="professor.bio" />
            <div v-if="professor.research_themes?.length" class="flex flex-wrap gap-2 mb-10">
              <span v-for="tag in professor.research_themes" :key="tag"
                class="text-xs px-3 py-1.5 bg-primary-50 text-primary-700 font-medium tracking-wide">
                {{ tag }}
              </span>
            </div>
            <NuxtLink to="/professor" class="btn-primary">
              プロフィールを見る
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
              </svg>
            </NuxtLink>
          </div>
        </div>
      </div>
    </section>

    <!-- MEMBERS -->
    <section class="py-24 bg-white">
      <div class="max-w-7xl mx-auto px-6 md:px-14">
        <div class="flex items-end justify-between mb-12">
          <div class="fade-in">
            <p class="section-label">Members</p>
            <h2 class="section-title">ゼミ生紹介</h2>
          </div>
          <NuxtLink to="/members" class="hidden md:inline-flex items-center gap-2 text-primary-700 text-sm font-medium hover:gap-3 transition-all fade-in">
            全員を見る
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
          </NuxtLink>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-5">
          <template v-if="members.length">
            <div v-for="member in members" :key="member.id" class="fade-in group cursor-pointer">
              <div class="aspect-square overflow-hidden mb-3 bg-gray-100">
                <img v-if="member.profile_image_url"
                  :src="useStorageUrl(member.profile_image_url) ?? ''"
                  :alt="member.name"
                  class="w-full h-full object-cover grayscale group-hover:grayscale-0 scale-[1.04] group-hover:scale-100 transition-all duration-500" />
                <div v-else class="w-full h-full bg-primary-100 flex items-center justify-center">
                  <span class="text-primary-300 text-2xl font-bold">{{ member.name[0] }}</span>
                </div>
              </div>
              <p class="text-sm font-medium text-gray-800">{{ member.name }}</p>
              <p class="text-xs text-gray-400 mt-0.5">{{ member.university_year ? `${member.university_year}年` : '' }}</p>
            </div>
          </template>
          <template v-else>
            <div v-for="i in 6" :key="i" class="fade-in">
              <div class="aspect-square bg-gray-100 mb-3" />
              <div class="h-3 bg-gray-100 rounded w-2/3 mb-1" />
              <div class="h-2.5 bg-gray-100 rounded w-1/3" />
            </div>
          </template>
        </div>
      </div>
    </section>

    <!-- CTA -->
    <section class="py-28 relative overflow-hidden"
      style="background: linear-gradient(160deg, #041c33 0%, #083a63 100%)">
      <div class="absolute inset-0 opacity-[0.04]"
        style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;" />
      <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-14 text-center fade-in">
        <p class="text-white/30 text-[10px] tracking-[0.4em] uppercase mb-8">Contact Us</p>
        <h2 class="text-white text-3xl md:text-4xl font-bold mb-6 leading-tight">お問い合わせ</h2>
        <p class="text-white/45 text-sm md:text-base leading-relaxed mb-12 max-w-md mx-auto">
          森聡研究会へのお問い合わせは<br>下記よりご連絡ください。
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <NuxtLink to="/contact" class="btn-outline-white">
            お問い合わせ
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
          </NuxtLink>
          <NuxtLink to="/faq" class="inline-flex items-center justify-center gap-2 px-7 py-3.5 text-white/40 hover:text-white text-sm font-medium transition-colors tracking-wide">
            FAQ を見る
          </NuxtLink>
        </div>
      </div>
    </section>

  </div>
</template>
