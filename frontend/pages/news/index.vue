<script setup lang="ts">
const route = useRoute()
const { get } = useApi()

const activeType = ref((route.query.type as string) || 'all')
const page = ref(Number(route.query.page) || 1)

const typeOptions = [
  { value: 'all',      label: 'すべて' },
  { value: 'news',     label: 'NEWS' },
  { value: 'activity', label: '活動報告' },
  { value: 'admission',label: '入ゼミ' },
]

const config = useRuntimeConfig()

const params = computed(() => ({
  per_page: 20,
  page: page.value,
  types: activeType.value === 'all' ? 'news,activity,admission' : activeType.value,
}))

const { data } = await useFetch<{ data: Post[]; last_page: number; total: number }>(
  `${config.public.apiBase}/posts`,
  { params, watch: [params] }
)

watch([activeType, page], () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
})

const typeLabel: Record<string, string> = {
  news: 'NEWS', blog: 'Blog', activity: '活動報告', admission: '入ゼミ',
}
const typeStyle: Record<string, string> = {
  news: 'bg-primary-700 text-white',
  blog: 'bg-emerald-700 text-white',
  activity: 'bg-amber-600 text-white',
  admission: 'bg-rose-700 text-white',
}
</script>

<template>
  <div>
    <!-- Page Hero -->
    <div class="pt-32 pb-16 relative" style="background: linear-gradient(155deg, #041c33 0%, #0d5189 100%)">
      <div class="absolute inset-0 opacity-[0.05]"
        style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;" />
      <div class="relative max-w-7xl mx-auto px-6 md:px-14">
        <p class="text-white/35 text-[10px] tracking-[0.4em] uppercase mb-4">Latest Updates</p>
        <h1 class="text-white text-4xl md:text-5xl font-bold tracking-tight">ニュース</h1>
      </div>
    </div>

    <!-- Filter -->
    <div class="bg-white border-b border-gray-100 sticky top-[60px] z-40">
      <div class="max-w-7xl mx-auto px-6 md:px-14">
        <div class="flex gap-0 overflow-x-auto">
          <button
            v-for="opt in typeOptions" :key="opt.value"
            class="px-5 py-4 text-sm font-medium whitespace-nowrap border-b-2 transition-colors"
            :class="activeType === opt.value
              ? 'border-primary-700 text-primary-700'
              : 'border-transparent text-gray-400 hover:text-gray-700'"
            @click="activeType = opt.value; page = 1"
          >
            {{ opt.label }}
          </button>
        </div>
      </div>
    </div>

    <!-- List -->
    <div class="max-w-7xl mx-auto px-6 md:px-14 py-16">
      <ul class="divide-y divide-gray-100">
        <li v-for="item in data?.data" :key="item.id">
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
        <li v-if="!data?.data?.length" class="py-16 text-center text-gray-400">
          記事がありません
        </li>
      </ul>

      <!-- Pagination -->
      <div v-if="(data?.last_page ?? 1) > 1" class="flex justify-center gap-2 mt-12">
        <button
          v-for="p in data?.last_page" :key="p"
          class="w-9 h-9 text-sm font-medium transition-colors"
          :class="page === p ? 'bg-primary-700 text-white' : 'text-gray-400 hover:text-gray-800'"
          @click="page = p"
        >{{ p }}</button>
      </div>
    </div>
  </div>
</template>
