<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import PostMeta from '@/Components/PostMeta.vue'
import { useTranslations } from '@/Composables/useTranslations'
import type { PostSummary } from '@/types'
import arrowUrl from '~img/sizdah/shared/cta-arrow.svg'

/**
 * Featured post row — Figma 270:5253 (1248x491).
 *
 * Copy column beside a 612x459 image. The category renders as a Yellow/600 pill
 * (270:5256); posts without a category simply omit it.
 *
 * The frame draws the image at the inline-end (physical right in this RTL
 * site) and the copy column at the inline-start — confirmed against the
 * screenshot, not just the layer order, since Figma's own child order doesn't
 * imply reading direction. The image markup comes first in the template so a
 * default `flex-row` places it correctly under `dir="rtl"` without a manual
 * `flex-row-reverse`. Same reasoning inside the copy column's own CTA/meta
 * row (270:5263): the frame puts "مطالعه مقاله" at that row's inline-start
 * and the date/reading-time strip at its inline-end, so `PostMeta` is first
 * in the template and the CTA link second.
 */
const props = defineProps<{ post: PostSummary }>()

const { t } = useTranslations()
</script>

<template>
  <article
    class="surface-glow flex flex-col gap-8 rounded-xl border-3 border-ink-400 p-4 lg:flex-row lg:items-center"
    style="--glow-angle: -20.03deg"
  >
    <Link
      :href="props.post.url"
      class="group block shrink-0 overflow-hidden rounded-lg border border-ink-100 shadow-card lg:w-[612px]"
    >
      <img
        v-if="props.post.image"
        :src="props.post.image.src"
        :srcset="props.post.image.srcset"
        :alt="props.post.image.alt"
        :width="props.post.image.width"
        :height="props.post.image.height"
        class="aspect-[612/459] w-full object-cover transition-transform duration-500 ease-brand group-hover:scale-105"
      />
      <div v-else class="aspect-[612/459] w-full bg-ink-900" aria-hidden="true" />
    </Link>

    <div class="flex flex-1 flex-col gap-8 lg:gap-24">
      <p
        v-if="props.post.category"
        class="w-fit rounded-round bg-brand-600 px-2 py-1 text-body-md text-ink-800"
      >
        {{ props.post.category.name }}
      </p>

      <div class="flex flex-col gap-8 lg:gap-24">
        <div class="flex flex-col gap-6">
          <h2 class="text-heading-xl text-ink-50">
            <Link
              :href="props.post.url"
              class="transition-colors duration-200 ease-brand hover:text-brand"
            >
              {{ props.post.title }}
            </Link>
          </h2>
          <p class="text-body-lg text-ink-300">{{ props.post.excerpt }}</p>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-4">
          <PostMeta :post="props.post" />

          <Link
            :href="props.post.url"
            class="flex items-center gap-2 text-body-lg text-ink-100 transition-colors duration-200 ease-brand hover:text-brand"
          >
            <img
              :src="arrowUrl"
              alt=""
              aria-hidden="true"
              width="24"
              height="24"
              class="size-6 flip-rtl"
            />
            {{ t('common.read_article') }}
          </Link>
        </div>
      </div>
    </div>
  </article>
</template>
