<script setup lang="ts">
import { onBeforeUnmount, onMounted, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import SectionHeading from '@/Components/SectionHeading.vue'
import type { PageSectionData, ProjectSummary } from '@/types'
import arrowUrl from '~img/sizdah/shared/cta-arrow.svg'
import underlineUrl from '~img/sizdah/shared/nav-underline.svg'
import sidewalkProjectUrl from '~img/sizdah/home/sidewalk-project.jpeg'
import projectCategoryUrl from '~img/sizdah/home/project-category.svg'
import { headingColors } from '@/lib/sectionColors'
import { useTranslations } from '@/Composables/useTranslations'

/**
 * Projects showcase — Figma 268:3461 (heading), 268:3539 (530x663 image) and
 * 268:3540 (the 615x565 row stack).
 *
 * The frame draws the stack as a "project cards" component set with an `open`
 * and a `close` state (138:2356 / 138:2358): one row shows its title at 40px
 * over a gold rule with the industry chip and summary beneath, the rest sit
 * collapsed at 32px. That is a disclosure, so it is built as one here — the
 * open row follows the pointer/focus and the 530px image cross-fades to match.
 *
 * The expand uses `grid-template-rows: 0fr -> 1fr` rather than max-height, so
 * the row opens to exactly its content height at any copy length.
 *
 * DEVIATION: the frame sets the open row's "مشاهده جزئیات پروژه" link in
 * Black/1000 (#141414) — near-black on the near-black page ground, i.e.
 * invisible. Read as a slip; it takes the brand colour so the link can be seen
 * and used. The label is "جزییات پروژه" (per review) unless the section's
 * `content` field overrides it — never the project title, which already sits
 * beside it.
 *
 * The heading's split grid is re-cut at `lg` to the same 615/530 columns as
 * the rows and image below, so the subtitle lines up with the image's edge.
 */
const props = defineProps<{
  section: PageSectionData
  projects: ProjectSummary[]
}>()

const { t } = useTranslations()

const active = ref(0)
const touchStartX = ref<number | null>(null)
const AUTO_ADVANCE_MS = 5000
let autoAdvanceTimer: number | null = null

function startSwipe(event: TouchEvent) {
  touchStartX.value = event.touches[0]?.clientX ?? null
}

function finishSwipe(event: TouchEvent) {
  if (touchStartX.value === null) return

  const startX = touchStartX.value
  const endX = event.changedTouches[0]?.clientX
  touchStartX.value = null

  if (startX === null || endX === undefined) return

  const distance = endX - startX

  if (Math.abs(distance) < 40 || props.projects.length < 2) return

  const direction = distance > 0 ? -1 : 1
  active.value = (active.value + direction + props.projects.length) % props.projects.length
}

function advanceProject() {
  if (props.projects.length < 2 || document.visibilityState === 'hidden') return

  active.value = (active.value + 1) % props.projects.length
}

onMounted(() => {
  if (props.projects.length < 2 || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return

  autoAdvanceTimer = window.setInterval(advanceProject, AUTO_ADVANCE_MS)
})

onBeforeUnmount(() => {
  if (autoAdvanceTimer !== null) window.clearInterval(autoAdvanceTimer)
})
</script>

<template>
  <section class="max-md:bg-transparent max-md:bg-none py-12 max-md:pb-[22px] md:py-24">
    <div class="container-sizdah">
      <SectionHeading
        data-reveal
        :eyebrow="props.section.eyebrow"
        :title="props.section.title"
        :subtitle="props.section.subtitle || props.section.description"
        :colors="headingColors(props.section)"
        split-class="lg:grid-cols-[minmax(0,615px)_minmax(0,530px)] lg:justify-between lg:gap-x-16"
      />

      <div
        class="mt-8 grid gap-8 max-md:mt-[74px] max-md:gap-6 md:mt-12 md:gap-10 lg:mt-16 lg:grid-cols-[minmax(0,615px)_minmax(0,530px)] lg:justify-between lg:gap-16"
      >
        <!--
          Rows lead in DOM so RTL puts them at the inline start (right), which
          is where 268:3540 sits; the image takes the opposite column. On mobile
          the image is pulled above the list instead.
        -->
        <ul class="hidden flex-col justify-between gap-8 lg:flex" data-reveal-group>
          <li v-for="(project, index) in props.projects" :key="project.slug">
            <div class="flex flex-col gap-4" @click="active = index" @mouseenter="active = index" @focusin="active = index">
              <div class="flex items-center justify-between gap-4">
                <div class="flex flex-col items-start">
                  <Link
                    :href="project.url"
                    class="transition-colors duration-300 ease-brand"
                    :class="
                      active === index
                        ? 'text-section-line font-semibold text-paper'
                        : 'text-heading-xl text-ink-300 hover:text-paper'
                    "
                  >
                    {{ project.title }}
                  </Link>

                  <!-- 138:2333 — the hand-inked gold rule under the open title only. -->
                  <span
                    aria-hidden="true"
                    class="relative mt-1 block h-[5px] w-full transition-opacity duration-300"
                    :class="active === index ? 'opacity-100' : 'opacity-0'"
                  >
                    <img
                      :src="underlineUrl"
                      alt=""
                      class="absolute inset-0 h-full w-full max-w-none"
                    />
                  </span>
                </div>

                <Link
                  v-show="active === index"
                  :href="project.url"
                  class="flex shrink-0 items-center gap-1 text-body-lg text-brand transition-colors hover:text-brand-600"
                >
                  <img
                    :src="arrowUrl"
                    alt=""
                    aria-hidden="true"
                    width="24"
                    height="24"
                    class="size-6 flip-rtl"
                  />
                  <span>{{ props.section.content || t('work.project_details') }}</span>
                </Link>
              </div>

              <div
                class="grid transition-[grid-template-rows] duration-500 ease-brand"
                :class="active === index ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'"
              >
                <div class="overflow-hidden">
                  <div class="flex flex-col gap-4">
                    <div v-if="project.industry" class="flex w-full items-center justify-start gap-1 text-right">
                      <img :src="projectCategoryUrl" alt="" aria-hidden="true" width="20" height="20" class="size-5" />
                      <p class="text-label-lg text-brand">{{ project.industry }}</p>
                    </div>
                    <p v-if="project.excerpt" class="text-body-lg text-ink-200">
                      {{ project.excerpt }}
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <!--
              143:2403 / 143:2484 — a rule with a 12px dot centred on it. The
              open row's rule is gold and 2px (143:2404); the collapsed ones are
              a flat 1px ink hairline (143:2485) — the frame draws them at
              different weights, not just different colours.
            -->
            <div
              v-if="index < props.projects.length - 1"
              aria-hidden="true"
              class="relative mt-8 w-full"
              :class="
                active === index
                  ? 'h-0.5 bg-gradient-to-l from-transparent via-brand to-transparent'
                  : 'h-px bg-ink-800'
              "
            >
              <span
                class="absolute inline-start-1/2 top-1/2 size-3 -translate-y-1/2 rounded-round border"
                :class="active === index ? 'border-brand bg-brand' : 'border-ink-600 bg-ink-1000'"
              />
            </div>
          </li>
        </ul>

        <div v-if="props.projects[active]" class="hidden h-[112px] flex-col gap-3 max-md:flex max-md:order-none">
          <div class="flex flex-row-reverse items-center justify-between text-right">
            <Link
              :href="props.projects[active].url"
              class="flex items-center gap-1 text-[14px] font-medium leading-[20px] text-paper"
            >
              <img :src="arrowUrl" alt="" aria-hidden="true" width="24" height="24" class="size-6 flip-rtl" />
              <span>{{ props.section.content || t('work.project_details') }}</span>
            </Link>
            <Link :href="props.projects[active].url" class="text-[16px] font-semibold leading-[20px] text-paper">
              {{ props.projects[active].title }}
            </Link>
          </div>
          <div class="flex flex-col items-start gap-2 text-right">
            <div class="flex w-full items-center justify-start gap-1">
              <img :src="projectCategoryUrl" alt="" aria-hidden="true" width="20" height="20" class="size-5" />
              <p v-if="props.projects[active].industry" class="text-[12px] font-medium leading-[15px] text-brand">
                {{ props.projects[active].industry }}
              </p>
            </div>
            <p v-if="props.projects[active].excerpt" class="line-clamp-2 w-full text-[14px] leading-[18px] text-ink-200">
              {{ props.projects[active].excerpt }}
            </p>
          </div>
        </div>

        <div class="order-first w-full max-md:order-none max-md:-translate-x-px lg:order-none">
          <div
            class="relative aspect-[530/663] w-full touch-pan-y overflow-hidden rounded-xl bg-ink-900"
            @touchstart="startSwipe"
            @touchend="finishSwipe"
          >
            <img
              v-for="(project, index) in props.projects"
              :key="project.slug"
              :src="project.image?.src ?? sidewalkProjectUrl"
              :srcset="project.image?.srcset"
              :alt="project.image?.alt ?? project.title"
              width="530"
              height="663"
              loading="eager"
              decoding="async"
              class="absolute inset-0 size-full object-cover transition-[transform,opacity] duration-500 ease-brand will-change-transform"
              :style="{ transform: `translateX(${(index - active) * 100}%)` }"
              :class="active === index ? 'opacity-100' : 'opacity-0'"
            />
          </div>

          <div class="mt-5 flex -translate-x-1 items-center justify-center gap-2 lg:hidden" aria-label="انتخاب پروژه">
            <button
              v-for="(project, index) in props.projects"
              :key="`indicator-${project.slug}`"
              type="button"
              class="size-2 rounded-full border transition-all duration-300"
              :class="active === index ? 'border-brand bg-brand' : 'border-ink-500 bg-transparent'"
              :aria-label="`نمایش ${project.title}`"
              :aria-current="active === index ? 'true' : undefined"
              @click="active = index"
            />
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
