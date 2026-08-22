<script setup lang="ts">
import type { TestimonialItem } from '@/types'
import quoteUrl from '~img/sizdah/home/quote-mark.svg'
import scribbleUrl from '~img/sizdah/home/quote-scribble.svg'

/**
 * Testimonial card — Figma component set "testimonial" 546:7528, four across the
 * 1248 track at 268:3729.
 *
 * Re-measured against the master 2026-08-21. The previous version carried a note
 * saying it had been built from the established system rather than the frame,
 * and every one of its guesses was off:
 *
 *   root      1px Black/300 #B9B9B9 hairline (was ink-800), radius 24, p space24
 *   ground    a single raking Yellow/1000 wash over a hairline texture — NOT
 *             `.surface-glow`, which sinks the card below the page ground when
 *             the frame lifts it above (see `.testimonial-wash`, app.css)
 *   rhythm    space40 glyph -> quote, space48 quote -> attribution (was 24/40)
 *   quote     16 Regular on Black/50   (was Black/200)
 *   name      14 Medium  on Black/100  (was 16 Medium on White)
 *   role      12 Medium  on Black/200  (was 14 Regular on Black/300)
 *   attrib    space8 to the avatar, space4 between name and role (was 12 / 0)
 *
 * The set also ships a `Property 1=hover` variant (546:7529) that nothing
 * implemented: the card flips to a solid Yellow/1000 fill and the copy inverts
 * to the dark ramp. The frame swaps in separately-inked dark glyphs for it; both
 * marks are single-colour, so they are driven to black with a filter here rather
 * than shipping a second pair of near-identical SVGs.
 */
const props = defineProps<{ testimonial: TestimonialItem }>()
</script>

<template>
  <li
    class="testimonial-wash group flex h-full flex-col gap-10 rounded-xl border border-ink-300 p-6 transition-colors duration-200 ease-brand hover:bg-brand hover:bg-none"
  >
    <img
      :src="quoteUrl"
      alt=""
      aria-hidden="true"
      width="32"
      height="30"
      class="h-[30px] w-8 transition duration-200 ease-brand group-hover:brightness-0"
    />

    <div class="flex flex-1 flex-col justify-between gap-12">
      <blockquote
        class="text-body-lg text-ink-50 transition-colors duration-200 ease-brand group-hover:text-ink-1000"
      >
        {{ props.testimonial.quote }}
      </blockquote>

      <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-2">
          <img
            v-if="props.testimonial.avatar"
            :src="props.testimonial.avatar.src"
            :srcset="props.testimonial.avatar.srcset"
            :alt="props.testimonial.avatar.alt || props.testimonial.name"
            width="48"
            height="48"
            loading="lazy"
            decoding="async"
            class="size-12 shrink-0 rounded-round object-cover"
          />

          <div class="flex flex-col gap-1">
            <p
              class="text-label-lg text-ink-100 transition-colors duration-200 ease-brand group-hover:text-ink-900"
            >
              {{ props.testimonial.name }}
            </p>
            <p
              v-if="props.testimonial.role"
              class="text-label-md text-ink-200 transition-colors duration-200 ease-brand group-hover:text-ink-800"
            >
              {{ props.testimonial.role }}
            </p>
          </div>
        </div>

        <img
          :src="scribbleUrl"
          alt=""
          aria-hidden="true"
          width="48"
          height="48"
          class="size-12 shrink-0 transition duration-200 ease-brand group-hover:brightness-0"
        />
      </div>
    </div>
  </li>
</template>
