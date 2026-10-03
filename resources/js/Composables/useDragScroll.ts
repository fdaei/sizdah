import { useTemplateRef } from 'vue'

/**
 * Mouse drag-to-scroll for a horizontally scrolling row.
 *
 * Touch and trackpads already scroll an `overflow-x-auto` box natively; a
 * mouse cannot drag one, so a narrow desktop window shows a clipped row it
 * cannot move. Only mouse pointers are handled, and only once the pointer has
 * travelled 5px, so a plain click still lands. A drag that did move swallows
 * the click that follows it, so letting go over a link does not also navigate.
 *
 * `refKey` names the scroll container's template `ref`; wire the handlers
 * onto that same element (`onClickCapture` with `@click.capture`). `enabled`
 * lets a component switch the behaviour off when the row is not a scroller.
 */
export function useDragScroll(
  refKey: string,
  enabled: () => boolean = () => true,
): {
  onPointerDown: (event: PointerEvent) => void
  onPointerMove: (event: PointerEvent) => void
  onPointerUp: () => void
  onClickCapture: (event: MouseEvent) => void
  onDragStart: (event: DragEvent) => void
} {
  const scroller = useTemplateRef<HTMLElement>(refKey)
  let drag: { x: number; left: number; moved: boolean } | null = null
  let swallowClick = false

  function onPointerDown(event: PointerEvent): void {
    // A drag released off-target fires no click; never carry that over.
    swallowClick = false
    const el = scroller.value
    if (!enabled() || !el || event.pointerType !== 'mouse' || event.button !== 0) return
    if (el.scrollWidth <= el.clientWidth) return
    drag = { x: event.clientX, left: el.scrollLeft, moved: false }
  }

  function onPointerMove(event: PointerEvent): void {
    const el = scroller.value
    if (!drag || !el) return
    const dx = event.clientX - drag.x
    if (!drag.moved) {
      if (Math.abs(dx) < 5) return
      drag.moved = true
      el.setPointerCapture(event.pointerId)
      // Mandatory snapping would fight every intermediate scrollLeft.
      el.style.scrollSnapType = 'none'
    }
    el.scrollLeft = drag.left - dx
  }

  function onPointerUp(): void {
    if (!drag) return
    swallowClick = drag.moved
    if (drag.moved && scroller.value) scroller.value.style.scrollSnapType = ''
    drag = null
  }

  function onClickCapture(event: MouseEvent): void {
    if (!swallowClick) return
    swallowClick = false
    event.preventDefault()
    event.stopPropagation()
  }

  /** Links and images start a native drag-and-drop that would eat the pointer moves. */
  function onDragStart(event: DragEvent): void {
    if (enabled()) event.preventDefault()
  }

  return { onPointerDown, onPointerMove, onPointerUp, onClickCapture, onDragStart }
}
