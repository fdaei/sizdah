import { defineComponent, computed, unref, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrInterpolate } from "vue/server-renderer";
import { Head } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { u as useTranslations, a as _sfc_main$1 } from "../ssr.js";
import "@inertiajs/vue3/server";
const lostMapUrl = "/build/assets/lost-map-D0R_CJQa.png";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Error",
  __ssrInlineRender: true,
  props: {
    status: {}
  },
  setup(__props) {
    const props = __props;
    const { t } = useTranslations();
    const known = [403, 404, 429, 500, 503];
    const key = computed(() => known.includes(props.status) ? String(props.status) : "500");
    const title = computed(() => t(`errors.${key.value}.title`));
    const message = computed(() => t(`errors.${key.value}.message`));
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(Head), { title: title.value }, null, _parent));
      _push(`<section class="section-first flex min-h-[874px] flex-col items-center pb-0 text-center max-md:pt-[239px]"><div class="container-sizdah flex flex-col items-center">`);
      if (props.status === 404) {
        _push(`<img${ssrRenderAttr("src", unref(lostMapUrl))} alt="" aria-hidden="true" width="725" height="544" class="mb-[-40px] h-auto w-full max-w-[725px] max-md:mb-[-24px] max-md:h-[279.87px] max-md:w-[373.162px] max-md:object-cover">`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="flex w-full max-w-[506px] flex-col items-center gap-8 max-md:max-w-[362px] max-md:gap-5"><div class="flex flex-col items-center gap-4 max-md:gap-3"><h1 class="text-heading-xl text-brand-50 max-md:text-[20px] max-md:leading-6">${ssrInterpolate(title.value)}</h1><p class="whitespace-pre-line text-title-sm text-ink-200 max-md:text-[16px] max-md:leading-[20px]">${ssrInterpolate(message.value)}</p></div>`);
      _push(ssrRenderComponent(_sfc_main$1, {
        label: unref(t)("errors.back_home"),
        href: unref(route)("home"),
        size: "md",
        class: "max-md:!px-[18px] max-md:!py-3 max-md:!text-[16px] max-md:!leading-[22px]"
      }, null, _parent));
      _push(`</div></div></section><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Error.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
