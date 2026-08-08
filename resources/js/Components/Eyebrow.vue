<script setup lang="ts">
/**
 * The file's "small title" component — a brand-coloured label preceded by an
 * 8px square rotated 45°. Figma stores the square as an 11.313px rounded rect,
 * which is 8 * sqrt(2): the diagonal of an 8px square.
 *
 * The marker is drawn by `.eyebrow::before` (app.css) so it inherits inline
 * flow and lands at the inline start in both directions.
 */
defineProps<{
  text: string
  /** Renders as a heading kicker rather than standalone text. */
  as?: 'span' | 'p'
}>()
</script>

<template>
  <component :is="as ?? 'span'" class="eyebrow">{{ text }}</component>
</template>
