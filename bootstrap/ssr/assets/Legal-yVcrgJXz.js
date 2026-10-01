import { defineComponent, unref, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrInterpolate } from "vue/server-renderer";
import { _ as _sfc_main$1 } from "./SeoHead-B0PBocbK.js";
import "@inertiajs/vue3";
const trustBadgeUrl = "/build/assets/trust-badge-jirfooLv.svg";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Legal",
  __ssrInlineRender: true,
  props: {
    title: {},
    subtitle: {},
    content: {},
    updatedAt: {},
    seo: {}
  },
  setup(__props) {
    const props = __props;
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(_sfc_main$1, {
        seo: props.seo
      }, null, _parent));
      _push(`<article class="section-first pb-24 md:pt-[188px]"><div class="relative mx-auto hidden w-full max-w-frame xl:block"><img${ssrRenderAttr("src", unref(trustBadgeUrl))} alt="" aria-hidden="true" width="24" height="24" class="pointer-events-none absolute left-[calc(66.67%-16px)] top-[1258px] size-6"></div><div class="container-sizdah"><header class="flex max-w-measure flex-col gap-6" data-reveal><h1 class="text-display-lg text-ink-50 max-md:text-[40px] max-md:leading-[51px]">${ssrInterpolate(props.title)}</h1>`);
      if (props.subtitle) {
        _push(`<p class="text-title-md text-ink-200 max-md:text-[16px] max-md:leading-[22px]">${ssrInterpolate(props.subtitle)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</header><div class="rich-prose mt-24">${props.content ?? ""}</div></div></article><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Legal.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
