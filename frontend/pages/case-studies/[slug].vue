<script setup lang="ts">
const route = useRoute()
const { getOnce } = useApi()

const cs = await getOnce<any>(`/case-studies/${route.params.slug}`)
useSeoMeta({ title: `${cs.title} | 森聡研究会`, description: cs.description ?? undefined })
</script>

<template>
  <div>
    <div class="pt-32 pb-16 relative" style="background: linear-gradient(155deg, #041c33 0%, #0d5189 100%)">
      <div class="absolute inset-0 opacity-[0.05]"
        style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;" />
      <div class="relative max-w-4xl mx-auto px-6 md:px-14">
        <NuxtLink to="/case-studies" class="inline-flex items-center gap-2 text-white/40 hover:text-white text-xs tracking-widest uppercase mb-6 transition-colors">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
          </svg>
          ケーススタディ一覧
        </NuxtLink>
        <div class="flex items-center gap-3 mb-5">
          <time class="text-white/40 text-sm font-mono">
            {{ cs.published_at?.slice(0, 10).replace(/-/g, '.') }}
          </time>
          <span v-if="cs.author" class="text-white/40 text-sm">{{ cs.author.name }}</span>
        </div>
        <h1 class="text-white text-2xl md:text-4xl font-bold leading-tight">{{ cs.title }}</h1>
      </div>
    </div>

    <div class="max-w-4xl mx-auto px-6 md:px-14 py-16">
      <div v-if="cs.content"
        class="prose prose-lg prose-gray max-w-none prose-headings:font-bold prose-a:text-primary-700"
        v-html="cs.content" />
      <p v-else class="text-gray-400">本文がありません。</p>

      <div class="mt-12 pt-8 border-t border-gray-100">
        <NuxtLink to="/case-studies" class="inline-flex items-center gap-2 text-primary-700 text-sm font-medium hover:gap-3 transition-all">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
          </svg>
          一覧に戻る
        </NuxtLink>
      </div>
    </div>
  </div>
</template>
