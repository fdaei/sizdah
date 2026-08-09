<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import Eyebrow from '@/Components/Eyebrow.vue'
import FinalCtaCard from '@/Components/FinalCtaCard.vue'
import SeoHead from '@/Components/SeoHead.vue'
import { useTranslations } from '@/Composables/useTranslations'
import type { CardItem, PageSectionData, ProjectDetail, SeoMeta } from '@/types'

/**
 * Single project.
 *
 * DERIVED — no Sizdah frame exists for this route (see FIGMA/state.json
 * derivedScreens). Every field the controller already returns is rendered, using
 * the tokens and card treatments the designed frames establish. Replace when a
 * frame is designed.
 */
const props = defineProps<{
  project: ProjectDetail
  finalCta: PageSectionData | null
  seo: SeoMeta
}>()

const { t } = useTranslations()

const blocks: { key: string; items: CardItem[] }[] = [
  { key: 'goals', items: props.project.goals },
  { key: 'strategy', items: props.project.strategy },
  { key: 'deliverables', items: props.project.deliverables },
].filter((block) => block.items.length > 0)
</script>

<template>
  <SeoHead :seo="props.seo" />

  <article class="section-first pb-24">
    <div class="container-sizdah flex flex-col gap-24">
      <header class="flex flex-col gap-6">
        <Eyebrow v-if="props.project.industry" :text="props.project.industry" />
        <h1 class="max-w-[831px] text-display-lg text-ink-50">{{ props.project.title }}</h1>
        <p v-if="props.project.excerpt" class="max-w-[831px] text-title-md text-ink-200">
          {{ props.project.excerpt }}
        </p>

        <dl class="flex flex-wrap gap-x-12 gap-y-4">
          <div v-if="props.project.year" class="flex flex-col gap-1">
            <dt class="text-label-lg text-ink-400">{{ t('work.year') }}</dt>
            <dd class="text-body-lg text-ink-50 latin-nums">{{ props.project.year }}</dd>
          </div>
          <div v-if="props.project.services.length" class="flex flex-col gap-1">
            <dt class="text-label-lg text-ink-400">{{ t('work.services') }}</dt>
            <dd class="text-body-lg text-ink-50">{{ props.project.services.join('، ') }}</dd>
          </div>
          <div v-if="props.project.instagram" class="flex flex-col gap-1">
            <dt class="text-label-lg text-ink-400">{{ t('work.instagram') }}</dt>
            <dd class="text-body-lg">
              <a
                :href="props.project.instagram"
                target="_blank"
                rel="noopener noreferrer"
                class="text-brand underline underline-offset-4"
              >
                {{ props.project.instagram }}
              </a>
            </dd>
          </div>
        </dl>
      </header>

      <img
        v-if="props.project.banner ?? props.project.image"
        :src="(props.project.banner ?? props.project.image)!.src"
        :srcset="(props.project.banner ?? props.project.image)!.srcset"
        :alt="(props.project.banner ?? props.project.image)!.alt"
        :width="(props.project.banner ?? props.project.image)!.width"
        :height="(props.project.banner ?? props.project.image)!.height"
        class="w-full rounded-lg object-cover"
      />

      <section v-if="props.project.challenge" class="flex flex-col gap-6">
        <h2 class="text-heading-lg text-ink-50">{{ t('work.challenge') }}</h2>
        <p class="max-w-[831px] text-title-md text-ink-200">{{ props.project.challenge }}</p>
        <ul v-if="props.project.challengePoints.length" class="flex flex-col gap-4">
          <Eyebrow
            v-for="point in props.project.challengePoints"
            :key="point"
            as="li"
            size="lg"
            marker="dot"
            :text="point"
          />
        </ul>
      </section>

      <section v-for="block in blocks" :key="block.key" class="flex flex-col gap-6">
        <h2 class="text-heading-lg text-ink-50">{{ t(`work.${block.key}`) }}</h2>
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <div v-for="item in block.items" :key="item.title" class="card-surface flex flex-col gap-2 p-6">
            <h3 class="text-title-lg text-ink-50">{{ item.title }}</h3>
            <p class="text-body-md text-ink-300">{{ item.description }}</p>
          </div>
        </div>
      </section>

      <section v-if="props.project.showcase.length" class="flex flex-col gap-6">
        <h2 class="text-heading-lg text-ink-50">{{ t('work.showcase') }}</h2>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <img
            v-for="shot in props.project.showcase"
            :key="shot.src"
            :src="shot.src"
            :srcset="shot.srcset"
            :alt="shot.alt"
            :width="shot.width"
            :height="shot.height"
            loading="lazy"
            class="aspect-square w-full rounded-lg object-cover"
          />
        </div>
      </section>

      <section
        v-if="props.project.beforeAfter.before && props.project.beforeAfter.after"
        class="grid gap-6 md:grid-cols-2"
      >
        <figure v-for="side in ['before', 'after'] as const" :key="side" class="flex flex-col gap-2">
          <img
            :src="props.project.beforeAfter[side]!.src"
            :alt="props.project.beforeAfter[side]!.alt"
            :width="props.project.beforeAfter[side]!.width"
            :height="props.project.beforeAfter[side]!.height"
            loading="lazy"
            class="w-full rounded-lg object-cover"
          />
          <figcaption class="text-label-lg text-ink-400">{{ t(`work.${side}`) }}</figcaption>
        </figure>
      </section>

      <section v-if="props.project.results.length" class="flex flex-col gap-6">
        <h2 class="text-heading-lg text-ink-50">{{ t('work.results') }}</h2>
        <dl class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div v-for="stat in props.project.results" :key="stat.label" class="card-surface flex flex-col gap-2 p-6">
            <dt class="text-body-md text-ink-300">{{ stat.label }}</dt>
            <dd class="text-display-sm text-brand latin-nums">{{ stat.value }}</dd>
          </div>
        </dl>
        <p v-if="props.project.resultsSummary" class="max-w-[831px] text-title-md text-ink-200">
          {{ props.project.resultsSummary }}
        </p>
      </section>

      <Link
        v-if="props.project.next"
        :href="props.project.next.url"
        class="card-surface flex flex-col gap-2 p-8 transition-colors duration-200 ease-brand hover:border-brand"
      >
        <span class="text-label-lg text-ink-400">{{ t('work.next_case_study') }}</span>
        <span class="text-heading-sm text-ink-50">{{ props.project.next.title }}</span>
      </Link>

      <FinalCtaCard v-if="props.finalCta" :section="props.finalCta" />
    </div>
  </article>
</template>
