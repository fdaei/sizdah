<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import type { ProjectSummary } from '@/types'
import industryIconUrl from '~img/sizdah/home/project-industry.svg'
import titleUnderlineUrl from '~img/sizdah/work/project-title-underline.svg'

/**
 * Project post card — Figma "project post" 226:2818 (612x800) on the projects
 * frame 222:1989.
 *
 * Replaces the DERIVED ProjectCard for this listing. Geometry, top to bottom:
 * a 612x612 square image (radius 24, 2px ink-400 hairline), gap 40, then a
 * 148px detail block — caption (title row + 16 gap + two-line excerpt), gap 24,
 * then the service tag row.
 *
 * The title row is a space-between pair: the project title sits at the inline
 * start with a hand-drawn underline beneath it, the industry with its icon at
 * the inline end. The frame also carries a `Layer_1` mark over the title — it
 * is `visible: false` in the file, so it is deliberately not rendered here.
 *
 * Service tags are separated by 4px brand dots. The dots are decorative, so
 * they are aria-hidden and the row stays a real list.
 */
const props = defineProps<{ project: ProjectSummary }>()
</script>

<template>
  <article class="group">
    <Link :href="props.project.url" class="flex flex-col gap-10">
      <div class="aspect-square overflow-hidden rounded-[24px] border-2 border-ink-400">
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

      <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-4">
          <div class="flex items-start justify-between gap-4">
            <h3 class="flex flex-col items-start">
              <span
                class="text-display-sm font-semibold text-paper transition-colors duration-200 ease-brand group-hover:text-brand"
              >
                {{ props.project.title }}
              </span>
              <!--
                502:5759 — a 165x5 pen stroke, not a rule. It tracks the title
                width, so it is a block-level image at 100% rather than a border.
              -->
              <img :src="titleUnderlineUrl" alt="" aria-hidden="true" class="mt-1 h-[5px] w-full" />
            </h3>

            <p v-if="props.project.industry" class="flex shrink-0 items-center gap-2 pt-3">
              <span class="text-label-lg text-ink-50">{{ props.project.industry }}</span>
              <img :src="industryIconUrl" alt="" aria-hidden="true" width="24" height="24" />
            </p>
          </div>

          <p v-if="props.project.excerpt" class="text-body-lg text-ink-100">
            {{ props.project.excerpt }}
          </p>
        </div>

        <ul v-if="props.project.services.length" class="flex flex-wrap items-center gap-2">
          <li
            v-for="(service, index) in props.project.services"
            :key="service"
            class="flex items-center gap-2"
          >
            <span
              v-if="index > 0"
              class="size-1 shrink-0 rounded-round bg-brand"
              aria-hidden="true"
            />
            <span class="text-body-md text-ink-100">{{ service }}</span>
          </li>
        </ul>
      </div>
    </Link>
  </article>
</template>
