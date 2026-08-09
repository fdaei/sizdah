<script setup lang="ts">
/**
 * The file's "small title" component — a label preceded by an 8px marker.
 * Figma stores the marker as an 11.313px rounded rect, which is 8 * sqrt(2):
 * the diagonal of an 8px square.
 *
 * The frame reuses this component at two sizes with two markers:
 *  - `sm` + `square` — the brand-coloured section kicker (268:3034).
 *  - `lg` + `dot`    — the service feature bullets (315:4859), 20px ink-50 with
 *                      a glowing brand dot instead of a square.
 *
 * The marker is drawn by `.eyebrow::before` (app.css) so it inherits inline flow
 * and lands at the inline start in both directions.
 */
withDefaults(
  defineProps<{
    text: string
    /** Renders as a heading kicker rather than standalone text. */
    as?: 'span' | 'p' | 'li'
    size?: 'sm' | 'lg'
    marker?: 'square' | 'dot'
  }>(),
  { as: 'span', size: 'sm', marker: 'square' },
)
</script>

<template>
  <component
    :is="as"
    class="eyebrow"
    :class="[size === 'lg' && 'eyebrow--lg', marker === 'dot' && 'eyebrow--dot']"
  >
    {{ text }}
  </component>
</template>
