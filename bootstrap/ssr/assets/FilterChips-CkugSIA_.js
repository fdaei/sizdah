import { defineComponent, mergeProps, unref, withCtx, createVNode, toDisplayString, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderClass, ssrRenderList, ssrRenderComponent, ssrInterpolate, ssrRenderAttr } from "vue/server-renderer";
import { Link } from "@inertiajs/vue3";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "FilterChips",
  __ssrInlineRender: true,
  props: {
    options: {},
    active: {},
    label: {},
    variant: { default: "outline" },
    singleLine: { type: Boolean, default: false }
  },
  emits: ["select"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    function frameClass(value) {
      const active = value === props.active;
      return [
        "pointer-events-none absolute inset-y-0 transition-colors duration-200 ease-brand",
        !active && "bg-ink-300 group-hover:bg-paper",
        active && "bg-brand"
      ];
    }
    function chipClass(value) {
      const active = value === props.active;
      return [
        "group relative inline-flex items-center justify-center whitespace-nowrap rounded-lg px-[26px] py-[14px] text-body-lg transition-colors duration-200 ease-brand",
        !active && "text-paper",
        active && props.variant === "solid" && "bg-brand text-ink-1000",
        active && props.variant === "outline" && "bg-ink-900 text-brand"
      ];
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<nav${ssrRenderAttrs(mergeProps({
        "aria-label": props.label,
        class: props.singleLine && "scrollbar-hidden max-w-full overflow-x-auto"
      }, _attrs))}><ul class="${ssrRenderClass([props.singleLine ? "w-max flex-nowrap" : "flex-wrap", "flex items-start gap-3"])}"><!--[-->`);
      ssrRenderList(props.options, (option) => {
        _push(`<li class="${ssrRenderClass(props.singleLine && "shrink-0")}">`);
        if (option.href) {
          _push(ssrRenderComponent(unref(Link), {
            href: option.href,
            "preserve-scroll": "",
            "aria-current": option.value === props.active ? "page" : void 0,
            class: chipClass(option.value)
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(`<span aria-hidden="true" class="${ssrRenderClass([frameClass(option.value), "sketch-frame-chip-cap-end start-0 w-6"])}"${_scopeId}></span><span aria-hidden="true" class="${ssrRenderClass([frameClass(option.value), "sketch-frame-chip-middle inset-x-6"])}"${_scopeId}></span><span aria-hidden="true" class="${ssrRenderClass([frameClass(option.value), "sketch-frame-chip-cap-start end-0 w-6"])}"${_scopeId}></span><span class="relative"${_scopeId}>${ssrInterpolate(option.label)}</span>`);
              } else {
                return [
                  createVNode("span", {
                    "aria-hidden": "true",
                    class: ["sketch-frame-chip-cap-end start-0 w-6", frameClass(option.value)]
                  }, null, 2),
                  createVNode("span", {
                    "aria-hidden": "true",
                    class: ["sketch-frame-chip-middle inset-x-6", frameClass(option.value)]
                  }, null, 2),
                  createVNode("span", {
                    "aria-hidden": "true",
                    class: ["sketch-frame-chip-cap-start end-0 w-6", frameClass(option.value)]
                  }, null, 2),
                  createVNode("span", { class: "relative" }, toDisplayString(option.label), 1)
                ];
              }
            }),
            _: 2
          }, _parent));
        } else {
          _push(`<button type="button"${ssrRenderAttr("aria-pressed", option.value === props.active)} class="${ssrRenderClass(chipClass(option.value))}"><span aria-hidden="true" class="${ssrRenderClass([frameClass(option.value), "sketch-frame-chip-cap-end start-0 w-6"])}"></span><span aria-hidden="true" class="${ssrRenderClass([frameClass(option.value), "sketch-frame-chip-middle inset-x-6"])}"></span><span aria-hidden="true" class="${ssrRenderClass([frameClass(option.value), "sketch-frame-chip-cap-start end-0 w-6"])}"></span><span class="relative">${ssrInterpolate(option.label)}</span></button>`);
        }
        _push(`</li>`);
      });
      _push(`<!--]--></ul></nav>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/FilterChips.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
