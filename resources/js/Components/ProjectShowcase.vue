<script setup lang="ts">
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import SectionHeading from '@/Components/SectionHeading.vue'
import type { PageSectionData, ProjectSummary } from '@/types'
import industryUrl from '~img/sizdah/home/project-industry.svg'
import arrowUrl from '~img/sizdah/shared/cta-arrow.svg'

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
 * and used.
 */
const props = defineProps<{
  section: PageSectionData
  projects: ProjectSummary[]
}>()

const active = ref(0)
</script>

<template>
  <section class="section">
    <div class="container-sizdah">
      <SectionHeading
        data-reveal
        :eyebrow="props.section.eyebrow"
        :title="props.section.title"
        :subtitle="props.section.subtitle || props.section.description"
      />

      <div
        class="mt-12 grid gap-10 lg:mt-16 lg:grid-cols-[minmax(0,615px)_minmax(0,530px)] lg:justify-between lg:gap-16"
      >
        <!--
          Rows lead in DOM so RTL puts them at the inline start (right), which
          is where 268:3540 sits; the image takes the opposite column. On mobile
          the image is pulled above the list instead.
        -->
        <ul class="flex flex-col justify-between gap-8" data-reveal-group>
          <li v-for="(project, index) in props.projects" :key="project.slug">
            <div class="flex flex-col gap-4" @mouseenter="active = index" @focusin="active = index">
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

                  <!-- 138:2333 — the 2px gold rule under the open title only. -->
                  <span
                    aria-hidden="true"
                    class="mt-1 block h-0.5 w-full bg-gradient-to-l from-brand to-transparent transition-opacity duration-300"
                    :class="active === index ? 'opacity-100' : 'opacity-0'"
                  />
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
                  <span>{{ props.section.content || project.title }}</span>
                </Link>
              </div>

              <div
                class="grid transition-[grid-template-rows] duration-500 ease-brand"
                :class="active === index ? 'grid-rows-[1fr]' : 'grid-rows-[0fr]'"
              >
                <div class="overflow-hidden">
                  <div class="flex flex-col gap-4">
                    <p
                      v-if="project.industry"
                      class="flex items-center gap-2 text-label-lg text-ink-300"
                    >
                      <img
                        :src="industryUrl"
                        alt=""
                        aria-hidden="true"
                        width="24"
                        height="24"
                        class="size-6"
                      />
                      <span>{{ project.industry }}</span>
                    </p>
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

        <div
          class="relative order-first aspect-[530/663] w-full overflow-hidden rounded-xl bg-ink-900 lg:order-none"
        >
          <img
            v-for="(project, index) in props.projects"
            :key="project.slug"
            :src="project.image?.src"
            :srcset="project.image?.srcset"
            :alt="project.image?.alt ?? project.title"
            width="530"
            height="663"
            loading="lazy"
            decoding="async"
            class="absolute inset-0 size-full object-cover transition-opacity duration-500 ease-brand"
            :class="active === index ? 'opacity-100' : 'opacity-0'"
          />
        </div>
      </div>
    </div>
  </section>
</template>
