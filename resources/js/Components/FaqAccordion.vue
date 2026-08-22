<script setup lang="ts">
import SectionHeading from '@/Components/SectionHeading.vue'
import type { FaqItem, PageSectionData } from '@/types'
import illustrationUrl from '~img/sizdah/home/faq-illustration.svg'
import plusUrl from '~img/sizdah/home/faq-plus.svg'
import minusUrl from '~img/sizdah/home/faq-minus.svg'

/**
 * FAQ — Figma "FAQ section" 434:5512, with the heading at 434:5470, the
 * subtitle at 434:5584 and the 320px illustration at 561:7806.
 *
 * Each question is a gold/100 box on a gold/200 hairline: the question at the
 * inline start, a 24px +/- toggle opposite it, and the answer revealed beneath.
 * Built on native `<details>`/`<summary>` so it opens without JavaScript and
 * keyboard and screen-reader behaviour come for free — the same choice the rest
 * of the site's disclosures make.
 *
 * The frame draws the first box open and the other three closed; that is a
 * presentation state, so the first item opens by default here too.
 */
const props = defineProps<{ section: PageSectionData; faqs: FaqItem[] }>()
</script>

<template>
  <section class="section">
    <div class="container-sizdah">
      <div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(0,719px)] lg:gap-16">
        <!--
          Heading column leads in DOM so RTL sets it at the inline start, which
          is where 434:5470 sits; the boxes take the opposite column.
        -->
        <div class="flex flex-col gap-10">
          <SectionHeading
            data-reveal
            :eyebrow="props.section.eyebrow"
            :title="props.section.title"
            :subtitle="props.section.subtitle || props.section.description"
            layout="stacked"
          />

          <img
            :src="illustrationUrl"
            alt=""
            aria-hidden="true"
            width="320"
            height="320"
            class="hidden size-80 self-center lg:block"
          />
        </div>

        <ul class="flex flex-col gap-6" data-reveal-group>
          <li v-for="(faq, index) in props.faqs" :key="faq.question">
            <details
              class="group rounded-lg border border-gold-200 bg-gold-100 p-8"
              :open="index === 0"
            >
              <summary
                class="flex cursor-pointer list-none items-center justify-between gap-6 [&::-webkit-details-marker]:hidden"
              >
                <span class="text-title-sm text-ink-1000">{{ faq.question }}</span>

                <img
                  :src="plusUrl"
                  alt=""
                  aria-hidden="true"
                  width="24"
                  height="24"
                  class="size-6 shrink-0 group-open:hidden"
                />
                <img
                  :src="minusUrl"
                  alt=""
                  aria-hidden="true"
                  width="24"
                  height="24"
                  class="hidden size-6 shrink-0 group-open:block"
                />
              </summary>

              <p class="mt-6 text-body-lg text-ink-700">{{ faq.answer }}</p>
            </details>
          </li>
        </ul>
      </div>
    </div>
  </section>
</template>
