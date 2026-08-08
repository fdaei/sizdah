import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'
import logical from 'tailwindcss-logical'

/**
 * Sizdah design tokens — extracted verbatim from Figma variables.
 * Source: file v1l4ANft5Wtb8wPThyP7P9, page "User Interface" (0:1).
 * Read via Figma MCP `get_variable_defs` on node 268:2962 (canonical dark Home).
 *
 * Do not hand-edit values here — if the design changes, re-read the variables
 * and regenerate. Every value below maps 1:1 to a named Figma variable.
 *
 * @type {import('tailwindcss').Config}
 */
export default {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.{js,ts,vue}',
    './app/Filament/**/*.php',
    './vendor/filament/**/*.blade.php',
  ],

  darkMode: 'class',

  theme: {
    // The file ships desktop frames at 1440 only — no tablet frames exist.
    // sm/md/lg behaviour is derived; see FIGMA/state.json.
    screens: {
      xs: '480px',
      sm: '640px',
      md: '768px',
      lg: '1024px',
      xl: '1280px',
      '2xl': '1440px',
    },

    extend: {
      colors: {
        /*
         | Figma "Yellow/*". Yellow/1000 is the brand accent used for CTAs,
         | eyebrow dots, numerals and rules. The lighter steps are surfaces
         | (Yellow/50 and /200 back the one cream section on Home).
         */
        brand: {
          DEFAULT: '#F8B937',
          primary: '#F8B937',
          50: '#FEFBF5',
          200: '#FEF1D7',
          600: '#FBD587',
          800: '#F9C75F',
          900: '#F9C04B',
          1000: '#F8B937',
        },

        /*
         | Figma "Black/*". ink-1000 is the page ground for every frame in the
         | file; the ramp above it carries borders, muted copy and card fills.
         */
        ink: {
          50: '#F3F3F3',
          100: '#E8E8E8',
          200: '#D0D0D0',
          300: '#B9B9B9',
          400: '#A1A1A1',
          500: '#8A8A8A',
          600: '#727272',
          700: '#5B5B5B',
          800: '#434343',
          900: '#2C2C2C',
          1000: '#141414',
        },

        // Figma "White" — an off-white, not #FFF. Body copy on dark.
        paper: '#F1F1F1',
      },

      // Figma space4 … space96
      spacing: {
        1: '4px',
        2: '8px',
        3: '12px',
        4: '16px',
        6: '24px',
        8: '32px',
        10: '40px',
        12: '48px',
        16: '64px',
        24: '96px',
      },

      // Figma radiusXS / radiusSM / radiusLG / radiusROUND
      borderRadius: {
        xs: '4px',
        sm: '8px',
        lg: '16px',
        round: '1000px',
      },

      fontFamily: {
        /*
         | The Figma variables name "Peyda" for every FA text style. Peyda is
         | not licensed for this project, so fa/ar render in Vazirmatn — near
         | identical metrics, and the stack the brief specifies. Latin copy
         | uses Poppins. Both are self-hosted (public/fonts/*).
         |
         | Deviation from the file is deliberate and recorded in
         | TRANSLATIONS_TODO.md / FIGMA/state.json.
         */
        sans: ['Poppins', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        arabic: ['Vazirmatn', 'Tahoma', 'sans-serif'],
      },

      fontSize: {
        /*
         | Figma "FA-Desktop/*" type scale. The variables report
         | `lineHeight: 100`, but every laid-out text node in the file
         | contradicts that: a 20px title node measures 30px tall (268:3562,
         | 268:3681), a 14px body line measures ~22px (268:3682). 1.5 is what
         | the frames are actually built on, and the section heights depend
         | on it, so 1.5 is the token.
         */
        'label-md': ['12px', { lineHeight: '1.5', fontWeight: '500' }],
        'label-lg': ['14px', { lineHeight: '1.5', fontWeight: '500' }],
        'body-md': ['14px', { lineHeight: '1.5', fontWeight: '400' }],
        'body-lg': ['16px', { lineHeight: '1.5', fontWeight: '400' }],
        'title-sm': ['18px', { lineHeight: '1.5', fontWeight: '400' }],
        'title-md': ['20px', { lineHeight: '1.5', fontWeight: '500' }],
        'title-lg': ['22px', { lineHeight: '1.5', fontWeight: '500' }],
        'heading-sm': ['24px', { lineHeight: '1.5', fontWeight: '500' }],
        'heading-md': ['28px', { lineHeight: '1.5', fontWeight: '500' }],
        // Standalone page headings — 404 (266:2831) and the section titles that
        // sit above a centred copy block. Medium, set at leading-normal.
        'heading-lg': ['32px', { lineHeight: '1.27', fontWeight: '500' }],
        'display-sm': ['36px', { lineHeight: '1.5', fontWeight: '700' }],
        'display-md': ['40px', { lineHeight: '1.5', fontWeight: '700' }],
        'display-lg': ['48px', { lineHeight: '1.5', fontWeight: '700' }],

        /*
         | Page headlines are a separate style from the scale above: SemiBold,
         | and set at leading-normal rather than 1.5. Measured off the frames —
         | a 48px line box is 61px tall (268:2999) and a 64px one is 81px
         | (268:3000), i.e. 1.27 in both cases.
         |
         | The file only ships 1440px frames, so the desktop value is pinned as
         | the clamp maximum and the viewport term takes over below it. At
         | 1440px `5vw` = 72px and `6.5vw` = 94px, so both clamp to the exact
         | Figma size; they only shrink on narrower screens.
         */
        'hero-line': ['clamp(28px, 5vw, 48px)', { lineHeight: '1.27', fontWeight: '600' }],
        'hero-accent': ['clamp(36px, 6.5vw, 64px)', { lineHeight: '1.27', fontWeight: '600' }],
      },

      maxWidth: {
        // Figma content track: 1248 inside a 1440 frame (96 gutters).
        container: '1248px',
        frame: '1440px',
        // Centred copy blocks on Home / blog hero measure 612.
        measure: '612px',
      },

      boxShadow: {
        card: '4px 4px 12px 0px rgba(0, 0, 0, 0.05)',
      },

      backdropBlur: {
        header: '15px',
      },

      transitionTimingFunction: {
        brand: 'cubic-bezier(0.22, 1, 0.36, 1)',
      },

      zIndex: {
        header: '50',
        overlay: '60',
        menu: '70',
      },

      keyframes: {
        'fade-up': {
          '0%': { opacity: '0', transform: 'translateY(24px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        marquee: {
          '0%': { transform: 'translateX(0)' },
          '100%': { transform: 'translateX(-50%)' },
        },
      },

      animation: {
        'fade-up': 'fade-up 0.6s cubic-bezier(0.22, 1, 0.36, 1) both',
        marquee: 'marquee 40s linear infinite',
      },
    },
  },

  plugins: [forms, typography, logical],
}
