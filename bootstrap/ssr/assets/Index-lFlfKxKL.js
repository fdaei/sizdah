import { defineComponent, mergeProps, unref, withCtx, openBlock, createBlock, createCommentVNode, createVNode, createTextVNode, toDisplayString, useSSRContext, computed } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrRenderAttr, ssrInterpolate, ssrRenderList } from "vue/server-renderer";
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { _ as _sfc_main$2, a as _sfc_main$6 } from "./BlogCard-C2Vr3W2e.js";
import { _ as _sfc_main$4 } from "./Eyebrow-BY5jAbbp.js";
import { u as useTranslations, _ as _export_sfc } from "../ssr.js";
import { _ as _sfc_main$5 } from "./FilterChips-CkugSIA_.js";
import { _ as _sfc_main$3 } from "./SeoHead-B0PBocbK.js";
import { _ as _sfc_main$7 } from "./StartTogetherCard-DwqCyDIf.js";
import "./calendar-BHdPRM2s.js";
import "@inertiajs/vue3/server";
const mobileBackgroundUrl = "/build/assets/mobile-background-BwlSqgFn.jpg";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "FeaturedPostCard",
  __ssrInlineRender: true,
  props: {
    post: {}
  },
  setup(__props) {
    const props = __props;
    const { t } = useTranslations();
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<article${ssrRenderAttrs(mergeProps({
        class: "featured-post-card surface-glow sketch-frame flex flex-col gap-8 p-4 lg:flex-row lg:items-center",
        style: { "--glow-angle": "-20.03deg", "--mobile-background": `url(${unref(mobileBackgroundUrl)})` }
      }, _attrs))} data-v-077f6994>`);
      _push(ssrRenderComponent(unref(Link), {
        href: props.post.url,
        class: "group block shrink-0 overflow-hidden rounded-lg border border-ink-100 shadow-card max-md:w-full lg:w-[612px]"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            if (props.post.image) {
              _push2(`<picture data-v-077f6994${_scopeId}>`);
              if (props.post.image.avif) {
                _push2(`<source${ssrRenderAttr("srcset", props.post.image.avif)} type="image/avif" data-v-077f6994${_scopeId}>`);
              } else {
                _push2(`<!---->`);
              }
              if (props.post.image.webp) {
                _push2(`<source${ssrRenderAttr("srcset", props.post.image.webp)} type="image/webp" data-v-077f6994${_scopeId}>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`<img${ssrRenderAttr("src", props.post.image.src)}${ssrRenderAttr("srcset", props.post.image.srcset)}${ssrRenderAttr("sizes", props.post.image.sizes)}${ssrRenderAttr("alt", props.post.image.alt)}${ssrRenderAttr("width", props.post.image.width)}${ssrRenderAttr("height", props.post.image.height)} loading="eager" fetchpriority="high" decoding="async" class="aspect-[612/459] w-full object-cover transition-transform duration-500 ease-brand group-hover:scale-105" data-v-077f6994${_scopeId}></picture>`);
            } else {
              _push2(`<div class="aspect-[612/459] w-full bg-ink-900" aria-hidden="true" data-v-077f6994${_scopeId}></div>`);
            }
          } else {
            return [
              props.post.image ? (openBlock(), createBlock("picture", { key: 0 }, [
                props.post.image.avif ? (openBlock(), createBlock("source", {
                  key: 0,
                  srcset: props.post.image.avif,
                  type: "image/avif"
                }, null, 8, ["srcset"])) : createCommentVNode("", true),
                props.post.image.webp ? (openBlock(), createBlock("source", {
                  key: 1,
                  srcset: props.post.image.webp,
                  type: "image/webp"
                }, null, 8, ["srcset"])) : createCommentVNode("", true),
                createVNode("img", {
                  src: props.post.image.src,
                  srcset: props.post.image.srcset,
                  sizes: props.post.image.sizes,
                  alt: props.post.image.alt,
                  width: props.post.image.width,
                  height: props.post.image.height,
                  loading: "eager",
                  fetchpriority: "high",
                  decoding: "async",
                  class: "aspect-[612/459] w-full object-cover transition-transform duration-500 ease-brand group-hover:scale-105"
                }, null, 8, ["src", "srcset", "sizes", "alt", "width", "height"])
              ])) : (openBlock(), createBlock("div", {
                key: 1,
                class: "aspect-[612/459] w-full bg-ink-900",
                "aria-hidden": "true"
              }))
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<div class="flex flex-1 flex-col gap-8 lg:gap-24" data-v-077f6994>`);
      if (props.post.category) {
        _push(`<p class="w-fit rounded-round bg-brand-600 px-2 py-1 text-body-md text-ink-800" data-v-077f6994>${ssrInterpolate(props.post.category.name)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="flex flex-col gap-8 lg:gap-24" data-v-077f6994><div class="flex flex-col gap-6" data-v-077f6994><h2 class="text-heading-xl text-ink-50 max-md:text-[24px] max-md:leading-[30px]" data-v-077f6994>`);
      _push(ssrRenderComponent(unref(Link), {
        href: props.post.url,
        class: "transition-colors duration-200 ease-brand hover:text-brand"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`${ssrInterpolate(props.post.title)}`);
          } else {
            return [
              createTextVNode(toDisplayString(props.post.title), 1)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</h2><p class="text-body-lg text-ink-300" data-v-077f6994>${ssrInterpolate(props.post.excerpt)}</p></div><div class="flex flex-wrap items-center justify-between gap-4" data-v-077f6994>`);
      _push(ssrRenderComponent(_sfc_main$2, {
        post: props.post
      }, null, _parent));
      _push(ssrRenderComponent(unref(Link), {
        href: props.post.url,
        class: "flex items-center gap-2 text-body-lg text-ink-100 transition-colors duration-200 ease-brand hover:text-brand"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`${ssrInterpolate(unref(t)("common.read_article"))} <svg aria-hidden="true" focusable="false" width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="size-8" data-v-077f6994${_scopeId}><path d="M7.32131 13.3255C7.14474 11.9476 7.11965 11.0563 6.74489 9.27071C6.77457 9.26219 8.41058 11.0239 8.42029 11.0343C10.1043 12.825 10.3523 13.1304 12.0632 14.8949C13.208 16.0756 14.444 17.4 15.5198 18.4359C16.5327 19.4112 17.0675 19.7174 17.6117 19.5478C19.0394 19.1031 17.3031 17.4361 16.8822 16.9452C16.143 16.083 15.3405 15.2825 14.5717 14.448C13.5459 13.3346 12.5055 12.2347 11.4507 11.1487C11.2006 10.8913 9.76502 9.46905 8.71327 8.4698C10.0957 8.47707 11.3546 8.50634 11.9254 8.49913C12.7961 8.4881 13.4024 8.5873 14.2841 8.36686C15.2594 8.12302 15.1933 6.47067 14.4384 6.49307C13.3252 6.52614 12.1911 6.58145 10.9962 6.52132C9.47161 6.4446 7.94381 6.24591 6.49246 5.75562C4.63171 5.12705 3.53382 5.67838 4.1589 7.7275C4.64258 9.31316 4.97542 10.9448 5.15141 12.5932C5.25957 13.6064 5.28993 14.0764 5.66469 14.7157C6.27327 15.7539 7.51642 14.848 7.32131 13.3255Z" fill="currentColor" data-v-077f6994${_scopeId}></path></svg>`);
          } else {
            return [
              createTextVNode(toDisplayString(unref(t)("common.read_article")) + " ", 1),
              (openBlock(), createBlock("svg", {
                "aria-hidden": "true",
                focusable: "false",
                width: "32",
                height: "32",
                viewBox: "0 0 24 24",
                fill: "none",
                xmlns: "http://www.w3.org/2000/svg",
                class: "size-8"
              }, [
                createVNode("path", {
                  d: "M7.32131 13.3255C7.14474 11.9476 7.11965 11.0563 6.74489 9.27071C6.77457 9.26219 8.41058 11.0239 8.42029 11.0343C10.1043 12.825 10.3523 13.1304 12.0632 14.8949C13.208 16.0756 14.444 17.4 15.5198 18.4359C16.5327 19.4112 17.0675 19.7174 17.6117 19.5478C19.0394 19.1031 17.3031 17.4361 16.8822 16.9452C16.143 16.083 15.3405 15.2825 14.5717 14.448C13.5459 13.3346 12.5055 12.2347 11.4507 11.1487C11.2006 10.8913 9.76502 9.46905 8.71327 8.4698C10.0957 8.47707 11.3546 8.50634 11.9254 8.49913C12.7961 8.4881 13.4024 8.5873 14.2841 8.36686C15.2594 8.12302 15.1933 6.47067 14.4384 6.49307C13.3252 6.52614 12.1911 6.58145 10.9962 6.52132C9.47161 6.4446 7.94381 6.24591 6.49246 5.75562C4.63171 5.12705 3.53382 5.67838 4.1589 7.7275C4.64258 9.31316 4.97542 10.9448 5.15141 12.5932C5.25957 13.6064 5.28993 14.0764 5.66469 14.7157C6.27327 15.7539 7.51642 14.848 7.32131 13.3255Z",
                  fill: "currentColor"
                })
              ]))
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div></div></div></article>`);
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/FeaturedPostCard.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const FeaturedPostCard = /* @__PURE__ */ _export_sfc(_sfc_main$1, [["__scopeId", "data-v-077f6994"]]);
const paperclipUrl = "/build/assets/paperclip-CaEw2kcJ.svg";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Index",
  __ssrInlineRender: true,
  props: {
    heading: {},
    featured: {},
    posts: {},
    categories: {},
    filters: {},
    sections: {},
    seo: {}
  },
  setup(__props) {
    const props = __props;
    const { t } = useTranslations();
    const finalCta = computed(() => props.sections.final_cta);
    const filterOptions = computed(() => [
      { value: null, label: t("common.all"), href: route("insights.index") },
      ...props.categories.map((category) => ({
        value: category.slug,
        label: category.name,
        href: route("insights.index", { category: category.slug })
      }))
    ]);
    const wide = computed(() => props.posts.data.slice(0, 2));
    const rest = computed(() => props.posts.data.slice(2));
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(_sfc_main$3, {
        seo: props.seo
      }, null, _parent));
      _push(`<section class="section-first overflow-x-hidden pb-[199.24px] max-md:pb-12"><div class="container-sizdah relative isolate flex flex-col gap-16 max-md:gap-8 lg:gap-24"><header class="relative mx-auto flex w-full max-w-[1000px] flex-col items-center gap-16 text-center max-md:gap-8" data-reveal><div class="flex w-full max-w-measure flex-col items-center gap-10 max-md:gap-6 max-md:!max-w-full">`);
      if (props.heading.eyebrow) {
        _push(ssrRenderComponent(_sfc_main$4, {
          text: props.heading.eyebrow
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="flex flex-col items-center gap-6 max-md:gap-5"><h1 class="text-display-lg text-ink-50 max-md:w-full max-md:text-[40px] max-md:leading-[51px]">${ssrInterpolate(props.heading.title)}</h1>`);
      if (props.heading.description) {
        _push(`<p class="text-title-sm text-ink-200 max-md:text-[16px] max-md:leading-[22px]">${ssrInterpolate(props.heading.description)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div><div class="flex w-full justify-center max-md:justify-start">`);
      _push(ssrRenderComponent(_sfc_main$5, {
        options: filterOptions.value,
        active: props.filters.category,
        label: unref(t)("blog.categories"),
        "single-line": ""
      }, null, _parent));
      _push(`</div></header><div class="flex flex-col gap-16 lg:gap-[144px]">`);
      if (props.featured) {
        _push(`<div class="relative"><div aria-hidden="true" class="pointer-events-none absolute left-[45.75%] top-0 z-10 hidden -translate-x-1/2 translate-y-[calc(-50%-8px)] lg:block"><img${ssrRenderAttr("src", unref(paperclipUrl))} alt="" width="78" height="78"></div>`);
        _push(ssrRenderComponent(FeaturedPostCard, {
          post: props.featured
        }, null, _parent));
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      if (!props.posts.data.length) {
        _push(`<p class="py-16 text-center text-title-sm text-ink-300">${ssrInterpolate(props.filters.category || props.filters.q ? unref(t)("common.empty_results") : unref(t)("common.empty_posts"))}</p>`);
      } else {
        _push(`<!---->`);
      }
      if (wide.value.length) {
        _push(`<div class="grid gap-6 md:grid-cols-2" data-reveal-group><!--[-->`);
        ssrRenderList(wide.value, (post) => {
          _push(ssrRenderComponent(_sfc_main$6, {
            key: post.slug,
            post,
            variant: "wide",
            "heading-level": "h2"
          }, null, _parent));
        });
        _push(`<!--]--></div>`);
      } else {
        _push(`<!---->`);
      }
      if (rest.value.length) {
        _push(`<div class="grid gap-x-6 gap-y-16 sm:grid-cols-2 lg:grid-cols-3 lg:gap-y-24" data-reveal-group><!--[-->`);
        ssrRenderList(rest.value, (post) => {
          _push(ssrRenderComponent(_sfc_main$6, {
            key: post.slug,
            post,
            "heading-level": "h2"
          }, null, _parent));
        });
        _push(`<!--]--></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
      if (props.posts.lastPage > 1) {
        _push(`<nav${ssrRenderAttr("aria-label", unref(t)("common.pagination"))} class="flex items-center justify-center gap-6">`);
        if (props.posts.prevPageUrl) {
          _push(ssrRenderComponent(unref(Link), {
            href: props.posts.prevPageUrl,
            class: "text-body-lg text-paper transition-colors duration-200 ease-brand hover:text-brand"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(`${ssrInterpolate(unref(t)("common.previous"))}`);
              } else {
                return [
                  createTextVNode(toDisplayString(unref(t)("common.previous")), 1)
                ];
              }
            }),
            _: 1
          }, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`<span class="text-body-md text-ink-300 latin-nums">${ssrInterpolate(props.posts.currentPage)} / ${ssrInterpolate(props.posts.lastPage)}</span>`);
        if (props.posts.nextPageUrl) {
          _push(ssrRenderComponent(unref(Link), {
            href: props.posts.nextPageUrl,
            class: "text-body-lg text-paper transition-colors duration-200 ease-brand hover:text-brand"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(`${ssrInterpolate(unref(t)("common.next"))}`);
              } else {
                return [
                  createTextVNode(toDisplayString(unref(t)("common.next")), 1)
                ];
              }
            }),
            _: 1
          }, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`</nav>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
      if (finalCta.value) {
        _push(`<div class="container-sizdah mt-16 lg:mt-[190px]">`);
        _push(ssrRenderComponent(_sfc_main$7, {
          section: finalCta.value,
          "data-reveal": ""
        }, null, _parent));
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</section><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Insights/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
