<script setup lang="ts">
/**
 * The file's "small title" component — a label preceded by an 8px marker.
 *
 * The marker is always the glowing round disc. Figma sizes its wrapper as an
 * 11.313px box (8 * sqrt(2), the diagonal of the 8px square the disc is
 * rotated inside), which is why the node metadata reports that width; the
 * drawn element is 8px. There is no square/diamond variant anywhere in the
 * live file — the one this component used to offer was removed once all seven
 * instances had been read (GAPS G45).
 *
 * `markerRing` picks the rim, which is the only thing that varies between
 * instances: Yellow/50 on every dark ground (the default), Black/800 on the
 * one cream band. The `lg` size carries its own heavier White rim.
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
    /** Rim around the glowing dot: `ink` is the cream-band variant. */
    markerRing?: 'paper' | 'ink'
  }>(),
  { as: 'span', size: 'sm', markerRing: 'paper' },
)
</script>

<template>
  <component
    :is="as"
    class="eyebrow eyebrow--dot"
    :class="[size === 'lg' && 'eyebrow--lg', markerRing === 'ink' && 'eyebrow--dot-ink']"
  >
    {{ text }}
  </component>
</template>
