<script setup lang="ts">
import { computed, nextTick, ref, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { X } from 'lucide-vue-next'
import BrandLogo from '@/Components/BrandLogo.vue'
import CtaButton from '@/Components/CtaButton.vue'
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue'
import type { NavItem, SharedProps } from '@/types'

/**
 * Off-canvas navigation for < lg.
 *
 * No mobile frames exist in the file, so this is derived: the same items and
 * the same tokens as the desktop header, stacked. Focus is moved into the
 * panel on open and the body is locked while it is showing.
 */
const props = defineProps<{ open: boolean }>()
const emit = defineEmits<{ close: [] }>()

const page = usePage<SharedProps>()

const items = computed<NavItem[]>(() => page.props.navigation.header)
const cta = computed(() => items.value.find((item) => item.isCta) ?? null)
const links = computed(() => items.value.filter((item) => !item.isCta))

const panel = ref<HTMLElement | null>(null)

watch(
  () => props.open,
  async (open) => {
    document.body.style.overflow = open ? 'hidden' : ''

    if (open) {
      await nextTick()
      panel.value?.focus()
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
    <div v-if="open" class="fixed inset-0 z-menu lg:hidden">
      <div class="absolute inset-0 bg-ink-1000/80 backdrop-blur-sm" @click="emit('close')" />

      <div
        ref="panel"
        tabindex="-1"
        role="dialog"
        aria-modal="true"
        :aria-label="$t('common.primary_navigation')"
        class="absolute inset-y-0 end-0 flex w-full max-w-sm flex-col gap-8 overflow-y-auto border-s border-ink-800 bg-ink-1000 p-6 outline-none"
        @keydown.esc="emit('close')"
      >
        <div class="flex items-center justify-between">
          <BrandLogo :width="88" :title="page.props.settings.siteName" />
          <button
            type="button"
            class="inline-flex size-11 items-center justify-center rounded-sm text-paper"
            :aria-label="$t('common.close_menu')"
            @click="emit('close')"
          >
            <X class="size-6" aria-hidden="true" />
          </button>
        </div>

        <nav :aria-label="$t('common.primary_navigation')">
          <ul class="flex flex-col gap-2">
            <li v-for="item in links" :key="item.id">
              <Link
                :href="item.url"
                :target="item.target"
                class="block rounded-sm py-3 text-title-lg text-ink-200 transition-colors duration-200 ease-brand hover:text-brand"
                @click="emit('close')"
              >
                {{ item.label }}
              </Link>
            </li>
          </ul>
        </nav>

        <div class="mt-auto flex flex-col gap-6">
          <CtaButton v-if="cta" :label="cta.label" :href="cta.url" class="w-full" />
          <LanguageSwitcher />
        </div>
      </div>
    </div>
  </Transition>
</template>
