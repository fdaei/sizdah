<script setup lang="ts">
import { computed } from 'vue'
import engagementUrl from '~img/sizdah/home/kpi-engagement.svg'
import audienceUrl from '~img/sizdah/home/kpi-audience.svg'
import retentionUrl from '~img/sizdah/home/kpi-retention.svg'

/**
 * The file's "KPIs" component (71:2145) — three across on Home (268:3026).
 *
 * Figma: 4px border at Yellow/1000 40%, radiusSM, space12 inline / space24
 * block padding, over a 117.4deg wash that fades the same yellow from 6% to 0.
 * Value is Display/Small on Yellow/50, label is title/Medium on Yellow/1000,
 * and caption is title/Small on Black/300.
 *
 * The 4px rule is an inset ring, not a border: Figma draws strokes inside the
 * frame, so the card measures 153 including its rule. A CSS border sits outside
 * the padding box and pushed every card to 161.
 */
const props = defineProps<{
  /** Authored verbatim by the editor — already in the locale's digits. */
  value: string
  label: string
  caption?: string
  /** `SectionItem.icon`; unknown or missing keys render without a glyph. */
  icon?: string | null
}>()

/*
 | Composed from the exported Figma path fragments by
 | scripts/compose-figma-icons.py — see the docblock there. Keys are the
 | seeded `_icon` names, one per card in 268:3026: trading-graph,
 | business-coaching-strategy-1 and job-choose-candidate.
 */
const ICONS: Record<string, string> = {
  engagement: engagementUrl,
  audience: audienceUrl,
  retention: retentionUrl,
}

const iconUrl = computed(() => (props.icon ? ICONS[props.icon] : undefined))
</script>

<template>
  <div
    class="flex flex-col items-center justify-center gap-2 rounded-sm px-3 py-6 text-center ring-4 ring-inset ring-brand/40"
    style="
      background-image: linear-gradient(
        117.4deg,
        rgb(245 185 64 / 6%) 28.79%,
        rgb(245 185 64 / 0%) 100.08%
      );
    "
  >
    <!--
      71:2135 sits the value and its glyph on space8, not space16. The glyph
      leads in DOM order because the frame draws it on the reading-order side
      of the number — the right of the value in RTL, mirrored in LTR.
    -->
    <div class="flex items-center justify-center gap-2">
      <img
        v-if="iconUrl"
        :src="iconUrl"
        alt=""
        aria-hidden="true"
        width="32"
        height="32"
        class="size-8 shrink-0"
      />
      <!--
        `dir="auto"` as the frame emits it: "+40%" carries no strong character,
        so an RTL paragraph would reorder the sign to the trailing edge and
        render it "40%+".
      -->
      <p class="latin-nums text-display-sm text-brand-50" dir="auto" :data-counter="value">
        {{ value }}
      </p>
    </div>

    <div class="flex flex-col gap-1">
      <p class="text-title-md text-brand">{{ label }}</p>
      <p v-if="caption" class="text-title-sm text-ink-300">{{ caption }}</p>
    </div>
  </div>
</template>
