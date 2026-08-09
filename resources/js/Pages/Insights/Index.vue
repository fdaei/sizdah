<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import BlogCard from '@/Components/BlogCard.vue'
import Eyebrow from '@/Components/Eyebrow.vue'
import FeaturedPostCard from '@/Components/FeaturedPostCard.vue'
import FilterChips from '@/Components/FilterChips.vue'
import SeoHead from '@/Components/SeoHead.vue'
import { useTranslations } from '@/Composables/useTranslations'
import type { FilterOption, PostSummary, SeoMeta } from '@/types'

/**
 * Blog listing — Figma 268:4158.
 *
 * The frame lays out a featured row, then one 2-up row of 612px cards, then two
 * 3-up rows of 400px cards. Rather than pin those counts, the first two posts of
 * a page render wide and the rest render 3-up, so the rhythm holds for any page
 * size the backend paginates to.
 */
interface PostsPage {
  data: PostSummary[]
  currentPage: number
  lastPage: number
  total: number
  nextPageUrl: string | null
  prevPageUrl: string | null
}

const props = defineProps<{
  heading: { eyebrow: string; title: string; description: string }
  featured: PostSummary | null
  posts: PostsPage
  categories: { slug: string; name: string }[]
  filters: { category: string | null; q: string | null }
  seo: SeoMeta
}>()

const { t } = useTranslations()

const filterOptions = computed<FilterOption[]>(() => [
  { value: null, label: t('common.all'), href: route('insights.index') },
  ...props.categories.map((category) => ({
    value: category.slug,
    label: category.name,
    href: route('insights.index', { category: category.slug }),
  })),
])

// The frame's 2-up row, then everything else 3-up.
const wide = computed(() => props.posts.data.slice(0, 2))
const rest = computed(() => props.posts.data.slice(2))
</script>

<template>
  <SeoHead :seo="props.seo" />

  <section class="section-first pb-24">
    <div class="container-sizdah flex flex-col gap-12">
      <header class="mx-auto flex max-w-measure flex-col items-center gap-4 text-center">
        <Eyebrow v-if="props.heading.eyebrow" :text="props.heading.eyebrow" />
        <h1 class="text-display-md text-ink-50">{{ props.heading.title }}</h1>
        <p v-if="props.heading.description" class="text-title-sm text-ink-200">
          {{ props.heading.description }}
        </p>
      </header>

      <div class="flex justify-center">
        <FilterChips
          :options="filterOptions"
          :active="props.filters.category"
          :label="t('blog.categories')"
        />
      </div>

      <FeaturedPostCard v-if="props.featured" :post="props.featured" />

      <p v-if="!props.posts.data.length" class="py-16 text-center text-title-sm text-ink-300">
        {{ props.filters.category || props.filters.q ? t('common.empty_results') : t('common.empty_posts') }}
      </p>

      <div v-else class="flex flex-col gap-24">
        <div v-if="wide.length" class="grid gap-6 md:grid-cols-2">
          <BlogCard v-for="post in wide" :key="post.slug" :post="post" variant="wide" headingLevel="h2" />
        </div>

        <div v-if="rest.length" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <BlogCard v-for="post in rest" :key="post.slug" :post="post" headingLevel="h2" />
        </div>
      </div>

      <!--
        Not in the frame — the listing is paginated server-side, so without a
        control everything past the first page would be unreachable.
      -->
      <nav
        v-if="props.posts.lastPage > 1"
        :aria-label="t('common.pagination')"
        class="flex items-center justify-center gap-6"
      >
        <Link
          v-if="props.posts.prevPageUrl"
          :href="props.posts.prevPageUrl"
          class="text-body-lg text-paper transition-colors duration-200 ease-brand hover:text-brand"
        >
          {{ t('common.previous') }}
        </Link>
        <span class="text-body-md text-ink-300 latin-nums">
          {{ props.posts.currentPage }} / {{ props.posts.lastPage }}
        </span>
        <Link
          v-if="props.posts.nextPageUrl"
          :href="props.posts.nextPageUrl"
          class="text-body-lg text-paper transition-colors duration-200 ease-brand hover:text-brand"
        >
          {{ t('common.next') }}
        </Link>
      </nav>
    </div>
  </section>
</template>
