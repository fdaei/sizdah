<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import BrandLogo from '@/Components/BrandLogo.vue'
import footerBloomUrl from '~img/sizdah/shared/footer-bloom.svg'
import locationIconUrl from '~img/sizdah/shared/footer-location.svg'
import phoneIconUrl from '~img/sizdah/shared/footer-phone.svg'
import emailIconUrl from '~img/sizdah/shared/footer-email.svg'
import type { NavItem, SharedProps } from '@/types'

/**
 * Site footer. Figma page-instance Footer, e.g. `688:5781` on Contact
 * (`279:6325`) — every page frame carries its own copy at this node range
 * (`688:5xxx`/`688:6xxx`), all structurally identical.
 *
 * RE-VERIFIED 2026-09-02 (see GAPS G53): the file's footer was redesigned
 * since the 2026-08-21 pass this docblock previously described. The SAHRA/
 * unrebranded-instance deviation this docblock used to document no longer
 * applies — the current frame is properly Sizdah-branded (own wordmark,
 * Persian copy) — so the structure AND content now both come straight from
 * the frame; nothing is being substituted.
 *
 * Geometry: outer frame 1440x501.76, content track inset px96/pt48
 * (`container-sizdah` at `xl`). Top block (brand + 3 nav columns) is
 * 1248x218; a 40px gap to the bottom legal bar (1248x66, itself a 24px-padded
 * rule + 18px text row). Column heights still confirm the 1.5 type scale.
 * Column titles use the brand yellow while the remaining footer copy is white
 * so the navigation stays legible against the dark footer background.
 *
 * NEW this pass — a `Group 26` decorative wash sits behind the whole block,
 * x=96/y=52, 1248x449.76, seven brand-yellow (`#F8B937`) blobs at 3% opacity.
 * It alone accounts for the frame's height: 52 (its own top offset) + 449.76
 * (its height) = 501.76, exactly the frame height — the real content only
 * needs 324px, so the frame carries ~130px of pure bottom padding purely to
 * give the wash room to breathe. Exported as `footer-bloom.svg` and painted
 * as a `container-sizdah`-track-width background, `xl:` and up only — same
 * "container padding only matches the frame's 96px gutter at `xl`" reasoning
 * as Legal's trust-badge (`Legal.vue`, GAPS G49).
 *
 * SIZED 2026-09-04 (PARITY Phase 4): it carried `inset-x-0 w-full`, which is
 * the container's BORDER box (1440), not its 1248 track. The SVG scaled by
 * 1440/1248 and rendered 518.95 tall instead of 449.76 — overflowing the
 * footer by ~69px and adding that to the scroll height of EVERY page. Pinned
 * to the frame's own 1248x449.76.
 *
 * The old NOT-implemented note about a bleeding SAHRA wordmark (former
 * GAPS G24) is retired along with it — the current frame does not draw one.
 *
 * ICONS 2026-09-04 (PARITY Phase 2): the Info column's three glyphs were
 * `lucide-vue-next` components; they are now the frame's own exports —
 * `437:5981`/`437:5986`/`437:5990` (components `13:903`/`13:880`/`13:900`),
 * 16x16, stroked in Black/100 `#E8E8E8` by the export itself.
 *
 * They are deliberately NOT the `sizdah/contact/contact-*.svg` trio: those are
 * the same glyphs drawn at 24x24 in brand yellow for the Contact page. Both
 * carry the same 6.25% relative stroke weight (16/1 and 24/1.5), so they are
 * one glyph family at two sizes — two legitimate exports, not a duplicate.
 *
 * Kept as `<img>` rather than inline SVG because the frame defines no colour
 * variant for them; the neighbouring `hover:text-brand` applies to the link
 * text, not the icon, so nothing state-driven is lost.
 *
 * GEOMETRY 2026-09-04 (PARITY Phase 4). The frame is 501.76 tall = 48 top pad
 * + 324 content + 129.76 bottom pad. The bottom pad here was 177.76, i.e.
 * 501.76 - 324 — derived by subtracting the content from the total and
 * forgetting the top padding, so the footer stood 48px too tall on every page.
 *
 * It is set to 128.76, one less than the frame's 129.76, on purpose: Figma
 * draws this frame's 1px top stroke INSIDE the 501.76 box, while CSS
 * `border-top` adds its width on top of the padding box. The 1px is taken out
 * of the padding so the RENDERED height matches, which is what the diff
 * measures. Rendered: 502.2 against the frame's 501.76.
 *
 * Also corrected against 688:6453 this pass: the brand column is a fixed 402
 * (688:6474) and `max-w-[402px]` had let it collapse to the logo's 87px; the
 * column titles are tite/Small 18/400 (line box 22), not body-lg 16/500; and
 * the link items are Body/Large 16/400 in Black/100, not label-lg 14/500 in
 * white. Those three reconstruct the column height exactly:
 * 22 + 16 + (6x20 + 5x12) = 218, matching 688:6473.
 */
const page = usePage<SharedProps>()

const settings = computed(() => page.props.settings)
const columns = computed<NavItem[]>(() => page.props.navigation.footer)

const year = computed(() =>
  new Intl.DateTimeFormat(page.props.locale.htmlLang, { year: 'numeric' }).format(new Date()),
)

const contact = computed(() => settings.value.contact)
</script>

<template>
  <footer class="rounded-t-lg border-t border-warm-100 bg-ink-1000 shadow-footer">
    <div class="container-sizdah relative pb-12 pt-12 xl:pb-[128.76px]">
      <img
        :src="footerBloomUrl"
        alt=""
        aria-hidden="true"
        class="pointer-events-none absolute inset-x-[96px] top-[52px] hidden h-[449.76px] w-[1248px] xl:block"
      />

      <div class="relative flex flex-col gap-10">
        <div class="flex flex-col justify-between gap-10 md:flex-row md:items-start">
          <!-- Brand + positioning line -->
          <div class="flex w-full flex-col gap-8 md:w-[402px]">
            <BrandLogo :width="87" :title="settings.siteName" />
            <p class="text-body-lg text-ink-100">{{ settings.tagline }}</p>
          </div>

          <!-- Link columns -->
          <div class="grid grid-cols-2 gap-10 md:flex md:gap-[88px]">
            <nav
              v-for="column in columns"
              :key="column.id"
              :aria-label="column.label"
              class="flex flex-col gap-4"
            >
              <h2 class="text-title-sm text-brand">
                {{ column.label }}
              </h2>

              <ul class="flex flex-col gap-3">
                <li v-for="child in column.children" :key="child.id">
                  <Link
                    :href="child.url"
                    :target="child.target"
                    class="text-body-lg text-ink-100 transition-colors duration-200 ease-brand hover:text-brand"
                  >
                    {{ child.label }}
                  </Link>
                </li>
              </ul>
            </nav>

            <!-- Contact column — from settings, not the menu tree -->
            <div class="flex flex-col gap-4">
              <h2 class="text-title-sm text-brand">
                {{ $t('footer.info') }}
              </h2>

              <ul class="flex flex-col gap-3 text-body-lg text-ink-100">
                <li v-if="contact.location" class="flex items-center gap-2">
                  <img
                    :src="locationIconUrl"
                    alt=""
                    aria-hidden="true"
                    class="size-4 shrink-0"
                    width="16"
                    height="16"
                  />
                  <span>{{ contact.location }}</span>
                </li>
                <li v-if="contact.phone" class="flex items-center gap-2">
                  <img
                    :src="phoneIconUrl"
                    alt=""
                    aria-hidden="true"
                    class="size-4 shrink-0"
                    width="16"
                    height="16"
                  />
                  <a
                    :href="`tel:${contact.phone.replace(/\s/g, '')}`"
                    class="latin-nums transition-colors duration-200 ease-brand hover:text-brand"
                    dir="ltr"
                  >
                    {{ contact.phone }}
                  </a>
                </li>
                <li v-if="contact.email" class="flex items-center gap-2">
                  <img
                    :src="emailIconUrl"
                    alt=""
                    aria-hidden="true"
                    class="size-4 shrink-0"
                    width="16"
                    height="16"
                  />
                  <a
                    :href="`mailto:${contact.email}`"
                    class="transition-colors duration-200 ease-brand hover:text-brand"
                    dir="ltr"
                  >
                    {{ contact.email }}
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div
          class="flex flex-col items-start justify-between gap-4 border-t border-warm-300 py-6 sm:flex-row sm:items-center"
        >
          <p class="text-label-lg text-ink-200">
            {{ $t('footer.copyright', { year, name: settings.siteName }) }}
          </p>

          <div class="flex items-center gap-4">
            <Link
              :href="route('legal.terms')"
              class="text-label-lg text-ink-100 underline transition-colors duration-200 ease-brand hover:text-brand"
            >
              {{ $t('footer.terms') }}
            </Link>
            <Link
              :href="route('legal.privacy')"
              class="text-label-lg text-ink-100 underline transition-colors duration-200 ease-brand hover:text-brand"
            >
              {{ $t('footer.privacy_policy') }}
            </Link>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>
