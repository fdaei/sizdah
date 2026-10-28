import type { CtaVariant, SectionCta } from '@/types'

/**
 * Editor overrides for a section button (Filament "Buttons" panel). Each
 * component passes its own design default for the slot, so an untouched
 * section keeps rendering exactly as the Figma frame draws it.
 */
export function ctaVariant(cta: SectionCta | null | undefined, fallback: CtaVariant): CtaVariant {
  return cta?.variant ?? fallback
}

export function ctaArrow(cta: SectionCta | null | undefined, fallback: boolean): boolean {
  return cta?.icon ? cta.icon === 'arrow' : fallback
}
