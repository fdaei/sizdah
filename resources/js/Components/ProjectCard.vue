<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import type { ProjectSummary } from '@/types'

/**
 * Project card.
 *
 * DERIVED — the Sizdah file has no work/portfolio frame. Built from the design
 * system the other frames establish (blog card geometry, ink-900 surface, brand
 * hover) so the section is visually of a piece with the rest of the site rather
 * than invented. Replace once a frame exists.
 */
const props = defineProps<{ project: ProjectSummary }>()
</script>

<template>
  <article class="group flex flex-col gap-4">
    <Link :href="props.project.url" class="flex flex-col gap-4">
      <div class="aspect-square overflow-hidden rounded-lg border border-ink-800">
        <img
          v-if="props.project.image"
          :src="props.project.image.src"
          :srcset="props.project.image.srcset"
          :alt="props.project.image.alt"
          :width="props.project.image.width"
          :height="props.project.image.height"
          loading="lazy"
          class="size-full object-cover transition-transform duration-500 ease-brand group-hover:scale-105"
        />
        <div v-else class="size-full bg-ink-900" aria-hidden="true" />
      </div>

      <div class="flex flex-col gap-2">
        <p v-if="props.project.industry" class="text-body-md text-brand">
          {{ props.project.industry }}
        </p>
        <h3
          class="text-heading-sm text-ink-50 transition-colors duration-200 ease-brand group-hover:text-brand"
        >
          {{ props.project.title }}
        </h3>
        <p v-if="props.project.excerpt" class="text-body-md text-ink-300">
          {{ props.project.excerpt }}
        </p>
      </div>
    </Link>

    <ul v-if="props.project.services.length" class="flex flex-wrap gap-2">
      <li
        v-for="service in props.project.services"
        :key="service"
        class="rounded-round border border-ink-700 px-3 py-1 text-label-md text-ink-300"
      >
        {{ service }}
      </li>
    </ul>
  </article>
</template>
