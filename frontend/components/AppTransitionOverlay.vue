<script setup lang="ts">
const { visible, animKey } = usePageTransition()
const config = useRuntimeConfig()
const storageBase = config.public.storageBase as string
const apiBase = config.public.apiBase as string

const transitionImages = useState<string[]>('transition-images', () => [])
const bgUrl = ref('/forest.png')

function toFullUrl(path: string): string {
  if (!path) return '/forest.png'
  if (path.startsWith('http')) return path
  return `${storageBase}/${path}`
}

onMounted(async () => {
  if (transitionImages.value.length === 0) {
    try {
      const settings = await $fetch<{ transition_image_urls?: string[] | null }>(`${apiBase}/settings`)
      transitionImages.value = settings?.transition_image_urls ?? []
    } catch {}
  }
})

watch(animKey, () => {
  const imgs = transitionImages.value
  if (imgs.length > 0) {
    const raw = imgs[Math.floor(Math.random() * imgs.length)]
    bgUrl.value = toFullUrl(raw)
  }
})
</script>

<template>
  <ClientOnly>
    <div v-if="visible" :key="animKey" class="wipe-wrapper">
      <div class="wipe-forest" :style="`background-image: url('${bgUrl}')`" />
    </div>
  </ClientOnly>
</template>

<style scoped>
.wipe-wrapper {
  position: fixed;
  inset: 0;
  z-index: 9999;
  pointer-events: none;
  overflow: hidden;
  animation: r-expand 1.8s ease-in-out forwards;
}

.wipe-forest {
  position: absolute;
  inset: 0;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  -webkit-mask-image: radial-gradient(circle, transparent var(--r), black calc(var(--r) + 35%));
  mask-image:         radial-gradient(circle, transparent var(--r), black calc(var(--r) + 35%));
}

.wipe-forest::after {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(4, 28, 51, 0.35);
}

@keyframes r-expand {
  from { --r: -35%; }
  to   { --r: 100%; }
}
</style>

<style>
@property --r {
  syntax: '<percentage>';
  initial-value: -35%;
  inherits: true;
}
</style>
