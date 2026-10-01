<script setup lang="ts">
import { computed, nextTick, onBeforeUnmount, onMounted, ref } from 'vue'
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
import heroBgUrl from '~img/sizdah/home/hero-bg.jpg'
import trustMarkUrl from '~img/sizdah/clients/trust-divider.svg'
import underlineUrl from '~img/sizdah/shared/nav-underline.svg'
import heroNoteArrowUrl from '~img/sizdah/shared/hero-note-icon.svg'
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

/**
 * The hero's ground. The shipped art already has the hairline mesh, the warm
 * corner glow and the four journey marks baked into it, so nothing draws those
 * separately any more — the whole block just sits on top of the image. An
 * editor can still swap it per locale through the hero section's image field.
 *
 * `hero-bg.jpg` replaces the earlier `hero-bg.png` (which was JPEG data under
 * a .png name) trimmed from 2560x1393 to 2560x1390. Its last two rows were a
 * JPEG edge artefact — ~#4B3F25/#524631 against the ~#171410 interior, i.e.
 * three times brighter than the art around them. With `bg-cover bg-top` that
 * band is flush with the section's bottom edge at `lg` (where
 * `min-h-[783px]` matches the 2560x1393 ratio at the 1440 frame) and so is
 * invisible; below that the section grows taller than the art's ratio, cover
 * scales by height, and the band lands INSIDE the section — the thin gold
 * rule that used to appear between the hero and the KPI row on narrow
 * viewports. Nothing in CSS drew it, so nothing in CSS could remove it.
 */
const heroBackground = computed(() => hero.value?.image?.src ?? heroBgUrl)
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

const CLIENT_LOOP_SECONDS = 32
const clientTrack = ref<HTMLElement | null>(null)
const clientDragging = ref(false)
let clientPosition = 0
let clientLoopWidth = 0
let clientPointerId: number | null = null
let clientLastX = 0
let clientHovered = false
let clientFrame = 0
let clientLastTime = 0
let clientResize: ResizeObserver | null = null

function clientDirection(): 1 | -1 {
  return document.documentElement.dir === 'rtl' ? 1 : -1
}

function renderClients() {
  if (!clientTrack.value) return
  clientPosition = ((clientPosition % clientLoopWidth) + clientLoopWidth) % clientLoopWidth
  clientTrack.value.style.transform = `translate3d(${clientDirection() * clientPosition}px, 0, 0)`
}

function tickClients(time: number) {
  const delta = clientLastTime ? Math.min(time - clientLastTime, 100) : 0
  clientLastTime = time
  if (!clientHovered && !clientDragging.value && clientLoopWidth > 0) {
    clientPosition += (clientLoopWidth / CLIENT_LOOP_SECONDS) * (delta / 1000)
    renderClients()
  }
  clientFrame = requestAnimationFrame(tickClients)
}

function onClientHover(event: PointerEvent, hovered: boolean) {
  if (event.pointerType === 'mouse') clientHovered = hovered
}

function onClientPointerDown(event: PointerEvent) {
  if (props.clients.length < 2 || (event.pointerType === 'mouse' && event.button !== 0)) return
  clientPointerId = event.pointerId
  clientLastX = event.clientX
  clientDragging.value = true
  ;(event.currentTarget as HTMLElement).setPointerCapture(event.pointerId)
}

function onClientPointerMove(event: PointerEvent) {
  if (event.pointerId !== clientPointerId) return
  clientPosition += clientDirection() * (event.clientX - clientLastX)
  clientLastX = event.clientX
  renderClients()
}

function onClientPointerUp(event: PointerEvent) {
  if (event.pointerId !== clientPointerId) return
  clientPointerId = null
  clientDragging.value = false
}

onMounted(() => {
  void nextTick(() => {
    const first = clientTrack.value?.querySelector<HTMLElement>('ul')
    if (!first || props.clients.length < 2) return

    const measure = () => {
      clientLoopWidth = first.getBoundingClientRect().width
      renderClients()
    }

    measure()
    clientResize = new ResizeObserver(measure)
    clientResize.observe(first)

    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      clientFrame = requestAnimationFrame(tickClients)
    }
  })
})

onBeforeUnmount(() => {
  cancelAnimationFrame(clientFrame)
  clientResize?.disconnect()
})

const activeTestimonial = ref(0)
const testimonialTouchStart = ref<number | null>(null)

function showTestimonial(index: number) {
  activeTestimonial.value = (index + props.testimonials.length) % props.testimonials.length
}

function nextTestimonial() {
  showTestimonial(activeTestimonial.value + 1)
}

function previousTestimonial() {
  showTestimonial(activeTestimonial.value - 1)
}

function startTestimonialSwipe(event: TouchEvent) {
  testimonialTouchStart.value = event.changedTouches[0]?.clientX ?? null
}

function endTestimonialSwipe(event: TouchEvent) {
  if (testimonialTouchStart.value === null) return
  const distance = event.changedTouches[0].clientX - testimonialTouchStart.value
  if (Math.abs(distance) > 40) distance > 0 ? previousTestimonial() : nextTestimonial()
  testimonialTouchStart.value = null
}
</script>

<template>
  <SeoHead :seo="props.seo" />

  <!--
    Hero — 268:2962 y=0..1113. Text column sits at the inline start (right in
    RTL, matching the frame) over the art, whose marks occupy the opposite
    side; below `lg` the copy is full width and the art crops behind it.
  -->
  <!--
    The ground is one full-bleed image (`heroBackground`) rather than a stack of
    layers: the mesh, the corner glow and the four journey marks are all baked
    into the art, so nothing is drawn over it and the copy simply sits on top.
    `bg-top` keeps the marks anchored when `bg-cover` crops the bottom on short
    viewports; the `lg` min-height is the art's own 2560x1393 ratio at the
    1440 frame width, so the marks are never cropped on desktop.
  -->
  <section
    class="home-hero section-first relative overflow-hidden bg-cover bg-top bg-no-repeat pb-12 sm:pb-16 max-md:h-[619px] lg:min-h-[783px] lg:pb-24"
    :style="{ backgroundImage: `url(${heroBackground})` }"
  >
    <div class="container-sizdah relative">
      <div class="flex min-w-0 flex-col gap-5 sm:gap-6 lg:max-w-[566px] lg:gap-0 lg:pt-4" data-reveal-group>
        <!--
          394:4964 — a 221px label over a 2px brand rule. Not the dotted
          `.eyebrow` used by the section headers further down the page; this
          one is the agency line and is underlined instead of bulleted.
        -->
        <p
          v-if="hero?.eyebrow"
          class="flex w-fit max-w-full flex-col gap-px text-label-lg text-ink-100 lg:max-w-[221px] lg:whitespace-nowrap max-md:hidden"
        >
          <span>{{ hero.eyebrow }}</span>
          <img
            :src="underlineUrl"
            alt=""
            aria-hidden="true"
            class="mt-1 h-[5px] w-full max-w-none"
          />
        </p>

        <h1 v-if="hero" class="me-4 flex w-auto flex-col gap-4 text-start max-md:mt-[32px] sm:gap-3 lg:me-0 lg:mt-[47.5px] lg:gap-4">
          <span class="text-hero-line text-paper max-md:text-[32px] max-md:leading-[40px]">{{ hero.title }}</span>
          <span class="text-hero-accent text-brand max-md:text-[40px] max-md:leading-[51px]">{{ hero.subtitle }}</span>
          <span class="text-hero-line text-paper max-md:text-[32px] max-md:leading-[40px]">{{ hero.description }}</span>
        </h1>

        <p v-if="hero?.content" class="max-w-[506px] text-title-sm text-ink-200 max-md:mt-[42px] max-md:text-[14px] max-md:leading-[20px] sm:leading-relaxed lg:mt-[59px]">
          {{ hero.content }}
        </p>

        <div v-if="hero" dir="ltr" class="flex flex-nowrap items-center justify-end gap-2 max-md:mt-[52px] sm:gap-4 lg:mt-[109px]">
          <CtaButton
            v-if="hero.primaryCta"
            :label="hero.primaryCta.label"
            :href="hero.primaryCta.url"
            variant="light"
            size="lg"
            with-arrow
            class="ring-1 ring-brand max-md:w-[134px] max-md:px-0 max-md:py-3 max-md:text-title-sm max-md:[&>img]:hidden"
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
            size="lg"
            class="max-md:w-[137px] max-md:px-0 max-md:py-3 max-md:text-title-sm"
          />
        </div>

        <!--
          268:2997 — the handwritten aside under the buttons, with the
          up-right doodle (268:3024) beside it. Fixed chrome rather than
          authored copy, so it comes from lang/{locale}/home.php.
        -->
        <p
          v-if="hero"
          class="flex items-start gap-2 text-body-lg text-ink-300 max-md:hidden lg:ms-[65px] lg:mt-[49px] lg:gap-[13px]"
        >
          <img
            :src="heroNoteArrowUrl"
            alt=""
            aria-hidden="true"
            width="56"
            height="56"
            class="size-10 shrink-0 lg:-mt-[35px] lg:size-14"
          />
          <span class="max-w-[173px]">{{ $t('home.hero.note') }}</span>
        </p>
      </div>
    </div>
  </section>

  <!--
    KPI row — 268:3026. The strip is 1036 wide inside the 1440 frame, i.e.
    narrower than the 1248 content track, so it is capped and centred rather
    than filling `.container-sizdah`; each card then hugs the frame's 334.667.
  -->
  <section v-if="kpi?.items.length" class="pb-16 max-md:pb-[80px] md:pb-24">
    <div class="container-sizdah">
      <h2 v-if="kpi.title" class="sr-only">{{ kpi.title }}</h2>
      <ul
        class="mx-auto grid w-full max-w-[1036px] grid-cols-3 gap-2 sm:gap-4"
        data-reveal-group
      >
        <!-- The gold line is the item's `title`; each card is a direct grid item. -->
        <StatCard
          v-for="(item, index) in kpi.items"
          :key="item.id"
          :value="['+40%', '+70K', '+90%'][index] ?? item.value"
          :label="item.title"
          :caption="item.description"
          :icon="item.icon"
        />
      </ul>
    </div>
  </section>

  <!--
    Trust proof — 268:3002. One sentence broken around the hand-drawn "سیزده"
    mark (268:3005), then the six client marks. `title` is the run before the
    mark and `subtitle` the run after it, which is how the frame splits it.
  -->
  <section v-if="trustProof" class="pb-16 max-md:pb-[76px] md:pb-24 lg:pb-[117px]">
    <div class="container-sizdah flex flex-col items-center gap-6 lg:gap-0">
      <p class="flex flex-wrap items-center justify-center gap-1 text-center max-md:h-[54px] max-md:w-[258px] max-md:flex-nowrap max-md:whitespace-nowrap max-md:text-[16px]" data-reveal>
        <span class="text-title-lg text-brand-50">{{ trustProof.title }}</span>
        <img
          :src="trustMarkUrl"
          alt=""
          aria-hidden="true"
          width="72"
          height="34"
          class="h-auto w-[72px] max-md:size-[54px]"
        />
        <span v-if="trustProof.subtitle" class="text-title-md text-brand-50">
          {{ trustProof.subtitle }}
        </span>
      </p>

      <div
        v-if="props.clients.length"
        class="marquee-mask client-carousel w-full max-w-[1036px] overflow-hidden select-none max-md:h-[80px]"
        :class="{ 'is-dragging': clientDragging }"
        data-reveal
        @pointerenter="onClientHover($event, true)"
        @pointerleave="onClientHover($event, false)"
        @pointerdown="onClientPointerDown"
        @pointermove="onClientPointerMove"
        @pointerup="onClientPointerUp"
        @pointercancel="onClientPointerUp"
      >
        <div ref="clientTrack" class="marquee-track client-carousel-track max-md:h-[80px]">
          <ul class="flex shrink-0 items-center gap-x-12 pe-12 max-md:h-[80px]">
            <li v-for="client in props.clients" :key="client.name" class="shrink-0">
              <ClientLogo :client="client" />
            </li>
          </ul>

          <!-- The duplicate completes the continuous loop; assistive tech only needs one set. -->
          <ul aria-hidden="true" class="flex shrink-0 items-center gap-x-12 pe-12 max-md:h-[80px]">
            <li v-for="client in props.clients" :key="`duplicate-${client.name}`" class="shrink-0">
              <ClientLogo :client="client" />
            </li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- Services band — 268:3032, the one cream section on the page. -->
  <ServiceOrbit v-if="servicesCloud" :section="servicesCloud" :services="props.services" />

  <!-- Lead magnet — 391:4795, the larger of the two strips: 2780..2974, i.e.
       124 below the services band and 146 above the projects heading. -->
  <section v-if="leadMagnet" class="section max-md:pb-2 max-md:pt-10 lg:pb-[146px] lg:pt-[124px]">
    <div class="container-sizdah">
      <LeadMagnetBanner :section="leadMagnet" size="lg" source="home" data-reveal />
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
        class="mt-12 grid gap-[2px] border-2 border-ink-300 bg-ink-300 max-md:gap-0 max-md:border-0 sm:grid-cols-2 lg:mt-16 lg:grid-cols-3"
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
  <section v-if="reviews && props.testimonials.length" class="section max-md:pb-[50px]">
    <div class="container-sizdah">
      <SectionHeading
        :eyebrow="reviews.eyebrow"
        :title="reviews.title"
        :subtitle="reviews.subtitle || reviews.description"
        gap="lg"
        class="max-md:gap-6 max-md:[&>div>h2]:whitespace-nowrap max-md:[&>div>h2]:text-[26px] max-md:[&>div>h2]:leading-[36px] max-md:[&>div>p]:text-[16px] max-md:[&>div>p]:leading-[22px]"
      />

      <!-- Mobile becomes a swipeable carousel; desktop keeps the editorial grid. -->
      <ul
        class="testimonial-carousel mt-2 flex h-auto touch-pan-y gap-[26px] overflow-hidden max-md:mt-10 lg:mt-2 lg:grid lg:h-[297px] lg:overflow-visible"
        :style="{ '--testimonial-index': activeTestimonial }"
        @touchstart="startTestimonialSwipe"
        @touchend="endTestimonialSwipe"
        :class="[
          props.testimonials.length > 1 && 'sm:grid-cols-2',
          props.testimonials.length > 2 && 'lg:grid-cols-3',
          props.testimonials.length > 3 && 'lg:grid-cols-4',
          props.testimonials.length === 1 && 'max-w-[300px] max-md:max-w-none max-md:w-full',
        ]"
        data-reveal-group
      >
        <TestimonialCard
          v-for="testimonial in props.testimonials"
          :key="testimonial.name"
          :testimonial="testimonial"
        />
      </ul>

      <div v-if="props.testimonials.length > 1" class="testimonial-controls mt-6 flex items-center justify-center gap-4 max-md:mt-0 lg:hidden">
        <button type="button" class="testimonial-arrow" aria-label="نظر قبلی" @click="previousTestimonial">←</button>
        <div class="flex items-center gap-2" role="tablist" aria-label="انتخاب نظر مشتری">
          <button v-for="(_, index) in props.testimonials" :key="`testimonial-dot-${index}`" type="button" role="tab" :aria-selected="activeTestimonial === index" :aria-label="`نمایش نظر ${index + 1}`" class="testimonial-dot" :class="activeTestimonial === index && 'is-active'" @click="showTestimonial(index)" />
        </div>
        <button type="button" class="testimonial-arrow" aria-label="نظر بعدی" @click="nextTestimonial">→</button>
      </div>
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
