export const useScrollAnimation = () => {
  const observe = () => {
    if (import.meta.server) return
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible')
            observer.unobserve(entry.target)
          }
        })
      },
      { threshold: 0.07, rootMargin: '0px 0px -32px 0px' }
    )
    document.querySelectorAll('.fade-in:not(.is-visible)').forEach((el) => observer.observe(el))
  }

  return { observe }
}
