<script setup lang="ts">
const { get } = useApi()
const { observe } = useScrollAnimation()
const page = ref(1)

const params = computed(() => ({ type: 'blog', per_page: 12, page: page.value }))
const { data, refresh } = await get<{ data: Post[]; last_page: number }>('/posts', params.value)

watch(page, async () => {
  await refresh()
  window.scrollTo({ top: 0, behavior: 'smooth' })
  nextTick(observe)
})

watch(data, () => nextTick(observe))
</script>

<template>
  <div>
    <div class="pt-32 pb-16 relative" style="background: linear-gradient(155deg, #041c33 0%, #0d5189 100%)">
      <div class="absolute inset-0 opacity-[0.05]"
        style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;" />
      <div class="relative max-w-7xl mx-auto px-6 md:px-14">
        <p class="fade-in text-white/35 text-[10px] tracking-[0.4em] uppercase mb-4">Student Blog</p>
        <h1 class="fade-in text-white text-4xl md:text-5xl font-bold tracking-tight">ブログ</h1>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 md:px-14 py-16">
      <div v-if="data?.data?.length" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <NuxtLink
          v-for="post in data.data" :key="post.id"
          :to="`/blog/${post.slug}`"
          class="fade-in group block"
        >
          <div class="aspect-video bg-primary-50 overflow-hidden mb-4">
            <img v-if="post.thumbnail_url"
              :src="useStorageUrl(post.thumbnail_url) ?? ''"
              :alt="post.title"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <div v-else class="w-full h-full bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center">
              <span class="text-primary-400 text-4xl font-bold opacity-30">Blog</span>
            </div>
          </div>
          <time class="text-gray-400 text-xs font-mono">
            {{ post.published_at?.slice(0, 10).replace(/-/g, '.') }}
          </time>
          <h2 class="text-gray-800 font-bold mt-1 group-hover:text-primary-700 transition-colors leading-snug">
            {{ post.title }}
          </h2>
          <p v-if="post.excerpt" class="text-gray-500 text-sm mt-2 leading-relaxed line-clamp-2">{{ post.excerpt }}</p>
        </NuxtLink>
      </div>
      <p v-else class="py-16 text-center text-gray-400">まだ記事がありません</p>

      <div v-if="(data?.last_page ?? 1) > 1" class="flex justify-center gap-2 mt-12">
        <button v-for="p in data?.last_page" :key="p"
          class="w-9 h-9 text-sm font-medium transition-colors"
          :class="page === p ? 'bg-primary-700 text-white' : 'text-gray-400 hover:text-gray-800'"
          @click="page = p">{{ p }}</button>
      </div>
    </div>
  </div>
</template>
