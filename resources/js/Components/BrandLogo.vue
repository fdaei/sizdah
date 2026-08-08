<script setup lang="ts">
import logoUrl from '~img/sizdah/shared/logo.svg'

/**
 * Sizdah wordmark. Figma I268:3031;91:2581 — 104 x 59.568.
 *
 * The intrinsic ratio is preserved by setting both dimensions from one scale
 * factor, so the mark never causes layout shift while the SVG loads.
 */
withDefaults(
  defineProps<{
    /** Rendered width in px. Height follows the 104:59.568 aspect. */
    width?: number
    title: string
  }>(),
  { width: 104 },
)

const ASPECT = 59.568 / 104
</script>

<template>
  <img
    :src="logoUrl"
    :alt="title"
    :width="width"
    :height="Math.round(width * ASPECT)"
    :style="{ width: `${width}px`, height: `${Math.round(width * ASPECT)}px` }"
    decoding="async"
  />
</template>
