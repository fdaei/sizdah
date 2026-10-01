import { defineComponent, mergeProps, unref, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderStyle, ssrInterpolate, ssrRenderComponent, ssrRenderAttr } from "vue/server-renderer";
import { a as _sfc_main$1 } from "../ssr.js";
const journeyUrl = "/build/assets/cta-journey-DgNjVzZJ.svg";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "StartTogetherCard",
  __ssrInlineRender: true,
  props: {
    section: {}
  },
  setup(__props) {
    const props = __props;
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<section${ssrRenderAttrs(mergeProps({ class: "sketch-frame overflow-hidden bg-surface-raised p-8 lg:p-12" }, _attrs))}><div class="grid-mesh pointer-events-none absolute inset-y-0 inline-start-0 w-[57.47%]" style="${ssrRenderStyle({ "--mesh-color": "rgb(20 20 20 / 10%)", "--mesh-cell-x": "113.4px", "--mesh-cell-y": "115.3px", "background-position": "right 37.5px top 75.5px" })}" aria-hidden="true"></div><div class="relative z-10 flex flex-col items-center gap-10 lg:flex-row lg:justify-between lg:gap-6"><div class="flex w-full flex-col items-start gap-12 text-start lg:max-w-[554px]"><div class="flex flex-col items-start gap-12"><div class="flex flex-col items-start gap-2">`);
      if (props.section.eyebrow) {
        _push(`<p class="font-display text-title-md text-brand">${ssrInterpolate(props.section.eyebrow)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="flex flex-col gap-6"><h2 class="text-section-line text-ink-1000">${ssrInterpolate(props.section.title)}</h2>`);
      if (props.section.description) {
        _push(`<p class="text-title-sm text-ink-800">${ssrInterpolate(props.section.description)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div>`);
      if (props.section.primaryCta) {
        _push(ssrRenderComponent(_sfc_main$1, {
          label: props.section.primaryCta.label,
          href: props.section.primaryCta.url,
          size: "lg",
          "with-arrow": ""
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
      if (props.section.subtitle) {
        _push(`<p class="text-body-lg text-ink-700">${ssrInterpolate(props.section.subtitle)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div><img${ssrRenderAttr("src", unref(journeyUrl))} alt="" aria-hidden="true" width="592" height="417" class="w-full max-w-[592px]"></div></section>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/StartTogetherCard.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
