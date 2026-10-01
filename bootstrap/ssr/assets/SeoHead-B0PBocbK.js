import { defineComponent, computed, unref, mergeProps, withCtx, createVNode, resolveDynamicComponent, openBlock, createBlock, createCommentVNode, Fragment, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrRenderVNode } from "vue/server-renderer";
import { usePage, Head } from "@inertiajs/vue3";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "SeoHead",
  __ssrInlineRender: true,
  props: {
    seo: {}
  },
  setup(__props) {
    const props = __props;
    const page = usePage();
    const settings = computed(() => page.props.settings);
    const ogLocale = computed(() => page.props.locale.htmlLang.replace("-", "_"));
    const schemaJson = computed(() => {
      if (!props.seo.schema) {
        return void 0;
      }
      return JSON.stringify(props.seo.schema).replace(/</g, "\\u003c");
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(unref(Head), mergeProps({
        title: props.seo.title
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<meta name="description"${ssrRenderAttr("content", props.seo.description)}${_scopeId}><link rel="canonical"${ssrRenderAttr("href", props.seo.canonical)}${_scopeId}>`);
            if (props.seo.noindex) {
              _push2(`<meta name="robots" content="noindex, nofollow"${_scopeId}>`);
            } else {
              _push2(`<!---->`);
            }
            if (schemaJson.value) {
              ssrRenderVNode(_push2, createVNode(resolveDynamicComponent("script"), { type: "application/ld+json" }, null), _parent2, _scopeId);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<meta property="og:type"${ssrRenderAttr("content", props.seo.type)}${_scopeId}><meta property="og:title"${ssrRenderAttr("content", props.seo.title)}${_scopeId}><meta property="og:description"${ssrRenderAttr("content", props.seo.description)}${_scopeId}><meta property="og:url"${ssrRenderAttr("content", props.seo.canonical)}${_scopeId}><meta property="og:site_name"${ssrRenderAttr("content", settings.value.seo.organizationName)}${_scopeId}><meta property="og:locale"${ssrRenderAttr("content", ogLocale.value)}${_scopeId}>`);
            if (props.seo.image) {
              _push2(`<meta property="og:image"${ssrRenderAttr("content", props.seo.image)}${_scopeId}>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`<meta name="twitter:card"${ssrRenderAttr("content", props.seo.image ? "summary_large_image" : "summary")}${_scopeId}><meta name="twitter:title"${ssrRenderAttr("content", props.seo.title)}${_scopeId}><meta name="twitter:description"${ssrRenderAttr("content", props.seo.description)}${_scopeId}>`);
            if (props.seo.image) {
              _push2(`<meta name="twitter:image"${ssrRenderAttr("content", props.seo.image)}${_scopeId}>`);
            } else {
              _push2(`<!---->`);
            }
            if (props.seo.type === "article") {
              _push2(`<!--[-->`);
              if (props.seo.publishedAt) {
                _push2(`<meta property="article:published_time"${ssrRenderAttr("content", props.seo.publishedAt)}${_scopeId}>`);
              } else {
                _push2(`<!---->`);
              }
              if (props.seo.modifiedAt) {
                _push2(`<meta property="article:modified_time"${ssrRenderAttr("content", props.seo.modifiedAt)}${_scopeId}>`);
              } else {
                _push2(`<!---->`);
              }
              if (props.seo.author) {
                _push2(`<meta property="article:author"${ssrRenderAttr("content", props.seo.author)}${_scopeId}>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`<!--]-->`);
            } else {
              _push2(`<!---->`);
            }
          } else {
            return [
              createVNode("meta", {
                name: "description",
                content: props.seo.description
              }, null, 8, ["content"]),
              createVNode("link", {
                rel: "canonical",
                href: props.seo.canonical
              }, null, 8, ["href"]),
              props.seo.noindex ? (openBlock(), createBlock("meta", {
                key: 0,
                name: "robots",
                content: "noindex, nofollow"
              })) : createCommentVNode("", true),
              schemaJson.value ? (openBlock(), createBlock(resolveDynamicComponent("script"), {
                key: 1,
                type: "application/ld+json",
                innerHTML: schemaJson.value
              }, null, 8, ["innerHTML"])) : createCommentVNode("", true),
              createVNode("meta", {
                property: "og:type",
                content: props.seo.type
              }, null, 8, ["content"]),
              createVNode("meta", {
                property: "og:title",
                content: props.seo.title
              }, null, 8, ["content"]),
              createVNode("meta", {
                property: "og:description",
                content: props.seo.description
              }, null, 8, ["content"]),
              createVNode("meta", {
                property: "og:url",
                content: props.seo.canonical
              }, null, 8, ["content"]),
              createVNode("meta", {
                property: "og:site_name",
                content: settings.value.seo.organizationName
              }, null, 8, ["content"]),
              createVNode("meta", {
                property: "og:locale",
                content: ogLocale.value
              }, null, 8, ["content"]),
              props.seo.image ? (openBlock(), createBlock("meta", {
                key: 2,
                property: "og:image",
                content: props.seo.image
              }, null, 8, ["content"])) : createCommentVNode("", true),
              createVNode("meta", {
                name: "twitter:card",
                content: props.seo.image ? "summary_large_image" : "summary"
              }, null, 8, ["content"]),
              createVNode("meta", {
                name: "twitter:title",
                content: props.seo.title
              }, null, 8, ["content"]),
              createVNode("meta", {
                name: "twitter:description",
                content: props.seo.description
              }, null, 8, ["content"]),
              props.seo.image ? (openBlock(), createBlock("meta", {
                key: 3,
                name: "twitter:image",
                content: props.seo.image
              }, null, 8, ["content"])) : createCommentVNode("", true),
              props.seo.type === "article" ? (openBlock(), createBlock(Fragment, { key: 4 }, [
                props.seo.publishedAt ? (openBlock(), createBlock("meta", {
                  key: 0,
                  property: "article:published_time",
                  content: props.seo.publishedAt
                }, null, 8, ["content"])) : createCommentVNode("", true),
                props.seo.modifiedAt ? (openBlock(), createBlock("meta", {
                  key: 1,
                  property: "article:modified_time",
                  content: props.seo.modifiedAt
                }, null, 8, ["content"])) : createCommentVNode("", true),
                props.seo.author ? (openBlock(), createBlock("meta", {
                  key: 2,
                  property: "article:author",
                  content: props.seo.author
                }, null, 8, ["content"])) : createCommentVNode("", true)
              ], 64)) : createCommentVNode("", true)
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/SeoHead.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
