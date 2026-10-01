import { defineComponent, mergeProps, unref, withCtx, createVNode, openBlock, createBlock, toDisplayString, createCommentVNode, Fragment, renderList, useSSRContext, computed } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrRenderAttr, ssrInterpolate, ssrRenderList, ssrRenderStyle } from "vue/server-renderer";
import { route } from "ziggy-js";
import { _ as _sfc_main$3 } from "./Eyebrow-BY5jAbbp.js";
import { _ as _sfc_main$4 } from "./FilterChips-CkugSIA_.js";
import { Link } from "@inertiajs/vue3";
import { _ as _sfc_main$2 } from "./SeoHead-B0PBocbK.js";
import { _ as _sfc_main$5 } from "./StartTogetherCard-DwqCyDIf.js";
import { u as useTranslations } from "../ssr.js";
import "@inertiajs/vue3/server";
const titleUnderlineUrl = "/build/assets/project-title-underline-SoZA67Lr.svg";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "ProjectPostCard",
  __ssrInlineRender: true,
  props: {
    project: {}
  },
  setup(__props) {
    const props = __props;
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<article${ssrRenderAttrs(mergeProps({ class: "group h-full" }, _attrs))}>`);
      _push(ssrRenderComponent(unref(Link), {
        href: props.project.url,
        class: "flex h-full flex-col gap-10"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="aspect-square shrink-0 overflow-hidden rounded-[24px] border-2 border-ink-400 shadow-card"${_scopeId}>`);
            if (props.project.image) {
              _push2(`<img${ssrRenderAttr("src", props.project.image.src)}${ssrRenderAttr("srcset", props.project.image.srcset)}${ssrRenderAttr("alt", props.project.image.alt)}${ssrRenderAttr("width", props.project.image.width)}${ssrRenderAttr("height", props.project.image.height)} loading="lazy" class="size-full object-cover transition-transform duration-500 ease-brand group-hover:scale-105"${_scopeId}>`);
            } else {
              _push2(`<div class="size-full bg-ink-900" aria-hidden="true"${_scopeId}></div>`);
            }
            _push2(`</div><div class="flex flex-1 flex-col gap-6"${_scopeId}><div class="flex flex-1 flex-col gap-4"${_scopeId}><div class="flex items-start justify-between gap-4"${_scopeId}><h3 class="flex min-w-0 flex-col items-start"${_scopeId}><span class="text-display-sm font-semibold text-paper transition-colors duration-200 ease-brand group-hover:text-brand"${_scopeId}>${ssrInterpolate(props.project.title)}</span><img${ssrRenderAttr("src", unref(titleUnderlineUrl))} alt="" aria-hidden="true" class="mt-1 h-[5px] w-full"${_scopeId}></h3>`);
            if (props.project.industry) {
              _push2(`<p class="shrink-0 pt-3 text-label-lg text-brand"${_scopeId}>${ssrInterpolate(props.project.industry)}</p>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><p class="mt-auto line-clamp-2 min-h-10 text-body-lg text-ink-100"${_scopeId}>${ssrInterpolate(props.project.excerpt)}</p></div>`);
            if (props.project.services.length) {
              _push2(`<ul class="flex min-h-[17.5px] flex-nowrap items-center gap-2 overflow-hidden"${_scopeId}><!--[-->`);
              ssrRenderList(props.project.services, (service, index) => {
                _push2(`<li class="flex shrink-0 items-center gap-2"${_scopeId}>`);
                if (index > 0) {
                  _push2(`<span class="size-1 shrink-0 rounded-round bg-brand" aria-hidden="true"${_scopeId}></span>`);
                } else {
                  _push2(`<!---->`);
                }
                _push2(`<span class="whitespace-nowrap text-body-md text-ink-100"${_scopeId}>${ssrInterpolate(service)}</span></li>`);
              });
              _push2(`<!--]--></ul>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", { class: "aspect-square shrink-0 overflow-hidden rounded-[24px] border-2 border-ink-400 shadow-card" }, [
                props.project.image ? (openBlock(), createBlock("img", {
                  key: 0,
                  src: props.project.image.src,
                  srcset: props.project.image.srcset,
                  alt: props.project.image.alt,
                  width: props.project.image.width,
                  height: props.project.image.height,
                  loading: "lazy",
                  class: "size-full object-cover transition-transform duration-500 ease-brand group-hover:scale-105"
                }, null, 8, ["src", "srcset", "alt", "width", "height"])) : (openBlock(), createBlock("div", {
                  key: 1,
                  class: "size-full bg-ink-900",
                  "aria-hidden": "true"
                }))
              ]),
              createVNode("div", { class: "flex flex-1 flex-col gap-6" }, [
                createVNode("div", { class: "flex flex-1 flex-col gap-4" }, [
                  createVNode("div", { class: "flex items-start justify-between gap-4" }, [
                    createVNode("h3", { class: "flex min-w-0 flex-col items-start" }, [
                      createVNode("span", { class: "text-display-sm font-semibold text-paper transition-colors duration-200 ease-brand group-hover:text-brand" }, toDisplayString(props.project.title), 1),
                      createVNode("img", {
                        src: unref(titleUnderlineUrl),
                        alt: "",
                        "aria-hidden": "true",
                        class: "mt-1 h-[5px] w-full"
                      }, null, 8, ["src"])
                    ]),
                    props.project.industry ? (openBlock(), createBlock("p", {
                      key: 0,
                      class: "shrink-0 pt-3 text-label-lg text-brand"
                    }, toDisplayString(props.project.industry), 1)) : createCommentVNode("", true)
                  ]),
                  createVNode("p", { class: "mt-auto line-clamp-2 min-h-10 text-body-lg text-ink-100" }, toDisplayString(props.project.excerpt), 1)
                ]),
                props.project.services.length ? (openBlock(), createBlock("ul", {
                  key: 0,
                  class: "flex min-h-[17.5px] flex-nowrap items-center gap-2 overflow-hidden"
                }, [
                  (openBlock(true), createBlock(Fragment, null, renderList(props.project.services, (service, index) => {
                    return openBlock(), createBlock("li", {
                      key: service,
                      class: "flex shrink-0 items-center gap-2"
                    }, [
                      index > 0 ? (openBlock(), createBlock("span", {
                        key: 0,
                        class: "size-1 shrink-0 rounded-round bg-brand",
                        "aria-hidden": "true"
                      })) : createCommentVNode("", true),
                      createVNode("span", { class: "whitespace-nowrap text-body-md text-ink-100" }, toDisplayString(service), 1)
                    ]);
                  }), 128))
                ])) : createCommentVNode("", true)
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</article>`);
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/ProjectPostCard.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const figmaWorkFirstUrl = "/build/assets/figma-1074-first-BtNYtzAg.png";
const figmaWorkSecondUrl = "/build/assets/figma-1074-second-25FxDWVR.png";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Index",
  __ssrInlineRender: true,
  props: {
    heading: {},
    projects: {},
    filters: {},
    activeFilter: {},
    sections: {},
    seo: {}
  },
  setup(__props) {
    const props = __props;
    const { t } = useTranslations();
    const filterOptions = computed(() => [
      { value: null, label: t("common.all"), href: route("work.index") },
      ...props.filters.map((filter) => ({
        value: filter.slug,
        label: filter.name,
        href: route("work.index", { service: filter.slug })
      }))
    ]);
    const finalCta = computed(() => props.sections.final_cta);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(_sfc_main$2, {
        seo: props.seo
      }, null, _parent));
      _push(`<section class="section-first pb-[177.24px] max-md:pt-[132px]"><div class="container-sizdah relative isolate"><header class="mx-auto flex max-w-full flex-col items-center gap-16 text-center max-md:gap-8" data-reveal><div class="flex w-full max-w-[612px] flex-col items-center gap-10">`);
      if (props.heading.eyebrow) {
        _push(ssrRenderComponent(_sfc_main$3, {
          text: props.heading.eyebrow,
          class: "max-md:hidden"
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="hidden items-center gap-1 text-[14px] text-brand max-md:flex">پروژه های ما <span class="size-2 rounded-full bg-brand"></span></div><div class="flex flex-col items-center gap-6 max-md:gap-6"><h1 class="text-display-lg text-ink-50 max-md:text-[26px] max-md:leading-[36px]"><span class="max-md:hidden">${ssrInterpolate(props.heading.title)}</span><span class="hidden max-md:block">هر برند<br>داستان خودش را دارد</span></h1>`);
      if (props.heading.description) {
        _push(`<p class="text-title-sm text-ink-200 max-md:text-[16px] max-md:leading-[22px]"><span class="max-md:hidden">${ssrInterpolate(props.heading.description)}</span><span class="hidden max-md:block">نمونه‌ای از برندهایی که در این مسیر همراهشان بوده‌ایم</span></p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div>`);
      if (props.filters.length) {
        _push(`<div class="flex w-full justify-center max-md:justify-start max-md:overflow-x-auto max-md:pb-2">`);
        _push(ssrRenderComponent(_sfc_main$4, {
          options: filterOptions.value,
          active: props.activeFilter,
          label: unref(t)("work.services"),
          variant: "solid",
          "single-line": ""
        }, null, _parent));
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</header>`);
      if (!props.projects.length) {
        _push(`<p class="mt-[110px] py-16 text-center text-title-sm text-ink-300">${ssrInterpolate(props.activeFilter ? unref(t)("common.empty_results") : unref(t)("common.empty_projects"))}</p>`);
      } else {
        _push(`<div class="mt-16 grid gap-x-6 gap-y-16 max-md:hidden sm:grid-cols-2 md:mt-[110px] md:gap-y-24 lg:grid-cols-3" data-reveal-group><!--[-->`);
        ssrRenderList(props.projects, (project) => {
          _push(ssrRenderComponent(_sfc_main$1, {
            key: project.slug,
            project,
            "data-reveal": ""
          }, null, _parent));
        });
        _push(`<!--]--></div>`);
      }
      _push(`<div class="mt-[77px] hidden flex-col gap-[86px] pb-[64px] max-md:flex"><article class="h-[542px] rounded-2xl bg-ink-900 p-2" style="${ssrRenderStyle({ "background-image": "linear-gradient(90deg, rgb(0 0 0 / 20%), rgb(0 0 0 / 20%)), linear-gradient(-54deg, rgb(248 185 55 / 0%), rgb(248 185 55 / 10%)), linear-gradient(90deg, rgb(20 20 20 / 80%), rgb(20 20 20 / 80%))" })}"><img${ssrRenderAttr("src", unref(figmaWorkFirstUrl))} alt="" class="h-[248px] w-full rounded-2xl border border-warm-100 object-cover"><div class="mt-6 flex flex-col items-end gap-4 text-right"><div class="flex w-full flex-row-reverse items-center justify-between"><span class="text-[14px] text-brand">↙ مطالعه مقاله</span><span class="rounded-full bg-brand-100 px-2 py-1 text-[14px] text-ink-800">رشد</span></div><h3 class="text-[22px] leading-normal text-ink-50">چطور شبکه‌های اجتماعی را به یک مسیر رشد تبدیل کنیم؟</h3><p class="text-[16px] leading-normal text-ink-300">از انتشار پراکنده و پیام‌های ناهماهنگ تا ساخت یک سیستم منسجم؛ سیستمی که مخاطب درست را جذب می‌کند، به‌مرور اعتماد می‌سازد و توجه را به نتیجه واقعی برای کسب‌وکار تبدیل می‌کند.</p><p class="text-[14px] text-ink-200">۲۰ اردیبهشت ۱۴۰۳ · زمان مطالعه : ۵ دقیقه</p></div></article><article class="h-[488px] overflow-hidden rounded-2xl border border-warm-100 shadow-card"><img${ssrRenderAttr("src", unref(figmaWorkSecondUrl))} alt="" class="h-[362px] w-full object-cover"><div class="flex flex-col items-end gap-4 p-4 text-right"><p class="text-[14px] text-ink-200">۲۰ اردیبهشت ۱۴۰۳ · زمان مطالعه : ۵ دقیقه</p><h3 class="text-[22px] leading-normal text-ink-50">وقتی بدون مسیر محتوا تولید می‌کنید، چه اتفاقی می‌افتد؟</h3></div></article></div>`);
      if (finalCta.value) {
        _push(`<div class="mt-24 max-md:hidden md:mt-[259px]">`);
        _push(ssrRenderComponent(_sfc_main$5, {
          section: finalCta.value,
          "data-reveal": ""
        }, null, _parent));
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></section><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Work/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
