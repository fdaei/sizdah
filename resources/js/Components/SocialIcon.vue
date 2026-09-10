<script setup lang="ts">
import { computed } from 'vue'
import type { SocialLink } from '@/types'
import instagramUrl from '~img/sizdah/social/instagram.svg'
import linkedinUrl from '~img/sizdah/social/linkedin.svg'
import whatsappUrl from '~img/sizdah/social/whatsapp.svg'
import telegramUrl from '~img/sizdah/social/telegram.svg'
import xUrl from '~img/sizdah/social/x.svg'
import youtubeUrl from '~img/sizdah/social/youtube.svg'

/**
 * Social link chip — Figma "social" (279:6487).
 *
 * 56px square, radiusLG, 24px glyph. The file draws five; the set actually
 * rendered comes from settings.socialLinks, so adding a platform in Filament
 * is enough as long as its slug is mapped below.
 *
 * The outline is the hand-drawn sketch frame, not a CSS border: 279:6487 reads
 * as `cornerRadius: 16` with a 1px #A1A1A1 stroke in the node data, but the
 * frame actually draws it as a wobbly traced path — the API's rounded-rect
 * numbers describe the container, not what renders. `.sketch-frame-social`
 * (app.css) masks that 57px drawing onto an overlay span so it takes its
 * colour from here: ink-400, brand on hover, mirroring the border it replaced.
 * It is the social chip's own drawing, NOT the filter chip's — that one is
 * 105 x 46 and squeezed its corners to a visibly tighter radius here.
 *
 * Glyphs are 24px WIDE but not all 24 tall (youtube is 24 x 18), so the img is
 * sized on width with `h-auto`. It rendered as `size-6` until 2026-09-11,
 * which stretched youtube 1.33x vertically — the reported "youtube doesn't
 * match Figma". The glyph files themselves are byte-identical to the frame's.
 */
const props = defineProps<{ link: SocialLink }>()

const glyphs: Record<string, string> = {
  instagram: instagramUrl,
  linkedin: linkedinUrl,
  whatsapp: whatsappUrl,
  telegram: telegramUrl,
  x: xUrl,
  twitter: xUrl,
  youtube: youtubeUrl,
}

const glyph = computed<string | undefined>(() => glyphs[props.link.platform.toLowerCase()])
</script>

<template>
  <a
    :href="props.link.url"
    target="_blank"
    rel="noopener noreferrer"
    class="group relative inline-flex size-14 shrink-0 items-center justify-center rounded-lg"
  >
    <span
      aria-hidden="true"
      class="sketch-frame-social pointer-events-none absolute inset-0 bg-ink-400 transition-colors duration-200 ease-brand group-hover:bg-brand"
    />
    <span class="sr-only">{{ props.link.label }}</span>
    <img
      v-if="glyph"
      :src="glyph"
      alt=""
      aria-hidden="true"
      width="24"
      class="relative block h-auto w-6"
    />
    <!-- Unmapped platform: the label's first letter keeps the row even. -->
    <span v-else aria-hidden="true" class="relative text-title-md text-paper">
      {{ props.link.label.charAt(0) }}
    </span>
  </a>
</template>
