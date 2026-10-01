import { defineComponent, computed, ref, onMounted, onBeforeUnmount, unref, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderAttr, ssrRenderList, ssrRenderStyle, ssrRenderClass } from "vue/server-renderer";
import { usePage } from "@inertiajs/vue3";
import { _ as _sfc_main$2 } from "./StartTogetherCard-DwqCyDIf.js";
import { _ as _sfc_main$1 } from "./SeoHead-B0PBocbK.js";
import { _ as _export_sfc } from "../ssr.js";
import "@inertiajs/vue3/server";
import "ziggy-js";
const heroIllustrationUrl = "/build/assets/hero-team-DTWzygXN.webp";
const heroScriptUrl = "/build/assets/hero-script-C9xSwSj3.svg";
const storyMarkUrl = "/build/assets/story-mark-BAWj656s.svg";
const pathIconUrl = "/build/assets/principle-path-Bfbz2IRn.svg";
const impactIconUrl = "/build/assets/principle-impact-76OAYa1H.svg";
const identityIconUrl = "/build/assets/principle-identity-Bmli4luO.svg";
const discoveryIconUrl = "/build/assets/principle-discovery-nHskCRl7.svg";
const figmaMohammadUrl = "/build/assets/figma-mohammad-DSo1vyZd.png";
const figmaAminUrl = "/build/assets/figma-amin-BUPqF6Gw.png";
const figmaStoryIconUrl = "/build/assets/figma-story-icon-6IgY4-Nn.svg";
const TEAM_LOOP_SECONDS = 40;
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "About",
  __ssrInlineRender: true,
  props: {
    sections: {},
    team: {},
    seo: {}
  },
  setup(__props) {
    const props = __props;
    const page = usePage();
    const hero = computed(() => props.sections.about_hero);
    const story = computed(() => props.sections.story);
    const think = computed(() => props.sections.how_we_think);
    const teamSection = computed(() => props.sections.team);
    const finalCta = computed(() => props.sections.final_cta);
    const showHeroScript = computed(() => page.props.locale.current === "fa");
    const ICONS = {
      path: pathIconUrl,
      impact: impactIconUrl,
      identity: identityIconUrl,
      discovery: discoveryIconUrl
    };
    const ICON_ORDER = [discoveryIconUrl, identityIconUrl, impactIconUrl, pathIconUrl];
    function iconFor(item, index) {
      if (item.icon && ICONS[item.icon]) {
        return ICONS[item.icon];
      }
      return ICON_ORDER[index];
    }
    const teamTrack = ref(null);
    const teamList = ref([]);
    const teamDragging = ref(false);
    let teamPos = 0;
    let teamLoopWidth = 0;
    let teamFrame = 0;
    let teamLastTime = 0;
    let teamResize = null;
    function teamDirection() {
      return teamTrack.value && getComputedStyle(teamTrack.value).direction === "rtl" ? 1 : -1;
    }
    function renderTeamTrack() {
      if (!teamTrack.value) {
        return;
      }
      if (teamLoopWidth > 0) {
        teamPos = (teamPos % teamLoopWidth + teamLoopWidth) % teamLoopWidth;
      }
      teamTrack.value.style.transform = `translate3d(${teamDirection() * teamPos}px, 0, 0)`;
    }
    function tickTeam(time) {
      const dt = teamLastTime ? Math.min(time - teamLastTime, 100) : 0;
      teamLastTime = time;
      if (!teamDragging.value && teamLoopWidth > 0) {
        teamPos += teamLoopWidth / TEAM_LOOP_SECONDS * (dt / 1e3);
        renderTeamTrack();
      }
      teamFrame = requestAnimationFrame(tickTeam);
    }
    onMounted(() => {
      const first = teamList.value[0];
      if (!first || props.team.length < 2) {
        return;
      }
      teamLoopWidth = first.offsetWidth;
      teamResize = new ResizeObserver(() => {
        teamLoopWidth = first.offsetWidth;
        renderTeamTrack();
      });
      teamResize.observe(first);
      if (!window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
        teamFrame = requestAnimationFrame(tickTeam);
      }
    });
    onBeforeUnmount(() => {
      cancelAnimationFrame(teamFrame);
      teamResize == null ? void 0 : teamResize.disconnect();
    });
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b;
      _push(`<!--[-->`);
      _push(ssrRenderComponent(_sfc_main$1, {
        seo: props.seo
      }, null, _parent));
      _push(`<div class="about-page section-first md:pt-[250px] pb-[147.24px]" data-v-164edd59><div class="container-sizdah flex flex-col gap-24 lg:gap-48" data-v-164edd59>`);
      if (hero.value) {
        _push(`<section class="about-hero relative isolate grid items-center gap-12 lg:grid-cols-[minmax(0,466px)_minmax(0,1fr)] lg:gap-[86px]" data-v-164edd59><div class="flex flex-col items-start gap-6 text-start" data-reveal data-v-164edd59><h1 class="flex flex-wrap items-center gap-x-2 text-hero-line font-bold text-ink-50" data-v-164edd59><span data-v-164edd59>${ssrInterpolate(hero.value.title)}</span>`);
        if (showHeroScript.value) {
          _push(`<img${ssrRenderAttr("src", unref(heroScriptUrl))} alt="" aria-hidden="true" width="128" height="46" class="inline-block h-[0.96em] w-auto" data-v-164edd59>`);
        } else {
          _push(`<!---->`);
        }
        if (hero.value.subtitle) {
          _push(`<span data-v-164edd59>${ssrInterpolate(hero.value.subtitle)}</span>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</h1>`);
        if (hero.value.content) {
          _push(`<p class="text-heading-md text-brand-200 max-md:text-[26px] max-md:leading-[36px]" data-v-164edd59>${ssrInterpolate(hero.value.content)}</p>`);
        } else {
          _push(`<!---->`);
        }
        if (hero.value.description) {
          _push(`<p class="text-title-sm text-ink-200" data-v-164edd59>${ssrInterpolate(hero.value.description)}</p>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
        if (hero.value.image) {
          _push(`<img${ssrRenderAttr("src", hero.value.image.src)}${ssrRenderAttr("srcset", hero.value.image.srcset)}${ssrRenderAttr("alt", hero.value.image.alt)}${ssrRenderAttr("width", hero.value.image.width)}${ssrRenderAttr("height", hero.value.image.height)} class="w-full max-md:mx-auto max-md:w-[348px]" data-v-164edd59>`);
        } else {
          _push(`<img${ssrRenderAttr("src", unref(heroIllustrationUrl))} alt="" aria-hidden="true" width="696" height="522" class="w-full max-md:mx-auto max-md:w-[348px]" data-v-164edd59>`);
        }
        _push(`</section>`);
      } else {
        _push(`<!---->`);
      }
      if (story.value) {
        _push(`<section class="about-story relative isolate" data-v-164edd59><img${ssrRenderAttr("src", unref(storyMarkUrl))} alt="" aria-hidden="true" width="144" height="144" class="pointer-events-none absolute inline-end-[24%] block-start-[-52px] -z-10 hidden size-36 lg:block" data-v-164edd59><div class="mx-auto flex max-w-[824px] flex-col items-center gap-10 text-center lg:gap-20" data-v-164edd59><div class="about-story-heading flex flex-row-reverse items-center gap-2" data-v-164edd59><img${ssrRenderAttr("src", unref(figmaStoryIconUrl))} alt="" aria-hidden="true" class="size-14" data-v-164edd59><h2 class="text-section-line text-brand-50" data-v-164edd59>${ssrInterpolate(story.value.title)}</h2></div><div class="flex flex-col gap-6" data-v-164edd59>`);
        if (story.value.description) {
          _push(`<p class="text-title-lg text-ink-200" data-v-164edd59>${ssrInterpolate(story.value.description)}</p>`);
        } else {
          _push(`<!---->`);
        }
        if (story.value.content) {
          _push(`<div class="rich-prose" data-v-164edd59>${story.value.content ?? ""}</div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div></div></section>`);
      } else {
        _push(`<!---->`);
      }
      if (think.value) {
        _push(`<section class="about-think flex flex-col gap-10 lg:gap-20" data-v-164edd59><div class="flex flex-col items-start gap-4 text-start md:flex-row md:items-center md:justify-between md:gap-16" data-v-164edd59><h2 class="text-section-line text-brand-50" data-v-164edd59>${ssrInterpolate(think.value.title)}</h2>`);
        if (think.value.description) {
          _push(`<p class="max-w-[506px] text-title-md text-ink-200" data-v-164edd59>${ssrInterpolate(think.value.description)}</p>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
        if (think.value.items.length) {
          _push(`<ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4" data-reveal-group data-v-164edd59><!--[-->`);
          ssrRenderList(think.value.items, (item, index) => {
            _push(`<li class="sketch-frame flex flex-col items-start gap-10 px-6 py-14 text-start" style="${ssrRenderStyle({ "background-image": "linear-gradient(-45deg, rgb(248 185 55 / 0%) 0%, rgb(248 185 55 / 8%) 100%),\n                linear-gradient(0deg, rgb(0 0 0 / 20%), rgb(0 0 0 / 20%))" })}" data-v-164edd59>`);
            if (iconFor(item, index)) {
              _push(`<img${ssrRenderAttr("src", iconFor(item, index))} alt="" aria-hidden="true" width="40" height="40" class="size-10 shrink-0" data-v-164edd59>`);
            } else {
              _push(`<!---->`);
            }
            _push(`<div class="flex flex-col gap-6" data-v-164edd59><h3 class="text-heading-sm text-brand-50" data-v-164edd59><span class="max-md:hidden" data-v-164edd59>${ssrInterpolate(item.title)}</span><span class="hidden max-md:inline" data-v-164edd59>شناخت، قبل از اجرا</span></h3>`);
            if (item.description) {
              _push(`<p class="text-title-sm text-ink-300" data-v-164edd59><span class="max-md:hidden" data-v-164edd59>${ssrInterpolate(item.description)}</span><span class="hidden max-md:inline" data-v-164edd59>اول مسئله و مخاطب را می‌فهمیم، بعد وارد اجرا می‌شویم</span></p>`);
            } else {
              _push(`<!---->`);
            }
            _push(`</div></li>`);
          });
          _push(`<!--]--></ul>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</section>`);
      } else {
        _push(`<!---->`);
      }
      if (props.team.length) {
        _push(`<section class="about-team flex flex-col gap-10 lg:gap-20" data-v-164edd59><div class="flex flex-col items-start gap-4 text-start md:flex-row md:items-center md:justify-between md:gap-16" data-v-164edd59>`);
        if ((_a = teamSection.value) == null ? void 0 : _a.title) {
          _push(`<h2 class="text-section-line text-brand-50" data-v-164edd59>${ssrInterpolate(teamSection.value.title)}</h2>`);
        } else {
          _push(`<!---->`);
        }
        if ((_b = teamSection.value) == null ? void 0 : _b.description) {
          _push(`<p class="max-w-[506px] text-title-md text-ink-200" data-v-164edd59>${ssrInterpolate(teamSection.value.description)}</p>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div><div class="${ssrRenderClass([{ "is-draggable": props.team.length > 1, "is-dragging": teamDragging.value }, "team-carousel w-full select-none overflow-hidden"])}" data-reveal data-v-164edd59><div class="team-carousel-track flex w-max" data-v-164edd59><!--[-->`);
        ssrRenderList(props.team.length > 1 ? 2 : 1, (copyIndex) => {
          _push(`<ul${ssrRenderAttr("aria-hidden", copyIndex === 2 ? "true" : void 0)} class="team-carousel-list flex shrink-0 gap-[26px] pe-[26px]" data-v-164edd59><!--[-->`);
          ssrRenderList(props.team, (member) => {
            _push(`<li class="sketch-frame group flex w-[220px] shrink-0 flex-col overflow-hidden bg-brand-200 transition-colors duration-200 ease-brand hover:bg-brand" data-v-164edd59>`);
            if (member.image) {
              _push(`<img${ssrRenderAttr("src", member.image.src)}${ssrRenderAttr("srcset", member.image.srcset)}${ssrRenderAttr("alt", copyIndex === 2 ? "" : member.image.alt)}${ssrRenderAttr("width", member.image.width)}${ssrRenderAttr("height", member.image.height)} loading="lazy" draggable="false" class="aspect-square w-full rounded-lg object-cover object-bottom grayscale transition-[filter] duration-300 ease-brand group-hover:grayscale-0" data-v-164edd59>`);
            } else {
              _push(`<div class="aspect-square w-full rounded-lg bg-warm-200" aria-hidden="true" data-v-164edd59></div>`);
            }
            _push(`<div class="flex flex-col gap-1 p-4 text-start" data-v-164edd59><p class="text-heading-sm text-warm-900 group-hover:text-ink-1000" data-v-164edd59>${ssrInterpolate(member.name)}</p><p class="text-title-md text-ink-700 group-hover:text-ink-800" data-v-164edd59>${ssrInterpolate(member.role)}</p></div></li>`);
          });
          _push(`<!--]--></ul>`);
        });
        _push(`<!--]--></div></div><div class="about-mobile-team-cards hidden max-md:flex" data-v-164edd59><!--[-->`);
        ssrRenderList([{ name: "محمد", role: "گرافیک دیزاینر", image: unref(figmaMohammadUrl) }, { name: "امین", role: "مدیرعامل", image: unref(figmaAminUrl) }], (member) => {
          _push(`<div class="h-[247px] w-[173px] shrink-0 rounded-xl border-[3px] border-brand-100 bg-brand-100 p-0 text-right" data-v-164edd59><img${ssrRenderAttr("src", member.image)} alt="" class="h-[164px] w-full rounded-xl object-cover" data-v-164edd59><div class="p-3 text-ink-900" data-v-164edd59><p class="text-[18px]" data-v-164edd59>${ssrInterpolate(member.name)}</p><p class="text-[14px]" data-v-164edd59>${ssrInterpolate(member.role)}</p></div></div>`);
        });
        _push(`<!--]--></div></section>`);
      } else {
        _push(`<!---->`);
      }
      if (finalCta.value) {
        _push(ssrRenderComponent(_sfc_main$2, {
          section: finalCta.value,
          class: "about-cta"
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/About.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
const About = /* @__PURE__ */ _export_sfc(_sfc_main, [["__scopeId", "data-v-164edd59"]]);
export {
  About as default
};
