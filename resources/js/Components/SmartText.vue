<script setup lang="ts">
/**
 * Renders a value in the face its own content asks for.
 *
 *   digits  -> Lahzeh (لحظه), via `font-numeric`
 *   text    -> Peyda  (پیدا), via `font-arabic`
 *
 * Mixed content is split per run, so "سال ۱۴۰۲ بود" sets the year in Lahzeh
 * and the two Persian words in Peyda on one line, rather than picking one
 * face for the whole string. `lib/typeface.ts` owns that segmentation and
 * documents which separators keep a date or a price together.
 *
 * RTL: the wrapper carries a first-strong `dir`, and every numeric run is
 * wrapped in `<bdi>`. The isolation matters — without it a trailing number in
 * an RTL sentence reorders around adjacent punctuation, so "نسخه ۲.۰:" would
 * paint its colon on the wrong side. `<bdi>` is the element the bidi
 * algorithm defines for exactly this, and needs no CSS.
 *
 * Interpolation is text-only by design: the value goes through `{{ }}`, never
 * `v-html`, so an admin-authored string cannot inject markup here.
 *
 * The wrapper carries `data-content-type` so a test or a QA pass can assert
 * what the detector decided without reading computed styles. It is inert —
 * nothing styles or scripts off it.
 */
import { computed } from 'vue'
import { useSmartFont } from '@/Composables/useSmartFont'
import { TYPEFACE_CLASS } from '@/lib/typeface'

const props = withDefaults(
  defineProps<{
    value: string | number | null | undefined
    /** Wrapper element. Keep it inline unless the value is a block on its own. */
    as?: string
    /** Weight to prefetch; the rendered weight still comes from your classes. */
    weight?: number | string
    /**
     * Set false to leave `dir` to the surrounding document — appropriate when
     * this sits inside a paragraph whose direction is already correct and an
     * inner `dir` would create a needless isolate.
     */
    withDir?: boolean
  }>(),
  { as: 'span', weight: 400, withDir: true },
)

const { contentType, fontClass, direction, segments } = useSmartFont(() => props.value, {
  weight: () => props.weight,
})

/** Mixed content is the only case that needs more than one element. */
const isMixed = computed(() => contentType.value === 'mixed')
</script>

<template>
  <component
    :is="as"
    :class="isMixed ? TYPEFACE_CLASS.text : fontClass"
    :dir="withDir ? direction : undefined"
    :data-content-type="contentType"
  >
    <template v-if="isMixed">
      <template v-for="(part, index) in segments" :key="index">
        <bdi v-if="part.typeface === 'numeric'" :class="TYPEFACE_CLASS.numeric">{{
          part.value
        }}</bdi>
        <template v-else>{{ part.value }}</template>
      </template>
    </template>
    <template v-else>{{ value }}</template>
  </component>
</template>
