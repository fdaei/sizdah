<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import PostMeta from '@/Components/PostMeta.vue'
import { useTranslations } from '@/Composables/useTranslations'
import type { PostSummary } from '@/types'

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
    class="surface-glow sketch-frame flex flex-col gap-8 p-4 lg:flex-row lg:items-center"
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
            {{ t('common.read_article') }}
            <svg
              aria-hidden="true"
              focusable="false"
              width="32"
              height="32"
              viewBox="0 0 24 24"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
              class="size-8"
            >
              <path
                d="M7.32131 13.3255C7.14474 11.9476 7.11965 11.0563 6.74489 9.27071C6.77457 9.26219 8.41058 11.0239 8.42029 11.0343C10.1043 12.825 10.3523 13.1304 12.0632 14.8949C13.208 16.0756 14.444 17.4 15.5198 18.4359C16.5327 19.4112 17.0675 19.7174 17.6117 19.5478C19.0394 19.1031 17.3031 17.4361 16.8822 16.9452C16.143 16.083 15.3405 15.2825 14.5717 14.448C13.5459 13.3346 12.5055 12.2347 11.4507 11.1487C11.2006 10.8913 9.76502 9.46905 8.71327 8.4698C10.0957 8.47707 11.3546 8.50634 11.9254 8.49913C12.7961 8.4881 13.4024 8.5873 14.2841 8.36686C15.2594 8.12302 15.1933 6.47067 14.4384 6.49307C13.3252 6.52614 12.1911 6.58145 10.9962 6.52132C9.47161 6.4446 7.94381 6.24591 6.49246 5.75562C4.63171 5.12705 3.53382 5.67838 4.1589 7.7275C4.64258 9.31316 4.97542 10.9448 5.15141 12.5932C5.25957 13.6064 5.28993 14.0764 5.66469 14.7157C6.27327 15.7539 7.51642 14.848 7.32131 13.3255Z"
                fill="currentColor"
              />
            </svg>
          </Link>
        </div>
      </div>
    </div>
  </article>
</template>
