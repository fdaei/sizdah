import { onBeforeUnmount, onMounted, type Ref } from 'vue'

/**
 * Hydrates server-rendered `.video-facade` placeholders (App\Services\Content\LazyMedia):
 * on click the thumbnail is swapped for the real YouTube iframe, autoplaying.
 */
export function useVideoFacade(container: Ref<HTMLElement | null>): void {
  function onClick(event: MouseEvent): void {
    const button = (event.target as HTMLElement | null)?.closest('.video-facade__play')
    const facade = button?.closest<HTMLElement>('.video-facade')
    const src = facade?.dataset.embed

    if (!facade || !src || !src.startsWith('https://www.youtube-nocookie.com/embed/')) return

    const iframe = document.createElement('iframe')
    iframe.src = src
    iframe.title = facade.dataset.title ?? 'YouTube'
    iframe.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture'
    iframe.allowFullscreen = true
    iframe.className = 'video-facade__frame'
    facade.replaceChildren(iframe)
  }

  onMounted(() => container.value?.addEventListener('click', onClick))
  onBeforeUnmount(() => container.value?.removeEventListener('click', onClick))
}
