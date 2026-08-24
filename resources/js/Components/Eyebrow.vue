<script setup lang="ts">
/**
 * The file's "small title" component — a label preceded by an 8px marker.
 * Figma stores the marker's wrapper as an 11.313px box (8 * sqrt(2), the
 * diagonal of a rotated 8px square) — but the marker itself, in every
 * instance actually checked (309:4757 Services header, 268:3036 Home
 * services-band, both re-verified 2026-08-24), is the glowing round dot, not
 * a plain square. There is no live "square" variant in the file; the `square`
 * marker this component still offers is a plain diamond nobody's frame draws
 * — kept only because some call sites (Services/Insights-index/Contact/
 * Work-index page headers) were built against it before this was caught, not
 * because it is correct. Pass `marker="dot"` everywhere; `SectionHeading.vue`
 * already does. See .figma-sync/GAPS.md.
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
