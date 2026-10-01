import { defineComponent, computed, unref, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderList, ssrRenderClass, ssrRenderAttr, ssrRenderStyle } from "vue/server-renderer";
import { _ as _sfc_main$2 } from "./Eyebrow-BY5jAbbp.js";
import { _ as _sfc_main$1 } from "./SeoHead-B0PBocbK.js";
import { _ as _sfc_main$3 } from "./StartTogetherCard-DwqCyDIf.js";
import { b as underlineUrl } from "../ssr.js";
import "@inertiajs/vue3";
import "@inertiajs/vue3/server";
import "ziggy-js";
const ringUrl = "/build/assets/ring-3LIl7ONN.svg";
const sparkUrl = "/build/assets/spark-BOJ5ldR6.svg";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Services",
  __ssrInlineRender: true,
  props: {
    heading: {},
    services: {},
    sections: {},
    seo: {}
  },
  setup(__props) {
    const props = __props;
    const finalCta = computed(() => props.sections.final_cta);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(_sfc_main$1, {
        seo: props.seo
      }, null, _parent));
      _push(`<div class="section-first pb-24 md:pt-[188px] lg:pb-[224px]"><div class="container-sizdah relative isolate"><header class="mx-auto flex w-fit max-w-full flex-col items-center gap-10 text-center max-md:gap-6" data-reveal>`);
      if (props.heading.eyebrow) {
        _push(ssrRenderComponent(_sfc_main$2, {
          text: props.heading.eyebrow
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="flex flex-col items-center gap-6 max-md:gap-5"><h1 class="max-w-[638px] text-display-lg text-ink-50 max-md:text-[40px] max-md:leading-[51px]">${ssrInterpolate(props.heading.title)}</h1>`);
      if (props.heading.description) {
        _push(`<p class="text-title-lg text-ink-200 max-md:text-[16px] max-md:leading-[22px]">${ssrInterpolate(props.heading.description)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></header><div class="mt-16 flex flex-col gap-24 lg:mt-[140px] lg:gap-[200px]"><!--[-->`);
      ssrRenderList(props.services, (service, index) => {
        _push(`<section class="relative flex flex-col items-center gap-8 lg:flex-row lg:justify-center lg:gap-[135px]" data-reveal><div class="${ssrRenderClass([index % 2 === 0 ? "lg:order-last" : "lg:order-first", "relative w-full lg:min-w-0 lg:basis-[604px]"])}"><div class="overflow-hidden rounded-sm">`);
        if (service.image) {
          _push(`<img${ssrRenderAttr("src", service.image.src)}${ssrRenderAttr("srcset", service.image.srcset)}${ssrRenderAttr("alt", service.image.alt)}${ssrRenderAttr("width", service.image.width)}${ssrRenderAttr("height", service.image.height)} loading="lazy" class="aspect-square w-full object-cover">`);
        } else {
          _push(`<div class="aspect-square w-full bg-ink-900" aria-hidden="true"></div>`);
        }
        _push(`</div><img${ssrRenderAttr("src", unref(sparkUrl))} alt="" aria-hidden="true" class="${ssrRenderClass([[
          index % 2 === 0 ? "inline-start-[-17px] flip-ltr" : "inline-end-[-17px] flip-rtl",
          index === 0 ? "h-[29.04px]" : "h-[23.75px]"
        ], "pointer-events-none absolute bottom-full w-[20px] max-w-none"])}"></div><div class="flex w-full flex-col gap-2 lg:min-w-0 lg:basis-[505.313px]"><p class="relative w-fit"><img${ssrRenderAttr("src", unref(ringUrl))} alt="" aria-hidden="true" class="pointer-events-none absolute top-1/2 -z-10 h-[109.12px] w-[163px] max-w-none -translate-y-1/2" style="${ssrRenderStyle({
          insetInlineStart: `-${[59.85, 59.85, 38.85, 43.85][index % 4]}px`
        })}"><span class="font-display text-display-xl leading-[145px] text-paper latin-nums" aria-hidden="true">${ssrInterpolate(String(index + 1).padStart(2, "0"))}</span></p><div class="flex flex-col gap-14"><div class="flex flex-col gap-10"><div class="w-fit"><h2 class="text-display-sm font-bold text-paper max-md:text-[24px] max-md:leading-[30px]">${ssrInterpolate(service.title)}</h2><span class="relative block h-[2px] w-full"><img${ssrRenderAttr("src", unref(underlineUrl))} alt="" aria-hidden="true" class="absolute inset-x-0 -top-[1.7px] h-[4.71px] w-full max-w-none"></span></div><p class="text-title-md text-ink-200 max-md:text-[16px] max-md:leading-[22px]">${ssrInterpolate(service.description)}</p></div>`);
        if (service.features.length) {
          _push(`<ul class="flex flex-col gap-4"><!--[-->`);
          ssrRenderList(service.features, (feature) => {
            _push(ssrRenderComponent(_sfc_main$2, {
              key: feature,
              as: "li",
              size: "lg",
              text: feature
            }, null, _parent));
          });
          _push(`<!--]--></ul>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div></div></section>`);
      });
      _push(`<!--]--></div>`);
      if (finalCta.value) {
        _push(ssrRenderComponent(_sfc_main$3, {
          section: finalCta.value,
          class: "mt-24 lg:mt-[224px]",
          "data-reveal": ""
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Services.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
