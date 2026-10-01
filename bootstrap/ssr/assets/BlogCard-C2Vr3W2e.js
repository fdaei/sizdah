import { defineComponent, mergeProps, unref, useSSRContext, withCtx, createVNode, resolveDynamicComponent, createTextVNode, toDisplayString, openBlock, createBlock, createCommentVNode } from "vue";
import { ssrRenderAttrs, ssrRenderAttr, ssrInterpolate, ssrRenderComponent, ssrRenderClass, ssrRenderVNode } from "vue/server-renderer";
import { Link } from "@inertiajs/vue3";
import { u as useTranslations } from "../ssr.js";
import { c as calendarUrl } from "./calendar-BHdPRM2s.js";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "PostMeta",
  __ssrInlineRender: true,
  props: {
    post: {}
  },
  setup(__props) {
    const props = __props;
    const { t } = useTranslations();
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "flex items-center gap-4 text-body-md text-ink-200" }, _attrs))}><span class="flex items-center gap-2"><img${ssrRenderAttr("src", unref(calendarUrl))} alt="" aria-hidden="true" width="24" height="22" class="h-[22px] w-6 shrink-0"><time${ssrRenderAttr("datetime", props.post.publishedAtIso)}>${ssrInterpolate(props.post.publishedAt)}</time></span><span class="flex items-center gap-2"><span aria-hidden="true" class="size-1 shrink-0 rounded-round bg-brand"></span><span>${ssrInterpolate(unref(t)("blog.reading_time", { minutes: props.post.readingTime }))}</span></span></div>`);
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/PostMeta.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "BlogCard",
  __ssrInlineRender: true,
  props: {
    post: {},
    variant: { default: "default" },
    headingLevel: { default: "h3" }
  },
  setup(__props) {
    const props = __props;
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<article${ssrRenderAttrs(mergeProps({ class: "flex flex-col gap-4" }, _attrs))}>`);
      _push(ssrRenderComponent(unref(Link), {
        href: props.post.url,
        class: "group flex flex-col gap-4"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="${ssrRenderClass([props.variant === "wide" ? "aspect-[612/400]" : "aspect-square", "overflow-hidden rounded-lg border border-warm-100 shadow-card"])}"${_scopeId}>`);
            if (props.post.image) {
              _push2(`<picture${_scopeId}>`);
              if (props.post.image.avif) {
                _push2(`<source${ssrRenderAttr("srcset", props.post.image.avif)} type="image/avif"${_scopeId}>`);
              } else {
                _push2(`<!---->`);
              }
              if (props.post.image.webp) {
                _push2(`<source${ssrRenderAttr("srcset", props.post.image.webp)} type="image/webp"${_scopeId}>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`<img${ssrRenderAttr("src", props.post.image.src)}${ssrRenderAttr("srcset", props.post.image.srcset)}${ssrRenderAttr("sizes", props.post.image.sizes)}${ssrRenderAttr("alt", props.post.image.alt)}${ssrRenderAttr("width", props.post.image.width)}${ssrRenderAttr("height", props.post.image.height)} loading="eager" decoding="async" class="size-full object-cover transition-transform duration-500 ease-brand group-hover:scale-105"${_scopeId}></picture>`);
            } else {
              _push2(`<div class="size-full bg-ink-900" aria-hidden="true"${_scopeId}></div>`);
            }
            _push2(`</div><div class="flex flex-col gap-4"${_scopeId}>`);
            _push2(ssrRenderComponent(_sfc_main$1, {
              post: props.post
            }, null, _parent2, _scopeId));
            ssrRenderVNode(_push2, createVNode(resolveDynamicComponent(props.headingLevel), {
              class: ["text-ink-50 transition-colors duration-200 ease-brand group-hover:text-brand", props.variant === "wide" ? "text-heading-md max-md:text-[24px] max-md:leading-[30px]" : "text-heading-sm max-md:text-[20px] max-md:leading-[25px]"]
            }, {
              default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                if (_push3) {
                  _push3(`${ssrInterpolate(props.post.title)}`);
                } else {
                  return [
                    createTextVNode(toDisplayString(props.post.title), 1)
                  ];
                }
              }),
              _: 1
            }), _parent2, _scopeId);
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", {
                class: ["overflow-hidden rounded-lg border border-warm-100 shadow-card", props.variant === "wide" ? "aspect-[612/400]" : "aspect-square"]
              }, [
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
                    decoding: "async",
                    class: "size-full object-cover transition-transform duration-500 ease-brand group-hover:scale-105"
                  }, null, 8, ["src", "srcset", "sizes", "alt", "width", "height"])
                ])) : (openBlock(), createBlock("div", {
                  key: 1,
                  class: "size-full bg-ink-900",
                  "aria-hidden": "true"
                }))
              ], 2),
              createVNode("div", { class: "flex flex-col gap-4" }, [
                createVNode(_sfc_main$1, {
                  post: props.post
                }, null, 8, ["post"]),
                (openBlock(), createBlock(resolveDynamicComponent(props.headingLevel), {
                  class: ["text-ink-50 transition-colors duration-200 ease-brand group-hover:text-brand", props.variant === "wide" ? "text-heading-md max-md:text-[24px] max-md:leading-[30px]" : "text-heading-sm max-md:text-[20px] max-md:leading-[25px]"]
                }, {
                  default: withCtx(() => [
                    createTextVNode(toDisplayString(props.post.title), 1)
                  ]),
                  _: 1
                }, 8, ["class"]))
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
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/BlogCard.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main$1 as _,
  _sfc_main as a
};
