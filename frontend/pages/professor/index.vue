<script setup lang="ts">
const { getOnce } = useApi()

let professor: any = null
try { professor = await getOnce('/professor') } catch {}
</script>

<template>
  <div>
    <div class="pt-32 pb-16 relative" style="background: linear-gradient(155deg, #041c33 0%, #0d5189 100%)">
      <div class="absolute inset-0 opacity-[0.05]"
        style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 36px 36px;" />
      <div class="relative max-w-7xl mx-auto px-6 md:px-14">
        <p class="text-white/35 text-[10px] tracking-[0.4em] uppercase mb-4">Professor</p>
        <h1 class="text-white text-4xl md:text-5xl font-bold tracking-tight">森 聡 教授</h1>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 md:px-14 py-16">
      <div class="grid md:grid-cols-3 gap-16">
        <!-- Photo -->
        <div class="md:col-span-1">
          <div class="aspect-[4/5] overflow-hidden bg-primary-50 relative">
            <img v-if="professor?.profile_image_url"
              :src="useStorageUrl(professor.profile_image_url) ?? ''"
              alt="森聡教授" class="w-full h-full object-cover" />
            <img v-else src="https://picsum.photos/seed/professor/400/500" alt="森聡教授" class="w-full h-full object-cover" />
          </div>
          <div class="mt-6 space-y-2">
            <h2 class="text-2xl font-bold text-gray-900">{{ professor?.name ?? '森 聡' }}</h2>
            <p class="text-gray-500 text-sm">{{ professor?.name_en ?? 'Satoshi Mori' }}</p>
            <p class="text-gray-500 text-sm">{{ professor?.title ?? '慶應義塾大学 法学部 教授' }}</p>
          </div>
          <div v-if="professor?.research_themes?.length" class="mt-6">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-3">研究テーマ</p>
            <div class="flex flex-wrap gap-2">
              <span v-for="theme in professor.research_themes" :key="theme"
                class="text-xs px-3 py-1.5 bg-primary-50 text-primary-700 font-medium">{{ theme }}</span>
            </div>
          </div>
          <div v-else class="mt-6 flex flex-wrap gap-2">
            <span v-for="tag in ['国際政治学', '現代アメリカ外交', '冷戦史', 'インド太平洋戦略', 'AI・安全保障']" :key="tag"
              class="text-xs px-3 py-1.5 bg-primary-50 text-primary-700 font-medium">{{ tag }}</span>
          </div>
        </div>

        <!-- Text -->
        <div class="md:col-span-2 space-y-12">
          <div>
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-4">プロフィール</p>
            <div v-if="professor?.bio" class="text-gray-600 text-sm leading-[2]" v-html="professor.bio" />
            <div v-else class="text-gray-600 text-sm leading-[2] space-y-4">
              <p>専門は国際政治学、現代アメリカ外交、冷戦史。1972年大阪府生まれ。小学生時代にロンドン、高校時代に香港で過ごす。</p>
              <p>1995年京都大学法学部卒業。外務省勤務後、コロンビア大学ロースクール修了。2007年東京大学にて博士号取得。2008年法政大学法学部准教授、2010年より教授。現在、慶應義塾大学法学部教授。</p>
              <p>現在の研究領域は国際秩序の動態分析、アメリカのインド太平洋戦略、人工知能などの先端技術と安全保障の関係。</p>
            </div>
          </div>

          <div v-if="professor?.career?.length">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-4">経歴</p>
            <dl class="space-y-3">
              <div v-for="(item, i) in professor.career" :key="i" class="flex gap-4">
                <dt class="text-gray-400 text-sm font-mono w-16 shrink-0">{{ item.year }}</dt>
                <dd class="text-gray-600 text-sm leading-relaxed">{{ item.description }}</dd>
              </div>
            </dl>
          </div>
          <div v-else>
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-4">経歴</p>
            <dl class="space-y-3">
              <div v-for="item in [
                { year: '1995', desc: '京都大学法学部卒業' },
                { year: '2001', desc: 'コロンビア大学ロースクール修了 (LL.M.)' },
                { year: '2007', desc: '東京大学大学院 博士号取得' },
                { year: '2008', desc: '法政大学法学部 准教授' },
                { year: '2010', desc: '法政大学法学部 教授' },
              ]" :key="item.year" class="flex gap-4">
                <dt class="text-gray-400 text-sm font-mono w-16 shrink-0">{{ item.year }}</dt>
                <dd class="text-gray-600 text-sm">{{ item.desc }}</dd>
              </div>
            </dl>
          </div>

          <div v-if="professor?.awards?.length">
            <p class="text-xs font-semibold tracking-[0.2em] uppercase text-primary-700 mb-4">受賞歴</p>
            <dl class="space-y-3">
              <div v-for="(item, i) in professor.awards" :key="i" class="flex gap-4">
                <dt class="text-gray-400 text-sm font-mono w-16 shrink-0">{{ item.year }}</dt>
                <dd class="text-gray-600 text-sm">{{ item.name }}</dd>
              </div>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
