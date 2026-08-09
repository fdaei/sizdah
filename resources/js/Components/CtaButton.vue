<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import arrowUrl from '~img/sizdah/shared/cta-arrow.svg'

/**
 * The file's "CTA" component (21:2146).
 *
 * Figma: Yellow/1000 fill, radiusSM, space24 inline / space12 block padding,
 * 18px ink label, optional 24px arrow at the inline start. Every instance in
 * the file measures 57px tall, which the token padding alone does not produce
 * — the component carries an explicit min-height so buttons line up with the
 * frames rather than sitting 6px short.
 */
const props = withDefaults(
  defineProps<{
    label: string
    /** Inertia link target. Omit to render a <button>. */
    href?: string
    /**
     * `light` is the white-filled button on the final CTA card. The frame gives
     * it a white label on a white fill — unreadable — so it takes an ink label.
     */
    variant?: 'solid' | 'outline' | 'light'
    /**
     * `md` is the hero/inline size (space24 / space12, 18px label). `lg` is the
     * standalone size the 404 and final-CTA frames use (space32 / space16,
     * 20px label) — both land on the same 57px height.
     */
    size?: 'md' | 'lg'
    withArrow?: boolean
    type?: 'button' | 'submit'
    disabled?: boolean
    external?: boolean
  }>(),
  {
    href: undefined,
    variant: 'solid',
    size: 'md',
    withArrow: false,
    type: 'button',
    disabled: false,
    external: false,
  },
)

defineEmits<{ click: [event: MouseEvent] }>()

const classes = computed(() => [
  'inline-flex min-h-[57px] items-center justify-center gap-1 rounded-sm',
  'transition-colors duration-200 ease-brand',
  props.size === 'lg' ? 'gap-2 px-8 py-4 text-title-md' : 'px-6 py-3 text-title-sm',
])
</script>

<template>
  <component
    :is="href ? (external ? 'a' : Link) : 'button'"
    :href="href"
    :type="href ? undefined : type"
    :disabled="href ? undefined : disabled"
    :target="external ? '_blank' : undefined"
    :rel="external ? 'noopener noreferrer' : undefined"
    :class="[
      classes,
      variant === 'solid' && 'bg-brand text-ink-1000 hover:bg-brand-900',
      variant === 'light' && 'bg-white text-ink-1000 hover:bg-ink-50',
      variant === 'outline' &&
        'border border-ink-600 text-paper hover:border-brand hover:text-brand',
      disabled && 'pointer-events-none opacity-50',
    ]"
    @click="$emit('click', $event)"
  >
    <!--
      The arrow points along the reading direction, so it mirrors under RTL
      (.flip-rtl in app.css) rather than being duplicated per locale.
    -->
    <img
      v-if="withArrow"
      :src="arrowUrl"
      alt=""
      aria-hidden="true"
      width="24"
      height="24"
      class="size-6 flip-rtl"
    />
    <span>{{ props.label }}</span>
  </component>
</template>
