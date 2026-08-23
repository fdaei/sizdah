<script setup lang="ts">
import BlogCard from '@/Components/BlogCard.vue'
import ArticleMeta from '@/Components/ArticleMeta.vue'
import FinalCtaCard from '@/Components/FinalCtaCard.vue'
import LeadMagnetBanner from '@/Components/LeadMagnetBanner.vue'
import SeoHead from '@/Components/SeoHead.vue'
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
 */
const props = defineProps<{
  post: PostDetail
  leadMagnet: PageSectionData | null
  finalCta: PageSectionData | null
  seo: SeoMeta
}>()

const { t } = useTranslations()
</script>

<template>
  <SeoHead :seo="props.seo" />

  <article class="section-first pb-24">
    <div class="container-sizdah relative isolate flex flex-col gap-12">
      <!-- 299:7865 — the shared 109px mesh, 872 tall, from frame y=202. -->
      <div
        class="grid-mesh pointer-events-none absolute inset-inline-start-0 inset-block-start-[22px] -z-10 hidden h-[872px] w-full max-w-container lg:block"
        style="--mesh-cell-x: 109.095px; --mesh-cell-y: 109.095px"
        aria-hidden="true"
      />

      <!--
        285:5060 — a 670 track on gap 64, wrapping the 612 column (285:5061)
        whose title block (285:5065) is gap 24. The headline is Display/Medium
        (40/700), not the 36 this carried.
      -->
      <header class="mx-auto flex max-w-[670px] flex-col items-center gap-16">
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
        class="aspect-[1248/624] w-full rounded-lg object-cover"
      />

      <!-- eslint-disable-next-line vue/no-v-html -- admin-authored rich text -->
      <div class="rich-prose mx-auto w-full max-w-[831px]" v-html="props.post.content" />

      <LeadMagnetBanner
        v-if="props.leadMagnet"
        :section="props.leadMagnet"
        class="mx-auto w-full max-w-[831px]"
      />

      <section v-if="props.post.related.length" class="mt-12 flex flex-col gap-6">
        <h2 class="relative w-fit text-display-sm text-ink-50">
          {{ t('blog.related') }}
          <!-- 294:7700 — a hand-drawn rule under the heading. -->
          <img
            :src="relatedRuleUrl"
            alt=""
            aria-hidden="true"
            width="188"
            height="5"
            class="pointer-events-none absolute inset-inline-end-0 top-full mt-1 hidden w-32 sm:block"
          />
        </h2>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <BlogCard v-for="related in props.post.related" :key="related.slug" :post="related" />
        </div>
      </section>

      <FinalCtaCard v-if="props.finalCta" :section="props.finalCta" class="mt-12" />
    </div>
  </article>
</template>
