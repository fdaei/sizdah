<script setup lang="ts">
import { nextTick, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { X } from 'lucide-vue-next'
import { route } from 'ziggy-js'
import CtaButton from '@/Components/CtaButton.vue'
import { useTranslations } from '@/Composables/useTranslations'
import userFieldUrl from '~img/sizdah/contact/field-user.svg'
import envelopeIconUrl from '~img/sizdah/shared/lead-magnet-envelope.svg'
import successIconUrl from '~img/sizdah/shared/lead-magnet-success.svg'
import successScribbleUrl from '~img/sizdah/shared/lead-magnet-success-scribble.svg'
import tickMarksUrl from '~img/sizdah/shared/lead-magnet-tick-marks.svg'

/**
 * Lead-magnet signup popup — Figma "lead magnet form" 416:5399 (default) and
 * 416:6003 (success), found nested as an overlay inside two otherwise-identical
 * "blog list" exploration frames (416:4959 / 416:5834) rather than filed as
 * their own component sheet — easy to miss, and previously missed: see GAPS
 * G51. `LeadMagnetBanner`'s CTA opens this rather than navigating anywhere.
 *
 * The frame draws a "نام و نام خانوادگی" (full name) field (416:5431) above
 * email. GAPS G51 originally dropped it — no `name` column existed and
 * nothing downstream read it — but the user asked for it back on visual
 * review of this exact frame (GAPS G54), so `newsletter_subscriptions` now
 * has a nullable `name` column and this collects and submits it; still
 * optional, since neither the success state nor any notification greets the
 * user by name.
 *
 * The modal's title/subtitle (416:5401/416:5402) are the frame's own fixed
 * copy — "چک‌لیست مسیر محتوا را دریافت کنید" / "یک چک‌لیست کوتاه..." — not a
 * mirror of whichever banner opened it. `LeadMagnetBanner` used to pass its
 * own section title/description through as props here, so the dialog just
 * repeated the banner text above it instead of the frame's actual checklist
 * pitch; now sourced from `forms.newsletter.title`/`.description` directly.
 *
 * The success view (416:6003) draws its own two-line copy (416:6004, fixed
 * as `forms.newsletter.success` with a literal `\n`, same pattern as
 * `errors.message` on the 404 page) plus a solid "شروع گفتگو" CTA (416:6024,
 * reusing `common.start_conversation` rather than a new key for the same
 * string) to `route('contact')`, and a small hand-drawn scribble (416:6040)
 * near the card's inline-end corner. All three were missing entirely — the
 * card previously rendered only the checkmark and a single line of text.
 *
 * A second decoration — a small hand-drawn tick-mark cluster ("Group 33",
 * user-supplied) — sits near the card's inline-start edge on BOTH states:
 * `416:5469` (default, at card-relative 47,355) and `416:6026` (success, at
 * card-relative 48,224). Neither is a descendant of the card frame itself —
 * `get_design_context` on the card alone won't surface them — they're
 * siblings positioned against the full-bleed overlay wrapper (`416:4927`
 * default / `416:6001` success), found via `get_metadata` on those wrappers.
 * Reused as one asset (`lead-magnet-tick-marks.svg`) at two positions.
 *
 * A visual diff against a fresh Figma screenshot of `416:4927` while placing
 * the tick marks turned up a third, still-missing decoration: the default
 * (non-success) card never got the scratch/pencil-mark accent near the email
 * field that the success card already has (`416:6040`, built as
 * `successScribbleUrl`). Its default-state counterpart is `416:5484`, at
 * card-relative 617,217.5 — reuses the same asset, no new file needed.
 *
 * Dialog mechanics copy `MobileMenu.vue`: focus moves into the panel on open,
 * Escape and a backdrop click both close it, body scroll is locked while open.
 */
const props = defineProps<{
  open: boolean
  /** Matches `NewsletterSubscriptionRequest`'s `source` enum. */
  source: 'home' | 'article' | 'contact'
}>()

const emit = defineEmits<{ close: [] }>()

const { t } = useTranslations()

const panel = ref<HTMLElement | null>(null)
const nameInput = ref<HTMLInputElement | null>(null)
const succeeded = ref(false)

const form = useForm<{ name: string; email: string; source: string; website: string }>({
  name: '',
  email: '',
  source: props.source,
  website: '',
})

function submit(): void {
  form.post(route('newsletter.store'), {
    preserveScroll: true,
    onSuccess: async () => {
      succeeded.value = true
      form.reset()
      // The submit button this click focused is removed by the v-else swap
      // to the success view, which drops focus to <body> — and once focus
      // leaves the panel, Escape no longer bubbles through its handler.
      await nextTick()
      panel.value?.focus()
    },
  })
}

function close(): void {
  emit('close')
}

// Reset to the form state (not stuck on the success screen) the next time
// this opens, and route focus the same way MobileMenu does.
watch(
  () => props.open,
  async (open) => {
    document.body.style.overflow = open ? 'hidden' : ''

    if (open) {
      succeeded.value = false
      form.clearErrors()
      await nextTick()
      if (nameInput.value) {
        nameInput.value.focus()
      } else {
        panel.value?.focus()
      }
    }
  },
)
</script>

<template>
  <Transition
    enter-active-class="transition-opacity duration-300 ease-brand"
    enter-from-class="opacity-0"
    leave-active-class="transition-opacity duration-200 ease-brand"
    leave-to-class="opacity-0"
  >
    <div v-if="props.open" class="fixed inset-0 z-menu flex items-center justify-center p-4">
      <div class="absolute inset-0 bg-ink-1000/80 backdrop-blur-sm" @click="close" />

      <div
        ref="panel"
        tabindex="-1"
        role="dialog"
        aria-modal="true"
        :aria-label="t('forms.newsletter.dialog_label')"
        class="relative flex w-full max-w-[706px] flex-col items-center gap-4 rounded-lg p-8 outline-none md:p-12"
        style="
          background-image:
            linear-gradient(-32.38deg, rgb(248 185 55 / 0) 2.3248%, rgb(248 185 55 / 10%) 100%),
            linear-gradient(0deg, #141414, #141414);
        "
        @keydown.esc="close"
      >
        <button
          type="button"
          class="absolute end-4 top-4 inline-flex size-9 items-center justify-center rounded-sm text-ink-300 transition-colors duration-200 ease-brand hover:text-paper"
          :aria-label="t('common.close')"
          @click="close"
        >
          <X class="size-5" aria-hidden="true" />
        </button>

        <template v-if="!succeeded">
          <div class="flex flex-col items-center gap-6 text-center">
            <p class="text-heading-xl text-ink-50">{{ t('forms.newsletter.title') }}</p>
            <p class="max-w-[612px] text-body-md text-ink-300">
              {{ t('forms.newsletter.description') }}
            </p>
          </div>

          <form
            novalidate
            class="mt-2 flex w-full max-w-[396px] flex-col items-center gap-8"
            @submit.prevent="submit"
          >
            <!-- Honeypot: never shown, never announced; bots fill it, people cannot. -->
            <div class="hidden" aria-hidden="true">
              <label for="lead-magnet-website">Website</label>
              <input
                id="lead-magnet-website"
                v-model="form.website"
                type="text"
                tabindex="-1"
                autocomplete="off"
              />
            </div>

            <div class="flex w-full flex-col gap-2">
              <label for="lead-magnet-name" class="text-label-lg text-ink-100">
                {{ t('forms.newsletter.name') }}
              </label>
              <div
                class="flex items-center gap-2 rounded-lg border-3 border-brand-300 bg-white/80 p-3"
              >
                <input
                  id="lead-magnet-name"
                  ref="nameInput"
                  v-model="form.name"
                  type="text"
                  autocomplete="name"
                  :placeholder="t('forms.newsletter.name_placeholder')"
                  class="w-full min-w-0 bg-transparent text-end text-body-md text-ink-1000 placeholder:text-ink-600 focus:outline-none"
                />
                <img
                  :src="userFieldUrl"
                  alt=""
                  aria-hidden="true"
                  width="24"
                  height="24"
                  class="size-6 shrink-0"
                />
              </div>
              <p v-if="form.errors.name" class="text-label-md text-brand">
                {{ form.errors.name }}
              </p>
            </div>

            <div class="flex w-full flex-col gap-2">
              <label for="lead-magnet-email" class="text-label-lg text-ink-100">
                {{ t('forms.newsletter.email') }}
              </label>
              <div
                class="flex items-center gap-2 rounded-lg border-3 border-brand-300 bg-white/80 p-3"
              >
                <input
                  id="lead-magnet-email"
                  v-model="form.email"
                  type="email"
                  autocomplete="email"
                  :placeholder="t('forms.newsletter.email_placeholder')"
                  class="w-full min-w-0 bg-transparent text-end text-body-md text-ink-1000 placeholder:text-ink-600 focus:outline-none"
                />
                <img
                  :src="envelopeIconUrl"
                  alt=""
                  aria-hidden="true"
                  width="24"
                  height="18"
                  class="size-6 shrink-0"
                />
              </div>
              <p v-if="form.errors.email" class="text-label-md text-brand">
                {{ form.errors.email }}
              </p>
            </div>

            <button
              type="submit"
              :disabled="form.processing"
              class="inline-flex items-center justify-center rounded-sm bg-brand px-6 py-3 text-title-sm text-ink-1000 transition-colors duration-200 ease-brand hover:bg-brand-900 disabled:pointer-events-none disabled:opacity-50"
            >
              {{ form.processing ? t('common.sending') : t('forms.newsletter.submit') }}
            </button>

            <p class="text-label-md text-ink-500">{{ t('forms.newsletter.disclaimer') }}</p>
          </form>

          <!-- 416:5469 "Group 33" — tick-mark cluster near the card's inline-start edge. -->
          <img
            :src="tickMarksUrl"
            alt=""
            aria-hidden="true"
            width="56"
            height="38"
            class="pointer-events-none absolute end-[47px] top-[355px] hidden w-14 h-[38px] md:block"
          />

          <!-- 416:5484 — scratch/pencil-mark accent near the email field, the
               default-state counterpart of the success card's 416:6040. -->
          <img
            :src="successScribbleUrl"
            alt=""
            aria-hidden="true"
            width="64"
            height="64"
            class="pointer-events-none absolute start-[25px] top-[218px] hidden size-16 md:block"
          />
        </template>

        <template v-else>
          <div class="flex flex-col items-center gap-6 py-4 text-center" role="status">
            <img
              :src="successIconUrl"
              alt=""
              aria-hidden="true"
              width="96"
              height="96"
              class="size-24"
            />
            <p class="whitespace-pre-line text-heading-sm text-ink-50">
              {{ t('forms.newsletter.success') }}
            </p>
            <CtaButton
              :label="t('common.start_conversation')"
              :href="route('contact')"
              @click="close"
            />
          </div>

          <!-- 416:6040 — a small hand-drawn scribble near the card's inline-end corner. -->
          <img
            :src="successScribbleUrl"
            alt=""
            aria-hidden="true"
            width="64"
            height="64"
            class="pointer-events-none absolute start-[33px] top-[111px] hidden size-16 md:block"
          />

          <!-- 416:6026 "Group 33" — tick-mark cluster near the card's inline-start edge. -->
          <img
            :src="tickMarksUrl"
            alt=""
            aria-hidden="true"
            width="56"
            height="38"
            class="pointer-events-none absolute end-[48px] top-[224px] hidden w-14 h-[38px] md:block"
          />
        </template>
      </div>
    </div>
  </Transition>
</template>
