<script setup lang="ts">
import { computed, ref } from 'vue'
import { countries, flagEmoji, type Country } from '@/lib/countries'
import { useTranslations } from '@/Composables/useTranslations'
import caretUrl from '~img/sizdah/contact/field-caret.svg'

/**
 * Phone-field country picker — Contact's `279:6406` shows a bordered
 * flag+chevron chip at the end of the number field, but the frame only ever
 * depicts the Oman default; search/selection is this project's own addition
 * on top of that affordance, not something the frame specifies further.
 */
const model = defineModel<Country>({ required: true })

const { t } = useTranslations()

const open = ref(false)
const query = ref('')

const filtered = computed(() => {
  const q = query.value.trim().toLowerCase()

  if (!q) return countries

  const digits = q.replace(/[^\d]/g, '')

  return countries.filter(
    (country) =>
      country.nameFa.includes(query.value.trim()) ||
      country.nameEn.toLowerCase().includes(q) ||
      (digits !== '' && country.dialCode.replace('+', '').startsWith(digits)),
  )
})

function select(country: Country): void {
  model.value = country
  open.value = false
  query.value = ''
}

function toggle(): void {
  open.value = !open.value
}
</script>

<template>
  <div class="relative flex shrink-0 border-e-2 border-brand-200">
    <button
      type="button"
      class="flex items-center gap-1.5 px-3 text-body-md text-ink-1000"
      :aria-expanded="open"
      aria-controls="country-options"
      @click="toggle"
    >
      <img
        :src="caretUrl"
        alt=""
        aria-hidden="true"
        width="16"
        height="16"
        class="size-4 shrink-0 transition-transform duration-200"
        :class="open && 'rotate-180'"
      />
      <span aria-hidden="true" class="text-lg leading-none">{{ flagEmoji(model.iso2) }}</span>
      <span class="sr-only">{{ model.nameFa }}</span>
    </button>

    <div
      v-show="open"
      id="country-options"
      class="absolute inline-start-0 top-full z-20 mt-2 w-72 rounded-lg border-3 border-brand-200 bg-white p-2 shadow-[4px_4px_12px_0px_rgba(0,0,0,0.15)]"
    >
      <input
        v-model="query"
        type="text"
        :aria-label="t('forms.contact.country_search')"
        :placeholder="t('forms.contact.country_search')"
        class="mb-2 w-full rounded-md border border-ink-300 bg-white px-3 py-2 text-body-sm text-ink-1000 placeholder:text-ink-600 focus:border-brand focus:outline-none focus:ring-0"
      />

      <ul class="max-h-56 overflow-y-auto" role="listbox">
        <li v-for="country in filtered" :key="country.iso2">
          <button
            type="button"
            role="option"
            :aria-selected="country.iso2 === model.iso2"
            class="flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-start text-body-sm text-ink-1000 hover:bg-brand-200"
            :class="country.iso2 === model.iso2 && 'bg-brand-200'"
            @click="select(country)"
          >
            <span aria-hidden="true" class="text-base leading-none">{{
              flagEmoji(country.iso2)
            }}</span>
            <span class="flex-1 truncate">{{ country.nameFa }}</span>
            <span class="latin-nums text-ink-600" dir="ltr">{{ country.dialCode }}</span>
          </button>
        </li>
        <li v-if="filtered.length === 0" class="px-2 py-3 text-center text-body-sm text-ink-600">
          {{ t('forms.contact.country_not_found') }}
        </li>
      </ul>
    </div>
  </div>
</template>
