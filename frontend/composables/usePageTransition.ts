export const usePageTransition = () => {
  const visible = useState('page-transition-visible', () => false)
  const animKey = useState('page-transition-key', () => 0)

  const show = () => {
    animKey.value++
    visible.value = true
  }
  const hide = () => { visible.value = false }

  return { visible, animKey, show, hide }
}
