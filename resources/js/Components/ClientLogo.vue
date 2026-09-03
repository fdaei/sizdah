<script setup lang="ts">
import { computed } from 'vue'
import type { ClientItem } from '@/types'
import parsUrl from '~img/sizdah/clients/pars.svg'
import allDigitallUrl from '~img/sizdah/clients/alldigitall.svg'
import baghcheUrl from '~img/sizdah/clients/baghche.svg'
import nooraUrl from '~img/sizdah/clients/noora.svg'
import vanakUrl from '~img/sizdah/clients/vanak.svg'
import tavakoliUrl from '~img/sizdah/clients/tavakoli.svg'

/**
 * The file's "logo" component (268:3017) — a 128px box holding an 80px-wide
 * mark, laid out six across on Home's trust strip (268:3002). Only the width
 * is fixed; each mark keeps its own height (pars is 80x24.41), so the box
 * centres them rather than scaling them to fill it.
 *
 * The marks are drawn in Figma at full colour and knocked back to luminosity,
 * so they read as one grey row against the ink ground instead of six
 * competing brand palettes. Hovering restores each mark's original colour.
 * Figma's own paint is `mix-blend-mode: luminosity`,
 * but every logo here sits inside a GSAP `data-reveal` item — GSAP leaves a
 * non-`none` `transform` on the `<li>` even at rest (`translate(0px, 0px)`),
 * which per spec creates a new stacking context and silently isolates any
 * descendant `mix-blend-mode` from the real page background, so it blends
 * against nothing and renders at full colour. `grayscale` is self-contained
 * (a filter, not a compositing blend) and immune to that, so it stands in.
 *
 * Sourcing order: the CMS logo if an editor has uploaded one, otherwise the
 * frame export matched on the client's name. Every `Client` row currently has
 * a null `logo`, so in practice the exports carry this section — but an upload
 * takes over the moment one is made, without a code change. A client that
 * matches neither falls back to its name as text rather than a blank cell.
 */
const props = defineProps<{ client: ClientItem }>()

/*
 | Keyed on the seeded `Client.name` values, which match the six marks the
 | frame ships one-for-one and in the same order.
 */
const EXPORTS: Record<string, string> = {
  'کلینیک پارس': parsUrl,
  'allDigitall.ir': allDigitallUrl,
  باغچه: baghcheUrl,
  نورا: nooraUrl,
  ونک: vanakUrl,
  توکلی: tavakoliUrl,
}

const source = computed(() => props.client.logo || EXPORTS[props.client.name] || null)
</script>

<template>
  <span v-if="source" class="flex size-32 shrink-0 items-center justify-center">
    <img
      :src="source"
      :alt="props.client.name"
      width="80"
      loading="lazy"
      decoding="async"
      class="h-auto w-20 object-contain grayscale transition-[filter] duration-300 ease-brand hover:grayscale-0"
    />
  </span>
  <span v-else class="text-title-sm text-ink-400">{{ props.client.name }}</span>
</template>
