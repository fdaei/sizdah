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
 * The file's "logo" component (268:3017) — a 128px box holding an 80x67.287
 * mark, laid out six across on Home's trust strip (268:3002).
 *
 * The marks are drawn in Figma at full colour and knocked back with
 * `mix-blend-luminosity`, so they read as one grey row against the ink ground
 * instead of six competing brand palettes.
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
  <img
    v-if="source"
    :src="source"
    :alt="props.client.name"
    width="128"
    height="128"
    loading="lazy"
    decoding="async"
    class="size-32 shrink-0 object-contain opacity-80 mix-blend-luminosity"
  />
  <span v-else class="text-title-sm text-ink-400">{{ props.client.name }}</span>
</template>
