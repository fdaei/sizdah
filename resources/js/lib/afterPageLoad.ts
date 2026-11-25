/** Run optional work after load and an idle slot; callers can cancel on navigation. */
export function afterPageLoad(callback: () => void): () => void {
  let cancelled = false
  let timer: ReturnType<typeof setTimeout> | undefined
  let idle: number | undefined
  const run = () => {
    if (!cancelled) callback()
  }
  const schedule = () => {
    timer = setTimeout(() => {
      if ('requestIdleCallback' in window) idle = window.requestIdleCallback(run, { timeout: 2000 })
      else run()
    }, 1000)
  }
  if (document.readyState === 'complete') schedule()
  else window.addEventListener('load', schedule, { once: true })
  return () => {
    cancelled = true
    clearTimeout(timer)
    window.removeEventListener('load', schedule)
    if (idle !== undefined) window.cancelIdleCallback(idle)
  }
}
