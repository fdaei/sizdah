/**
 * Shared prop shapes. These mirror what
 * App\Http\Middleware\HandleInertiaRequests::share() sends on every request,
 * plus the DTOs returned by the page controllers.
 */

export type LocaleCode = 'en' | 'fa' | 'ar'
export type Direction = 'ltr' | 'rtl'

export interface LocaleOption {
  code: LocaleCode
  name: string
  native: string
  direction: Direction
}

export interface LocaleProp {
  current: LocaleCode
  direction: Direction
  font: 'sans' | 'arabic'
  htmlLang: string
  supported: LocaleOption[]
}

export interface NavItem {
  id: number
  label: string
  url: string
  target: '_self' | '_blank'
  isCta: boolean
  children: NavItem[]
}

export interface SocialLink {
  platform: string
  label: string
  url: string
  icon: string
}

export interface SiteSettings {
  siteName: string
  tagline: string
  description: string
  contact: {
    whatsapp: string
    phone: string
    email: string
    location: string
    workingWith: string
  }
  socialLinks: SocialLink[]
  seo: {
    defaultTitle: string
    defaultDescription: string
    defaultImage: string | null
    organizationName: string
  }
}

export interface SeoMeta {
  title: string
  description: string
  image: string | null
  canonical: string
  type: 'website' | 'article'
  publishedAt?: string | null
  modifiedAt?: string | null
  author?: string | null
  noindex?: boolean
}

export interface MediaImage {
  src: string
  srcset?: string
  alt: string
  width: number
  height: number
}

export interface SharedProps {
  locale: LocaleProp
  alternates: Record<LocaleCode, string>
  navigation: {
    header: NavItem[]
    footer: NavItem[]
  }
  settings: SiteSettings
  flash: {
    success: string | null
    error: string | null
  }
  auth: {
    user: { id: number; name: string; email: string } | null
  }
  [key: string]: unknown
}

/* -------------------------------------------------------- Editorial blocks */

/**
 * Mirrors `ContentTransformer::cta()` — null when the section has no label.
 */
export interface SectionCta {
  label: string
  url: string
}

/**
 * Mirrors `ContentTransformer::sectionItem()`. One row of a section's repeater:
 * a KPI, a process step, a why-us card. Every text field is emitted as a string
 * (never null) by the transformer, so components can render without guards.
 */
export interface SectionItem {
  id: number
  value: string
  label: string
  suffix: string
  title: string
  description: string
  badge: string
  features: string[]
  footer: string
  icon: string | null
  image: MediaImage | null
}

/**
 * Mirrors `ContentTransformer::section()`. Editorial copy for one section —
 * the eyebrow/title/subtitle plus any repeater items.
 */
export interface PageSectionData {
  type: string
  eyebrow: string
  title: string
  subtitle: string
  description: string
  content: string
  colors: {
    eyebrow: string | null
    title: string | null
    subtitle: string | null
    description: string | null
    content: string | null
  }
  primaryCta: SectionCta | null
  secondaryCta: SectionCta | null
  image: MediaImage | null
  items: SectionItem[]
}

/**
 * `ContentTransformer::sectionMap()` — keyed by `SectionType` value, so a page
 * reads `sections.hero`. A key is absent when the editor has not created that
 * section, hence the optional value.
 */
export type SectionMap = Record<string, PageSectionData | undefined>

/* ------------------------------------------------------------ Content DTOs */

export interface ProjectSummary {
  slug: string
  title: string
  excerpt: string
  industry: string
  url: string
  /** Listing cards reveal these on hover — Figma "project detail" 553:921. */
  services: string[]
  image: MediaImage | null
}

export interface ProjectDetail extends ProjectSummary {
  year: string | null
  instagram: string | null
  challenge: string
  challengePoints: string[]
  goals: CardItem[]
  strategy: CardItem[]
  deliverables: CardItem[]
  showcase: MediaImage[]
  results: ResultStat[]
  resultsSummary: string
  beforeAfter: { before: MediaImage | null; after: MediaImage | null }
  next: { slug: string; title: string; url: string } | null
  banner: MediaImage | null
}

export interface CardItem {
  title: string
  description: string
}

export interface ResultStat {
  label: string
  value: string
}

export interface ServiceItem {
  slug: string
  title: string
  description: string
  features: string[]
  image: MediaImage | null
}

export interface PostSummary {
  slug: string
  title: string
  excerpt: string
  url: string
  /** Already formatted for the active locale — see ContentTransformer. */
  publishedAt: string
  /** ISO 8601, for <time datetime>. */
  publishedAtIso: string
  readingTime: number
  category: { slug: string; name: string } | null
  image: MediaImage | null
}

export interface PostDetail extends PostSummary {
  subtitle: string
  content: string
  author: { name: string; email: string | null } | null
  tags: { slug: string; name: string }[]
  related: PostSummary[]
}

export interface TeamMemberItem {
  name: string
  role: string
  image: MediaImage | null
}

export interface TestimonialItem {
  quote: string
  name: string
  role: string
  avatar: MediaImage | null
}

export interface FaqItem {
  question: string
  answer: string
}

export interface ClientItem {
  name: string
  logo: string
  url: string | null
}

export interface KpiItem {
  value: string
  label: string
}
