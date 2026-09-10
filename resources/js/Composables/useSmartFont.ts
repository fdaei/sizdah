import { computed, toValue, watchEffect, type ComputedRef, type MaybeRefOrGetter } from 'vue'
import {
  detect,
  ensureLoaded,
  isNumeric as isNumericValue,
  resolveDirection,
  segment,
  TYPEFACE_CLASS,
  TYPEFACE_FAMILY,
  typefaceFor,
  type ContentType,
  type Direction,
  type Segment,
  type Typeface,
} from '@/lib/typeface'

/**
 * Reactive face selection for a piece of content.
 *
 * The rule the design asks for is content-driven, not component-driven:
 * digits are set in Lahzeh (لحظه) and everything else in Peyda (پیدا). This
 * composable is the reactive seam over `lib/typeface.ts` — it re-classifies
 * whenever the source changes and warms the chosen face through the CSS Font
 * Loading API so the switch does not flash the fallback.
 *
 * For display, prefer `<SmartText>`, which also handles the per-run split of
 * mixed content. Reach for the composable directly when the face has to land
 * on an element you already own — most often a live input:
 *
 *   const text = ref('')
 *   const { fontClass, direction } = useSmartFont(text)
 *   <input v-model="text" :class="fontClass" :dir="direction" />
 *
 * Passing the ref itself (not `text.value`) is what keeps it reactive.
 */
export interface SmartFont {
  /** `numeric` | `text` | `mixed` | `empty`. */
  contentType: ComputedRef<ContentType>
  /** The face for the value taken as a whole; `mixed` resolves to `text`. */
  typeface: ComputedRef<Typeface>
  /** Tailwind class for that face — `font-numeric` or `font-arabic`. */
  fontClass: ComputedRef<string>
  /** The CSS family name, for the rare case a style binding needs it. */
  fontFamily: ComputedRef<string>
  /** First-strong direction, for the `dir` attribute. */
  direction: ComputedRef<Direction>
  /** The value split into same-face runs, in source order. */
  segments: ComputedRef<Segment[]>
  /** True only when the whole value is one number. */
  isNumeric: ComputedRef<boolean>
}

export interface SmartFontOptions {
  /**
   * Weight to warm. Only affects which file is prefetched — the rendered
   * weight still comes from the element's own CSS.
   */
  weight?: MaybeRefOrGetter<number | string>
  /**
   * Set false to skip the prefetch. Worth doing for content that is far
   * below the fold, where `font-display: swap` alone is enough.
   */
  preload?: boolean
}

export function useSmartFont(
  source: MaybeRefOrGetter<string | number | null | undefined>,
  options: SmartFontOptions = {},
): SmartFont {
  const { weight = 400, preload = true } = options

  const raw = computed(() => {
    const value = toValue(source)

    return value === null || value === undefined ? '' : String(value)
  })

  const contentType = computed(() => detect(raw.value))
  const typeface = computed(() => typefaceFor(raw.value))
  const segments = computed(() => segment(raw.value))
  const direction = computed(() => resolveDirection(raw.value))
  const fontClass = computed(() => TYPEFACE_CLASS[typeface.value])
  const fontFamily = computed(() => TYPEFACE_FAMILY[typeface.value])
  const isNumeric = computed(() => isNumericValue(raw.value))

  if (preload) {
    /*
     | Warms every face the current value needs — both of them while the
     | content is mixed. `ensureLoaded` de-duplicates per family+weight, so
     | re-running on each keystroke costs one Map lookup, not a request.
     */
    watchEffect(() => {
      const faces = new Set(segments.value.map((part) => part.typeface))

      for (const face of faces) {
        void ensureLoaded(face, toValue(weight))
      }
    })
  }

  return {
    contentType,
    typeface,
    fontClass,
    fontFamily,
    direction,
    segments,
    isNumeric,
  }
}
