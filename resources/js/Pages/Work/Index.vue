<script setup lang="ts">
import { computed } from 'vue'
import { route } from 'ziggy-js'
import Eyebrow from '@/Components/Eyebrow.vue'
import FilterChips from '@/Components/FilterChips.vue'
import ProjectCard from '@/Components/ProjectCard.vue'
import SeoHead from '@/Components/SeoHead.vue'
import { useTranslations } from '@/Composables/useTranslations'
import type { FilterOption, ProjectSummary, SeoMeta } from '@/types'

/**
 * Work listing.
 *
 * DERIVED — no Sizdah frame exists for this route (see FIGMA/state.json
 * derivedScreens). The controller and its props are untouched; the layout
 * reuses the blog listing's hero + filter-pill + card-grid rhythm so it belongs
 * to the same system. Replace when a frame is designed.
 */
const props = defineProps<{
  heading: { eyebrow: string; title: string; description: string }
  projects: ProjectSummary[]
  filters: { slug: string; name: string }[]
  activeFilter: string | null
  seo: SeoMeta
}>()

const { t } = useTranslations()

const filterOptions = computed<FilterOption[]>(() => [
  { value: null, label: t('common.all'), href: route('work.index') },
  ...props.filters.map((filter) => ({
    value: filter.slug,
    label: filter.name,
    href: route('work.index', { service: filter.slug }),
  })),
])
</script>

<template>
  <SeoHead :seo="props.seo" />

  <section class="section-first pb-24">
    <div class="container-sizdah flex flex-col gap-12">
      <header class="mx-auto flex max-w-measure flex-col items-center gap-4 text-center">
        <Eyebrow v-if="props.heading.eyebrow" :text="props.heading.eyebrow" />
        <h1 class="text-display-md text-ink-50">{{ props.heading.title }}</h1>
        <p v-if="props.heading.description" class="text-title-sm text-ink-200">
          {{ props.heading.description }}
        </p>
      </header>

      <div v-if="props.filters.length" class="flex justify-center">
        <FilterChips
          :options="filterOptions"
          :active="props.activeFilter"
          :label="t('work.services')"
        />
      </div>

      <p v-if="!props.projects.length" class="py-16 text-center text-title-sm text-ink-300">
        {{ props.activeFilter ? t('common.empty_results') : t('common.empty_projects') }}
      </p>

      <div v-else class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <ProjectCard v-for="project in props.projects" :key="project.slug" :project="project" />
      </div>
    </div>
  </section>
</template>
