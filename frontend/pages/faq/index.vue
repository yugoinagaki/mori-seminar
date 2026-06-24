<script setup lang="ts">
const { get } = useApi()
const { data } = await get<{ question: string; answer: string; category: string | null; id: number }[]>('/faqs')

const faqs = computed(() => data.value ?? [])
const categories = computed(() => [...new Set(faqs.value.map(f => f.category).filter(Boolean))])
const openId = ref<number | null>(null)
const toggle = (id: number) => { openId.value = openId.value === id ? null : id }
</script>

<template>
  <div>
    <div class="pt-32 pb-16 relative" style="background: linear-gradient(155deg, #041c33 0%, #0d5189 100%)">
      <div class="absolute inset-0 opacity-[0.05]"
        style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;" />
      <div class="relative max-w-7xl mx-auto px-6 md:px-14">
        <p class="text-white/35 text-[10px] tracking-[0.4em] uppercase mb-4">FAQ</p>
        <h1 class="text-white text-4xl md:text-5xl font-bold tracking-tight">よくある質問</h1>
      </div>
    </div>

    <div class="max-w-3xl mx-auto px-6 md:px-14 py-16">
      <div v-if="faqs.length">
        <template v-for="cat in [...categories, null]" :key="cat">
          <div v-if="faqs.filter(f => f.category === cat).length" class="mb-12">
            <h2 v-if="cat" class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-6">{{ cat }}</h2>
            <dl class="divide-y divide-gray-100">
              <div v-for="faq in faqs.filter(f => f.category === cat)" :key="faq.id">
                <dt>
                  <button
                    class="w-full flex items-start justify-between gap-4 py-5 text-left"
                    @click="toggle(faq.id)"
                  >
                    <span class="text-gray-800 font-medium text-sm md:text-base leading-relaxed">{{ faq.question }}</span>
                    <svg class="w-5 h-5 text-primary-700 shrink-0 mt-0.5 transition-transform duration-200"
                      :class="openId === faq.id ? 'rotate-180' : ''"
                      fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                  </button>
                </dt>
                <dd v-show="openId === faq.id" class="pb-5 text-gray-500 text-sm leading-relaxed">
                  {{ faq.answer }}
                </dd>
              </div>
            </dl>
          </div>
        </template>
      </div>
      <p v-else class="py-16 text-center text-gray-400">FAQはまだ登録されていません</p>

      <div class="mt-12 pt-8 border-t border-gray-100 text-center">
        <p class="text-gray-500 text-sm mb-6">解決しない場合はお気軽にお問い合わせください</p>
        <NuxtLink to="/contact" class="btn-primary">お問い合わせ</NuxtLink>
      </div>
    </div>
  </div>
</template>
