export default defineNuxtConfig({
  compatibilityDate: '2024-04-03',
  devtools: { enabled: true },
  devServer: { port: 3001 },
  modules: ['@nuxtjs/tailwindcss'],
  runtimeConfig: {
    guardianApiKey: process.env.GUARDIAN_API_KEY || 'YOUR_KEY_HERE',
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000/api',
      storageBase: process.env.NUXT_PUBLIC_STORAGE_BASE || 'http://localhost:8000/storage',
    }
  },
  css: ['~/assets/css/main.css'],
  app: {
    head: {
      title: '森聡研究会 | 慶應義塾大学',
      htmlAttrs: { lang: 'ja' },
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        {
          name: 'description',
          content: '慶應義塾大学 森聡研究会の公式サイト。国際政治学・現代アメリカ外交・先端技術を研究しています。'
        }
      ],
      link: [
        { rel: 'preconnect', href: 'https://fonts.googleapis.com' },
        { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: '' },
        {
          rel: 'stylesheet',
          href: 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+JP:wght@300;400;500;700&family=Shippori+Mincho:wght@400;500;600;700;800&display=swap'
        }
      ]
    }
  }
})
