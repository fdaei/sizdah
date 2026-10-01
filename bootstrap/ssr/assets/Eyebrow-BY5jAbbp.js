import { defineComponent, createVNode, resolveDynamicComponent, mergeProps, withCtx, createTextVNode, toDisplayString, useSSRContext } from "vue";
import { ssrRenderVNode, ssrInterpolate } from "vue/server-renderer";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Eyebrow",
  __ssrInlineRender: true,
  props: {
    text: {},
    as: { default: "span" },
    size: { default: "sm" },
    markerRing: { default: "paper" }
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      ssrRenderVNode(_push, createVNode(resolveDynamicComponent(__props.as), mergeProps({
        class: ["eyebrow eyebrow--dot", [__props.size === "lg" && "eyebrow--lg", __props.markerRing === "ink" && "eyebrow--dot-ink"]]
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`${ssrInterpolate(__props.text)}`);
          } else {
            return [
              createTextVNode(toDisplayString(__props.text), 1)
            ];
          }
        }),
        _: 1
      }), _parent);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Eyebrow.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
