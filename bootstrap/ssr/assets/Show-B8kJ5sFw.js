import { defineComponent, ref, computed, unref, withCtx, createVNode, toDisplayString, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderStyle, ssrInterpolate, ssrRenderList, ssrRenderAttr, ssrRenderClass } from "vue/server-renderer";
import { Link } from "@inertiajs/vue3";
import { _ as _sfc_main$3 } from "./FilterChips-CkugSIA_.js";
import { _ as _sfc_main$2 } from "./SectionHeading-BytK5z_z.js";
import { _ as _sfc_main$1 } from "./SeoHead-B0PBocbK.js";
import { _ as _sfc_main$4 } from "./StartTogetherCard-DwqCyDIf.js";
import { u as useTranslations } from "../ssr.js";
import "./Eyebrow-BY5jAbbp.js";
import "@inertiajs/vue3/server";
import "ziggy-js";
const servicesIconUrl = "/build/assets/meta-services-BQePM__T.svg";
const instagramIconUrl = "/build/assets/meta-instagram-cGo48Iln.svg";
const yearIconUrl = "/build/assets/meta-year-BxRmknfs.svg";
const industryIconUrl = "/build/assets/meta-industry-DGVdUrOb.svg";
const challengeMarkUrl = "/build/assets/challenge-mark-xWk8J3Mo.svg";
const nextArrowUrl = "/build/assets/next-arrow-DoKL_L76.svg";
const resultRoiUrl = "/build/assets/result-roi-C-W0F0A0.svg";
const resultReachUrl = "/build/assets/result-reach-rp323zDn.svg";
const resultInteractionUrl = "/build/assets/result-interaction-MoRfyL5C.svg";
const resultFollowerUrl = "/build/assets/result-follower-CiiS3-NW.svg";
const resultViewUrl = "/build/assets/result-view-C_G_d1uE.svg";
const strategyRuleUrl = "/build/assets/strategy-rule-h-Bn0_xo2a.svg";
const strategyRuleVerticalUrl = "/build/assets/strategy-rule-v-DkJOITZQ.svg";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Show",
  __ssrInlineRender: true,
  props: {
    project: {},
    finalCta: {},
    seo: {}
  },
  setup(__props) {
    const props = __props;
    const { t } = useTranslations();
    const meta = [
      { key: "industry", icon: industryIconUrl, value: props.project.industry },
      { key: "year", icon: yearIconUrl, value: props.project.year },
      {
        key: "instagram",
        icon: instagramIconUrl,
        value: props.project.instagram,
        // 411:8619 sets the handle in `ts1` — an underline text style.
        underline: true
      },
      { key: "services", icon: servicesIconUrl, value: props.project.services.join("، ") }
    ].filter((item) => item.value);
    const cardBlocks = [
      {
        key: "goals",
        items: props.project.goals,
        // Single row, so the 16 gutter is the only gap the frame draws.
        columns: "gap-4 sm:grid-cols-2 lg:grid-cols-4",
        lead: "mt-20 md:mt-[136px]"
      },
      {
        key: "deliverables",
        items: props.project.deliverables,
        // 429:5092 -> 429:5117 is 40 between the rows against 16 between columns.
        columns: "gap-x-4 gap-y-10 sm:grid-cols-2 lg:grid-cols-3",
        lead: "mt-20 md:mt-[233px]"
      }
    ].filter((block) => block.items.length > 0);
    const resultIcons = {
      roi: { src: resultRoiUrl, width: 32, height: 32 },
      reach: { src: resultReachUrl, width: 32, height: 32 },
      interaction: { src: resultInteractionUrl, width: 28, height: 28 },
      follower: { src: resultFollowerUrl, width: 32, height: 32 },
      view: { src: resultViewUrl, width: 32, height: 32 }
    };
    const resultIconAliases = {
      roi: "roi",
      "بازگشت سرمایه": "roi",
      reach: "reach",
      دسترسی: "reach",
      interaction: "interaction",
      تعامل: "interaction",
      follower: "follower",
      دنبال‌کننده: "follower",
      view: "view",
      بازدید: "view"
    };
    function resultIcon(result) {
      var _a;
      const key = resultIconAliases[((_a = result.icon) == null ? void 0 : _a.trim().toLowerCase()) ?? ""] ?? resultIconAliases[result.label.trim().toLowerCase()];
      return key ? resultIcons[key] : void 0;
    }
    const activeTag = ref(null);
    const showcaseFilters = computed(() => {
      const tags = [...new Set(props.project.showcase.map((image) => image.tag).filter(Boolean))];
      if (tags.length === 0) {
        return [];
      }
      return [
        { value: null, label: t("common.all") },
        ...tags.map((tag) => ({ value: tag, label: tag }))
      ];
    });
    const visibleShowcase = computed(
      () => activeTag.value === null ? props.project.showcase : props.project.showcase.filter((image) => image.tag === activeTag.value)
    );
    function ordinal(index) {
      return String(index + 1).padStart(2, "0");
    }
    const strategyColumns = computed(() => {
      const cells = props.project.strategy.map((item, index) => ({ item, index }));
      return [
        cells.filter((cell) => cell.index % 2 === 0),
        cells.filter((cell) => cell.index % 2 === 1)
      ];
    });
    const strategyLead = computed(
      () => props.project.deliverables.length > 0 ? "mt-20 md:mt-[121px]" : "mt-20 md:mt-[233px]"
    );
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(_sfc_main$1, {
        seo: props.seo
      }, null, _parent));
      _push(`<article class="section-first pb-[442.38px] md:pt-[188px]"><div class="container-sizdah relative isolate flex flex-col"><div class="grid-mesh pointer-events-none absolute inline-start-6 block-start-[22px] -z-10 hidden h-[872px] w-[1200px] max-w-[calc(100%-theme(spacing.6))] lg:block" style="${ssrRenderStyle({ "--mesh-cell-x": "109.095px", "--mesh-cell-y": "109.095px" })}" aria-hidden="true"></div><header class="flex flex-col items-center gap-16" data-reveal><div class="flex w-full max-w-measure flex-col items-center gap-6 text-center"><h1 class="text-display-md text-ink-50">${ssrInterpolate(props.project.title)}</h1>`);
      if (props.project.excerpt) {
        _push(`<p class="text-title-sm text-ink-200">${ssrInterpolate(props.project.excerpt)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
      if (unref(meta).length) {
        _push(`<dl class="flex flex-wrap justify-center gap-4"><!--[-->`);
        ssrRenderList(unref(meta), (item) => {
          _push(`<div class="surface-meta-chip relative flex items-start gap-2 rounded-lg px-[26px] py-[14px]"><span aria-hidden="true" class="sketch-frame-chip pointer-events-none absolute inset-0 bg-brand-300"></span><img${ssrRenderAttr("src", item.icon)} alt="" aria-hidden="true" width="24" height="24" class="size-6 shrink-0"><div class="flex flex-col justify-center gap-2 whitespace-nowrap"><dt class="text-label-lg text-ink-50">${ssrInterpolate(unref(t)(`work.${item.key}`))}</dt><dd class="${ssrRenderClass([item.underline && "underline", "latin-nums text-body-md text-ink-200"])}">${ssrInterpolate(item.value)}</dd></div></div>`);
        });
        _push(`<!--]--></dl>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</header>`);
      if (props.project.banner ?? props.project.image) {
        _push(`<figure class="mt-20 overflow-hidden rounded-xl md:mt-[153px]" data-reveal><img${ssrRenderAttr("src", (props.project.banner ?? props.project.image).src)}${ssrRenderAttr("srcset", (props.project.banner ?? props.project.image).srcset)}${ssrRenderAttr("alt", (props.project.banner ?? props.project.image).alt)}${ssrRenderAttr("width", (props.project.banner ?? props.project.image).width)}${ssrRenderAttr("height", (props.project.banner ?? props.project.image).height)} class="aspect-[2/1] w-full object-cover shadow-[3.32px_3.32px_9.96px_0_rgb(0_0_0/5%)]"></figure>`);
      } else {
        _push(`<!---->`);
      }
      if (props.project.challenge) {
        _push(`<section class="mt-20 flex flex-col items-center gap-10 md:mt-[163px]" data-reveal><h2 class="relative mx-auto w-fit text-center text-section-line text-brand-50">${ssrInterpolate(unref(t)("work.challenge"))} <img${ssrRenderAttr("src", unref(challengeMarkUrl))} alt="" aria-hidden="true" width="144" height="144" class="pointer-events-none absolute inline-start-full top-1/2 ms-3 hidden size-36 -translate-y-[calc(50%+29px)] lg:block"></h2><p class="w-full max-w-[832px] text-center text-title-lg text-ink-200">${ssrInterpolate(props.project.challenge)}</p>`);
        if (props.project.challengePoints.length) {
          _push(`<ul class="flex w-full max-w-[832px] flex-col gap-3"><!--[-->`);
          ssrRenderList(props.project.challengePoints, (point) => {
            _push(`<li class="flex items-start gap-3 text-title-sm text-ink-200"><span class="mt-2 size-2 shrink-0 rounded-round bg-brand" aria-hidden="true"></span><span>${ssrInterpolate(point)}</span></li>`);
          });
          _push(`<!--]--></ul>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</section>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<!--[-->`);
      ssrRenderList(unref(cardBlocks), (block) => {
        _push(`<section class="${ssrRenderClass([block.lead, "flex flex-col gap-10"])}"><h2 class="text-section-line text-brand-50">${ssrInterpolate(unref(t)(`work.${block.key}`))}</h2><ul class="${ssrRenderClass([block.columns, "grid"])}" data-reveal-group><!--[-->`);
        ssrRenderList(block.items, (item, index) => {
          _push(`<li class="surface-case-card sketch-frame flex h-full flex-col items-start justify-center gap-10 px-6 py-14" data-reveal><span class="latin-nums text-display-sm font-semibold text-brand" aria-hidden="true">${ssrInterpolate(ordinal(index))}</span><div class="flex w-full flex-col gap-6"><h3 class="text-heading-sm text-brand-50">${ssrInterpolate(item.title)}</h3><p class="text-title-sm text-ink-200">${ssrInterpolate(item.description)}</p></div></li>`);
        });
        _push(`<!--]--></ul></section>`);
      });
      _push(`<!--]-->`);
      if (props.project.strategy.length) {
        _push(`<section class="${ssrRenderClass([strategyLead.value, "grid lg:grid-cols-[minmax(0,31%)_minmax(0,1fr)] lg:gap-x-10 2xl:gap-x-[198px]"])}">`);
        _push(ssrRenderComponent(_sfc_main$2, {
          eyebrow: unref(t)("work.strategy_eyebrow"),
          title: unref(t)("work.strategy_title"),
          subtitle: unref(t)("work.strategy_subtitle"),
          layout: "stacked"
        }, null, _parent));
        _push(`<div class="relative mt-12 grid gap-x-6 gap-y-[52px] sm:grid-cols-2 lg:mt-[59px]">`);
        if (strategyColumns.value[1].length) {
          _push(`<img${ssrRenderAttr("src", unref(strategyRuleVerticalUrl))} alt="" aria-hidden="true" width="4" height="400" class="pointer-events-none absolute inset-y-0 hidden h-full w-1 max-w-none sm:block" style="${ssrRenderStyle({ "inset-inline-start": "calc(50% - 2px)" })}">`);
        } else {
          _push(`<!---->`);
        }
        _push(`<!--[-->`);
        ssrRenderList(strategyColumns.value, (column, columnIndex) => {
          _push(`<div class="${ssrRenderClass([columnIndex === 1 && "sm:mt-[38px]", "contents sm:flex sm:flex-col sm:gap-[52px]"])}"><!--[-->`);
          ssrRenderList(column, (cell) => {
            _push(`<!--[-->`);
            if (cell.index > 1) {
              _push(`<img${ssrRenderAttr("src", unref(strategyRuleUrl))} alt="" aria-hidden="true" width="320" height="4" class="hidden h-1 w-full max-w-[320px] sm:block" style="${ssrRenderStyle({ order: cell.index - 1 })}">`);
            } else {
              _push(`<!---->`);
            }
            _push(`<div class="flex items-start gap-4" style="${ssrRenderStyle({ order: cell.index })}" data-reveal><span class="latin-nums text-heading-xl text-brand" aria-hidden="true">${ssrInterpolate(ordinal(cell.index))}</span><div class="flex max-w-[252px] flex-col gap-2"><h3 class="text-heading-sm text-ink-50">${ssrInterpolate(cell.item.title)}</h3><p class="text-title-sm text-ink-200">${ssrInterpolate(cell.item.description)}</p></div></div><!--]-->`);
          });
          _push(`<!--]--></div>`);
        });
        _push(`<!--]--></div></section>`);
      } else {
        _push(`<!---->`);
      }
      if (props.project.showcase.length) {
        _push(`<section class="mt-20 flex flex-col gap-[57px] md:mt-[167px]"><div class="flex flex-wrap items-center justify-between gap-6"><h2 class="text-section-line text-brand-50">${ssrInterpolate(unref(t)("work.showcase"))}</h2>`);
        if (showcaseFilters.value.length) {
          _push(ssrRenderComponent(_sfc_main$3, {
            options: showcaseFilters.value,
            active: activeTag.value,
            label: unref(t)("work.showcase"),
            onSelect: ($event) => activeTag.value = $event
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`</div><ul class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3" data-reveal-group><!--[-->`);
        ssrRenderList(visibleShowcase.value, (image) => {
          _push(`<li data-reveal><img${ssrRenderAttr("src", image.src)}${ssrRenderAttr("srcset", image.srcset)}${ssrRenderAttr("alt", image.alt)}${ssrRenderAttr("width", image.width)}${ssrRenderAttr("height", image.height)} loading="lazy" class="aspect-[4/5] w-full rounded-xl object-cover"></li>`);
        });
        _push(`<!--]--></ul></section>`);
      } else {
        _push(`<!---->`);
      }
      if (props.project.results.length || props.project.resultsSummary) {
        _push(`<section class="mt-20 flex flex-col gap-12 md:mt-[189px]"><div class="flex flex-col gap-12"><h2 class="text-section-line text-brand-50">${ssrInterpolate(unref(t)("work.results"))}</h2>`);
        if (props.project.results.length) {
          _push(`<ul class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5" data-reveal-group><!--[-->`);
          ssrRenderList(props.project.results, (result) => {
            _push(`<li class="flex h-full flex-col items-start justify-center gap-4 rounded-sm bg-gold-100 px-8 py-4" data-reveal>`);
            if (resultIcon(result)) {
              _push(`<img${ssrRenderAttr("src", resultIcon(result).src)}${ssrRenderAttr("width", resultIcon(result).width)}${ssrRenderAttr("height", resultIcon(result).height)} alt="" aria-hidden="true" class="block h-auto">`);
            } else {
              _push(`<!---->`);
            }
            _push(`<div class="flex flex-col gap-2"><span class="text-title-lg text-warm-900">${ssrInterpolate(result.label)}</span><span dir="ltr" class="latin-nums block text-heading-lg text-brand">${ssrInterpolate(result.value)}</span></div></li>`);
          });
          _push(`<!--]--></ul>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
        if (props.project.resultsSummary) {
          _push(`<p class="whitespace-pre-line text-heading-sm text-ink-200">${ssrInterpolate(props.project.resultsSummary)}</p>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</section>`);
      } else {
        _push(`<!---->`);
      }
      if (props.project.beforeAfter.before && props.project.beforeAfter.after) {
        _push(`<section class="mt-20 grid gap-6 md:mt-[127px] md:grid-cols-2" data-reveal-group><!--[-->`);
        ssrRenderList(["before", "after"], (side) => {
          _push(`<figure class="flex flex-col gap-6" data-reveal><figcaption class="text-center text-heading-xl font-bold text-ink-50">${ssrInterpolate(unref(t)(`work.${side}`))}</figcaption><img${ssrRenderAttr("src", props.project.beforeAfter[side].src)}${ssrRenderAttr("srcset", props.project.beforeAfter[side].srcset)}${ssrRenderAttr("alt", props.project.beforeAfter[side].alt)} loading="lazy" class="aspect-[2/1] w-full rounded-sm object-cover shadow-[0_4px_10px_0_rgb(0_0_0/5%)]"></figure>`);
        });
        _push(`<!--]--></section>`);
      } else {
        _push(`<!---->`);
      }
      if (props.project.next) {
        _push(`<section class="mt-20 flex flex-col items-start gap-8 md:mt-[270px]" data-reveal><p class="text-heading-sm text-ink-200">${ssrInterpolate(unref(t)("work.next_case_study"))}</p>`);
        _push(ssrRenderComponent(unref(Link), {
          href: props.project.next.url,
          class: "group flex items-center gap-4 transition-colors duration-200 ease-brand"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`<span class="text-display-md text-ink-50 transition-colors duration-200 ease-brand group-hover:text-brand"${_scopeId}>${ssrInterpolate(props.project.next.title)}</span><img${ssrRenderAttr("src", unref(nextArrowUrl))} alt="" aria-hidden="true" width="40" height="40" class="size-10 transition-transform duration-200 ease-brand group-hover:-translate-x-2"${_scopeId}>`);
            } else {
              return [
                createVNode("span", { class: "text-display-md text-ink-50 transition-colors duration-200 ease-brand group-hover:text-brand" }, toDisplayString(props.project.next.title), 1),
                createVNode("img", {
                  src: unref(nextArrowUrl),
                  alt: "",
                  "aria-hidden": "true",
                  width: "40",
                  height: "40",
                  class: "size-10 transition-transform duration-200 ease-brand group-hover:-translate-x-2"
                }, null, 8, ["src"])
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(`</section>`);
      } else {
        _push(`<!---->`);
      }
      if (props.finalCta) {
        _push(ssrRenderComponent(_sfc_main$4, {
          section: props.finalCta,
          class: "mt-20 md:mt-[172px]",
          "data-reveal": ""
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`</div></article><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Work/Show.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
