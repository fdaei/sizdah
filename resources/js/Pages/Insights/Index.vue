<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import BlogCard from '@/Components/BlogCard.vue'
import Eyebrow from '@/Components/Eyebrow.vue'
import FeaturedPostCard from '@/Components/FeaturedPostCard.vue'
import FilterChips from '@/Components/FilterChips.vue'
import SeoHead from '@/Components/SeoHead.vue'
import StartTogetherCard from '@/Components/StartTogetherCard.vue'
import { useTranslations } from '@/Composables/useTranslations'
import type { FilterOption, PageSectionData, PostSummary, SectionMap, SeoMeta } from '@/types'
import bookIconUrl from '~img/sizdah/insights/book.svg'
import bookMarkUrl from '~img/sizdah/insights/book-arrow.svg'
import paperclipUrl from '~img/sizdah/insights/paperclip.svg'

/**
 * Blog listing — Figma 268:4158.
 *
 * The frame lays out a featured row, then one 2-up row of 612px cards, then two
 * 3-up rows of 400px cards. Rather than pin those counts, the first two posts of
 * a page render wide and the rest render 3-up, so the rhythm holds for any page
 * size the backend paginates to.
 *
 * Two hand-drawn flourishes the original build missed (299:7730, 456:6721):
 * a book-and-mark accent (298:7715 + 299:7724) sits at the header column's
 * corner in the frame — but at the exact top of the headline's own text box,
 * which would overlap real copy of any length, so it is pinned just above the
 * eyebrow pill instead, not at the frame's literal y=0. A paperclip
 * (456:6721) sits centred in the gap between the header and the card grid.
 *
 * The frame also lays a 1200x1200 hairline grid (691:7297) behind the header,
 * starting level with it at y=180 — same position/size as the grid Work's
 * listing (266:2771) and About (359:9560) draw with the shared `.grid-mesh`
 * utility, and the same 109.095px cell measured off this frame's own hairline
 * vectors, so it reuses that treatment rather than shipping the flattened
 * Figma export as an image. The page's amber corner glow is the shared
 * `.page-wash` on `AppLayout`, not part of this frame — no separate asset needed.
 *
 * The closing "Frame 96467" card (577:9485) — same shared StartTogetherCard
 * every other page ends on (see its docblock) — was never wired up even
 * though `sections.final_cta` has been available from the controller all
 * along.
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
  sections: SectionMap
  seo: SeoMeta
}>()

const { t } = useTranslations()

const finalCta = computed<PageSectionData | undefined>(() => props.sections.final_cta)

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
    <!-- Header sits 96 above the content block; 270:5252 then runs on 144. -->
    <div class="container-sizdah relative isolate flex flex-col gap-16 lg:gap-24">
      <!-- 691:7297 — the mesh starts level with the content, at frame y=180. -->
      <div
        class="grid-mesh pointer-events-none absolute inline-start-0 block-start-0 -z-10 hidden h-[1200px] w-full max-w-container lg:block"
        style="--mesh-cell-x: 109.095px; --mesh-cell-y: 109.095px"
        aria-hidden="true"
      />

      <!-- 268:5233 — 670 track, 64 to the chip row; 268:5234 is the 612 column. -->
      <header class="relative mx-auto flex max-w-[670px] flex-col items-center gap-16 text-center">
        <!-- 298:7715 + 299:7724 — book-and-mark corner accent; see docblock. -->
        <div
          aria-hidden="true"
          class="pointer-events-none absolute inline-start-0 block-end-full mb-2 hidden items-center gap-1 lg:flex"
        >
          <img :src="bookMarkUrl" alt="" width="16" height="20" />
          <img :src="bookIconUrl" alt="" width="48" height="39" />
        </div>

        <div class="flex w-full max-w-measure flex-col items-center gap-10">
          <Eyebrow v-if="props.heading.eyebrow" :text="props.heading.eyebrow" />

          <div class="flex flex-col items-center gap-6">
            <h1 class="text-display-lg text-ink-50">{{ props.heading.title }}</h1>
            <p v-if="props.heading.description" class="text-title-sm text-ink-200">
              {{ props.heading.description }}
            </p>
          </div>
        </div>

        <div class="flex w-full justify-center">
          <FilterChips
            :options="filterOptions"
            :active="props.filters.category"
            :label="t('blog.categories')"
          />
        </div>
      </header>

      <!-- 456:6721 — a hand-drawn paperclip between the header and the grid. -->
      <img :src="paperclipUrl" alt="" aria-hidden="true" width="64" height="64" class="mx-auto hidden lg:block" />

      <!--
        270:5252 runs 144 between the featured card, the 2-up row and the 3-up
        block; inside the 3-up block (270:5286) the two rows sit 96 apart while
        columns stay 24. Hence the split gap on the last grid.
      -->
      <div class="flex flex-col gap-16 lg:gap-[144px]">
        <FeaturedPostCard v-if="props.featured" :post="props.featured" />

        <p v-if="!props.posts.data.length" class="py-16 text-center text-title-sm text-ink-300">
          {{
            props.filters.category || props.filters.q
              ? t('common.empty_results')
              : t('common.empty_posts')
          }}
        </p>

        <div v-if="wide.length" class="grid gap-6 md:grid-cols-2">
          <BlogCard
            v-for="post in wide"
            :key="post.slug"
            :post="post"
            variant="wide"
            heading-level="h2"
          />
        </div>

        <div
          v-if="rest.length"
          class="grid gap-x-6 gap-y-16 sm:grid-cols-2 lg:grid-cols-3 lg:gap-y-24"
        >
          <BlogCard v-for="post in rest" :key="post.slug" :post="post" heading-level="h2" />
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

    <!-- 577:9485 — the shared closing card the frame ends on, 190 below the grid. -->
    <div v-if="finalCta" class="container-sizdah mt-16 lg:mt-[190px]">
      <StartTogetherCard :section="finalCta" />
    </div>
  </section>
</template>
