<script setup lang="ts">
import { computed, ref } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import Eyebrow from '@/Components/Eyebrow.vue'
import SeoHead from '@/Components/SeoHead.vue'
import SocialIcon from '@/Components/SocialIcon.vue'
import { useTranslations } from '@/Composables/useTranslations'
import type { SeoMeta, SharedProps } from '@/types'
import phoneIconUrl from '~img/sizdah/contact/contact-phone.svg'
import locationIconUrl from '~img/sizdah/contact/contact-location.svg'
import emailIconUrl from '~img/sizdah/contact/contact-email.svg'
import workingWithIconUrl from '~img/sizdah/home/kpi-retention.svg'
import brandFieldUrl from '~img/sizdah/contact/field-brand.svg'
import userFieldUrl from '~img/sizdah/contact/field-user.svg'
import serviceFieldUrl from '~img/sizdah/contact/field-service.svg'
import caretUrl from '~img/sizdah/contact/field-caret.svg'
import omanFlagUrl from '~img/sizdah/contact/flag-oman.svg'
import wordmarkUrl from '~img/sizdah/shared/wordmark-inline.svg'
import upRightUrl from '~img/sizdah/shared/up-right-arrow.svg'

/**
 * Contact — Figma 279:6325.
 *
 * Two cards side by side: details (423px) and form (801px), then a social row.
 * The form posts to the existing contact.store route and its field names match
 * ContactSubmissionRequest exactly, so no backend change was needed. `email` is
 * in the request rules but absent from the frame, so it is not rendered.
 */
interface ServiceOption {
  id: number
  title: string
}

const props = defineProps<{
  heading: { eyebrow: string; title: string; description: string }
  services: ServiceOption[]
  seo: SeoMeta
}>()

const { t } = useTranslations()
const page = usePage<SharedProps>()

const contact = computed(() => page.props.settings.contact)
const socialLinks = computed(() => page.props.settings.socialLinks)

const details = computed(() => [
  { key: 'whatsapp', icon: phoneIconUrl, value: contact.value.whatsapp },
  { key: 'location', icon: locationIconUrl, value: contact.value.location },
  { key: 'email', icon: emailIconUrl, value: contact.value.email },
  { key: 'working_with', icon: workingWithIconUrl, value: contact.value.workingWith },
])

/*
 | `website` is the honeypot and `form_started_at` the timestamp trap — both are
 | validated by ContactSubmissionRequest and must be sent even though neither is
 | drawn in the frame.
 */
const form = useForm<{
  brand_name: string
  name: string
  service_ids: number[]
  phone: string
  message: string
  website: string
  form_started_at: number
}>({
  brand_name: '',
  name: '',
  service_ids: [],
  phone: '',
  message: '',
  website: '',
  form_started_at: Math.floor(Date.now() / 1000),
})

const servicesOpen = ref(false)

const selectedServices = computed(() =>
  props.services
    .filter((service) => form.service_ids.includes(service.id))
    .map((service) => service.title),
)

function submit(): void {
  form.post(route('contact.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      form.form_started_at = Math.floor(Date.now() / 1000)
      servicesOpen.value = false
    },
  })
}

// Shared between the five inputs so the light-on-dark field treatment stays in
// one place (Figma: 80% white fill, 3px Yellow/300 border, radiusLG).
const fieldClass =
  'w-full rounded-lg border-3 border-brand-200 bg-white/80 p-3 text-body-md text-ink-1000 placeholder:text-ink-600 focus:border-brand focus:outline-none focus:ring-0'
</script>

<template>
  <SeoHead :seo="props.seo" />

  <section class="section-first pb-24">
    <div class="container-sizdah relative isolate">
      <!-- 279:6376 — the same 109px mesh the other frames use, 872 tall, from y=212. -->
      <div
        class="grid-mesh pointer-events-none absolute inset-inline-start-0 inset-block-start-[32px] -z-10 hidden h-[872px] w-full max-w-container lg:block"
        style="--mesh-cell-x: 109.095px; --mesh-cell-y: 109.095px"
        aria-hidden="true"
      />

      <!--
        279:6399 — a 612 column on gap 40; the title block (279:6403) is gap 24.
        The headline is Display/Large (48/700) and the lede is tite/Medium
        (20/500), not the 40/18 pair this header carried.
      -->
      <header class="mx-auto flex max-w-measure flex-col items-center gap-10 text-center">
        <Eyebrow v-if="props.heading.eyebrow" :text="props.heading.eyebrow" />

        <div class="flex flex-col items-center gap-6">
          <h1 class="text-display-lg text-ink-50">{{ props.heading.title }}</h1>
          <p v-if="props.heading.description" class="text-title-md text-ink-200">
            {{ props.heading.description }}
          </p>
        </div>
      </header>

      <div class="mt-12 grid gap-6 lg:grid-cols-[423fr_801fr]">
        <!-- Details card — 279:6409 -->
        <div
          class="flex flex-col gap-10 rounded-xl border-3 border-ink-300 bg-ink-1000/20 bg-gradient-to-tl from-brand/10 to-transparent p-8"
        >
          <h2 class="text-title-lg text-brand-50">{{ t('forms.details.title') }}</h2>

          <ul class="flex flex-col gap-6">
            <li v-for="(detail, index) in details" :key="detail.key" class="flex flex-col gap-6">
              <div class="flex items-center gap-4">
                <span
                  class="inline-flex size-12 shrink-0 items-center justify-center rounded-round border border-ink-700"
                >
                  <img
                    :src="detail.icon"
                    alt=""
                    aria-hidden="true"
                    width="24"
                    height="24"
                    class="size-6"
                  />
                </span>
                <span class="flex flex-col gap-1">
                  <span class="text-label-lg text-brand-50">
                    {{ t(`forms.details.${detail.key}`) }}
                  </span>
                  <span class="text-body-md text-ink-200 latin-nums">{{ detail.value }}</span>
                </span>
              </div>
              <hr v-if="index < details.length - 1" class="border-0 border-t-2 border-ink-800" />
            </li>
          </ul>
        </div>

        <!-- Form card — 279:6439 -->
        <form
          class="flex flex-col gap-6 rounded-xl border-3 border-ink-300 bg-ink-1000/20 bg-gradient-to-tr from-brand/10 to-transparent p-8"
          novalidate
          @submit.prevent="submit"
        >
          <!-- Honeypot: never shown, never announced; bots fill it, people cannot. -->
          <div class="hidden" aria-hidden="true">
            <label for="website">Website</label>
            <input
              id="website"
              v-model="form.website"
              type="text"
              tabindex="-1"
              autocomplete="off"
            />
          </div>

          <div class="grid gap-6 md:grid-cols-2">
            <div class="flex flex-col gap-2">
              <label for="brand_name" class="text-label-lg text-brand-50">
                {{ t('forms.contact.brand') }}
              </label>
              <div class="relative">
                <img
                  :src="brandFieldUrl"
                  alt=""
                  aria-hidden="true"
                  width="24"
                  height="24"
                  class="pointer-events-none absolute inset-block-start-1/2 inline-start-3 size-6 -translate-y-1/2"
                />
                <input
                  id="brand_name"
                  v-model="form.brand_name"
                  type="text"
                  :class="[fieldClass, 'ps-12']"
                  :placeholder="t('forms.contact.brand_placeholder')"
                  autocomplete="organization"
                />
              </div>
              <p v-if="form.errors.brand_name" class="text-label-md text-brand">
                {{ form.errors.brand_name }}
              </p>
            </div>

            <div class="flex flex-col gap-2">
              <label for="name" class="text-label-lg text-brand-50">
                {{ t('forms.contact.name') }}
              </label>
              <div class="relative">
                <img
                  :src="userFieldUrl"
                  alt=""
                  aria-hidden="true"
                  width="24"
                  height="24"
                  class="pointer-events-none absolute inset-block-start-1/2 inline-start-3 size-6 -translate-y-1/2"
                />
                <input
                  id="name"
                  v-model="form.name"
                  type="text"
                  required
                  :class="[fieldClass, 'ps-12']"
                  :placeholder="t('forms.contact.name_placeholder')"
                  autocomplete="name"
                />
              </div>
              <p v-if="form.errors.name" class="text-label-md text-brand">{{ form.errors.name }}</p>
            </div>
          </div>

          <div class="grid gap-6 md:grid-cols-2">
            <!--
              Multi-select rendered as a disclosure of checkboxes rather than a
              native <select multiple>, which cannot be styled to the frame and
              is poor on touch. Semantics stay real: a fieldset of checkboxes.
            -->
            <div class="flex flex-col gap-2">
              <span class="text-label-lg text-brand-50">{{ t('forms.contact.services') }}</span>
              <div class="relative">
                <button
                  type="button"
                  :class="[fieldClass, 'flex items-center gap-3 text-start']"
                  :aria-expanded="servicesOpen"
                  aria-controls="service-options"
                  @click="servicesOpen = !servicesOpen"
                >
                  <img
                    :src="serviceFieldUrl"
                    alt=""
                    aria-hidden="true"
                    width="24"
                    height="24"
                    class="size-6 shrink-0"
                  />
                  <span
                    class="flex-1 truncate"
                    :class="selectedServices.length ? 'text-ink-1000' : 'text-ink-600'"
                  >
                    {{
                      selectedServices.length
                        ? selectedServices.join('، ')
                        : t('forms.contact.services_placeholder')
                    }}
                  </span>
                  <img
                    :src="caretUrl"
                    alt=""
                    aria-hidden="true"
                    width="24"
                    height="24"
                    class="size-6 shrink-0 transition-transform duration-200"
                    :class="servicesOpen && 'rotate-180'"
                  />
                </button>

                <fieldset
                  v-show="servicesOpen"
                  id="service-options"
                  class="absolute inset-inline-0 z-10 mt-2 flex max-h-64 flex-col gap-2 overflow-y-auto rounded-lg border-3 border-brand-200 bg-white p-3"
                >
                  <legend class="sr-only">{{ t('forms.contact.services') }}</legend>
                  <label
                    v-for="service in props.services"
                    :key="service.id"
                    class="flex items-center gap-2 text-body-md text-ink-1000"
                  >
                    <input
                      v-model="form.service_ids"
                      type="checkbox"
                      :value="service.id"
                      class="size-4 rounded-xs border-ink-400 text-brand focus:ring-brand"
                    />
                    {{ service.title }}
                  </label>
                </fieldset>
              </div>
              <p v-if="form.errors.service_ids" class="text-label-md text-brand">
                {{ form.errors.service_ids }}
              </p>
            </div>

            <div class="flex flex-col gap-2">
              <label for="phone" class="text-label-lg text-brand-50">
                {{ t('forms.contact.phone') }}
              </label>
              <div class="relative">
                <span
                  class="pointer-events-none absolute inset-block-start-1/2 inline-start-3 flex -translate-y-1/2 items-center gap-1"
                >
                  <img
                    :src="omanFlagUrl"
                    alt=""
                    aria-hidden="true"
                    width="20"
                    height="20"
                    class="h-5 w-5"
                  />
                  <span class="text-body-md text-ink-1000 latin-nums" dir="ltr">+968</span>
                </span>
                <input
                  id="phone"
                  v-model="form.phone"
                  type="tel"
                  dir="ltr"
                  :class="[fieldClass, 'ps-[92px] text-start']"
                  :placeholder="t('forms.contact.phone_placeholder')"
                  autocomplete="tel"
                />
              </div>
              <p v-if="form.errors.phone" class="text-label-md text-brand">
                {{ form.errors.phone }}
              </p>
            </div>
          </div>

          <div class="flex flex-col gap-2">
            <label for="message" class="text-label-lg text-brand-50">
              {{ t('forms.contact.message') }}
            </label>
            <textarea
              id="message"
              v-model="form.message"
              rows="5"
              :class="[fieldClass, 'h-[151px] resize-none']"
              :placeholder="t('forms.contact.message_placeholder')"
            />
            <p v-if="form.errors.message" class="text-label-md text-brand">
              {{ form.errors.message }}
            </p>
          </div>

          <button
            type="submit"
            :disabled="form.processing"
            class="w-full rounded-md bg-brand px-8 py-4 text-title-md text-ink-1000 transition-colors duration-200 ease-brand hover:bg-brand-900 disabled:opacity-50"
          >
            {{ form.processing ? t('common.sending') : t('forms.contact.submit') }}
          </button>
        </form>
      </div>

      <!-- Social row — 279:6485 -->
      <div class="mt-14 flex flex-col gap-8 md:flex-row md:items-center md:justify-between">
        <ul class="flex flex-wrap items-center gap-6">
          <li v-for="link in socialLinks" :key="link.platform">
            <SocialIcon :link="link" />
          </li>
        </ul>

        <p class="relative flex items-center gap-2 text-body-lg font-medium text-ink-100">
          <img
            :src="wordmarkUrl"
            alt=""
            aria-hidden="true"
            width="56"
            height="20"
            class="h-5 w-14"
          />
          {{ t('forms.details.follow') }}
          <img
            :src="upRightUrl"
            alt=""
            aria-hidden="true"
            width="91"
            height="87"
            class="pointer-events-none absolute inset-block-end-full inline-end-0 hidden h-auto w-[91px] flip-rtl lg:block"
          />
        </p>
      </div>
    </div>
  </section>
</template>
