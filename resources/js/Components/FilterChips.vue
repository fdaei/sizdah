<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import type { FilterOption } from '@/types'

/**
 * Filter chip row — Figma 268:5241 (insights) and 222:2475 (projects).
 *
 * Inactive: 2px ink-300 outline, 16px paper label. Active: ink-900 fill, 3px
 * brand outline, brand label. (The frame's active pill is also 1px larger at
 * 17px; that is treated as a slip and both states use the 16px body token.)
 *
 * Radius is **16px, not a full round**. Both frames agree — insights
 * 268:5242-268:5250 and projects 222:2472-222:2480 are all `cornerRadius: 16`
 * on a 44px chip. This rendered as `rounded-round` until 2026-08-21, which made
 * every chip a stadium pill on both pages; the name "pill" in the old docblock
 * is where that came from.
 *
 * Navigation is real links, not buttons, so a filtered listing is shareable and
 * works without JavaScript.
 */
const props = defineProps<{
  options: FilterOption[]
  active: string | null
  /** Accessible name for the group, e.g. "categories". */
  label: string
}>()
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
          :class="
            option.value === props.active
              ? 'border-3 border-brand bg-ink-900 text-brand'
              : 'border-2 border-ink-300 text-paper hover:border-paper'
          "
        >
          {{ option.label }}
        </Link>
      </li>
    </ul>
  </nav>
</template>
