<script setup lang="ts">
import { computed, nextTick, onBeforeUnmount, onMounted, ref } from 'vue'
import TestimonialCard from '@/Components/TestimonialCard.vue'
import { MOTION } from '@/lib/motion'
import type { TestimonialItem } from '@/types'

/**
 * Home testimonials as an endless loop instead of a wrapping grid — a fifth
 * review used to drop onto a second row and run into the next section.
 *
 *   lg+      four across (the 268:3729 track); the row drifts continuously,
 *            pauses while the mouse rests on it, and can be dragged either way
 *   < lg     two (sm) or one card per view; autoplay steps one card at a time,
 *            swipe/drag steps manually, and the dots underneath track the
 *            active card with a fill that runs for the dwell time
 *
 * The list is rendered twice so the loop never shows an empty edge; the
 * position is kept modulo one set's width, exactly like the clients marquee.
 */
const props = defineProps<{ testimonials: TestimonialItem[] }>()

const GAP = 26
const SWIPE_THRESHOLD = 40
const dwell = `${MOTION.testimonials.dwell}s`

const viewport = ref<HTMLElement | null>(null)
const track = ref<HTMLElement | null>(null)
const perView = ref(1)
const cardWidth = ref(0)
const active = ref(0)
const paused = ref(false)
const dragging = ref(false)

const count = computed(() => props.testimonials.length)
const loops = computed(() => count.value > 1)
const continuous = computed(() => perView.value >= 4)

let offset = 0
let frame = 0
let lastTime = 0
let dwellElapsed = 0
let hovered = false
let reducedMotion = false
let tween: { from: number; to: number; start: number; duration: number } | null = null
let drag: { id: number; x: number; offset: number; moved: boolean } | null = null
let resize: ResizeObserver | null = null

function step(): number {
  return cardWidth.value + GAP
}

function loopWidth(): number {
  return count.value * step()
}

/** RTL lays the track out leftwards, so moving to the next card is a move right. */
function direction(): 1 | -1 {
  return document.documentElement.dir === 'rtl' ? 1 : -1
}

function render(): void {
  const width = loopWidth()
  if (!track.value || width <= 0) return
  offset = ((offset % width) + width) % width
  track.value.style.transform = `translate3d(${direction() * offset}px, 0, 0)`
  active.value = Math.round(offset / step()) % count.value
}

function measure(): void {
  const el = viewport.value
  if (!el) return
  perView.value = window.matchMedia('(min-width: 1024px)').matches
    ? 4
    : window.matchMedia('(min-width: 640px)').matches
      ? 2
      : 1
  cardWidth.value = (el.clientWidth - GAP * (perView.value - 1)) / perView.value
  // Re-anchor on the active card so a resize never leaves the row half-way.
  tween = null
  offset = active.value * step()
  render()
}

function goTo(index: number): void {
  if (!loops.value) return
  const width = loopWidth()
  let to = index * step()
  // Travel the short way round the loop.
  if (to - offset > width / 2) to -= width
  if (offset - to > width / 2) to += width
  tween = {
    from: offset,
    to,
    start: performance.now(),
    duration: (reducedMotion ? 0.2 : MOTION.testimonials.slide) * 1000,
  }
  dwellElapsed = 0
}

function next(): void {
  goTo(active.value + 1)
}

function previous(): void {
  goTo(active.value - 1)
}

function syncPaused(): void {
  paused.value = hovered || dragging.value
}

function tick(time: number): void {
  const delta = lastTime ? Math.min(time - lastTime, 100) : 0
  lastTime = time

  if (tween) {
    const t = Math.min((time - tween.start) / tween.duration, 1)
    offset = tween.from + (tween.to - tween.from) * (1 - Math.pow(1 - t, 3))
    if (t === 1) tween = null
    render()
  } else if (!paused.value && !reducedMotion && loops.value) {
    if (continuous.value) {
      offset += (step() / MOTION.testimonials.driftPerCard) * (delta / 1000)
      render()
    } else {
      dwellElapsed += delta
      if (dwellElapsed >= MOTION.testimonials.dwell * 1000) next()
    }
  }

  frame = requestAnimationFrame(tick)
}

function onPointerEnter(event: PointerEvent): void {
  if (event.pointerType !== 'mouse') return
  hovered = true
  syncPaused()
}

function onPointerLeave(event: PointerEvent): void {
  if (event.pointerType !== 'mouse') return
  hovered = false
  syncPaused()
}

function onPointerDown(event: PointerEvent): void {
  if (!loops.value || (event.pointerType === 'mouse' && event.button !== 0)) return
  tween = null
  drag = { id: event.pointerId, x: event.clientX, offset, moved: false }
  dragging.value = true
  syncPaused()
}

function onPointerMove(event: PointerEvent): void {
  if (!drag || event.pointerId !== drag.id) return
  const dx = event.clientX - drag.x
  if (!drag.moved) {
    if (Math.abs(dx) < 5) return
    drag.moved = true
    viewport.value?.setPointerCapture(event.pointerId)
  }
  offset = drag.offset + direction() * dx
  render()
}

function onPointerUp(event: PointerEvent): void {
  if (!drag || event.pointerId !== drag.id) return
  const travelled = offset - drag.offset
  const startIndex = Math.round(drag.offset / step())
  drag = null
  dragging.value = false
  syncPaused()

  if (continuous.value) return
  // A wrap during the drag shows up as a jump of about one loop; undo it.
  const width = loopWidth()
  const moved =
    Math.abs(travelled) > width / 2 ? travelled - Math.sign(travelled) * width : travelled
  if (Math.abs(moved) > SWIPE_THRESHOLD) goTo(startIndex + (moved > 0 ? 1 : -1))
  else goTo(active.value)
}

onMounted(() => {
  void nextTick(() => {
    if (!viewport.value) return
    reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches
    measure()
    resize = new ResizeObserver(measure)
    resize.observe(viewport.value)
    if (loops.value) frame = requestAnimationFrame(tick)
  })
})

onBeforeUnmount(() => {
  cancelAnimationFrame(frame)
  resize?.disconnect()
})
</script>

<template>
  <div>
    <div
      ref="viewport"
      class="testimonial-viewport overflow-hidden"
      :class="{
        'is-looping': loops,
        'is-dragging': dragging,
        'is-continuous': continuous && loops,
      }"
      data-reveal
      @pointerenter="onPointerEnter"
      @pointerleave="onPointerLeave"
      @pointerdown="onPointerDown"
      @pointermove="onPointerMove"
      @pointerup="onPointerUp"
      @pointercancel="onPointerUp"
    >
      <div
        ref="track"
        class="flex w-max select-none items-stretch will-change-transform"
        :style="{ '--testimonial-card': `${cardWidth}px`, gap: `${GAP}px` }"
      >
        <ul class="testimonial-set flex shrink-0 items-stretch" :style="{ gap: `${GAP}px` }">
          <TestimonialCard
            v-for="testimonial in props.testimonials"
            :key="testimonial.name"
            :testimonial="testimonial"
          />
        </ul>

        <!-- The duplicate completes the loop; assistive tech only needs one set. -->
        <ul
          v-if="loops"
          aria-hidden="true"
          class="testimonial-set flex shrink-0 items-stretch"
          :style="{ gap: `${GAP}px` }"
        >
          <TestimonialCard
            v-for="testimonial in props.testimonials"
            :key="`duplicate-${testimonial.name}`"
            :testimonial="testimonial"
          />
        </ul>
      </div>
    </div>

    <div v-if="loops && !continuous" class="mt-6 flex items-center justify-center gap-4">
      <button type="button" class="testimonial-arrow" aria-label="نظر قبلی" @click="previous">
        →
      </button>
      <div class="flex items-center gap-2" role="tablist" aria-label="انتخاب نظر مشتری">
        <button
          v-for="(_, index) in props.testimonials"
          :key="`testimonial-dot-${index}`"
          type="button"
          role="tab"
          :aria-selected="active === index"
          :aria-label="`نمایش نظر ${index + 1}`"
          class="testimonial-dot"
          :class="{ 'is-active': active === index, 'is-paused': paused }"
          @click="goTo(index)"
        >
          <!-- Keyed on the index so the fill restarts with every new slide. -->
          <span v-if="active === index" :key="`fill-${active}`" class="testimonial-dot-fill" />
        </button>
      </div>
      <button type="button" class="testimonial-arrow" aria-label="نظر بعدی" @click="next">←</button>
    </div>
  </div>
</template>

<style scoped>
.testimonial-set > :deep(li) {
  flex: 0 0 var(--testimonial-card);
  width: var(--testimonial-card);
}

.testimonial-viewport.is-looping {
  cursor: grab;
  /* Vertical swipes still scroll the page; horizontal ones drive the row. */
  touch-action: pan-y;
}

.testimonial-viewport.is-dragging {
  cursor: grabbing;
}

/* Soft edges while the row drifts, so cards slide in rather than appear cut. */
.testimonial-viewport.is-continuous {
  -webkit-mask-image: linear-gradient(to right, transparent, #000 4%, #000 96%, transparent);
  mask-image: linear-gradient(to right, transparent, #000 4%, #000 96%, transparent);
}

.testimonial-dot-fill {
  @apply absolute inset-y-0 start-0 rounded-full bg-brand;
  animation: testimonial-dot-fill v-bind(dwell) linear forwards;
}

.testimonial-dot.is-paused .testimonial-dot-fill {
  animation-play-state: paused;
}

@keyframes testimonial-dot-fill {
  from {
    width: 0;
  }
  to {
    width: 100%;
  }
}

@media (prefers-reduced-motion: reduce) {
  .testimonial-dot-fill {
    animation: none;
    width: 100%;
  }
}
</style>
