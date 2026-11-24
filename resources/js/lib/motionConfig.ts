export const MOTION = {
  duration: {
    /** Component state changes (hover, open/close). */
    state: 0.3,
    reveal: 0.6,
    hero: 0.8,
  },
  ease: 'power3.out',
  stagger: 0.08,
  /** Home testimonials (TestimonialCarousel.vue), all in seconds. */
  testimonials: {
    /** Desktop drift: time for the loop to travel one card width. */
    driftPerCard: 5,
    /** Mobile/tablet autoplay: how long each slide rests before advancing. */
    dwell: 4.5,
    /** Mobile/tablet: one slide-to-slide move. */
    slide: 0.6,
  },
} as const

