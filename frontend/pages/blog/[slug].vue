<script setup lang="ts">
const route = useRoute()
const { getOnce } = useApi()

const post = await getOnce<Post>(`/posts/${route.params.slug}`)
useSeoMeta({ title: `${post.title} | 森聡研究会`, description: post.excerpt ?? undefined })
</script>

<template>
  <div>
    <div class="pt-32 pb-16 relative" style="background: linear-gradient(155deg, #041c33 0%, #0d5189 100%)">
      <div class="absolute inset-0 opacity-[0.05]"
        style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;" />
      <div class="relative max-w-4xl mx-auto px-6 md:px-14">
        <NuxtLink to="/blog" class="fade-in inline-flex items-center gap-2 text-white/40 hover:text-white text-xs tracking-widest uppercase mb-6 transition-colors">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          ブログ一覧
        </NuxtLink>
        <div class="fade-in flex items-center gap-3 mb-5">
          <span class="text-[10px] font-bold tracking-[0.15em] px-2.5 py-1 bg-emerald-700 text-white">Blog</span>
          <time class="text-white/40 text-sm font-mono">
            {{ post.published_at?.slice(0, 10).replace(/-/g, '.') }}
          </time>
          <span v-if="post.author" class="text-white/40 text-sm">{{ post.author.name }}</span>
        </div>
        <h1 class="fade-in text-white text-2xl md:text-4xl font-bold leading-tight">{{ post.title }}</h1>
      </div>
    </div>

    <div class="max-w-4xl mx-auto px-6 md:px-14 py-16">
      <div v-if="post.content"
        class="fade-in prose prose-lg prose-gray max-w-none prose-headings:font-bold prose-a:text-primary-700"
        v-html="post.content" />
      <p v-else class="fade-in text-gray-400">本文がありません。</p>

      <div v-if="post.tags?.length" class="fade-in flex flex-wrap gap-2 mt-12 pt-8 border-t border-gray-100">
        <span v-for="tag in post.tags" :key="tag.id"
          class="text-xs px-3 py-1.5 bg-primary-50 text-primary-700 font-medium">
          {{ tag.name }}
        </span>
      </div>
      <div class="fade-in mt-12 pt-8 border-t border-gray-100">
        <NuxtLink to="/blog" class="inline-flex items-center gap-2 text-primary-700 text-sm font-medium hover:gap-3 transition-all">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
          </svg>
          ブログ一覧に戻る
        </NuxtLink>
      </div>
    </div>
  </div>
</template>
