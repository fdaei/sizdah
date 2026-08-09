<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import PostMeta from '@/Components/PostMeta.vue'
import type { PostSummary } from '@/types'

/**
 * Blog card — Figma "blog card" (277:5849).
 *
 * The file ships two widths, 400 (3-up) and 612 (2-up), both 540 tall — which
 * means the image is 400px tall in each and only its aspect changes. That is
 * expressed as an aspect ratio so the card scales instead of pinning heights.
 */
const props = withDefaults(
  defineProps<{
    post: PostSummary
    /** `wide` is the 612px 2-up variant. */
    variant?: 'default' | 'wide'
    /** Cards under an h2 section title need h3 to keep the outline legal. */
    headingLevel?: 'h2' | 'h3'
  }>(),
  { variant: 'default', headingLevel: 'h3' },
)
</script>

<template>
  <article class="flex flex-col gap-4">
    <Link :href="props.post.url" class="group flex flex-col gap-4">
      <div
        class="overflow-hidden rounded-lg border border-ink-100 shadow-card"
        :class="props.variant === 'wide' ? 'aspect-[612/400]' : 'aspect-square'"
      >
        <img
          v-if="props.post.image"
          :src="props.post.image.src"
          :srcset="props.post.image.srcset"
          :alt="props.post.image.alt"
          :width="props.post.image.width"
          :height="props.post.image.height"
          loading="lazy"
          class="size-full object-cover transition-transform duration-500 ease-brand group-hover:scale-105"
        />
        <div v-else class="size-full bg-ink-900" aria-hidden="true" />
      </div>

      <div class="flex flex-col gap-4">
        <PostMeta :post="props.post" />

        <component
          :is="props.headingLevel"
          class="text-heading-sm text-ink-50 transition-colors duration-200 ease-brand group-hover:text-brand"
        >
          {{ props.post.title }}
        </component>
      </div>
    </Link>
  </article>
</template>
