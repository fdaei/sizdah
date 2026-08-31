<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import SectionHeading from '@/Components/SectionHeading.vue'
import type { PageSectionData, PostSummary } from '@/types'
import calendarUrl from '~img/sizdah/shared/calendar.svg'
import arrowUrl from '~img/sizdah/shared/up-right-arrow.svg'
import dividerUrl from '~img/sizdah/home/insight-divider.svg'

/**
 * Insights row — Figma "insight cards" 430:5247, under the heading at 268:3797.
 *
 * A 1248x424 pair: the lead post as a cream "Big insight" card (430:5311) at
 * the inline start, and the next two as compact rows beside it, split by the
 * hand-drawn rule at 430:5251.
 *
 * The cream card is the only light surface on this half of the page — gold/100
 * under a raking brand wash on a gold/200 hairline — so its copy inverts to ink
 * while the two compact rows stay on the dark page ground.
 *
 * Only the first three posts are drawn; the frame has no room for more and the
 * full list lives on the insights index.
 */
const props = defineProps<{ section: PageSectionData; posts: PostSummary[] }>()

const lead = computed<PostSummary | undefined>(() => props.posts[0])
const rest = computed(() => props.posts.slice(1, 3))
</script>

<template>
  <section v-if="lead" class="section">
    <div class="container-sizdah">
      <SectionHeading
        data-reveal
        :eyebrow="props.section.eyebrow"
        :title="props.section.title"
        :subtitle="props.section.subtitle || props.section.description"
        gap="lg"
      />

      <div
        class="mt-12 grid items-stretch gap-6 lg:mt-16 lg:grid-cols-2 [&>*]:min-w-0"
        data-reveal-group
      >
        <!-- 430:5311 — the lead post, cream on the dark ground. -->
        <article
          class="flex flex-col gap-4 rounded-xl border border-gold-200 p-4 shadow-card sm:flex-row"
          style="
            background-image:
              linear-gradient(-32.7deg, rgb(248 185 55 / 0) 2.3248%, rgb(248 185 55 / 10%) 100%),
              linear-gradient(0deg, #fff8eb, #fff8eb);
          "
        >
          <img
            v-if="lead.image"
            :src="lead.image.src"
            :srcset="lead.image.srcset"
            :alt="lead.image.alt || lead.title"
            width="287"
            height="390"
            loading="lazy"
            decoding="async"
            class="w-full rounded-lg object-cover sm:order-first sm:w-[47%]"
          />

          <div class="flex flex-1 flex-col justify-between gap-6 p-2">
            <div class="flex flex-col gap-4">
              <p class="flex items-center gap-2 text-body-md text-ink-600">
                <img
                  :src="calendarUrl"
                  alt=""
                  aria-hidden="true"
                  width="24"
                  height="24"
                  class="size-6"
                />
                <time :datetime="lead.publishedAtIso">{{ lead.publishedAt }}</time>
              </p>

              <h3 class="text-title-md text-gold">
                <Link :href="lead.url" class="transition-opacity hover:opacity-80">
                  {{ lead.title }}
                </Link>
              </h3>

              <p v-if="lead.excerpt" class="text-body-md text-warm-700">{{ lead.excerpt }}</p>
            </div>

            <Link
              :href="lead.url"
              class="flex size-12 shrink-0 items-center justify-center rounded-round border border-warm-800 bg-warm-1000 transition-colors hover:bg-warm-900"
              :aria-label="lead.title"
            >
              <img
                :src="arrowUrl"
                alt=""
                aria-hidden="true"
                width="32"
                height="32"
                class="size-8 flip-rtl"
              />
            </Link>
          </div>
        </article>

        <!-- 430:5249 — the two compact rows, split by the hand-drawn rule. -->
        <ul class="flex flex-col justify-center gap-6">
          <li v-for="(post, index) in rest" :key="post.slug" class="flex flex-col gap-6">
            <article class="flex items-center gap-6">
              <img
                v-if="post.image"
                :src="post.image.src"
                :srcset="post.image.srcset"
                :alt="post.image.alt || post.title"
                width="190"
                height="190"
                loading="lazy"
                decoding="async"
                class="size-[120px] shrink-0 rounded-lg object-cover sm:size-[190px]"
              />

              <div class="flex min-w-0 flex-col gap-4">
                <p class="flex items-center gap-2 text-body-md text-ink-200">
                  <img
                    :src="calendarUrl"
                    alt=""
                    aria-hidden="true"
                    width="24"
                    height="24"
                    class="size-6"
                  />
                  <time :datetime="post.publishedAtIso">{{ post.publishedAt }}</time>
                </p>

                <h3 class="text-title-md text-ink-200">
                  <Link :href="post.url" class="transition-colors hover:text-brand">
                    {{ post.title }}
                  </Link>
                </h3>
              </div>
            </article>

            <img
              v-if="index < rest.length - 1"
              :src="dividerUrl"
              alt=""
              aria-hidden="true"
              width="612"
              height="3"
              class="h-[3px] w-full"
            />
          </li>
        </ul>
      </div>
    </div>
  </section>
</template>
