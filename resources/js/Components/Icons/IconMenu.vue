<template>
  <!--
    Hand-drawn hamburger. NOT a Figma export — the file designs desktop 1440
    frames only, so no mobile header and no burger glyph exists anywhere in it
    (checked the icon sheet `149:2691` and the component sheet `21:2152`).
    Drawn here to the house rule instead of pulled from a package; see GAPS G67.

    Weight is the family's 6.25% relative stroke (1.5 in a 24 box) that
    `AppFooter.vue` already documents for the 16/1 and 24/1.5 glyph pair, so it
    sits at the same ink density as `IconClose` beside it in the header.
    The three rules are deliberately unequal in length and each bows slightly,
    which is what separates this from a geometric icon set.
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
    <path d="M3.4 6.6C8 5.9 14.5 6.4 20.6 6.1" />
    <path d="M3.1 12.1C9 11.6 15 12.5 20.9 11.9" />
    <path d="M3.6 17.6C8.5 17.2 14 17.7 20.4 17.3" />
  </svg>
</template>
