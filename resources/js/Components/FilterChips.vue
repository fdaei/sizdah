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
 * Navigation is real links, not buttons, so a filtered listing is shareable and
 * works without JavaScript.
 */
const props = withDefaults(
  defineProps<{
    options: FilterOption[]
    active: string | null
    /** Accessible name for the group, e.g. "categories". */
    label: string
    /** `outline` — insights (268:5250): dark fill, brand label. `solid` — projects (222:2472): brand fill, dark label. */
    variant?: 'outline' | 'solid'
  }>(),
  { variant: 'outline' },
)
</script>

<template>
  <nav :aria-label="props.label">
    <ul class="flex flex-wrap items-start gap-3">
      <li v-for="option in props.options" :key="option.value ?? 'all'">
        <Link
          :href="option.href"
          preserve-scroll
          :aria-current="option.value === props.active ? 'page' : undefined"
          class="inline-flex items-center justify-center rounded-lg px-6 py-3 text-body-lg transition-colors duration-200 ease-brand"
          :class="[
            option.value !== props.active && 'border-2 border-ink-300 text-paper hover:border-paper',
            option.value === props.active &&
              props.variant === 'solid' &&
              'border-3 border-brand bg-brand text-ink-1000',
            option.value === props.active &&
              props.variant === 'outline' &&
              'border-3 border-brand bg-ink-900 text-brand',
          ]"
        >
          {{ option.label }}
        </Link>
      </li>
    </ul>
  </nav>
</template>
