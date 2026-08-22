import gsap from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

/**
 * GSAP lifecycle.
 *
 * Inertia swaps the page component without a full reload, so ScrollTrigger
 * instances from the previous page would survive, keep listening, and fight the
 * new layout. app.ts tears them down on `before` and re-initialises after two
 * frames on `navigate`.
 *
 * The Figma file stores no keyframe data, so durations and easing below are
 * ours, not extracted. Tune them here — nothing else should define timing.
 */

let registered = false

export const MOTION = {
  duration: {
    /** Component state changes (hover, open/close). */
    state: 0.3,
    reveal: 0.6,
    hero: 0.8,
  },
  ease: 'power3.out',
  stagger: 0.08,
} as const

function prefersReducedMotion(): boolean {
  return window.matchMedia('(prefers-reduced-motion: reduce)').matches
}

/**
 * Scroll-reveal for anything marked `data-reveal`, or every child of a
 * `data-reveal-group` so a row animates together.
 *
 * The hidden start state lives in CSS behind `html.motion-ready` (app.css), so
 * if this bundle ever fails to run the content is simply visible rather than
 * stuck at opacity 0.
 */
function buildReveals(): void {
  const groups = gsap.utils.toArray<HTMLElement>('[data-reveal-group]')

  groups.forEach((group) => {
    const children = Array.from(group.children) as HTMLElement[]

    children.forEach((child) => child.classList.add('will-reveal'))

    gsap.to(children, {
      opacity: 1,
      y: 0,
      duration: MOTION.duration.reveal,
      ease: MOTION.ease,
      stagger: MOTION.stagger,
      scrollTrigger: { trigger: group, start: 'top 85%', once: true },
    })
  })

  const singles = gsap.utils.toArray<HTMLElement>('[data-reveal]')

  singles.forEach((element) => {
    element.classList.add('will-reveal')

    gsap.to(element, {
      opacity: 1,
      y: 0,
      duration: MOTION.duration.reveal,
      ease: MOTION.ease,
      scrollTrigger: { trigger: element, start: 'top 85%', once: true },
    })
  })
}

/**
 * Counts a KPI value up to its authored number.
 *
 * Only the digits are animated — the element keeps whatever prefix/suffix the
 * editor typed ("+۷۰ هزار"), and the authored digit system is preserved by
 * mapping each rendered digit back through the original string's numerals.
 */
function buildCounters(): void {
  const counters = gsap.utils.toArray<HTMLElement>('[data-counter]')

  counters.forEach((element) => {
    const raw = element.dataset.counter ?? ''
    const digits = raw.match(/[\d۰-۹٠-٩]+/)

    if (digits === null) {
      return
    }

    // Normalise Persian (U+06Fx) and Arabic-Indic (U+066x) digits to ASCII so
    // the tween has a real number to count, then map back on render.
    const toAscii = (input: string): string =>
      input
        .replace(/[۰-۹]/g, (d) => String(d.charCodeAt(0) - 0x06f0))
        .replace(/[٠-٩]/g, (d) => String(d.charCodeAt(0) - 0x0660))

    const sample = digits[0]
    const target = Number(toAscii(sample))

    if (!Number.isFinite(target)) {
      return
    }

    const base = sample.codePointAt(0) ?? 0
    const offset =
      base >= 0x06f0 && base <= 0x06f9 ? 0x06f0 : base >= 0x0660 && base <= 0x0669 ? 0x0660 : 0x0030

    const render = (value: number): string =>
      String(Math.round(value))
        .split('')
        .map((d) => String.fromCodePoint(offset + Number(d)))
        .join('')

    const counter = { value: 0 }

    gsap.to(counter, {
      value: target,
      duration: 1.6,
      ease: 'power2.out',
      scrollTrigger: { trigger: element, start: 'top 90%', once: true },
      onUpdate: () => {
        element.textContent = raw.replace(sample, render(counter.value))
      },
    })
  })
}

export function initMotion(): void {
  if (typeof window === 'undefined') {
    return
  }

  if (prefersReducedMotion()) {
    document.documentElement.classList.add('reduced-motion')

    return
  }

  if (!registered) {
    gsap.registerPlugin(ScrollTrigger)
    registered = true
  }

  document.documentElement.classList.add('motion-ready')

  buildReveals()
  buildCounters()

  ScrollTrigger.refresh()
}

export function killPageAnimations(): void {
  ScrollTrigger.getAll().forEach((trigger) => trigger.kill())
  gsap.globalTimeline.clear()
}
