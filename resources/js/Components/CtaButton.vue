<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import arrowUrl from '~img/sizdah/shared/cta-arrow.svg'

/**
 * The file's "CTA" component (21:2146).
 *
 * Figma: the component set 21:2164 ships four variants — sm/lg x BW/WB.
 * `sm` is space24/space12 with an 18px Regular label; `lg` is space32/space16
 * with a 20px Medium label. Both round on radiusSM and take an optional 24px
 * arrow at the inline start.
 *
 * Heights fall out of the padding plus the label's line box and must NOT be
 * pinned: sm measures 46px bare (12+22+12, e.g. the header CTA I268:3031;21:2147)
 * and 48px with the arrow, because the 24px glyph then outgrows the 22px text
 * box; lg measures 57px (16+25+16). An earlier `min-h-[57px]` here forced every
 * small button to the large button's height, inflating the header CTA by 11px.
 */
const props = withDefaults(
  defineProps<{
    label: string
    /** Inertia link target. Omit to render a <button>. */
    href?: string
    /**
     * `light` is the white-filled button on the final CTA card. The frame gives
     * it a white label on a white fill — unreadable — so it takes an ink label.
     *
     * `brand-outline` is the hollow button the Home lead magnet draws on cream
     * (391:4796): no fill, a 1px brand rule and a brand label. `outline` cannot
     * stand in — its ink rule and paper label are meant for the dark ground.
     */
    variant?: 'solid' | 'outline' | 'light' | 'brand-outline'
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
  'inline-flex items-center justify-center gap-1 rounded-sm',
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
      variant === 'brand-outline' &&
        'border border-brand text-brand hover:bg-brand hover:text-ink-1000',
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
