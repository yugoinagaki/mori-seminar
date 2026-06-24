<script setup lang="ts">
const quickLinks = [
  { label: 'Annual Theme', href: '/theme' },
  { label: 'News', href: '/news' },
  { label: 'Professor', href: '/professor' },
  { label: 'Blog', href: '/blog' },
  { label: 'Members', href: '/members' },
  { label: 'Case Studies', href: '/case-studies' },
  { label: 'FAQ', href: '/faq' },
  { label: 'Contact', href: '/contact' },
]
</script>

<template>
  <footer class="bg-primary-950 text-white">
    <div class="max-w-7xl mx-auto px-6 py-20">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-14">
        <!-- Brand -->
        <div>
          <p class="text-[9px] tracking-[0.3em] uppercase text-white/35 mb-2">Keio University</p>
          <h3 class="text-2xl font-bold mb-5 tracking-wide">森聡研究会</h3>
          <p class="text-white/40 text-sm leading-relaxed mb-8">
            国際政治学・現代アメリカ外交・<br>先端技術を研究するゼミナール
          </p>
          <!-- SNS -->
          <div class="flex gap-5">
            <a
              href="https://twitter.com/morisemi_keio"
              target="_blank" rel="noopener"
              class="text-white/35 hover:text-white transition-colors"
              aria-label="X (Twitter)"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
              </svg>
            </a>
            <a
              href="https://instagram.com/keio.mori"
              target="_blank" rel="noopener"
              class="text-white/35 hover:text-white transition-colors"
              aria-label="Instagram"
            >
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
              </svg>
            </a>
          </div>
        </div>

        <!-- Links -->
        <div>
          <h4 class="text-[10px] tracking-[0.25em] uppercase text-white/35 font-semibold mb-6">Menu</h4>
          <ul class="grid grid-cols-2 gap-x-6 gap-y-3">
            <li v-for="link in quickLinks" :key="link.href">
              <NuxtLink :to="link.href" class="text-white/55 hover:text-white text-sm transition-colors">
                {{ link.label }}
              </NuxtLink>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <h4 class="text-[10px] tracking-[0.25em] uppercase text-white/35 font-semibold mb-6">Contact</h4>
          <a
            href="mailto:morisemi2020@gmail.com"
            class="text-white/55 hover:text-white text-sm transition-colors block mb-2"
          >
            morisemi2020@gmail.com
          </a>
          <p class="text-white/25 text-xs leading-relaxed">
            ※ 森先生個人への連絡先ではありません
          </p>
        </div>
      </div>

      <div class="border-t border-white/10 mt-14 pt-8 flex flex-col md:flex-row justify-between items-center gap-3">
        <p class="text-white/25 text-xs">
          © 2026 Mori Seminar, Keio University. All rights reserved.
        </p>
        <p class="text-white/20 text-xs">慶應義塾大学 法学部</p>
      </div>
    </div>
  </footer>
</template>
