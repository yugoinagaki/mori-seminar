<script setup lang="ts">
const route = useRoute()
const { getOnce } = useApi()

const post = await getOnce<Post>(`/posts/${route.params.slug}`)

useSeoMeta({
  title: `${post.title} | 森聡研究会`,
  description: post.excerpt ?? undefined,
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
    <!-- Hero -->
    <div class="pt-32 pb-16 relative" style="background: linear-gradient(155deg, #041c33 0%, #0d5189 100%)">
      <div class="absolute inset-0 opacity-[0.05]"
        style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;" />
      <div class="relative max-w-4xl mx-auto px-6 md:px-14">
        <NuxtLink to="/news" class="inline-flex items-center gap-2 text-white/40 hover:text-white text-xs tracking-widest uppercase mb-6 transition-colors">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          ニュース一覧
        </NuxtLink>
        <div class="flex items-center gap-3 mb-5">
          <span class="text-[10px] font-bold tracking-[0.15em] px-2.5 py-1"
            :class="typeStyle[post.type] ?? 'bg-gray-600 text-white'">
            {{ typeLabel[post.type] ?? post.type }}
          </span>
          <time class="text-white/40 text-sm font-mono">
            {{ post.published_at?.slice(0, 10).replace(/-/g, '.') }}
          </time>
        </div>
        <h1 class="text-white text-2xl md:text-4xl font-bold leading-tight">{{ post.title }}</h1>
      </div>
    </div>

    <!-- Content -->
    <div class="max-w-4xl mx-auto px-6 md:px-14 py-16">
      <div
        v-if="post.content"
        class="prose prose-lg prose-gray max-w-none
               prose-headings:font-bold prose-headings:text-gray-900
               prose-a:text-primary-700 prose-a:no-underline hover:prose-a:underline
               prose-img:rounded prose-img:w-full"
        v-html="post.content"
      />
      <p v-else class="text-gray-400">本文がありません。</p>

      <!-- Tags -->
      <div v-if="post.tags?.length" class="flex flex-wrap gap-2 mt-12 pt-8 border-t border-gray-100">
        <span v-for="tag in post.tags" :key="tag.id"
          class="text-xs px-3 py-1.5 bg-primary-50 text-primary-700 font-medium">
          {{ tag.name }}
        </span>
      </div>

      <div class="mt-12 pt-8 border-t border-gray-100">
        <NuxtLink to="/news" class="inline-flex items-center gap-2 text-primary-700 text-sm font-medium hover:gap-3 transition-all">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
          </svg>
          ニュース一覧に戻る
        </NuxtLink>
      </div>
    </div>
  </div>
</template>
