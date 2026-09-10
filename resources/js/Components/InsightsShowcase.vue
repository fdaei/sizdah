<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import SectionHeading from '@/Components/SectionHeading.vue'
import type { PageSectionData, PostSummary } from '@/types'
import calendarUrl from '~img/sizdah/shared/calendar.svg'
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
          class="sketch-frame flex flex-col gap-4 p-4 shadow-card sm:flex-row sm:justify-between"
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
            width="279"
            height="392"
            loading="lazy"
            decoding="async"
            class="w-full rounded-lg object-cover sm:order-first sm:w-[279px] sm:shrink-0"
          />

          <div class="flex flex-col justify-between gap-6 p-2 sm:w-[270px] sm:shrink-0">
            <div class="flex flex-col gap-[80px]">
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

              <div class="flex flex-col gap-[32px]">
                <h3 class="text-title-md text-gold">
                  <Link :href="lead.url" class="transition-opacity hover:opacity-80">
                    {{ lead.title }}
                  </Link>
                </h3>

                <p v-if="lead.excerpt" class="text-body-md text-warm-700">{{ lead.excerpt }}</p>
              </div>
            </div>

            <!--
              Inlined rather than <img src> so the arrow can pick up the brand
              yellow on hover via currentColor — an external SVG can't be
              recoloured from the parent.
            -->
            <Link
              :href="lead.url"
              class="flex size-12 shrink-0 self-end items-center justify-center rounded-round border border-warm-800 bg-warm-1000 text-warm-100 transition-colors hover:bg-warm-900 hover:text-brand"
              :aria-label="lead.title"
            >
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
                width="188"
                height="188"
                loading="lazy"
                decoding="async"
                class="size-[120px] shrink-0 rounded-lg object-cover sm:size-[188px]"
              />

              <div class="flex min-w-0 flex-col gap-6">
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
