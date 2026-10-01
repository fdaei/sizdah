import { defineComponent, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderList } from "vue/server-renderer";
import { _ as _sfc_main$1 } from "./SeoHead-B0PBocbK.js";
import "@inertiajs/vue3";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Show",
  __ssrInlineRender: true,
  props: {
    service: {},
    seo: {}
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(_sfc_main$1, { seo: __props.seo }, null, _parent));
      _push(`<main id="main" class="section-first container-sizdah py-32"><article class="mx-auto max-w-3xl"><h1 class="text-display-lg text-ink-50">${ssrInterpolate(__props.service.title)}</h1><p class="mt-8 text-title-lg text-ink-200">${ssrInterpolate(__props.service.description)}</p>`);
      if (__props.service.features.length) {
        _push(`<ul class="mt-10 list-disc space-y-4 ps-6 text-body-lg text-ink-200"><!--[-->`);
        ssrRenderList(__props.service.features, (feature) => {
          _push(`<li>${ssrInterpolate(feature)}</li>`);
        });
        _push(`<!--]--></ul>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<nav class="mt-12 flex gap-6" aria-label="Related pages"><a href="/en/services" class="underline">All services</a><a href="/en/contact" class="underline">Contact us</a></nav></article></main><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Services/Show.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
