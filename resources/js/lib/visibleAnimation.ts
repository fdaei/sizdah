/** Stop animation frames while their content is offscreen or the tab is hidden. */
export function visibleAnimation(element: HTMLElement, tick: (time: number) => void): () => void {
  let visible = false
  let frame = 0
  const animate = (time: number) => {
    tick(time)
    frame = requestAnimationFrame(animate)
  }
  const sync = () => {
    cancelAnimationFrame(frame)
    if (visible && !document.hidden) frame = requestAnimationFrame(animate)
  }
  const observer = new IntersectionObserver(([entry]) => {
    visible = entry.isIntersecting
    sync()
  })
  observer.observe(element)
  document.addEventListener('visibilitychange', sync)
  return () => {
    observer.disconnect()
    cancelAnimationFrame(frame)
    document.removeEventListener('visibilitychange', sync)
  }
}
