<script setup lang="ts">
import { computed } from 'vue'
import SeoHead from '@/Components/SeoHead.vue'
import CtaButton from '@/Components/CtaButton.vue'
import StatCard from '@/Components/StatCard.vue'
import heroFallbackUrl from '~img/sizdah/home/hero-journey.png'
import type {
  ClientItem,
  PostSummary,
  ProjectSummary,
  SectionMap,
  SeoMeta,
  ServiceItem,
  TestimonialItem,
  FaqItem,
} from '@/types'

/**
 * Home — Figma 268:2962 (canonical dark frame; 13:2766 is its light twin and
 * is not implemented separately).
 *
 * Editorial copy comes from the `home` Page's sections, keyed by SectionType,
 * so everything here stays editable in Filament. The hero's three headline
 * lines map onto existing columns rather than new ones:
 *
 *   title       -> line 1, White        ("ما به مسیر")
 *   subtitle    -> line 2, Yellow/1000  ("رشد و فروش")
 *   description -> line 3, White        ("کسب و کار شما کمک می‌کنیم")
 *   content     -> the paragraph beneath (268:2996)
 *
 * See FIGMA/state.json for the per-section node map and the outstanding
 * sections.
 */
const props = defineProps<{
  sections: SectionMap
  services: ServiceItem[]
  projects: ProjectSummary[]
  clients: ClientItem[]
  testimonials: TestimonialItem[]
  posts: PostSummary[]
  faqs: FaqItem[]
  seo: SeoMeta
}>()

const hero = computed(() => props.sections.hero)
const kpi = computed(() => props.sections.kpi)
</script>

<template>
  <SeoHead :seo="props.seo" />

  <!--
    Hero — 268:2962 y=0..1113. Text column sits at the inline start (right in
    RTL, matching the frame) with the illustration opposite; below `lg` the two
    stack and the illustration follows the copy.
  -->
  <section class="section-first relative overflow-hidden pb-16">
    <!-- Decorative hairline mesh, Figma "Group" 268:2966 (884x884). -->
    <div
      class="grid-mesh pointer-events-none absolute inset-block-start-[122px] inset-inline-start-16 hidden size-[884px] lg:block"
      aria-hidden="true"
    />

    <div class="container-sizdah relative">
      <div class="grid items-center gap-12 lg:grid-cols-[minmax(0,517px)_minmax(0,1fr)] lg:gap-16">
        <div class="flex flex-col gap-6">
          <h1 v-if="hero" class="flex flex-col gap-4 text-start">
            <span class="text-hero-line text-paper">{{ hero.title }}</span>
            <span class="text-hero-accent text-brand">{{ hero.subtitle }}</span>
            <span class="text-hero-line text-paper">{{ hero.description }}</span>
          </h1>

          <p
            v-if="hero?.content"
            class="max-w-[506px] text-title-sm leading-[normal] text-ink-200"
          >
            {{ hero.content }}
          </p>

          <div v-if="hero" class="flex flex-wrap items-center gap-4">
            <CtaButton
              v-if="hero.primaryCta"
              :label="hero.primaryCta.label"
              :href="hero.primaryCta.url"
              with-arrow
            />
            <CtaButton
              v-if="hero.secondaryCta"
              :label="hero.secondaryCta.label"
              :href="hero.secondaryCta.url"
              variant="outline"
            />
          </div>
        </div>

        <!--
          Figma places a 700x673 raster here (268:3030). The editor can swap it
          per locale through the hero section's image field; the exported frame
          asset is the fallback so the page never renders a hole.
        -->
        <img
          :src="hero?.image?.src ?? heroFallbackUrl"
          :srcset="hero?.image?.srcset"
          :alt="hero?.image?.alt ?? ''"
          :aria-hidden="hero?.image ? undefined : 'true'"
          width="700"
          height="673"
          class="h-auto w-full max-w-[700px] justify-self-center"
        />
      </div>
    </div>
  </section>

  <!-- KPI row — 268:3026, three "KPIs" instances across the 1036px track. -->
  <section v-if="kpi?.items.length" class="pb-16 md:pb-24">
    <div class="container-sizdah">
      <h2 v-if="kpi.title" class="sr-only">{{ kpi.title }}</h2>
      <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <li v-for="item in kpi.items" :key="item.id" class="contents">
          <StatCard
            :value="item.value"
            :label="item.label"
            :caption="item.description"
            :icon="item.icon"
          />
        </li>
      </ul>
    </div>
  </section>

  <!--
    TODO — remaining Home sections, in frame order. Each needs its own
    get_design_context pass; node IDs are recorded in FIGMA/state.json so the
    next session can resume without re-deriving them.

      268:3002  trust proof / logo strip      (y1209, 6 "logo" instances)
      268:3032  services orbit, dark band     (y1522, 1440x1130)
      391:4795  lead magnet banner            (y2780)
      268:3461  projects showcase heading     (y3120)
      268:3539  project image + 268:3540 rows (y3407)
      268:3547  6-step process grid           (y4511, 1248x848)
      268:3475  why-us heading + diagram      (y5550)
      268:3676  why-us cards, 4 x 312x216     (y6458)
      268:3720  testimonials, 4 cards         (y6890)
      268:3797  final CTA heading             (y7751)

    Not in this frame at all: footer (AppLayout renders it), insights and FAQ.
    `posts` and `faqs` are therefore accepted as props but unrendered — see the
    deviation note in FIGMA/state.json.
  -->
</template>
