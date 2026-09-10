<template>
  <!--
    Hand-drawn tick, for the flash banner's success state. NOT a Figma export:
    the file carries no flash/toast state at all, so there is no success glyph
    to pull. Same reasoning and same 6.25% stroke weight as `IconMenu`;
    see GAPS G67.

    The stroke is drawn as two curves rather than two straight segments so the
    tick reads as inked, matching `IconClose` (a real Freehand export) sitting
    in the same banner as the dismiss control.
  -->
  <svg
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="1.5"
    stroke-linecap="round"
    stroke-linejoin="round"
    xmlns="http://www.w3.org/2000/svg"
    aria-hidden="true"
    focusable="false"
  >
    <path d="M4.2 12.8C6 14.4 7.9 16.3 9.6 18.6C12.4 13.6 15.9 8.6 19.9 4.9" />
  </svg>
</template>
