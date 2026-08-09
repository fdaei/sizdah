<script setup lang="ts">
import CtaButton from '@/Components/CtaButton.vue'
import type { PageSectionData } from '@/types'
import doodleUrl from '~img/sizdah/shared/checklist-doodle.svg'

/**
 * Lead magnet strip — Figma "lead magnet" (303:4455).
 *
 * The one cream surface in an otherwise dark design: Yellow/200 fill under a
 * raking brand glow, with ink copy. Runs mid-article and again on Home.
 *
 * The frame draws a button but no email field, so this links to the section's
 * own CTA rather than posting inline; the newsletter route stays where the
 * form that collects an address lives.
 */
const props = defineProps<{ section: PageSectionData }>()
</script>

<template>
  <aside
    class="flex flex-col items-center gap-6 overflow-hidden rounded-sm p-8 md:flex-row md:justify-between"
    style="
      background-image:
        linear-gradient(-8.86deg, rgb(248 185 55 / 0) 2.32%, rgb(248 185 55 / 10%) 100%),
        linear-gradient(0deg, #fef1d7, #fef1d7);
    "
  >
    <div class="flex max-w-[458px] flex-col gap-2 text-center md:text-start">
      <p class="text-title-md text-ink-900">{{ props.section.title }}</p>
      <p v-if="props.section.description" class="text-body-md text-ink-600">
        {{ props.section.description }}
      </p>
    </div>

    <CtaButton
      v-if="props.section.primaryCta"
      :label="props.section.primaryCta.label"
      :href="props.section.primaryCta.url"
      class="shrink-0"
    />

    <img
      :src="doodleUrl"
      alt=""
      aria-hidden="true"
      width="64"
      height="49"
      class="hidden h-[49px] w-16 shrink-0 flip-rtl md:block"
    />
  </aside>
</template>
