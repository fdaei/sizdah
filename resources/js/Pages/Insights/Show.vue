<script setup lang="ts">
import { computed } from 'vue'
import BlogCard from '@/Components/BlogCard.vue'
import ArticleMeta from '@/Components/ArticleMeta.vue'
import ArticleShare from '@/Components/ArticleShare.vue'
import LeadMagnetBanner from '@/Components/LeadMagnetBanner.vue'
import SeoHead from '@/Components/SeoHead.vue'
import StartTogetherCard from '@/Components/StartTogetherCard.vue'
import { useTranslations } from '@/Composables/useTranslations'
import type { PageSectionData, PostDetail, SeoMeta } from '@/types'
import relatedRuleUrl from '~img/sizdah/insights/related-rule.svg'

/**
 * Article — Figma 285:4590 (the frame is named "blog list"; its content is a
 * single post, so it maps to insights.show).
 *
 * Order: centred title + meta chips, 1248x624 cover, prose body, the lead-magnet
 * strip, related posts, final CTA.
 *
 * The frame interleaves the lead magnet partway down the body (303:4455 sits at
 * y=792 inside the copy column). Body copy is a single rich-text field, so it
 * cannot be split at an arbitrary point without parsing HTML — the strip is
 * placed after the body instead. Recorded in FIGMA/state.json.
 *
 * Final CTA — the frame's closing card (577:9836, "Frame 96467") is the same
 * cream "شروع همکاری" card every other page ends on, confirmed by screenshot
 * against 577:10889 (About). This page used the dark `FinalCtaCard` until
 * 2026-08-24, on the belief that the frame still drew the old Sahra card at
 * 294:7672 — that node no longer exists in the file. See GAPS G34.
 *
 * Share rail (690:7022, "Frame 95907") floats in the container's inline-start
 * margin, 33px in from its edge, beside a prose column that is centred in the
 * 1248 track. Until 2026-09-09 it shared a centred flex row with the prose,
 * which is not the same thing: the row centred rail+prose as one unit and so
 * pushed the copy 75px off the centre line the frame draws it on. See the
 * comment on the block itself for the width it needs to float at.
 *
 * Geometry re-measured against the live frame 2026-09-09. Everything on this
 * page was laid out on a single 48px inter-block gap; the frame runs seven
 * different ones. The body scale was a step too large as well — 20/500
 * against the frame's 18/400 — which `.rich-prose-article` now corrects
 * without disturbing the legal pages `.rich-prose` was tuned for.
 */
const props = defineProps<{
  post: PostDetail
  leadMagnet: PageSectionData | null
  finalCta: PageSectionData | null
  seo: SeoMeta
}>()

const { t } = useTranslations()

// The article editor exposes [[lead_magnet]] as an insertion marker. Keep the
// authored rich text intact while replacing that marker with the actual
// interactive banner at render time.
const articleContent = computed(() => {
  const marker = '[[lead_magnet]]'
  const content = props.post.content
  const index = content.indexOf(marker)

  if (index < 0) {
    return { before: content, after: '', hasMarker: false }
  }

  return {
    before: content.slice(0, index),
    after: content.slice(index + marker.length),
    hasMarker: true,
  }
})
</script>

<template>
  <SeoHead :seo="props.seo" />

  <!--
    Vertical rhythm, all measured off the frame (285:4590) rather than the
    even gap-12 this used to run on: the blocks are 88 / 64 / 113 / 72 / 81 /
    224 / 123 apart, so each one carries its own margin instead.
    Content starts at y=207 — 99px clear of the 108px fixed header, not the
    72px `section-first` assumes for the other pages.
  -->
  <article class="section-first pb-[123px] md:pt-[207px]">
    <div class="container-sizdah relative isolate flex flex-col">
      <!-- 299:7865 — the shared 109px mesh, 872 tall, from frame y=202,
           i.e. 5px above the content column's own top edge. -->
      <div
        class="grid-mesh pointer-events-none absolute inline-start-0 block-start-[-5px] -z-10 hidden h-[872px] w-full max-w-container lg:block"
        style="--mesh-cell-x: 109.095px; --mesh-cell-y: 109.095px"
        aria-hidden="true"
      />

      <!--
        285:5060 — a 670 track wrapping the 612 column (285:5061) whose title
        block (285:5065) is gap 24. The headline is Display/Medium (40/700).

        The meta row (292:7554) is a SIBLING of that track, not a child of it,
        so the gap between them is the frame's own 465-377 = 88 — the 64 this
        carried came from reading 285:5061's internal gap instead.
      -->
      <header class="mx-auto flex max-w-[670px] flex-col items-center gap-[88px]">
        <div class="flex w-full max-w-measure flex-col items-center gap-6 text-center">
          <h1 class="text-display-md text-ink-50">{{ props.post.title }}</h1>
          <p v-if="props.post.subtitle" class="text-title-sm text-ink-200">
            {{ props.post.subtitle }}
          </p>
        </div>

        <ArticleMeta :post="props.post" />
      </header>

      <img
        v-if="props.post.image"
        :src="props.post.image.src"
        :srcset="props.post.image.srcset"
        :alt="props.post.image.alt"
        :width="props.post.image.width"
        :height="props.post.image.height"
        class="mt-16 aspect-[1248/624] w-full rounded-lg object-cover"
      />

      <!--
        294:7566 is simply centred in the 1248 track (307..1138 of 96..1344),
        and 690:7022 floats in the margin beside it at x=1192 — 33px in from
        the track's inline-start edge. The rail used to share a centred flex
        row with the prose, which dragged the copy 75px off centre by half the
        rail's own width; it is positioned out of flow instead. `sticky`
        inside the full-height wrapper keeps it usable down a long article,
        which the static frame can't itself demonstrate.

        1336px+ only, not the xl (1280) this used to gate on: the rail runs
        from 33 to 152 inside the track, so it needs the centred column's
        margin to be at least 152 wide, and at 1280 that margin is 128 — the
        rail would sit on top of the first words of every line.
      -->
      <div class="relative mx-auto mt-[113px] w-full max-w-container">
        <div
          class="pointer-events-none absolute inset-y-0 inline-start-[33px] hidden min-[1336px]:block"
        >
          <ArticleShare
            :url="props.seo.canonical"
            :title="props.post.title"
            class="pointer-events-auto sticky top-32"
          />
        </div>

        <div class="mx-auto w-full max-w-[831px]">
          <!-- eslint-disable-next-line vue/no-v-html -- admin-authored rich text -->
          <div class="rich-prose rich-prose-article" v-html="articleContent.before" />
        </div>
      </div>

      <!--
        303:4455 is 826 wide against the 831 prose column, i.e. the same
        column, not the 1248 track this used to be stretched across. The
        wrapper is what centres it: `LeadMagnetBanner` renders the strip and
        its modal as two roots, so a class passed to the component itself is
        dropped rather than inherited.
      -->
      <div
        v-if="props.leadMagnet && articleContent.hasMarker"
        class="mx-auto mt-[72px] w-full max-w-[826px]"
      >
        <LeadMagnetBanner :section="props.leadMagnet" source="article" />
      </div>

      <div
        v-if="articleContent.hasMarker && articleContent.after"
        class="mx-auto mt-[72px] w-full max-w-[831px]"
      >
        <!-- eslint-disable-next-line vue/no-v-html -- admin-authored rich text -->
        <div class="rich-prose rich-prose-article" v-html="articleContent.after" />
      </div>

      <div
        v-if="props.leadMagnet && !articleContent.hasMarker"
        class="mx-auto mt-[72px] w-full max-w-[826px]"
      >
        <LeadMagnetBanner :section="props.leadMagnet" source="article" />
      </div>

      <section v-if="props.post.related.length" class="mt-[81px] flex flex-col gap-12">
        <h2 class="relative w-fit text-display-sm font-semibold text-ink-50">
          {{ t('blog.related') }}
          <!-- 294:7700 — a hand-drawn rule under the heading. -->
          <img
            :src="relatedRuleUrl"
            alt=""
            aria-hidden="true"
            width="188"
            height="5"
            class="pointer-events-none absolute inline-end-0 top-full mt-1 hidden w-32 sm:block"
          />
        </h2>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <BlogCard v-for="related in props.post.related" :key="related.slug" :post="related" />
        </div>
      </section>

      <StartTogetherCard v-if="props.finalCta" :section="props.finalCta" class="mt-[224px]" />
    </div>
  </article>
</template>
