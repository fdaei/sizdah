<template>
  <!--
    Figma `cross 1` (`165:1533`) from the file's own icon sheet `149:2691` —
    the Streamline Freehand set every other glyph on the site was pulled from.
    Replaces `lucide-vue-next`'s `X`; see GAPS G67.

    Inline rather than an `<img>` (the usual choice here, per `AppFooter.vue`)
    because all three call sites drive the colour from the button — `text-paper`
    in `MobileMenu`, `text-ink-300 hover:text-paper` in `LeadMagnetModal`,
    `text-ink-400 hover:text-paper` in `FlashMessages`. `currentColor` is what
    makes those hovers reach the glyph.

    The export's own box is 64x64 but the drawing sits at x 13.86-62.95 /
    y 5.77-62.00 inside it, so rendering at `0 0 64 64` would shove the cross
    to the right and down. The viewBox below is the glyph's own bounds,
    squared off, so it optically centres in a square button.
  -->
  <svg
    viewBox="10.29 5.77 56.23 56.23"
    fill="currentColor"
    xmlns="http://www.w3.org/2000/svg"
    aria-hidden="true"
    focusable="false"
  >
    <path
      d="M62.3059 6.6895C61.6575 5.88872 60.4817 5.76763 59.6809 6.41997V6.41216C56.4426 9.07818 46.2903 20.8731 36.1926 32.836C34.6985 30.2754 33.3313 27.7774 32.1262 25.4278C26.8801 15.1954 24.4075 7.64654 24.3899 7.63286C24.0598 6.64849 23.009 6.11919 22.0325 6.44146C21.05 6.76372 20.5207 7.82427 20.843 8.79888C20.8801 8.88286 24.968 21.3106 33.6399 35.8672C25.7453 45.2501 18.2453 54.2696 14.5344 58.5625C13.8586 59.3438 13.9426 60.5235 14.7258 61.1993C15.0813 61.504 15.5149 61.6524 15.9504 61.6524C16.47 61.6524 16.9895 61.4336 17.3606 61.004C20.8996 56.8868 28.0813 48.2735 35.7004 39.2227C40.4543 46.7383 46.384 54.6016 53.5989 61.4844C53.9621 61.8282 54.427 62 54.8879 62C55.384 62 55.8723 61.8047 56.2434 61.4219C56.9504 60.6797 56.9231 59.4961 56.1809 58.7891C48.9231 51.875 42.97 43.8438 38.2356 36.211C48.6301 23.8946 59.1887 11.5821 62.0364 9.30669C62.8293 8.66411 62.9543 7.48833 62.3059 6.6895Z"
    />
  </svg>
</template>
