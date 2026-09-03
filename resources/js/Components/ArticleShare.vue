<script setup lang="ts">
import { ref } from 'vue'
import { useTranslations } from '@/Composables/useTranslations'
import copyLinkUrl from '~img/sizdah/blog/copy-link.svg'
import xUrl from '~img/sizdah/social/x.svg'
import linkedinUrl from '~img/sizdah/social/linkedin.svg'

/**
 * Article share rail — Figma "Frame 95907" (690:7022), floating beside the
 * body copy column. 68px Yellow/100 (#fff8eb) circles on a 16px track; not
 * in the `brand` scale (no /100 step) so it's an arbitrary value, same
 * reasoning as the border colour in `ArticleMeta.vue`.
 *
 * The file's "Share icons" instances resolve to the same placeholder glyph
 * for the X/LinkedIn buttons (an unresolved instance-swap on Figma's end) —
 * confirmed against the screenshot and reused this project's existing
 * `sizdah/social/{x,linkedin}.svg` (already Yellow/1000 #f8b937, byte-for-
 * byte the colour the frame renders). Only the copy-link glyph exports
 * correctly on its own node, so it's the one asset pulled fresh from Figma.
 */
const props = defineProps<{ url: string; title: string }>()

const { t } = useTranslations()

const copied = ref(false)
let resetTimer: ReturnType<typeof setTimeout> | undefined

async function copyLink() {
  await navigator.clipboard.writeText(props.url)
  copied.value = true
  clearTimeout(resetTimer)
  resetTimer = setTimeout(() => (copied.value = false), 2000)
}

const xShareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(props.url)}&text=${encodeURIComponent(props.title)}`
const linkedinShareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(props.url)}`
</script>

<template>
  <div class="flex flex-col items-center gap-6">
    <p class="text-label-lg text-paper">{{ t('blog.share') }}</p>

    <ul class="flex flex-col items-center gap-4">
      <li class="relative">
        <button
          type="button"
          class="flex size-[68px] items-center justify-center rounded-full bg-[#fff8eb] transition-opacity duration-200 ease-brand hover:opacity-80"
          @click="copyLink"
        >
          <span class="sr-only">{{ t('blog.copy_link') }}</span>
          <img
            :src="copyLinkUrl"
            alt=""
            aria-hidden="true"
            width="24"
            height="9.93"
            class="h-[9.93px] w-6"
          />
        </button>

        <Transition
          enter-active-class="transition duration-150 ease-brand"
          enter-from-class="opacity-0"
          leave-active-class="transition duration-150 ease-brand"
          leave-to-class="opacity-0"
        >
          <span
            v-if="copied"
            role="status"
            class="absolute inline-end-full top-1/2 me-2 -translate-y-1/2 whitespace-nowrap rounded-sm bg-ink-1000 px-2 py-1 text-label-md text-paper"
          >
            {{ t('blog.link_copied') }}
          </span>
        </Transition>
      </li>

      <li>
        <a
          :href="xShareUrl"
          target="_blank"
          rel="noopener noreferrer"
          class="flex size-[68px] items-center justify-center rounded-full bg-[#fff8eb] transition-opacity duration-200 ease-brand hover:opacity-80"
        >
          <span class="sr-only">X</span>
          <img :src="xUrl" alt="" aria-hidden="true" width="24" height="24" class="size-6" />
        </a>
      </li>

      <li>
        <a
          :href="linkedinShareUrl"
          target="_blank"
          rel="noopener noreferrer"
          class="flex size-[68px] items-center justify-center rounded-full bg-[#fff8eb] transition-opacity duration-200 ease-brand hover:opacity-80"
        >
          <span class="sr-only">LinkedIn</span>
          <img :src="linkedinUrl" alt="" aria-hidden="true" width="24" height="24" class="size-6" />
        </a>
      </li>
    </ul>
  </div>
</template>
