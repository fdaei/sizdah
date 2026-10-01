import { defineComponent, mergeProps, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrRenderClass, ssrInterpolate } from "vue/server-renderer";
import { _ as _sfc_main$1 } from "./Eyebrow-BY5jAbbp.js";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "SectionHeading",
  __ssrInlineRender: true,
  props: {
    eyebrow: { default: "" },
    title: {},
    subtitle: { default: "" },
    layout: { default: "split" },
    tone: { default: "dark" },
    gap: { default: "sm" },
    subtitleWidth: { default: "" },
    splitClass: { default: "" }
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: ["flex flex-col items-start", __props.gap === "lg" ? "gap-12" : "gap-6"]
      }, _attrs))}>`);
      if (__props.eyebrow) {
        _push(ssrRenderComponent(_sfc_main$1, {
          text: __props.eyebrow,
          "marker-ring": __props.tone === "dark" ? "paper" : "ink"
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="${ssrRenderClass([
        __props.layout === "split" && [
          "md:grid md:grid-cols-[minmax(0,505px)_minmax(0,1fr)] md:items-start md:gap-x-[132px] md:gap-y-6",
          __props.splitClass
        ],
        "flex w-full flex-col gap-6"
      ])}"><h2 class="${ssrRenderClass([__props.tone === "light" ? "text-ink-1000" : "text-ink-50", "whitespace-pre-line text-section-line font-bold"])}">${ssrInterpolate(__props.title)}</h2>`);
      if (__props.subtitle) {
        _push(`<p class="${ssrRenderClass([[
          __props.tone === "light" ? "text-ink-700" : "text-ink-200",
          __props.subtitleWidth || (__props.layout === "split" ? "md:max-w-[612px]" : "max-w-[400px]")
        ], "text-title-md"])}">${ssrInterpolate(__props.subtitle)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/SectionHeading.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
