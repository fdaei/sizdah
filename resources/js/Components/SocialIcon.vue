<script setup lang="ts">
import { computed } from 'vue'
import type { SocialLink } from '@/types'
import instagramUrl from '~img/sizdah/social/instagram.svg'
import linkedinUrl from '~img/sizdah/social/linkedin.svg'
import whatsappUrl from '~img/sizdah/social/whatsapp.svg'
import telegramUrl from '~img/sizdah/social/telegram.svg'
import xUrl from '~img/sizdah/social/x.svg'

/**
 * Social link chip — Figma "social" (279:6487).
 *
 * 56px square, 1px ink-400 border, radiusLG, 24px glyph. The file draws five;
 * the set actually rendered comes from settings.socialLinks, so adding a
 * platform in Filament is enough as long as its slug is mapped below.
 */
const props = defineProps<{ link: SocialLink }>()

const glyphs: Record<string, string> = {
  instagram: instagramUrl,
  linkedin: linkedinUrl,
  whatsapp: whatsappUrl,
  telegram: telegramUrl,
  x: xUrl,
  twitter: xUrl,
}

const glyph = computed<string | undefined>(
  () => glyphs[props.link.platform.toLowerCase()],
)
</script>

<template>
  <a
    :href="props.link.url"
    target="_blank"
    rel="noopener noreferrer"
    class="inline-flex size-14 shrink-0 items-center justify-center rounded-lg border border-ink-400 transition-colors duration-200 ease-brand hover:border-brand"
  >
    <span class="sr-only">{{ props.link.label }}</span>
    <img
      v-if="glyph"
      :src="glyph"
      alt=""
      aria-hidden="true"
      width="24"
      height="24"
      class="size-6"
    />
    <!-- Unmapped platform: the label's first letter keeps the row even. -->
    <span v-else aria-hidden="true" class="text-title-md text-paper">
      {{ props.link.label.charAt(0) }}
    </span>
  </a>
</template>
