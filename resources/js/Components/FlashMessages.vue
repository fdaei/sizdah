<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { CheckCircle2, X, XCircle } from 'lucide-vue-next'
import type { SharedProps } from '@/types'

/**
 * Session flash banner — form submissions and the 419 redirect both land here.
 *
 * Announced politely so a screen reader hears the result of a submit without
 * losing the caret position in the form.
 */
const page = usePage<SharedProps>()

const dismissed = ref(false)

const flash = computed(() => page.props.flash)
const message = computed(() => flash.value.success ?? flash.value.error ?? null)
const isError = computed(() => flash.value.error !== null && flash.value.error !== undefined)

watch(message, () => {
  dismissed.value = false
})
</script>

<template>
  <Transition
    enter-active-class="transition duration-300 ease-brand"
    enter-from-class="-translate-y-2 opacity-0"
    leave-active-class="transition duration-200 ease-brand"
    leave-to-class="-translate-y-2 opacity-0"
  >
    <div
      v-if="message && !dismissed"
      role="status"
      aria-live="polite"
      class="fixed inset-x-0 top-[108px] z-overlay mx-auto w-fit max-w-[90vw] px-4"
    >
      <div
        class="flex items-center gap-3 rounded-sm border px-6 py-3 text-label-lg shadow-card"
        :class="
          isError
            ? 'border-red-500/40 bg-ink-900 text-red-300'
            : 'border-brand/40 bg-ink-900 text-brand'
        "
      >
        <component
          :is="isError ? XCircle : CheckCircle2"
          class="size-5 shrink-0"
          aria-hidden="true"
        />
        <p>{{ message }}</p>
        <button
          type="button"
          class="ms-2 shrink-0 rounded-xs text-ink-400 transition-colors hover:text-paper"
          :aria-label="$t('common.close_menu')"
          @click="dismissed = true"
        >
          <X class="size-4" aria-hidden="true" />
        </button>
      </div>
    </div>
  </Transition>
</template>
