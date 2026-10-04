import type { PageSectionData } from '@/types'

type ColorField = keyof PageSectionData['colors']

export interface HeadingColors {
  eyebrow?: string | null
  title?: string | null
  subtitle?: string | null
}

/**
 * The optional per-field text colours an editor sets under "Text colours" on a
 * section in Filament. Returned as an inline style so it wins over the design
 * default class, and as `undefined` when unset so the default stays in charge.
 */
export function sectionColor(
  section: PageSectionData | null | undefined,
  field: ColorField,
): { color: string } | undefined {
  const color = section?.colors?.[field]

  return color ? { color } : undefined
}

/**
 * Colours for a `SectionHeading`. The heading's subtitle slot shows the
 * section's subtitle, falling back to its description, so the colour follows
 * whichever field actually fills it.
 */
export function headingColors(section: PageSectionData | null | undefined): HeadingColors {
  if (!section) {
    return {}
  }

  return {
    eyebrow: section.colors?.eyebrow,
    title: section.colors?.title,
    subtitle: section.subtitle ? section.colors?.subtitle : section.colors?.description,
  }
}
