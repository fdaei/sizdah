<script setup lang="ts">
import { computed } from 'vue'
import { route } from 'ziggy-js'
import Eyebrow from '@/Components/Eyebrow.vue'
import FilterChips from '@/Components/FilterChips.vue'
import ProjectPostCard from '@/Components/ProjectPostCard.vue'
import SeoHead from '@/Components/SeoHead.vue'
import StartTogetherCard from '@/Components/StartTogetherCard.vue'
import { useTranslations } from '@/Composables/useTranslations'
import type { FilterOption, PageSectionData, ProjectSummary, SectionMap, SeoMeta } from '@/types'

/**
 * Work listing — Figma "projects" 222:1989 (1440x4656).
 *
 * Rebuilt against the real frame; the previous version was DERIVED from the
 * blog listing because no frame existed. `261:2545` is the light twin of this
 * frame and still ends on the old English Sahra card — 222:1989 is canonical.
 *
 * Vertical rhythm from the frame: the centred header block (eyebrow 40 title
 * 24 description), gap 64 to the filter row, gap 110 to the grid, gap 259 to
 * the closing card. The grid is two 612px columns, 24 across and 96 down.
 *
 * The closing card is the same "Frame 96467" the About page ends on, so it is
 * the shared StartTogetherCard rather than a second copy.
 *
 * `266:2771` is a 1200x1200 hairline grid the frame lays behind the header,
 * centred in the 1440 canvas (x=120) and starting at the same y=180 the content
 * does. Its lines measure 109.095px apart on both axes, so it is drawn with the
 * shared `.grid-mesh` background rather than shipped as an SVG — the same way
 * About draws its own mesh (359:9560, 109x118).
 */
const props = defineProps<{
  heading: { eyebrow: string; title: string; description: string }
  projects: ProjectSummary[]
  filters: { slug: string; name: string }[]
  activeFilter: string | null
  sections: SectionMap
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

const finalCta = computed<PageSectionData | undefined>(() => props.sections.final_cta)
</script>

<template>
  <SeoHead :seo="props.seo" />

  <section class="section-first pb-24">
    <div class="container-sizdah relative isolate">
      <!-- 266:2771 — the mesh starts level with the content, at frame y=180. -->
      <div
        class="grid-mesh pointer-events-none absolute inline-start-0 block-start-0 -z-10 hidden h-[1200px] w-full max-w-container lg:block"
        style="--mesh-cell-x: 109.095px; --mesh-cell-y: 109.095px"
        aria-hidden="true"
      />
      <header class="mx-auto flex max-w-[670px] flex-col items-center gap-16 text-center">
        <!-- 222:2461 — the eyebrow/title/description column is 612 wide inside the 670 track. -->
        <div class="flex w-full max-w-[612px] flex-col items-center gap-10">
          <Eyebrow v-if="props.heading.eyebrow" :text="props.heading.eyebrow" />

          <div class="flex flex-col items-center gap-6">
            <h1 class="text-display-lg text-ink-50">{{ props.heading.title }}</h1>
            <p v-if="props.heading.description" class="text-title-sm text-ink-200">
              {{ props.heading.description }}
            </p>
          </div>
        </div>

        <!-- 222:2475 — the chip row stretches the full 670 track, not the 612 column. -->
        <div v-if="props.filters.length" class="flex w-full justify-center">
          <FilterChips
            :options="filterOptions"
            :active="props.activeFilter"
            :label="t('work.services')"
          />
        </div>
      </header>

      <p
        v-if="!props.projects.length"
        class="mt-[110px] py-16 text-center text-title-sm text-ink-300"
      >
        {{ props.activeFilter ? t('common.empty_results') : t('common.empty_projects') }}
      </p>

      <div
        v-else
        class="mt-16 grid gap-x-6 gap-y-16 md:mt-[110px] md:grid-cols-2 md:gap-y-24"
        data-reveal-group
      >
        <ProjectPostCard
          v-for="project in props.projects"
          :key="project.slug"
          :project="project"
          data-reveal
        />
      </div>

      <div v-if="finalCta" class="mt-24 md:mt-[259px]">
        <StartTogetherCard :section="finalCta" />
      </div>
    </div>
  </section>
</template>
