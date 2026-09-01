<script setup lang="ts">
import { nextTick, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { X } from 'lucide-vue-next'
import { route } from 'ziggy-js'
import { useTranslations } from '@/Composables/useTranslations'
import envelopeIconUrl from '~img/sizdah/shared/lead-magnet-envelope.svg'
import successIconUrl from '~img/sizdah/shared/lead-magnet-success.svg'

/**
 * Lead-magnet signup popup — Figma "lead magnet form" 416:5399 (default) and
 * 416:6003 (success), found nested as an overlay inside two otherwise-identical
 * "blog list" exploration frames (416:4959 / 416:5834) rather than filed as
 * their own component sheet — easy to miss, and previously missed: see GAPS
 * G51. `LeadMagnetBanner`'s CTA opens this rather than navigating anywhere.
 *
 * The frame draws a "نام و نام خانوادگی" (full name) field alongside email,
 * but `newsletter_subscriptions` (and `NewsletterSubscriptionRequest`) only
 * ever collect an email — no `name` column exists. Adding one is a schema
 * change for a value nothing downstream would read (the success state doesn't
 * greet the user by name), so this renders email-only, matching the real
 * contract rather than the frame literally. See GAPS G51.
 *
 * Dialog mechanics copy `MobileMenu.vue`: focus moves into the panel on open,
 * Escape and a backdrop click both close it, body scroll is locked while open.
 */
const props = defineProps<{
  open: boolean
  title: string
  description?: string | null
  /** Matches `NewsletterSubscriptionRequest`'s `source` enum. */
  source: 'home' | 'article' | 'contact'
}>()

const emit = defineEmits<{ close: [] }>()

const { t } = useTranslations()

const panel = ref<HTMLElement | null>(null)
const emailInput = ref<HTMLInputElement | null>(null)
const succeeded = ref(false)

const form = useForm<{ email: string; source: string; website: string }>({
  email: '',
  source: props.source,
  website: '',
})

function submit(): void {
  form.post(route('newsletter.store'), {
    preserveScroll: true,
    onSuccess: () => {
      succeeded.value = true
      form.reset()
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
      emailInput.value ? emailInput.value.focus() : panel.value?.focus()
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
            <p class="text-heading-xl text-ink-50">{{ props.title }}</p>
            <p v-if="props.description" class="max-w-[612px] text-body-md text-ink-300">
              {{ props.description }}
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
              <label for="lead-magnet-email" class="text-label-lg text-ink-100">
                {{ t('forms.newsletter.email') }}
              </label>
              <div
                class="flex items-center gap-2 rounded-lg border-3 border-[#fdeac3] bg-white/80 p-3"
              >
                <input
                  id="lead-magnet-email"
                  ref="emailInput"
                  v-model="form.email"
                  type="email"
                  autocomplete="email"
                  :placeholder="t('forms.newsletter.email_placeholder')"
                  class="w-full min-w-0 bg-transparent text-end text-body-md text-ink-1000 placeholder:text-ink-600 focus:outline-none"
                />
                <img :src="envelopeIconUrl" alt="" aria-hidden="true" width="24" height="18" class="size-6 shrink-0" />
              </div>
              <p v-if="form.errors.email" class="text-label-md text-brand">{{ form.errors.email }}</p>
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
        </template>

        <template v-else>
          <div class="flex flex-col items-center gap-6 py-4 text-center" role="status">
            <img :src="successIconUrl" alt="" aria-hidden="true" width="96" height="96" class="size-24" />
            <p class="text-heading-sm text-ink-50">{{ t('forms.newsletter.success') }}</p>
          </div>
        </template>
      </div>
    </div>
  </Transition>
</template>
