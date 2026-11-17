<script setup lang="ts">
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useTranslations } from '@/Composables/useTranslations'

/**
 * Visitor star rating for a service. A native radio group (keyboard + screen
 * reader friendly); submits on choice. Shows only approved averages.
 */
const props = defineProps<{ action: string; summary: { average: number; count: number } }>()

const { t } = useTranslations()
const form = useForm({ rating: 0 })
const hover = ref(0)

function submit(value: number): void {
  form.rating = value
  form.post(props.action, { preserveScroll: true })
}
</script>

<template>
  <section class="mt-12 rounded-lg border border-ink-800 p-6">
    <fieldset :disabled="form.processing || form.wasSuccessful">
      <legend class="text-title-sm text-ink-50">{{ t('forms.rating_label') }}</legend>
      <div class="mt-3 flex flex-row-reverse justify-end gap-1" dir="ltr" @mouseleave="hover = 0">
        <label
          v-for="n in [5, 4, 3, 2, 1]"
          :key="n"
          class="cursor-pointer text-[28px] leading-none transition-colors"
          :class="(hover || form.rating) >= n ? 'text-brand' : 'text-ink-700'"
          @mouseenter="hover = n"
        >
          <input
            type="radio"
            name="rating"
            class="sr-only"
            :value="n"
            :aria-label="t('forms.rating_star', { n })"
            @change="submit(n)"
          />
          <span aria-hidden="true">★</span>
        </label>
      </div>
    </fieldset>
    <p v-if="summary.count > 0" class="mt-3 text-body-md text-ink-300">
      {{ t('forms.rating_summary', { avg: summary.average, count: summary.count }) }}
    </p>
    <p v-if="form.errors.rating" class="mt-2 text-body-md text-red-400" role="alert">
      {{ form.errors.rating }}
    </p>
  </section>
</template>
