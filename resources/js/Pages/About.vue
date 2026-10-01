<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import StartTogetherCard from '@/Components/StartTogetherCard.vue'
import SeoHead from '@/Components/SeoHead.vue'
import type {
  PageSectionData,
  SectionItem,
  SectionMap,
  SeoMeta,
  SharedProps,
  TeamMemberItem,
} from '@/types'
import heroIllustrationUrl from '~img/sizdah/about/hero-team.webp'
import heroScriptUrl from '~img/sizdah/about/hero-script.svg'
import storyMarkUrl from '~img/sizdah/about/story-mark.svg'
import pathIconUrl from '~img/sizdah/about/principle-path.svg'
import impactIconUrl from '~img/sizdah/about/principle-impact.svg'
import identityIconUrl from '~img/sizdah/about/principle-identity.svg'
import discoveryIconUrl from '~img/sizdah/about/principle-discovery.svg'
import figmaMohammadUrl from '~img/sizdah/about/figma-mohammad.png'
import figmaAminUrl from '~img/sizdah/about/figma-amin.png'
import figmaStoryIconUrl from '~img/sizdah/about/figma-story-icon.svg'

/**
 * About — Figma 336:5623 (1440x4021).
 *
 * Supersedes the generic section-loop this page used to render: the route had
 * no frame when it was first built (FIGMA/state.json listed it under
 * derivedScreens), and one now exists. Five blocks, all fed by the `about`
 * Page's sections so everything stays editable in Filament:
 *
 *   about_hero    -> 336:5703  headline, lede, illustration
 *   story         -> 357:9335  centred 824px column
 *   how_we_think  -> 411:7908  header row + four principle cards
 *   team          -> 411:7906  header row + member cards (TeamMember rows)
 *   final_cta     -> 577:10889 cream card
 *
 * The frame is authored RTL/Persian, so its inline start is the right edge.
 * Every rule here is logical (`text-start`, `inset-inline-*`, `items-start`)
 * and the layout mirrors itself under LTR rather than being duplicated.
 *
 * DEVIATIONS, all deliberate:
 *  - The hero eyebrow (336:5705 "small title") is hidden in the frame, so
 *    `about_hero.eyebrow` is not rendered.
 *  - 336:5706 and 577:11228 set their eyebrows in Maneli, which is not
 *    licensed here; they take the `font-display` stack at the same size.
 *  - `hero-script.svg` is the hand-drawn Persian word "سیزده", so it is placed
 *    for fa only — en/ar render the headline as plain text.
 *  - The hero copy sits 69px above the illustration's centre in the frame;
 *    it is centred here rather than pinned to a magic offset.
 *  - Section rhythm is a uniform 192px (`lg:gap-48`); the frame runs 189-200
 *    between blocks and 251 before the CTA.
 */
const props = defineProps<{
  sections: SectionMap
  team: TeamMemberItem[]
  seo: SeoMeta
}>()

const page = usePage<SharedProps>()

const hero = computed<PageSectionData | undefined>(() => props.sections.about_hero)
const story = computed<PageSectionData | undefined>(() => props.sections.story)
const think = computed<PageSectionData | undefined>(() => props.sections.how_we_think)
const teamSection = computed<PageSectionData | undefined>(() => props.sections.team)
const finalCta = computed<PageSectionData | undefined>(() => props.sections.final_cta)

/** The word is Persian; en/ar get the headline as text. */
const showHeroScript = computed(() => page.props.locale.current === 'fa')

/*
 | Streamline Freehand glyphs, one per principle card (598:5713, 598:5718,
 | 593:5966, 591:5940). An item's `icon` selects one by name; the seeded items
 | carry no icon, so the frame's own order is the fallback and the section
 | still renders exactly as designed.
 */
const ICONS: Record<string, string> = {
  path: pathIconUrl,
  impact: impactIconUrl,
  identity: identityIconUrl,
  discovery: discoveryIconUrl,
}

/*
 | Reading order, not canvas order: the frame lays the row out left to right as
 | path / impact / identity / discovery, which under RTL is read last-to-first.
 | The seeded items run in reading order, so the fallback does too.
 */
const ICON_ORDER = [discoveryIconUrl, identityIconUrl, impactIconUrl, pathIconUrl]

function iconFor(item: SectionItem, index: number): string | undefined {
  if (item.icon && ICONS[item.icon]) {
    return ICONS[item.icon]
  }

  return ICON_ORDER[index]
}

/*
 | Team carousel: an endless marquee the visitor can also drag. The track holds
 | two copies of the list, so its position `pos` wraps within one copy's width
 | and the seam never shows. Auto-scroll runs one copy per 40s and pauses on
 | hover, while dragging, and entirely under reduced motion — dragging itself
 | still works there, since it only moves when the visitor moves it.
 */
const TEAM_LOOP_SECONDS = 40

const teamTrack = ref<HTMLElement | null>(null)
const teamList = ref<HTMLElement[]>([])
const teamDragging = ref(false)

let teamPos = 0
let teamLoopWidth = 0
let teamHovered = false
let teamDragX = 0
let teamPointerId: number | null = null
let teamFrame = 0
let teamLastTime = 0
let teamResize: ResizeObserver | null = null

/** RTL tracks travel right, LTR tracks travel left. */
function teamDirection(): 1 | -1 {
  return teamTrack.value && getComputedStyle(teamTrack.value).direction === 'rtl' ? 1 : -1
}

function renderTeamTrack(): void {
  if (!teamTrack.value) {
    return
  }

  if (teamLoopWidth > 0) {
    teamPos = ((teamPos % teamLoopWidth) + teamLoopWidth) % teamLoopWidth
  }

  teamTrack.value.style.transform = `translate3d(${teamDirection() * teamPos}px, 0, 0)`
}

function tickTeam(time: number): void {
  // Clamp so a backgrounded tab doesn't jump the row on return.
  const dt = teamLastTime ? Math.min(time - teamLastTime, 100) : 0
  teamLastTime = time

  if (!teamHovered && !teamDragging.value && teamLoopWidth > 0) {
    teamPos += (teamLoopWidth / TEAM_LOOP_SECONDS) * (dt / 1000)
    renderTeamTrack()
  }

  teamFrame = requestAnimationFrame(tickTeam)
}

/** Only a resting mouse pauses the row; a touch "hover" is just the drag. */
function onTeamHover(event: PointerEvent, hovered: boolean): void {
  if (event.pointerType === 'mouse') {
    teamHovered = hovered
  }
}

function onTeamPointerDown(event: PointerEvent): void {
  if (props.team.length < 2 || (event.pointerType === 'mouse' && event.button !== 0)) {
    return
  }

  teamPointerId = event.pointerId
  teamDragX = event.clientX
  teamDragging.value = true
  ;(event.currentTarget as HTMLElement).setPointerCapture(event.pointerId)
}

function onTeamPointerMove(event: PointerEvent): void {
  if (event.pointerId !== teamPointerId) {
    return
  }

  const dx = event.clientX - teamDragX
  teamDragX = event.clientX
  teamPos += teamDirection() * dx
  renderTeamTrack()
}

function onTeamPointerUp(event: PointerEvent): void {
  if (event.pointerId !== teamPointerId) {
    return
  }

  teamPointerId = null
  teamDragging.value = false
}

onMounted(() => {
  const first = teamList.value[0]

  if (!first || props.team.length < 2) {
    return
  }

  teamLoopWidth = first.offsetWidth
  teamResize = new ResizeObserver(() => {
    teamLoopWidth = first.offsetWidth
    renderTeamTrack()
  })
  teamResize.observe(first)

  if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    teamFrame = requestAnimationFrame(tickTeam)
  }
})

onBeforeUnmount(() => {
  cancelAnimationFrame(teamFrame)
  teamResize?.disconnect()
})
</script>

<template>
  <SeoHead :seo="props.seo" />

  <!--
    336:5623 puts the first content (the 696x522 portrait, 343:9292) at y=250,
    not the 180 that `section-first` gives every other page. The frame is
    absolutely positioned, so this is the frame's own offset rather than a
    shared rhythm.
  -->
  <!--
    336:5623 puts the footer at 3519.24 and the cream CTA (577:10889) at 2859;
    the slack below the CTA is 147.24.
  -->
  <div class="about-page section-first md:pt-[250px] pb-[147.24px]">
    <div class="container-sizdah flex flex-col gap-24 lg:gap-48">
      <!--
        Hero — 336:5703 / 343:9292. Copy column (466) at the inline start with
        the 696px illustration opposite; below `lg` they stack, copy first.
      -->
      <section
        v-if="hero"
        class="about-hero relative isolate grid items-center gap-12 lg:grid-cols-[minmax(0,466px)_minmax(0,1fr)] lg:gap-[86px]"
      >
        <div class="flex flex-col items-start gap-6 text-start" data-reveal>
          <h1 class="flex flex-wrap items-center gap-x-2 text-hero-line font-bold text-ink-50">
            <span>{{ hero.title }}</span>
            <img
              v-if="showHeroScript"
              :src="heroScriptUrl"
              alt=""
              aria-hidden="true"
              width="128"
              height="46"
              class="inline-block h-[0.96em] w-auto"
            />
            <span v-if="hero.subtitle">{{ hero.subtitle }}</span>
          </h1>

          <p v-if="hero.content" class="text-heading-md text-brand-200 max-md:text-[26px] max-md:leading-[36px]">
            {{ hero.content }}
          </p>

          <p v-if="hero.description" class="text-title-sm text-ink-200">
            {{ hero.description }}
          </p>
        </div>

        <img
          v-if="hero.image"
          :src="hero.image.src"
          :srcset="hero.image.srcset"
          :alt="hero.image.alt"
          :width="hero.image.width"
          :height="hero.image.height"
          class="w-full max-md:mx-auto max-md:w-[348px]"
        />
        <img
          v-else
          :src="heroIllustrationUrl"
          alt=""
          aria-hidden="true"
          width="696"
          height="522"
          class="w-full max-md:mx-auto max-md:w-[348px]"
        />
      </section>

      <!--
        Story — 357:9335. Centred 824px column over the decorative hairline
        mesh (359:9560, 109x118 cells) with the freehand mark at 583:5905.
      -->
      <section v-if="story" class="about-story relative isolate">
        <img
          :src="storyMarkUrl"
          alt=""
          aria-hidden="true"
          width="144"
          height="144"
          class="pointer-events-none absolute inline-end-[24%] block-start-[-52px] -z-10 hidden size-36 lg:block"
        />

        <div class="mx-auto flex max-w-[824px] flex-col items-center gap-10 text-center lg:gap-20">
          <div class="about-story-heading flex flex-row-reverse items-center gap-2">
            <img :src="figmaStoryIconUrl" alt="" aria-hidden="true" class="size-14" />
            <h2 class="text-section-line text-brand-50">{{ story.title }}</h2>
          </div>

          <div class="flex flex-col gap-6">
            <p v-if="story.description" class="text-title-lg text-ink-200">
              {{ story.description }}
            </p>

            <!-- eslint-disable-next-line vue/no-v-html -- admin-authored rich text -->
            <div v-if="story.content" class="rich-prose" v-html="story.content" />
          </div>
        </div>
      </section>

      <!-- How we think — 411:7908. Header row, then four principle cards. -->
      <section v-if="think" class="about-think flex flex-col gap-10 lg:gap-20">
        <div
          class="flex flex-col items-start gap-4 text-start md:flex-row md:items-center md:justify-between md:gap-16"
        >
          <h2 class="text-section-line text-brand-50">{{ think.title }}</h2>

          <p v-if="think.description" class="max-w-[506px] text-title-md text-ink-200">
            {{ think.description }}
          </p>
        </div>

        <ul
          v-if="think.items.length"
          class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4"
          data-reveal-group
        >
          <!--
            406:7225 and siblings: a raking brand glow over a 20% black wash,
            3px ink-200 rule, radiusLG. The glow runs corner to corner (-45deg
            from the node's gradient handles) and the layer itself sits at 80%.
          -->
          <li
            v-for="(item, index) in think.items"
            :key="item.id"
            class="sketch-frame flex flex-col items-start gap-10 px-6 py-14 text-start"
            style="
              background-image:
                linear-gradient(-45deg, rgb(248 185 55 / 0%) 0%, rgb(248 185 55 / 8%) 100%),
                linear-gradient(0deg, rgb(0 0 0 / 20%), rgb(0 0 0 / 20%));
            "
          >
            <img
              v-if="iconFor(item, index)"
              :src="iconFor(item, index)"
              alt=""
              aria-hidden="true"
              width="40"
              height="40"
              class="size-10 shrink-0"
            />

            <div class="flex flex-col gap-6">
              <h3 class="text-heading-sm text-brand-50">
                <span class="max-md:hidden">{{ item.title }}</span>
                <span class="hidden max-md:inline">شناخت، قبل از اجرا</span>
              </h3>
              <p v-if="item.description" class="text-title-sm text-ink-300">
                <span class="max-md:hidden">{{ item.description }}</span>
                <span class="hidden max-md:inline">اول مسئله و مخاطب را می‌فهمیم، بعد وارد اجرا می‌شویم</span>
              </p>
            </div>
          </li>
        </ul>
      </section>

      <!-- Team — 411:7906. Header row, then the member cards (598:5732). -->
      <section v-if="props.team.length" class="about-team flex flex-col gap-10 lg:gap-20">
        <div
          class="flex flex-col items-start gap-4 text-start md:flex-row md:items-center md:justify-between md:gap-16"
        >
          <h2 v-if="teamSection?.title" class="text-section-line text-brand-50">
            {{ teamSection.title }}
          </h2>

          <p v-if="teamSection?.description" class="max-w-[506px] text-title-md text-ink-200">
            {{ teamSection.description }}
          </p>
        </div>

        <div
          class="team-carousel w-full select-none overflow-hidden"
          :class="{ 'is-draggable': props.team.length > 1, 'is-dragging': teamDragging }"
          data-reveal
          @pointerenter="onTeamHover($event, true)"
          @pointerleave="onTeamHover($event, false)"
          @pointerdown="onTeamPointerDown"
          @pointermove="onTeamPointerMove"
          @pointerup="onTeamPointerUp"
          @pointercancel="onTeamPointerUp"
        >
          <div ref="teamTrack" class="team-carousel-track flex w-max">
            <ul
              v-for="copyIndex in props.team.length > 1 ? 2 : 1"
              :key="copyIndex"
              ref="teamList"
              :aria-hidden="copyIndex === 2 ? 'true' : undefined"
              class="team-carousel-list flex shrink-0 gap-[26px] pe-[26px]"
            >
              <!--
                No radius utility here: .sketch-frame's own 24px must win, or a
                tighter clip lets the hover fill poke out past the drawn corners.
              -->
              <li
                v-for="member in props.team"
                :key="member.name"
                class="sketch-frame group flex w-[220px] shrink-0 flex-col overflow-hidden bg-brand-200 transition-colors duration-200 ease-brand hover:bg-brand"
              >
                <img
                  v-if="member.image"
                  :src="member.image.src"
                  :srcset="member.image.srcset"
                  :alt="copyIndex === 2 ? '' : member.image.alt"
                  :width="member.image.width"
                  :height="member.image.height"
                  loading="lazy"
                  draggable="false"
                  class="aspect-square w-full rounded-lg object-cover object-bottom grayscale transition-[filter] duration-300 ease-brand group-hover:grayscale-0"
                />
                <div v-else class="aspect-square w-full rounded-lg bg-warm-200" aria-hidden="true" />

                <div class="flex flex-col gap-1 p-4 text-start">
                  <p class="text-heading-sm text-warm-900 group-hover:text-ink-1000">
                    {{ member.name }}
                  </p>
                  <p class="text-title-md text-ink-700 group-hover:text-ink-800">
                    {{ member.role }}
                  </p>
                </div>
              </li>
            </ul>
          </div>
        </div>
        <div class="about-mobile-team-cards hidden max-md:flex">
          <div v-for="member in [{ name: 'محمد', role: 'گرافیک دیزاینر', image: figmaMohammadUrl }, { name: 'امین', role: 'مدیرعامل', image: figmaAminUrl }]" :key="member.name" class="h-[247px] w-[173px] shrink-0 rounded-xl border-[3px] border-brand-100 bg-brand-100 p-0 text-right">
            <img :src="member.image" alt="" class="h-[164px] w-full rounded-xl object-cover" />
            <div class="p-3 text-ink-900"><p class="text-[18px]">{{ member.name }}</p><p class="text-[14px]">{{ member.role }}</p></div>
          </div>
        </div>
      </section>

      <!--
        Final CTA — 577:10889. Same card as every other page's final CTA
        (services and the article page included, as of GAPS G34 — the dark
        `FinalCtaCard` they used to use is now deleted). The same card is
        duplicated onto projects (577:9134), hence the component.
      -->
      <StartTogetherCard v-if="finalCta" :section="finalCta" class="about-cta" />
    </div>
  </div>
</template>

<style scoped>
.team-carousel-track {
  will-change: transform;
}

@media (max-width: 767px) {
  .about-page {
    min-height: 3689px;
    padding-top: 0;
    padding-bottom: 0;
  }

  .about-page > .container-sizdah {
    position: relative;
    height: 3689px;
    gap: 0;
  }

  .about-hero,
  .about-story,
  .about-think,
  .about-team,
  .about-cta {
    position: absolute;
    inset-inline: 0;
  }

  .about-hero {
    top: 132px;
    display: block;
    height: 534px;
  }

  .about-hero > div:first-child {
    position: absolute;
    inset-inline: 0;
    top: 15px;
    gap: 33px;
  }

  .about-hero > img {
    position: absolute;
    top: 264px;
    inset-inline: 7px;
    width: 348px;
    height: 261px;
    object-fit: cover;
  }

  .about-story {
    top: 737px;
    display: block;
    height: 430px;
  }

  .about-story > div {
    gap: 24px;
  }

  .about-story .rich-prose {
    font-size: 18px;
    line-height: normal;
  }

  .about-think {
    top: 1123px;
    display: block;
    height: 1110px;
  }

  .about-think > div:first-child {
    position: absolute;
    top: 132px;
    inset-inline: 0;
  }

  .about-think > ul {
    position: absolute;
    top: 268px;
    inset-inline: 0;
    display: block;
    height: 896px;
  }

  .about-think > ul > li {
    position: absolute;
    inset-inline: 0;
    width: 362px;
    height: 208px;
    padding: 32px 16px;
    gap: 24px;
  }

  .about-think > ul > li > img {
    width: 32px;
    height: 32px;
  }

  .about-think > ul > li h3 {
    font-size: 20px;
    line-height: normal;
  }

  .about-think > ul > li p {
    font-size: 16px;
    line-height: normal;
  }

  .about-think > ul > li:nth-child(1) { top: 0; }
  .about-think > ul > li:nth-child(2) { top: 224px; }
  .about-think > ul > li:nth-child(3) { top: 448px; }
  .about-think > ul > li:nth-child(4) { top: 672px; }

  .about-team {
    top: 2359px;
    display: block;
    height: 400px;
  }

  .about-team > div:first-child {
    position: absolute;
    top: 0;
    inset-inline: 0;
  }

  .about-team .team-carousel {
    position: absolute;
    top: 130px;
    inset-inline: 0;
    display: none;
  }

  .about-mobile-team-cards {
    position: absolute;
    top: 130px;
    inset-inline: 0;
    gap: 16px;
    direction: ltr;
    transform: translateX(20px);
  }

  .about-team .team-carousel-list {
    gap: 16px;
    padding-inline-end: 16px;
  }

  .about-team .team-carousel-list > li {
    width: 173px;
  }

  .about-team .team-carousel-list > li img {
    width: 164px;
    height: 164px;
  }

  .about-cta {
    top: 2824px;
    inset-inline: 20px;
    padding: 32px 16px;
  }

  .about-cta::after,
  .about-cta > .grid-mesh {
    display: none;
  }

  .about-cta > div.relative {
    align-items: center;
    gap: 32px;
  }

  .about-cta > div.relative > div {
    align-items: center;
    gap: 32px;
    text-align: center;
  }

  .about-cta > div.relative > div > div,
  .about-cta > div.relative > div > div > div {
    align-items: center;
  }

  .about-cta > div.relative > div > div > div {
    text-align: center;
  }

  .about-cta a {
    align-self: flex-end;
    width: 100px;
    padding: 12px 0;
    font-size: 16px;
    line-height: 22px;
  }

  .about-cta a > img {
    display: none;
  }
}

/* Vertical page scroll stays native on touch; horizontal swipes drag the row. */
.team-carousel.is-draggable {
  cursor: grab;
  touch-action: pan-y;
}

.team-carousel.is-dragging {
  cursor: grabbing;
}
</style>
