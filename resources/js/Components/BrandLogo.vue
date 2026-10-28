<script setup lang="ts">
import { computed } from 'vue'
import logoUrl from '~img/sizdah/shared/logo.svg'
import footerLogoUrl from '~img/sizdah/shared/logo-footer.svg'

/**
 * Sizdah wordmark. Figma I268:3031;91:2581 — 104 x 59.568.
 *
 * The intrinsic ratio is preserved by setting both dimensions from one scale
 * factor, so the mark never causes layout shift while the SVG loads.
 *
 * `variant="footer"` swaps in `logo-footer.svg`: the horizontal
 * "SIZDAH / MARKETING AGENCY" lockup, 80 x 28 (designer request 2026-10-28,
 * replaces the earlier recoloured bulb mark). Yellow wordmark, white tagline —
 * it only works on the dark footer ground.
 */
const props = withDefaults(
  defineProps<{
    /** Rendered width in px. Height follows the variant's own aspect. */
    width?: number
    title: string
    variant?: 'default' | 'footer'
  }>(),
  { width: 104, variant: 'default' },
)

const src = computed(() => (props.variant === 'footer' ? footerLogoUrl : logoUrl))

const height = computed(() =>
  Math.round(props.width * (props.variant === 'footer' ? 28 / 80 : 59.568 / 104)),
)
</script>

<template>
  <img
    :src="src"
    :alt="title"
    :width="width"
    :height="height"
    :style="{ width: `${width}px`, height: `${height}px` }"
    decoding="async"
  />
</template>
