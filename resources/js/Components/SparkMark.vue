<script setup lang="ts">
import sparkUrl from '~img/sizdah/shared/spark.svg'

/**
 * The brand-yellow three-stroke spark flourish.
 *
 * One master vector, instanced at several sizes across the file. The Services
 * frame draws it at the master's own 20x25 (511:9195, 511:9200, 322:5242 —
 * byte-identical exports, see GAPS G29); 322:5230 is the same artwork scaled
 * on y alone to 20x29.043 (every x coordinate matches to 4dp, every y is the
 * master's times 29.0429/25 = 1.1617). That is why this is one asset with a
 * `viewBox` and `preserveAspectRatio="none"` rather than two exports: a
 * non-uniform box is the design's own intent here, not a distortion.
 *
 * Decorative by default (`alt=""` + `aria-hidden`). Pass `title` only where
 * the mark actually carries meaning a screen reader would otherwise miss.
 *
 * Positioning is the caller's job — class attributes fall through to the
 * `<img>`, so absolute placement stays at the call site where the frame
 * coordinates that justify it can be commented.
 */
withDefaults(
  defineProps<{
    /** Rendered width in px. Master is 20. */
    width?: number
    /** Rendered height in px. Master is 25; 322:5230 stretches it to 29.043. */
    height?: number
    /** Accessible name. Empty (the default) renders the mark as decorative. */
    title?: string
  }>(),
  { width: 20, height: 25, title: '' },
)
</script>

<template>
  <img
    :src="sparkUrl"
    :alt="title"
    :aria-hidden="title ? undefined : 'true'"
    :width="width"
    :height="height"
    :style="{ width: `${width}px`, height: `${height}px` }"
    class="max-w-none"
    decoding="async"
  />
</template>
