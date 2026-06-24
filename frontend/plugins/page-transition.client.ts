const ANIM_MS = 1800

const TRANSITION_PAGES = new Set([
  '/theme',
  '/news',
  '/professor',
  '/blog',
  '/members',
  '/case-studies',
  '/faq',
  '/contact',
])

export default defineNuxtPlugin((nuxtApp) => {
  const { show, hide } = usePageTransition()
  const router = useRouter()
  let startTime = 0

  const shouldAnimate = (path: string): boolean => TRANSITION_PAGES.has(path)

  // 初回ロード
  if (shouldAnimate(router.currentRoute.value.path)) {
    show()
    startTime = Date.now()
  }

  nuxtApp.hook('app:mounted', () => {
    const elapsed = Date.now() - startTime
    setTimeout(hide, Math.max(0, ANIM_MS - elapsed))
  })

  // 対象ページへの遷移のみアニメーション
  router.beforeEach((to) => {
    if (!shouldAnimate(to.path)) {
      hide()
      return
    }
    show()
    startTime = Date.now()
  })

  nuxtApp.hook('page:finish', () => {
    const elapsed = Date.now() - startTime
    setTimeout(hide, Math.max(0, ANIM_MS - elapsed))
  })

  nuxtApp.hook('app:error', () => hide())
})
