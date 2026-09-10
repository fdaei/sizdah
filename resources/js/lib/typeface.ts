/*
 | Smart typeface selection.
 |
 | Two faces, picked from the *content* rather than from the component that
 | happens to render it:
 |
 |   digits  -> Lahzeh  (لحظه)  — public/fonts/lahzeh/, `font-numeric`
 |   text    -> Peyda   (پیدا)  — public/fonts/peyda/,  `font-arabic`
 |
 | This module is deliberately free of Vue and of the DOM (apart from the
 | opt-in loader at the bottom) so the classification can be reasoned about
 | and tested on its own. `useSmartFont()` and `<SmartText>` are thin
 | reactive wrappers over what is exported here.
 |
 | The installed Lahzeh cut is FaNum, which draws ASCII 0-9 with the Persian
 | numeral glyphs. A value stored as "1402" therefore *displays* as ۱۴۰۲
 | with no transliteration step anywhere in PHP or JS — see fonts.css.
 */

/** Which of the two faces a chunk of content belongs to. */
export type Typeface = 'numeric' | 'text'

/**
 * What a whole string is. `mixed` means it holds both kinds and has to be
 * segmented; the two faces then sit side by side on one line.
 */
export type ContentType = 'empty' | 'numeric' | 'text' | 'mixed'

/** Writing direction for a chunk of content. */
export type Direction = 'rtl' | 'ltr'

/** One run of same-face content, in source order. */
export interface Segment {
  value: string
  typeface: Typeface
}

/*
 | ------------------------------------------------------------ character sets
 |
 | Digits are recognised in all three scripts the site can receive: ASCII
 | (1402), Persian/Extended-Arabic (۱۴۰۲, U+06F0) and Arabic-Indic (١٤٠٢,
 | U+0660). Admin-authored content and API payloads disagree about which
 | they use, so detection must not privilege one.
 */
const DIGITS = '0-9\u06F0-\u06F9\u0660-\u0669'

/*
 | Characters that stay *inside* a numeric run, but only when a digit sits on
 | both sides of them — that is what keeps "1402/07/12", "14:30", "3.14",
 | "1,200" and "10-20" as single numeric runs while leaving a sentence-final
 | "." or a standalone "-" in the text face where it belongs.
 |
 | U+066B / U+066C are the Arabic decimal and thousands separators. U+060C
 | (، the Arabic comma) is deliberately NOT here: it is overwhelmingly a
 | sentence comma in Persian copy, and treating it as a thousands separator
 | would fuse "۱۲، ۱۳" into one run.
 |
 | Currency and percent signs are likewise left to the text face. If a
 | designer later wants "۲۵٪" set entirely in Lahzeh, add ٪/% here as a
 | trailing-only case rather than widening this set.
 */
const SEPARATORS = '.,:/\\-\u066B\u066C'

/**
 * A maximal numeric run: a digit, then any number of (optional single
 * separator + digit) pairs. Anchoring both ends on a digit is what makes
 * the separators "internal only".
 */
const NUMERIC_RUN = new RegExp(`[${DIGITS}](?:[${SEPARATORS}]?[${DIGITS}])*`, 'gu')

/** The same pattern, whole-string — used by `isNumeric`. */
const NUMERIC_ONLY = new RegExp(`^[${DIGITS}](?:[${SEPARATORS}]?[${DIGITS}])*$`, 'u')

/*
 | Strong-directional characters, in the Unicode bidi sense. The Arabic
 | ranges below skip U+0660-0669 and U+06F0-06F9 on purpose: those digits
 | are bidi class AN, not strong RTL, so "۱۴۰۲" on its own is a left-to-right
 | run and must not flip a container to RTL.
 */
const STRONG_RTL = new RegExp(
  '[' +
    '֐-׿' + // Hebrew
    '؀-ٟ' + // Arabic — letters and diacritics, stopping before the digits
    'ٰ-ۯ' + // Arabic — superscript alef through the Persian letters (پ چ ژ گ ک ی)
    'ۺ-ۿ' + // Arabic — the block's tail, after the Persian digits
    '܀-ݿ' + // Syriac + Arabic Supplement
    'ࢠ-ࣿ' + // Arabic Extended-A
    'יִ-﷿' + // Hebrew + Arabic presentation forms A
    'ﹰ-﻿' + // Arabic presentation forms B
    ']',
  'u',
)

const STRONG_LTR = new RegExp(
  '[' +
    'A-Za-z' +
    'À-ɏ' + // Latin-1 Supplement + Latin Extended-A/B
    'Ͱ-Ͽ' + // Greek
    'Ѐ-ӿ' + // Cyrillic
    ']',
  'u',
)

/** CSS font-family stack per face, mirroring tailwind.config.js. */
export const TYPEFACE_FAMILY: Record<Typeface, string> = {
  numeric: 'Lahzeh',
  text: 'Peyda',
}

/**
 * Tailwind class per face. Always reach for these rather than writing
 * `font-['Lahzeh']` — the tokens carry the fallback chains.
 */
export const TYPEFACE_CLASS: Record<Typeface, string> = {
  // `tracking-numeric` (currently 0em) rides along with the face rather than sitting
  // on `.font-numeric` itself: letter-spacing is an element-level property, so
  // it can only be applied where a numeric run has an element of its own —
  // which is precisely what <SmartText> builds.
  numeric: 'font-numeric tracking-numeric',
  text: 'font-arabic',
}

/**
 * Split `value` into alternating numeric / text runs, in source order.
 *
 * Whitespace and punctuation between two numbers land in a text segment, so
 * "۱۴۰۲ و ۱۴۰۳" yields numeric / text / numeric rather than one fused run.
 * An empty or whitespace-only input yields no segments at all.
 */
export function segment(value: string): Segment[] {
  if (!value) {
    return []
  }

  const segments: Segment[] = []
  let cursor = 0

  // `NUMERIC_RUN` is a module-level /g regex, so its lastIndex has to be
  // reset before every walk — otherwise consecutive calls resume mid-string.
  NUMERIC_RUN.lastIndex = 0

  let match: RegExpExecArray | null
  while ((match = NUMERIC_RUN.exec(value)) !== null) {
    if (match.index > cursor) {
      segments.push({ value: value.slice(cursor, match.index), typeface: 'text' })
    }
    segments.push({ value: match[0], typeface: 'numeric' })
    cursor = match.index + match[0].length
  }

  if (cursor < value.length) {
    segments.push({ value: value.slice(cursor), typeface: 'text' })
  }

  return segments
}

/**
 * Classify a whole string.
 *
 * Whitespace-only text runs do not count towards `mixed` — " 1402 " is
 * numeric, not mixed — because padding is not content.
 */
export function detect(value: string | number | null | undefined): ContentType {
  if (value === null || value === undefined) {
    return 'empty'
  }

  const text = String(value)

  if (text.trim() === '') {
    return 'empty'
  }

  let hasNumeric = false
  let hasText = false

  for (const part of segment(text)) {
    if (part.typeface === 'numeric') {
      hasNumeric = true
    } else if (part.value.trim() !== '') {
      hasText = true
    }
  }

  if (hasNumeric && hasText) {
    return 'mixed'
  }

  return hasNumeric ? 'numeric' : 'text'
}

/**
 * True when the whole (trimmed) value is a single number — the "input is a
 * number" case in its strictest reading. `detect() === 'numeric'` is the
 * looser test and is what the UI actually keys on.
 */
export function isNumeric(value: string | number | null | undefined): boolean {
  if (value === null || value === undefined) {
    return false
  }

  return NUMERIC_ONLY.test(String(value).trim())
}

/**
 * The face a whole string should use when it is rendered as one unit.
 * `mixed` resolves to `text`, because the surrounding copy is what sets the
 * baseline; the digit runs inside it are then overridden segment by segment.
 */
export function typefaceFor(value: string | number | null | undefined): Typeface {
  return detect(value) === 'numeric' ? 'numeric' : 'text'
}

/**
 * First-strong direction resolution, matching how the browser's own bidi
 * algorithm picks a paragraph direction.
 *
 * A string with no strong character — a bare number, an email, punctuation —
 * resolves to `ltr`, which is correct for digits: ۱۴۰۲ reads left to right
 * even inside Persian copy.
 */
export function resolveDirection(value: string | number | null | undefined): Direction {
  if (value === null || value === undefined) {
    return 'ltr'
  }

  for (const char of String(value)) {
    if (STRONG_RTL.test(char)) {
      return 'rtl'
    }

    if (STRONG_LTR.test(char)) {
      return 'ltr'
    }
  }

  return 'ltr'
}

/*
 | -------------------------------------------------------------- font loading
 |
 | The @font-face rules in fonts.css are declarations, not downloads: a face
 | is only fetched when something on the page is actually laid out in it. For
 | content-driven switching that is a beat too late — the first numeric run
 | paints in the fallback and then reflows once Lahzeh lands.
 |
 | `ensureLoaded` warms the face through the CSS Font Loading API as soon as
 | the content type is known, which is typically a frame or two before paint.
 | Failures are swallowed on purpose: a face that will not load is a `swap`
 | to the fallback stack, not a broken page.
 */
const loading = new Map<string, Promise<void>>()

export function ensureLoaded(typeface: Typeface, weight: number | string = 400): Promise<void> {
  const family = TYPEFACE_FAMILY[typeface]
  const key = `${weight} ${family}`

  const cached = loading.get(key)
  if (cached) {
    return cached
  }

  // Guards SSR and the handful of browsers without document.fonts — both
  // degrade to the plain @font-face behaviour, which is still correct.
  if (typeof document === 'undefined' || !('fonts' in document)) {
    return Promise.resolve()
  }

  const pending = document.fonts
    .load(`${weight} 1em "${family}"`)
    .then(() => undefined)
    .catch(() => undefined)

  loading.set(key, pending)

  return pending
}

/** Test seam: forget which faces have been warmed. */
export function resetLoadedCache(): void {
  loading.clear()
}
