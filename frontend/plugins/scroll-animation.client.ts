export default defineNuxtPlugin((nuxtApp) => {
  const { observe } = useScrollAnimation()

  nuxtApp.hook('app:mounted', () => nextTick(observe))
  nuxtApp.hook('page:finish', () => nextTick(observe))
})
