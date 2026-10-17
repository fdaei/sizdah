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
 * `variant="footer"` swaps in `logo-footer.svg`: identical geometry, but the
 * white letterforms are #1B1B1B (designer request 2026-10-17). The yellow
 * bulb stays brand yellow.
 */
const props = withDefaults(
  defineProps<{
    /** Rendered width in px. Height follows the 104:59.568 aspect. */
    width?: number
    title: string
    variant?: 'default' | 'footer'
  }>(),
  { width: 104, variant: 'default' },
)

const src = computed(() => (props.variant === 'footer' ? footerLogoUrl : logoUrl))

const ASPECT = 59.568 / 104
</script>

<template>
  <img
    :src="src"
    :alt="title"
    :width="width"
    :height="Math.round(width * ASPECT)"
    :style="{ width: `${width}px`, height: `${Math.round(width * ASPECT)}px` }"
    decoding="async"
  />
</template>
