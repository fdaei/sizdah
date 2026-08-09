<script setup lang="ts">
import BlogCard from '@/Components/BlogCard.vue'
import ArticleMeta from '@/Components/ArticleMeta.vue'
import FinalCtaCard from '@/Components/FinalCtaCard.vue'
import LeadMagnetBanner from '@/Components/LeadMagnetBanner.vue'
import SeoHead from '@/Components/SeoHead.vue'
import { useTranslations } from '@/Composables/useTranslations'
import type { PageSectionData, PostDetail, SeoMeta } from '@/types'

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
    <div class="container-sizdah flex flex-col gap-12">
      <header class="flex flex-col items-center gap-8">
        <div class="flex max-w-[670px] flex-col items-center gap-6 text-center">
          <h1 class="text-display-sm text-ink-50">{{ props.post.title }}</h1>
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
        <h2 class="text-display-sm text-ink-50">{{ t('blog.related') }}</h2>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <BlogCard v-for="related in props.post.related" :key="related.slug" :post="related" />
        </div>
      </section>

      <FinalCtaCard v-if="props.finalCta" :section="props.finalCta" class="mt-12" />
    </div>
  </article>
</template>
