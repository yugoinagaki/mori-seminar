<script setup lang="ts">
const isScrolled = ref(false)
const isMobileOpen = ref(false)

const navLinks = [
  { label: 'Home', href: '/' },
  { label: 'Theme', href: '/theme' },
  { label: 'News', href: '/news' },
  { label: 'Professor', href: '/professor' },
  { label: 'Blog', href: '/blog' },
  { label: 'Members', href: '/members' },
  { label: 'Case Studies', href: '/case-studies' },
  { label: 'FAQ', href: '/faq' },
  { label: 'Contact', href: '/contact' },
]

onMounted(() => {
  const onScroll = () => { isScrolled.value = window.scrollY > 60 }
  window.addEventListener('scroll', onScroll, { passive: true })
  onUnmounted(() => window.removeEventListener('scroll', onScroll))
})
</script>

<template>
  <nav
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
    :class="isScrolled ? 'backdrop-blur-md shadow-lg' : 'bg-transparent'"
    :style="isScrolled
      ? { background: 'linear-gradient(to bottom, rgba(10, 30, 70, 0.96) 0%, rgba(10, 30, 70, 0.3) 100%)' }
      : {}"
  >
    <div class="w-full px-4 flex items-center justify-between py-2">
      <!-- Logo -->
      <NuxtLink to="/" class="text-white flex items-center gap-3">
        <img :src="'/keio-logo.png'" alt="慶應義塾大学" class="h-8 w-auto object-contain shrink-0" />
        <img :src="'/seminar-logo.png'" alt="森聡研究会" class="h-20 w-auto object-contain" />
      </NuxtLink>

      <!-- Desktop nav -->
      <ul class="hidden lg:flex items-center gap-8">
        <li v-for="link in navLinks" :key="link.href">
          <NuxtLink
            :to="link.href"
            class="text-white/65 hover:text-white text-[13px] font-medium tracking-wide transition-colors duration-150"
            active-class="!text-white"
          >
            {{ link.label }}
          </NuxtLink>
        </li>
      </ul>

      <!-- Mobile toggle -->
      <button
        class="lg:hidden text-white p-1"
        @click="isMobileOpen = !isMobileOpen"
        :aria-expanded="isMobileOpen"
        aria-label="メニューを開く"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            :d="isMobileOpen ? 'M6 18L18 6M6 6l12 12' : 'M4 6h16M4 12h16M4 18h16'"
          />
        </svg>
      </button>
    </div>

    <!-- Mobile menu -->
    <Transition
      enter-active-class="transition-all duration-250 ease-out"
      enter-from-class="opacity-0 -translate-y-3"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition-all duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-3"
    >
      <div v-if="isMobileOpen" class="lg:hidden bg-primary-900 border-t border-white/10">
        <ul class="px-6 py-5 space-y-4">
          <li v-for="link in navLinks" :key="link.href">
            <NuxtLink
              :to="link.href"
              class="text-white/70 hover:text-white block text-sm font-medium"
              @click="isMobileOpen = false"
            >
              {{ link.label }}
            </NuxtLink>
          </li>
        </ul>
      </div>
    </Transition>
  </nav>
</template>
