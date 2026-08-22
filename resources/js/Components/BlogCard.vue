<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import PostMeta from '@/Components/PostMeta.vue'
import type { PostSummary } from '@/types'

/**
 * Blog card — Figma component set "blog card" 276:5724.
 *
 * The file ships two widths, 400 (3-up, Property 1=Variant2) and 612 (2-up,
 * Property 1=Default), both 540 tall — which means the image is 400px tall in
 * each and only its aspect changes. That is expressed as an aspect ratio so the
 * card scales instead of pinning heights.
 *
 * The two variants also differ in TITLE SIZE, which is easy to miss: 28px on the
 * 612 card (275:5577) against 24px on the 400 card (276:5746). Both are Peyda
 * Medium in Black/50. The image hairline is black/100 #E9E9E9 — the warm ramp,
 * not the cool ink-100 #E8E8E8 it was set to.
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
        class="overflow-hidden rounded-lg border border-warm-100 shadow-card"
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
          class="text-ink-50 transition-colors duration-200 ease-brand group-hover:text-brand"
          :class="props.variant === 'wide' ? 'text-heading-md' : 'text-heading-sm'"
        >
          {{ props.post.title }}
        </component>
      </div>
    </Link>
  </article>
</template>
