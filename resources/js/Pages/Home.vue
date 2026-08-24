<script setup lang="ts">
import { computed } from 'vue'
import SeoHead from '@/Components/SeoHead.vue'
import CtaButton from '@/Components/CtaButton.vue'
import StatCard from '@/Components/StatCard.vue'
import ClientLogo from '@/Components/ClientLogo.vue'
import ServiceOrbit from '@/Components/ServiceOrbit.vue'
import LeadMagnetBanner from '@/Components/LeadMagnetBanner.vue'
import ProjectShowcase from '@/Components/ProjectShowcase.vue'
import SectionHeading from '@/Components/SectionHeading.vue'
import ProcessStepCard from '@/Components/ProcessStepCard.vue'
import WhyUsGrid from '@/Components/WhyUsGrid.vue'
import TestimonialCard from '@/Components/TestimonialCard.vue'
import InsightsShowcase from '@/Components/InsightsShowcase.vue'
import FaqAccordion from '@/Components/FaqAccordion.vue'
import StartTogetherCard from '@/Components/StartTogetherCard.vue'
import HeroJourney from '@/Components/HeroJourney.vue'
import trustMarkUrl from '~img/sizdah/clients/trust-divider.svg'
import heroNoteArrowUrl from '~img/sizdah/shared/up-right-arrow.svg'
import testimonialRuleUrl from '~img/sizdah/home/testimonial-rule.svg'
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
 * Section order follows the frame top to bottom. Each block renders only when
 * the editor has created its section, so an unfinished page degrades to fewer
 * bands rather than to holes.
 *
 * The frame was redrawn after the first pass at this page: it is now 9919px
 * tall (was 11273), the process grid is a bordered dark grid rather than the
 * hand-drawn notched cards, and it gained the insights and FAQ bands that
 * earlier notes recorded as "not in this frame". See FIGMA/state.json.
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
const trustProof = computed(() => props.sections.trust_proof)
const servicesCloud = computed(() => props.sections.services_cloud)
const leadMagnet = computed(() => props.sections.lead_magnet)
const projectsShowcase = computed(() => props.sections.projects_showcase)
const process = computed(() => props.sections.process)
const whyUs = computed(() => props.sections.why_us)
const reviews = computed(() => props.sections.reviews)
const insights = computed(() => props.sections.insights)
const faq = computed(() => props.sections.faq)
const finalCta = computed(() => props.sections.final_cta)
</script>

<template>
  <SeoHead :seo="props.seo" />

  <!--
    Hero — 268:2962 y=0..1113. Text column sits at the inline start (right in
    RTL, matching the frame) with the illustration opposite; below `lg` the two
    stack and the illustration follows the copy.
  -->
  <section class="section-first relative overflow-hidden pb-16">
    <!-- Decorative hairline mesh, Figma "Group" 268:2966 — 884x778, not square. -->
    <div
      class="grid-mesh pointer-events-none absolute block-start-[122px] inline-start-16 hidden h-[778px] w-[884px] lg:block"
      aria-hidden="true"
    />

    <div class="container-sizdah relative">
      <div class="grid items-center gap-12 lg:grid-cols-[minmax(0,517px)_minmax(0,1fr)] lg:gap-16">
        <div class="flex flex-col gap-6" data-reveal-group>
          <!--
            394:4964 — a 221px label over a 2px brand rule. Not the dotted
            `.eyebrow` used by the section headers further down the page; this
            one is the agency line and is underlined instead of bulleted.
          -->
          <p
            v-if="hero?.eyebrow"
            class="flex w-fit max-w-[221px] flex-col gap-px text-label-lg text-ink-100"
          >
            <span>{{ hero.eyebrow }}</span>
            <span aria-hidden="true" class="mt-1 block h-0.5 w-full bg-brand" />
          </p>

          <h1 v-if="hero" class="flex flex-col gap-4 text-start">
            <span class="text-hero-line text-paper">{{ hero.title }}</span>
            <span class="text-hero-accent text-brand">{{ hero.subtitle }}</span>
            <span class="text-hero-line text-paper">{{ hero.description }}</span>
          </h1>

          <p v-if="hero?.content" class="max-w-[506px] text-title-sm leading-[normal] text-ink-200">
            {{ hero.content }}
          </p>

          <div v-if="hero" class="flex flex-wrap items-center gap-4">
            <CtaButton
              v-if="hero.primaryCta"
              :label="hero.primaryCta.label"
              :href="hero.primaryCta.url"
              size="lg"
              with-arrow
            />
            <!--
              268:2989 is a white fill with a 1px brand rule and an ink label —
              not the dark outline the other pages use, so it takes `light`
              plus the frame's border rather than `outline`. Both hero CTAs
              measure 57px (268:2990, 268:2989 are both lg-BW/lg-WB instances,
              32/16 padding + 20px Medium) — `size` was missing, so this was
              silently rendering at the smaller default (24/12, 18px).
            -->
            <CtaButton
              v-if="hero.secondaryCta"
              :label="hero.secondaryCta.label"
              :href="hero.secondaryCta.url"
              variant="light"
              size="lg"
              class="border border-brand"
            />
          </div>

          <!--
            268:2997 — the handwritten aside under the buttons, with the
            up-right doodle (268:3024) beside it. Fixed chrome rather than
            authored copy, so it comes from lang/{locale}/home.php.
          -->
          <p v-if="hero" class="flex items-center gap-2 text-body-lg text-ink-300">
            <img
              :src="heroNoteArrowUrl"
              alt=""
              aria-hidden="true"
              width="56"
              height="56"
              class="size-10 shrink-0 flip-rtl"
            />
            <span class="max-w-[173px]">{{ $t('home.hero.note') }}</span>
          </p>
        </div>

        <!--
          The frame draws this as loose vectors, not a raster, so it is composed
          in HeroJourney rather than shipped as one image. An editor can still
          override it per locale through the hero section's image field.
        -->
        <img
          v-if="hero?.image"
          :src="hero.image.src"
          :srcset="hero.image.srcset"
          :alt="hero.image.alt"
          width="700"
          height="673"
          class="h-auto w-full max-w-[700px] justify-self-center"
        />
        <HeroJourney v-else class="justify-self-center" />
      </div>
    </div>
  </section>

  <!-- KPI row — 268:3026, three "KPIs" instances across the 1036px track. -->
  <section v-if="kpi?.items.length" class="pb-16 md:pb-24">
    <div class="container-sizdah">
      <h2 v-if="kpi.title" class="sr-only">{{ kpi.title }}</h2>
      <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3" data-reveal-group>
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
    Trust proof — 268:3002. One sentence broken around the hand-drawn "سیزده"
    mark (268:3005), then the six client marks. `title` is the run before the
    mark and `subtitle` the run after it, which is how the frame splits it.
  -->
  <section v-if="trustProof" class="pb-16 md:pb-24">
    <div class="container-sizdah flex flex-col items-center gap-6">
      <p class="flex flex-wrap items-center justify-center gap-2 text-center" data-reveal>
        <span class="text-title-lg text-brand-50">{{ trustProof.title }}</span>
        <img
          :src="trustMarkUrl"
          alt=""
          aria-hidden="true"
          width="72"
          height="34"
          class="h-auto w-[72px]"
        />
        <span v-if="trustProof.subtitle" class="text-title-md text-brand-50">
          {{ trustProof.subtitle }}
        </span>
      </p>

      <ul
        v-if="props.clients.length"
        class="flex flex-wrap items-center justify-center gap-x-12 gap-y-4"
        data-reveal-group
      >
        <li v-for="client in props.clients" :key="client.name">
          <ClientLogo :client="client" />
        </li>
      </ul>
    </div>
  </section>

  <!-- Services band — 268:3032, the one cream section on the page. -->
  <ServiceOrbit v-if="servicesCloud" :section="servicesCloud" :services="props.services" />

  <!-- Lead magnet — 391:4795, the larger of the two strips. -->
  <section v-if="leadMagnet" class="section">
    <div class="container-sizdah">
      <LeadMagnetBanner :section="leadMagnet" size="lg" data-reveal />
    </div>
  </section>

  <!-- Projects — 268:3461 heading, 268:3539 image, 268:3540 rows. -->
  <ProjectShowcase
    v-if="projectsShowcase && props.projects.length"
    :section="projectsShowcase"
    :projects="props.projects"
  />

  <!--
    Process — 268:3468 heading over the six-up grid at 523:5798. The cells butt
    together and their 2px ink-300 edges collapse into single rules, so the grid
    paints the rules as its own background and gap rather than each card
    carrying a border that would double up.
  -->
  <section v-if="process?.items.length" class="section">
    <div class="container-sizdah">
      <SectionHeading
        :eyebrow="process.eyebrow"
        :title="process.title"
        :subtitle="process.subtitle || process.description"
      />

      <ul
        class="mt-12 grid gap-[2px] border-2 border-ink-300 bg-ink-300 sm:grid-cols-2 lg:mt-16 lg:grid-cols-3"
        data-reveal-group
      >
        <ProcessStepCard
          v-for="(item, index) in process.items"
          :key="item.id"
          :item="item"
          :index="index"
        />
      </ul>
    </div>
  </section>

  <!-- Why us — 268:3475 heading with the quartered claims. -->
  <WhyUsGrid v-if="whyUs?.items.length" :section="whyUs" />

  <!-- Testimonials — 268:3720 heading over the 268:3729 card row. -->
  <section v-if="reviews && props.testimonials.length" class="section">
    <div class="container-sizdah">
      <div class="relative">
        <SectionHeading
          :eyebrow="reviews.eyebrow"
          :title="reviews.title"
          :subtitle="reviews.subtitle || reviews.description"
        />
        <!-- 268:3728 — a hand-drawn rule tucked under the subtitle's end. -->
        <img
          :src="testimonialRuleUrl"
          alt=""
          aria-hidden="true"
          width="81"
          height="4"
          class="pointer-events-none absolute inline-end-0 top-full mt-1 hidden w-20 md:block"
        />
      </div>

      <!--
        The frame draws four cards. The CMS currently holds one, and stretching
        a lone card across a four-column track leaves three empty columns, so
        the track is sized to what actually exists and capped at the frame's
        four.
      -->
      <ul
        class="mt-12 grid gap-4 lg:mt-16"
        :class="[
          props.testimonials.length > 1 && 'sm:grid-cols-2',
          props.testimonials.length > 2 && 'lg:grid-cols-3',
          props.testimonials.length > 3 && 'lg:grid-cols-4',
          props.testimonials.length === 1 && 'max-w-[300px]',
        ]"
        data-reveal-group
      >
        <TestimonialCard
          v-for="testimonial in props.testimonials"
          :key="testimonial.name"
          :testimonial="testimonial"
        />
      </ul>
    </div>
  </section>

  <!-- Insights — 268:3797 heading over the 430:5247 row. -->
  <InsightsShowcase
    v-if="insights && props.posts.length"
    :section="insights"
    :posts="props.posts"
  />

  <!-- FAQ — 434:5512 boxes beside the 434:5470 heading. -->
  <FaqAccordion v-if="faq && props.faqs.length" :section="faq" :faqs="props.faqs" />

  <!--
    Final CTA — 553:7779. The same cream "شروع همکاری" card the about and work
    frames draw, so it reuses StartTogetherCard — the same card every page in
    the file ends on now; `FinalCtaCard`, the older dark Sahra card, is dead
    (see GAPS G34).
  -->
  <section v-if="finalCta" class="section">
    <div class="container-sizdah">
      <StartTogetherCard :section="finalCta" data-reveal />
    </div>
  </section>
</template>
