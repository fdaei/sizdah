<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import type { FilterOption } from '@/types'

/**
 * Filter chip row — Figma 268:5241 (insights) and 222:2475 (projects).
 *
 * Inactive: 2px ink-300 outline, 16px paper label — identical on both frames.
 * Radius is **16px, not a full round**: both frames agree — insights
 * 268:5242-268:5250 and projects 222:2472-222:2480 are all `cornerRadius: 16`
 * on a 44px chip. This rendered as `rounded-round` until 2026-08-21, which made
 * every chip a stadium pill on both pages; the name "pill" in the old docblock
 * is where that came from. (The frame's active label is also 1px larger at
 * 17px on both frames; that is treated as a slip and both states use the 16px
 * body token.)
 *
 * The ACTIVE state genuinely differs between the two frames (re-confirmed
 * 2026-09-01, GAPS G50 — an earlier note claiming "both frames agree" was
 * wrong for this one property): insights (268:5250) is ink-900 fill with a
 * brand outline and brand label; projects (222:2472) is a solid brand fill
 * with a brand outline and *dark* (ink-1000) label. `variant` selects which;
 * default is `outline` (insights) since that was the original, more common
 * call site.
 *
 * The chip outline is the hand-drawn sketch frame, not a CSS border:
 * `.sketch-frame-chip` (app.css) masks the chip-sized drawing onto an overlay
 * span, so the one asset stretches to any chip width and takes its colour from
 * this component — ink-300, paper on hover, brand when active, mirroring the
 * borders it replaced. Padding is 26/14px rather than the token 24/12 so the
 * chip keeps the exact box size it had while that outline was a 2px border.
 *
 * Navigation is real links, not buttons, so a filtered listing is shareable and
 * works without JavaScript. An option that carries no `href` renders a
 * `<button>` and emits `select` instead — the case-study content showcase
 * (430:5201) filters a gallery that is already on the page, so there is no
 * server round-trip to link to.
 */
const props = withDefaults(
  defineProps<{
    options: FilterOption[]
    active: string | null
    /** Accessible name for the group, e.g. "categories". */
    label: string
    /** `outline` — insights (268:5250): dark fill, brand label. `solid` — projects (222:2472): brand fill, dark label. */
    variant?: 'outline' | 'solid'
    /** Keep every chip on one row and allow horizontal scrolling when needed. */
    singleLine?: boolean
  }>(),
  { variant: 'outline', singleLine: false },
)

const emit = defineEmits<{ select: [value: string | null] }>()

/** Tint of the sketch outline, mirroring the old border colours. */
function frameClass(value: string | null): (string | false)[] {
  const active = value === props.active

  return [
    'sketch-frame-chip pointer-events-none absolute inset-0 transition-colors duration-200 ease-brand',
    !active && 'bg-ink-300 group-hover:bg-paper',
    active && 'bg-brand',
  ]
}

/** Shared between the Link and button branches so the two cannot drift. */
function chipClass(value: string | null): (string | false)[] {
  const active = value === props.active

  return [
    'group relative inline-flex items-center justify-center rounded-lg px-[26px] py-[14px] text-body-lg transition-colors duration-200 ease-brand',
    !active && 'text-paper',
    active && props.variant === 'solid' && 'bg-brand text-ink-1000',
    active && props.variant === 'outline' && 'bg-ink-900 text-brand',
  ]
}
</script>

<template>
  <nav
    :aria-label="props.label"
    :class="props.singleLine && 'scrollbar-hidden max-w-full overflow-x-auto'"
  >
    <ul
      class="flex items-start gap-3"
      :class="props.singleLine ? 'w-max flex-nowrap' : 'flex-wrap'"
    >
      <li
        v-for="option in props.options"
        :key="option.value ?? 'all'"
        :class="props.singleLine && 'shrink-0'"
      >
        <Link
          v-if="option.href"
          :href="option.href"
          preserve-scroll
          :aria-current="option.value === props.active ? 'page' : undefined"
          :class="chipClass(option.value)"
        >
          <span aria-hidden="true" :class="frameClass(option.value)" />
          <span class="relative">{{ option.label }}</span>
        </Link>

        <button
          v-else
          type="button"
          :aria-pressed="option.value === props.active"
          :class="chipClass(option.value)"
          @click="emit('select', option.value)"
        >
          <span aria-hidden="true" :class="frameClass(option.value)" />
          <span class="relative">{{ option.label }}</span>
        </button>
      </li>
    </ul>
  </nav>
</template>
