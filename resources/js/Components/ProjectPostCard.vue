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
 *
 * UNIFORM CARDS (2026-09-08): the Figma detail block is a FIXED 148px at both
 * widths — title 45 (one line), excerpt 40 (exactly two 20px lines), services
 * 17 (one row) — so every card in a row ends on the same baseline. Real
 * content does not cooperate: Persian project names such as "کلینیک پزشکی و
 * دندانپزشکی فخر" wrap to two lines in the 400-wide 3-up, and excerpts run one
 * or two lines, which used to shove each card's excerpt and service row to a
 * different height. Rather than truncate the titles, the card now stretches to
 * its grid cell (`h-full`) and anchors the two lower bands instead:
 *
 *   - the excerpt is a fixed two-line box (`min-h-10` + `line-clamp-2`), the
 *     40px the frame gives it, so its top edge is identical on every card;
 *   - the caption takes the slack (`flex-1` + `mt-auto` on the excerpt), so a
 *     wrapped title grows UPWARD into the gap under the image instead of
 *     pushing everything below it down;
 *   - the service row is a single non-wrapping line pinned to the bottom.
 *
 * When every title fits one line and every excerpt runs two — the frame's own
 * case — this collapses back to exactly the 40/16/24 rhythm above.
 */
const props = defineProps<{ project: ProjectSummary }>()
</script>

<template>
  <article class="group h-full">
    <Link :href="props.project.url" class="flex h-full flex-col gap-10">
      <div
        class="aspect-square shrink-0 overflow-hidden rounded-[24px] border-2 border-ink-400 shadow-card"
      >
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

      <div class="flex flex-1 flex-col gap-6">
        <div class="flex flex-1 flex-col gap-4">
          <div class="flex items-start justify-between gap-4">
            <h3 class="flex min-w-0 flex-col items-start">
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

          <!--
            226:2608 — 400x40, i.e. two 20px lines, whatever the excerpt's real
            length. Kept unconditional so a project without one still reserves
            the band and its neighbours stay aligned.
          -->
          <p class="mt-auto line-clamp-2 min-h-10 text-body-lg text-ink-100">
            {{ props.project.excerpt }}
          </p>
        </div>

        <!--
          226:2609 — a single 17px row (299 wide inside the 400 card, so three
          services fit). `flex-nowrap` keeps it one line so the card's foot
          never moves; the labels stay whole and the row clips at the inline end
          in the rare case a project carries more tags than the row can hold.
        -->
        <ul
          v-if="props.project.services.length"
          class="flex min-h-[17.5px] flex-nowrap items-center gap-2 overflow-hidden"
        >
          <li
            v-for="(service, index) in props.project.services"
            :key="service"
            class="flex shrink-0 items-center gap-2"
          >
            <span
              v-if="index > 0"
              class="size-1 shrink-0 rounded-round bg-brand"
              aria-hidden="true"
            />
            <span class="whitespace-nowrap text-body-md text-ink-100">{{ service }}</span>
          </li>
        </ul>
      </div>
    </Link>
  </article>
</template>
