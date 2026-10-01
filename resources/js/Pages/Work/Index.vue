<script setup lang="ts">
import { computed } from 'vue'
import { route } from 'ziggy-js'
import Eyebrow from '@/Components/Eyebrow.vue'
import FilterChips from '@/Components/FilterChips.vue'
import ProjectPostCard from '@/Components/ProjectPostCard.vue'
import SeoHead from '@/Components/SeoHead.vue'
import StartTogetherCard from '@/Components/StartTogetherCard.vue'
import { useTranslations } from '@/Composables/useTranslations'
import type { FilterOption, PageSectionData, ProjectSummary, SectionMap, SeoMeta } from '@/types'
import figmaWorkFirstUrl from '~img/sizdah/work/figma-1074-first.png'
import figmaWorkSecondUrl from '~img/sizdah/work/figma-1074-second.png'

/**
 * Work listing — Figma "projects" 222:1989 (1440x4656).
 *
 * Rebuilt against the real frame; the previous version was DERIVED from the
 * blog listing because no frame existed. `261:2545` is the light twin of this
 * frame and still ends on the old English Sahra card — 222:1989 is canonical.
 *
 * Vertical rhythm from the frame: the centred header block (eyebrow 40 title
 * 24 description), gap 64 to the filter row, gap 110 to the grid, gap 259 to
 * the closing card. The grid is 24 across and 96 down.
 *
 * The closing card is the same "Frame 96467" the About page ends on, so it is
 * the shared StartTogetherCard rather than a second copy.
 *
 * `266:2771` is a 1200x1200 hairline grid the frame lays behind the header,
 * centred in the 1440 canvas (x=120) and starting at the same y=180 the content
 * does. Its lines measure 109.095px apart on both axes, so it is drawn with the
 * shared `.grid-mesh` background rather than shipped as an SVG — the same way
 * About draws its own mesh (359:9560, 109x118).
 *
 * DELIBERATE DEVIATION 2026-09-08 (user decision) — the frame's grid is not a
 * uniform two-up: its first row (`226:2880`) holds three 400-wide "project
 * post" instances (`226:2818` etc.) and the rows after it (`226:2957`,
 * `226:3019`) are 612-wide two-ups. Both sizes are the *same* Figma component
 * just resized. That split was reproduced here until the real project count
 * (six) exposed what the frame's tidy sample never did: a 3-up row, a 2-up row,
 * then a single stranded 612 card, i.e. three different card sizes down one
 * page. The user asked for one set of identical cards, so the listing is now a
 * single 3-up grid of the 400-wide card at every position. See GAPS G54/G56.
 */
const props = defineProps<{
  heading: { eyebrow: string; title: string; description: string }
  projects: ProjectSummary[]
  filters: { slug: string; name: string }[]
  activeFilter: string | null
  sections: SectionMap
  seo: SeoMeta
}>()

const { t } = useTranslations()

const filterOptions = computed<FilterOption[]>(() => [
  { value: null, label: t('common.all'), href: route('work.index') },
  ...props.filters.map((filter) => ({
    value: filter.slug,
    label: filter.name,
    href: route('work.index', { service: filter.slug }),
  })),
])

const finalCta = computed<PageSectionData | undefined>(() => props.sections.final_cta)
</script>

<template>
  <SeoHead :seo="props.seo" />

  <!--
    222:1989 puts the footer at 4154.24 and the cream CTA (577:9134) at 3464;
    the slack below the CTA is 177.24, not the shared `pb-24` (96).
  -->
  <section class="section-first pb-[177.24px] max-md:pt-[132px]">
    <div class="container-sizdah relative isolate">
      <header
        class="mx-auto flex max-w-full flex-col items-center gap-16 text-center max-md:gap-8"
        data-reveal
      >
        <!-- 222:2461 — the eyebrow/title/description column is 612 wide inside the 670 track. -->
        <div class="flex w-full max-w-[612px] flex-col items-center gap-10">
          <Eyebrow v-if="props.heading.eyebrow" :text="props.heading.eyebrow" class="max-md:hidden" />
          <div class="hidden items-center gap-1 text-[14px] text-brand max-md:flex">پروژه های ما <span class="size-2 rounded-full bg-brand" /></div>

          <div class="flex flex-col items-center gap-6 max-md:gap-6">
            <h1 class="text-display-lg text-ink-50 max-md:text-[26px] max-md:leading-[36px]"><span class="max-md:hidden">{{ props.heading.title }}</span><span class="hidden max-md:block">هر برند<br />داستان خودش را دارد</span></h1>
            <p v-if="props.heading.description" class="text-title-sm text-ink-200 max-md:text-[16px] max-md:leading-[22px]">
              <span class="max-md:hidden">{{ props.heading.description }}</span><span class="hidden max-md:block">نمونه‌ای از برندهایی که در این مسیر همراهشان بوده‌ایم</span>
            </p>
          </div>
        </div>

        <!-- 222:2475 — the chip row stretches the full 670 track, not the 612 column. -->
        <div
          v-if="props.filters.length"
          class="flex w-full justify-center max-md:justify-start max-md:overflow-x-auto max-md:pb-2"
        >
          <FilterChips
            :options="filterOptions"
            :active="props.activeFilter"
            :label="t('work.services')"
            variant="solid"
            single-line
          />
        </div>
      </header>

      <p
        v-if="!props.projects.length"
        class="mt-[110px] py-16 text-center text-title-sm text-ink-300"
      >
        {{ props.activeFilter ? t('common.empty_results') : t('common.empty_projects') }}
      </p>

      <!--
        One uniform 3-up: 24 across, 96 down, every cell the frame's 400-wide
        card. `items-stretch` is the grid default, and ProjectPostCard is
        `h-full`, so each row's cards share a height and their excerpt and
        service bands line up.

        Three columns start at `lg`, not `md`: the card's 36px title needs the
        width, and at 768 a third of the track is 213px, which pushed the
        industry label clean out of the card. Tablets take the two-up instead.
      -->
      <div
        v-else
        class="mt-16 grid gap-x-6 gap-y-16 max-md:hidden sm:grid-cols-2 md:mt-[110px] md:gap-y-24 lg:grid-cols-3"
        data-reveal-group
      >
        <ProjectPostCard
          v-for="project in props.projects"
          :key="project.slug"
          :project="project"
          data-reveal
        />
      </div>

      <div class="mt-[77px] hidden flex-col gap-[86px] pb-[64px] max-md:flex">
        <article class="h-[542px] rounded-2xl bg-ink-900 p-2" style="background-image: linear-gradient(90deg, rgb(0 0 0 / 20%), rgb(0 0 0 / 20%)), linear-gradient(-54deg, rgb(248 185 55 / 0%), rgb(248 185 55 / 10%)), linear-gradient(90deg, rgb(20 20 20 / 80%), rgb(20 20 20 / 80%));">
          <img :src="figmaWorkFirstUrl" alt="" class="h-[248px] w-full rounded-2xl border border-warm-100 object-cover" />
          <div class="mt-6 flex flex-col items-end gap-4 text-right"><div class="flex w-full flex-row-reverse items-center justify-between"><span class="text-[14px] text-brand">↙ مطالعه مقاله</span><span class="rounded-full bg-brand-100 px-2 py-1 text-[14px] text-ink-800">رشد</span></div><h3 class="text-[22px] leading-normal text-ink-50">چطور شبکه‌های اجتماعی را به یک مسیر رشد تبدیل کنیم؟</h3><p class="text-[16px] leading-normal text-ink-300">از انتشار پراکنده و پیام‌های ناهماهنگ تا ساخت یک سیستم منسجم؛ سیستمی که مخاطب درست را جذب می‌کند، به‌مرور اعتماد می‌سازد و توجه را به نتیجه واقعی برای کسب‌وکار تبدیل می‌کند.</p><p class="text-[14px] text-ink-200">۲۰ اردیبهشت ۱۴۰۳ · زمان مطالعه : ۵ دقیقه</p></div>
        </article>
        <article class="h-[488px] overflow-hidden rounded-2xl border border-warm-100 shadow-card"><img :src="figmaWorkSecondUrl" alt="" class="h-[362px] w-full object-cover" /><div class="flex flex-col items-end gap-4 p-4 text-right"><p class="text-[14px] text-ink-200">۲۰ اردیبهشت ۱۴۰۳ · زمان مطالعه : ۵ دقیقه</p><h3 class="text-[22px] leading-normal text-ink-50">وقتی بدون مسیر محتوا تولید می‌کنید، چه اتفاقی می‌افتد؟</h3></div></article>
      </div>

      <div v-if="finalCta" class="mt-24 max-md:hidden md:mt-[259px]">
        <StartTogetherCard :section="finalCta" data-reveal />
      </div>
    </div>
  </section>
</template>
