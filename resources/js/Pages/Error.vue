<script setup lang="ts">
import { computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import CtaButton from '@/Components/CtaButton.vue'
import { useTranslations } from '@/Composables/useTranslations'
import lostMapUrl from '~img/sizdah/errors/lost-map.png'

/**
 * Error page — Figma 266:2825 ("404").
 *
 * bootstrap/app.php renders this for every non-JSON, non-admin error status,
 * so one component serves 403/404/429/500/503 while the frame only draws 404.
 * Copy is keyed by status in lang/{locale}/errors.php.
 *
 * The illustration has the numerals ۴۰۴ drawn into it, so it is shown for that
 * status only — every other status gets the same layout without it rather than
 * a picture that contradicts the message.
 */
const props = defineProps<{ status: number }>()

const { t } = useTranslations()

const known = [403, 404, 429, 500, 503]

const key = computed(() => (known.includes(props.status) ? String(props.status) : '500'))
const title = computed(() => t(`errors.${key.value}.title`))
const message = computed(() => t(`errors.${key.value}.message`))
</script>

<template>
  <Head :title="title" />

  <section class="section-first flex flex-col items-center pb-24 text-center">
    <div class="container-sizdah flex flex-col items-center">
      <!--
        725x544 in the frame, exported at 2x. The negative block-end margin is
        the overlap the frame draws between art and heading (266:2827).
      -->
      <img
        v-if="props.status === 404"
        :src="lostMapUrl"
        alt=""
        aria-hidden="true"
        width="725"
        height="544"
        class="mb-[-40px] h-auto w-full max-w-[725px]"
      />

      <div class="flex w-full max-w-[506px] flex-col items-center gap-8">
        <div class="flex flex-col items-center gap-4">
          <h1 class="text-heading-lg text-brand-50">
            {{ title }}
          </h1>
          <p class="text-title-sm leading-[normal] text-ink-200">
            {{ message }}
          </p>
        </div>

        <CtaButton
          :label="t('errors.back_home')"
          :href="route('home')"
          size="lg"
          with-arrow
        />
      </div>
    </div>
  </section>
</template>
