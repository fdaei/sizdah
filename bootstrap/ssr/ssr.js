import { Link, usePage, createInertiaApp } from "@inertiajs/vue3";
import createServer from "@inertiajs/vue3/server";
import { mergeProps, useSSRContext, defineComponent, unref, computed, createVNode, resolveDynamicComponent, withCtx, toDisplayString, openBlock, createBlock, createCommentVNode, createTextVNode, ref, watch, nextTick, inject, createSSRApp, h } from "vue";
import { ssrRenderAttrs, ssrRenderVNode, ssrInterpolate, ssrRenderAttr, ssrRenderList, ssrRenderComponent, ssrRenderClass, ssrRenderSlot, renderToString } from "vue/server-renderer";
import { route, ZiggyVue } from "ziggy-js";
async function resolvePageComponent(path, pages) {
  for (const p of Array.isArray(path) ? path : [path]) {
    const page = pages[p];
    if (typeof page === "undefined") {
      continue;
    }
    return typeof page === "function" ? page() : page;
  }
  throw new Error(`Page not found: ${path}`);
}
const _export_sfc = (sfc, props) => {
  const target = sfc.__vccOpts || sfc;
  for (const [key, val] of props) {
    target[key] = val;
  }
  return target;
};
const _sfc_main$a = {};
function _sfc_ssrRender$2(_ctx, _push, _parent, _attrs) {
  _push(`<svg${ssrRenderAttrs(mergeProps({
    viewBox: "0 0 24 24",
    fill: "none",
    stroke: "currentColor",
    "stroke-width": "1.5",
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    xmlns: "http://www.w3.org/2000/svg",
    "aria-hidden": "true",
    focusable: "false"
  }, _attrs))}><path d="M3.4 6.6C8 5.9 14.5 6.4 20.6 6.1"></path><path d="M3.1 12.1C9 11.6 15 12.5 20.9 11.9"></path><path d="M3.6 17.6C8.5 17.2 14 17.7 20.4 17.3"></path></svg>`);
}
const _sfc_setup$a = _sfc_main$a.setup;
_sfc_main$a.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Icons/IconMenu.vue");
  return _sfc_setup$a ? _sfc_setup$a(props, ctx) : void 0;
};
const IconMenu = /* @__PURE__ */ _export_sfc(_sfc_main$a, [["ssrRender", _sfc_ssrRender$2]]);
const logoUrl = "/build/assets/logo-D_l89T2S.svg";
const ASPECT = 59.568 / 104;
const _sfc_main$9 = /* @__PURE__ */ defineComponent({
  __name: "BrandLogo",
  __ssrInlineRender: true,
  props: {
    width: { default: 104 },
    title: {}
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<img${ssrRenderAttrs(mergeProps({
        src: unref(logoUrl),
        alt: __props.title,
        width: __props.width,
        height: Math.round(__props.width * ASPECT),
        style: { width: `${__props.width}px`, height: `${Math.round(__props.width * ASPECT)}px` },
        decoding: "async"
      }, _attrs))}>`);
    };
  }
});
const _sfc_setup$9 = _sfc_main$9.setup;
_sfc_main$9.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/BrandLogo.vue");
  return _sfc_setup$9 ? _sfc_setup$9(props, ctx) : void 0;
};
const arrowUrl = "/build/assets/cta-arrow-1hllL7dL.svg";
const _sfc_main$8 = /* @__PURE__ */ defineComponent({
  __name: "CtaButton",
  __ssrInlineRender: true,
  props: {
    label: {},
    href: { default: void 0 },
    variant: { default: "solid" },
    size: { default: "md" },
    withArrow: { type: Boolean, default: false },
    type: { default: "button" },
    disabled: { type: Boolean, default: false },
    external: { type: Boolean, default: false }
  },
  emits: ["click"],
  setup(__props) {
    const props = __props;
    const classes = computed(() => [
      "inline-flex items-center justify-center gap-1 rounded-sm",
      "transition-colors duration-200 ease-brand",
      props.size === "lg" ? "gap-2 px-8 py-4 text-title-md" : "px-6 py-3 text-title-sm"
    ]);
    return (_ctx, _push, _parent, _attrs) => {
      ssrRenderVNode(_push, createVNode(resolveDynamicComponent(__props.href ? __props.external ? "a" : unref(Link) : "button"), mergeProps({
        href: __props.href,
        type: __props.href ? void 0 : __props.type,
        disabled: __props.href ? void 0 : __props.disabled,
        target: __props.external ? "_blank" : void 0,
        rel: __props.external ? "noopener noreferrer" : void 0,
        class: [
          classes.value,
          __props.variant === "solid" && "bg-brand text-ink-1000 hover:bg-brand-900",
          __props.variant === "light" && "bg-white text-ink-1000 hover:bg-ink-50",
          __props.variant === "outline" && "border border-ink-600 text-paper hover:border-brand hover:text-brand",
          __props.variant === "brand-outline" && "border border-brand text-brand hover:bg-brand hover:text-ink-1000",
          __props.disabled && "pointer-events-none opacity-50"
        ],
        onClick: ($event) => _ctx.$emit("click", $event)
      }, _attrs), {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<span${_scopeId}>${ssrInterpolate(props.label)}</span>`);
            if (__props.withArrow) {
              _push2(`<img${ssrRenderAttr("src", unref(arrowUrl))} alt="" aria-hidden="true" width="24" height="24" class="size-6 flip-rtl"${_scopeId}>`);
            } else {
              _push2(`<!---->`);
            }
          } else {
            return [
              createVNode("span", null, toDisplayString(props.label), 1),
              __props.withArrow ? (openBlock(), createBlock("img", {
                key: 0,
                src: unref(arrowUrl),
                alt: "",
                "aria-hidden": "true",
                width: "24",
                height: "24",
                class: "size-6 flip-rtl"
              }, null, 8, ["src"])) : createCommentVNode("", true)
            ];
          }
        }),
        _: 1
      }), _parent);
    };
  }
});
const _sfc_setup$8 = _sfc_main$8.setup;
_sfc_main$8.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/CtaButton.vue");
  return _sfc_setup$8 ? _sfc_setup$8(props, ctx) : void 0;
};
const _sfc_main$7 = /* @__PURE__ */ defineComponent({
  __name: "LanguageSwitcher",
  __ssrInlineRender: true,
  setup(__props) {
    const page = usePage();
    const current = computed(() => page.props.locale.current);
    const options = computed(() => page.props.locale.supported);
    const alternates = computed(() => page.props.alternates);
    function urlFor(code) {
      return alternates.value[code] ?? `/${code}`;
    }
    return (_ctx, _push, _parent, _attrs) => {
      if (options.value.length > 1) {
        _push(`<nav${ssrRenderAttrs(mergeProps({
          "aria-label": _ctx.$t("common.change_language"),
          class: "flex items-center gap-1"
        }, _attrs))}><!--[-->`);
        ssrRenderList(options.value, (option, index) => {
          _push(`<!--[-->`);
          if (index > 0) {
            _push(`<span class="text-ink-700" aria-hidden="true">/</span>`);
          } else {
            _push(`<!---->`);
          }
          _push(ssrRenderComponent(unref(Link), {
            href: urlFor(option.code),
            lang: option.code,
            "aria-current": option.code === current.value ? "true" : void 0,
            class: ["rounded-xs px-1 py-1 text-label-lg transition-colors duration-200 ease-brand", option.code === current.value ? "text-brand" : "text-ink-400 hover:text-paper"]
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(`${ssrInterpolate(option.native)}`);
              } else {
                return [
                  createTextVNode(toDisplayString(option.native), 1)
                ];
              }
            }),
            _: 2
          }, _parent));
          _push(`<!--]-->`);
        });
        _push(`<!--]--></nav>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
});
const _sfc_setup$7 = _sfc_main$7.setup;
_sfc_main$7.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/LanguageSwitcher.vue");
  return _sfc_setup$7 ? _sfc_setup$7(props, ctx) : void 0;
};
const underlineUrl = "/build/assets/nav-underline-C7qqXZZC.svg";
const doodleUrl = "/build/assets/header-doodle-BJJRWrku.svg";
const _sfc_main$6 = /* @__PURE__ */ defineComponent({
  __name: "AppHeader",
  __ssrInlineRender: true,
  emits: ["openMenu"],
  setup(__props) {
    const page = usePage();
    const items = computed(() => page.props.navigation.header);
    const settings = computed(() => page.props.settings);
    const cta = computed(() => items.value.find((item) => item.isCta) ?? null);
    const links = computed(() => items.value.filter((item) => !item.isCta));
    const currentUrl = computed(() => page.url);
    function isActive(item) {
      const path = currentUrl.value.split("?")[0].replace(/\/$/, "");
      const target = new URL(item.url, "http://x").pathname.replace(/\/$/, "");
      return path === target;
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<header${ssrRenderAttrs(mergeProps({ class: "fixed inset-x-0 top-0 z-header bg-white/[0.15] backdrop-blur-header" }, _attrs))}><div class="container-sizdah flex items-center justify-between gap-4 py-[40px] pb-[24px] lg:gap-6 lg:py-6">`);
      _push(ssrRenderComponent(unref(Link), {
        href: `/${unref(page).props.locale.current}`,
        class: "shrink-0 lg:ms-9"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(_sfc_main$9, {
              width: 104,
              title: settings.value.siteName
            }, null, _parent2, _scopeId));
          } else {
            return [
              createVNode(_sfc_main$9, {
                width: 104,
                title: settings.value.siteName
              }, null, 8, ["title"])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<nav${ssrRenderAttr("aria-label", _ctx.$t("common.primary_navigation"))} class="hidden lg:block"><ul class="flex items-baseline gap-6"><!--[-->`);
      ssrRenderList(links.value, (item) => {
        _push(`<li>`);
        _push(ssrRenderComponent(unref(Link), {
          href: item.url,
          target: item.target,
          "aria-current": isActive(item) ? "page" : void 0,
          class: [
            "flex flex-col items-center justify-center px-1 py-1 font-normal transition-colors duration-200 ease-brand",
            isActive(item) ? "text-title-sm text-paper" : "text-title-md text-ink-200 hover:text-paper"
          ]
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`${ssrInterpolate(item.label)} `);
              if (isActive(item)) {
                _push2(`<span class="relative block h-[2px] w-full"${_scopeId}><img${ssrRenderAttr("src", unref(underlineUrl))} alt="" aria-hidden="true" class="absolute inset-x-0 -top-[1.7px] h-[4.71px] w-full max-w-none"${_scopeId}></span>`);
              } else {
                _push2(`<span class="block h-[9.676px] w-[45.276px]" aria-hidden="true"${_scopeId}></span>`);
              }
            } else {
              return [
                createTextVNode(toDisplayString(item.label) + " ", 1),
                isActive(item) ? (openBlock(), createBlock("span", {
                  key: 0,
                  class: "relative block h-[2px] w-full"
                }, [
                  createVNode("img", {
                    src: unref(underlineUrl),
                    alt: "",
                    "aria-hidden": "true",
                    class: "absolute inset-x-0 -top-[1.7px] h-[4.71px] w-full max-w-none"
                  }, null, 8, ["src"])
                ])) : (openBlock(), createBlock("span", {
                  key: 1,
                  class: "block h-[9.676px] w-[45.276px]",
                  "aria-hidden": "true"
                }))
              ];
            }
          }),
          _: 2
        }, _parent));
        _push(`</li>`);
      });
      _push(`<!--]--></ul></nav><div class="flex items-center gap-4">`);
      _push(ssrRenderComponent(_sfc_main$7, { class: "hidden lg:flex" }, null, _parent));
      if (cta.value) {
        _push(`<div class="relative isolate hidden lg:block">`);
        _push(ssrRenderComponent(_sfc_main$8, {
          label: cta.value.label,
          href: cta.value.url,
          class: "whitespace-nowrap"
        }, null, _parent));
        _push(`<img${ssrRenderAttr("src", unref(doodleUrl))} alt="" aria-hidden="true" width="16" height="20" class="pointer-events-none absolute -top-[18px] end-[-14px] z-20 h-5 w-4 flip-rtl"></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<button type="button" class="inline-flex size-10 shrink-0 items-center justify-center rounded-sm text-paper lg:hidden"${ssrRenderAttr("aria-label", _ctx.$t("common.open_menu"))}>`);
      _push(ssrRenderComponent(IconMenu, { class: "size-6" }, null, _parent));
      _push(`</button></div></div></header>`);
    };
  }
});
const _sfc_setup$6 = _sfc_main$6.setup;
_sfc_main$6.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layouts/AppHeader.vue");
  return _sfc_setup$6 ? _sfc_setup$6(props, ctx) : void 0;
};
const footerBloomUrl = "/build/assets/footer-bloom-Ce5aGyig.svg";
const locationIconUrl = "/build/assets/footer-location-DkCZa55k.svg";
const phoneIconUrl = "/build/assets/footer-phone-Dh0WcHp0.svg";
const emailIconUrl = "/build/assets/footer-email-Dh1N00tb.svg";
const _sfc_main$5 = /* @__PURE__ */ defineComponent({
  __name: "AppFooter",
  __ssrInlineRender: true,
  setup(__props) {
    const page = usePage();
    const settings = computed(() => page.props.settings);
    const columns = computed(() => page.props.navigation.footer);
    const year = computed(
      () => new Intl.DateTimeFormat(page.props.locale.htmlLang, { year: "numeric" }).format(/* @__PURE__ */ new Date())
    );
    const contact = computed(() => settings.value.contact);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<footer${ssrRenderAttrs(mergeProps({
        class: ["rounded-t-lg border-t border-warm-100 bg-ink-1000 shadow-footer", ["Work/Index", "About"].includes(unref(page).component) ? "max-md:h-[640px] max-md:overflow-hidden" : ""]
      }, _attrs))}><div class="container-sizdah relative pb-12 pt-12 max-md:pb-[30px] xl:pb-[128.76px]"><img${ssrRenderAttr("src", unref(footerBloomUrl))} alt="" aria-hidden="true" class="pointer-events-none absolute inset-x-[96px] top-[52px] hidden h-[449.76px] w-[1248px] xl:block"><div class="relative flex flex-col gap-10"><div class="flex flex-col justify-between gap-10 md:flex-row md:items-start"><div class="flex w-full flex-col gap-8 md:w-[402px]">`);
      _push(ssrRenderComponent(_sfc_main$9, {
        width: 87,
        title: settings.value.siteName
      }, null, _parent));
      _push(`<p class="text-body-lg text-ink-100">${ssrInterpolate(settings.value.tagline)}</p></div><div class="grid grid-cols-2 gap-10 md:flex md:gap-[88px]"><!--[-->`);
      ssrRenderList(columns.value, (column) => {
        _push(`<nav${ssrRenderAttr("aria-label", column.label)} class="flex flex-col gap-4"><h2 class="text-title-sm text-brand">${ssrInterpolate(column.label)}</h2><ul class="flex flex-col gap-3"><!--[-->`);
        ssrRenderList(column.children, (child) => {
          _push(`<li>`);
          _push(ssrRenderComponent(unref(Link), {
            href: child.url,
            target: child.target,
            class: "text-body-lg text-ink-100 transition-colors duration-200 ease-brand hover:text-brand"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(`${ssrInterpolate(child.label)}`);
              } else {
                return [
                  createTextVNode(toDisplayString(child.label), 1)
                ];
              }
            }),
            _: 2
          }, _parent));
          _push(`</li>`);
        });
        _push(`<!--]--></ul></nav>`);
      });
      _push(`<!--]--><div class="flex flex-col gap-4"><h2 class="text-title-sm text-brand">${ssrInterpolate(_ctx.$t("footer.info"))}</h2><ul class="flex flex-col gap-3 text-body-lg text-ink-100">`);
      if (contact.value.location) {
        _push(`<li class="flex items-center gap-2"><img${ssrRenderAttr("src", unref(locationIconUrl))} alt="" aria-hidden="true" class="h-4 w-auto shrink-0" width="16" height="16"><span>${ssrInterpolate(contact.value.location)}</span></li>`);
      } else {
        _push(`<!---->`);
      }
      if (contact.value.phone) {
        _push(`<li class="flex items-center gap-2"><img${ssrRenderAttr("src", unref(phoneIconUrl))} alt="" aria-hidden="true" class="h-4 w-auto shrink-0" width="16" height="16"><a${ssrRenderAttr("href", `tel:${contact.value.phone.replace(/\s/g, "")}`)} class="latin-nums transition-colors duration-200 ease-brand hover:text-brand" dir="ltr">${ssrInterpolate(contact.value.phone)}</a></li>`);
      } else {
        _push(`<!---->`);
      }
      if (contact.value.email) {
        _push(`<li class="flex items-center gap-2"><img${ssrRenderAttr("src", unref(emailIconUrl))} alt="" aria-hidden="true" class="h-4 w-auto shrink-0" width="16" height="16"><a${ssrRenderAttr("href", `mailto:${contact.value.email}`)} class="transition-colors duration-200 ease-brand hover:text-brand" dir="ltr">${ssrInterpolate(contact.value.email)}</a></li>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</ul></div></div></div><div class="flex flex-col items-start justify-between gap-4 border-t border-warm-300 py-6 sm:flex-row sm:items-center"><p class="text-label-lg text-ink-200">${ssrInterpolate(_ctx.$t("footer.copyright", { year: year.value, name: settings.value.siteName }))}</p><div class="flex items-center gap-4">`);
      _push(ssrRenderComponent(unref(Link), {
        href: unref(route)("legal.terms"),
        class: "text-label-lg text-ink-100 underline transition-colors duration-200 ease-brand hover:text-brand"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`${ssrInterpolate(_ctx.$t("footer.terms"))}`);
          } else {
            return [
              createTextVNode(toDisplayString(_ctx.$t("footer.terms")), 1)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(unref(Link), {
        href: unref(route)("legal.privacy"),
        class: "text-label-lg text-ink-100 underline transition-colors duration-200 ease-brand hover:text-brand"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`${ssrInterpolate(_ctx.$t("footer.privacy_policy"))}`);
          } else {
            return [
              createTextVNode(toDisplayString(_ctx.$t("footer.privacy_policy")), 1)
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div></div></div></div></footer>`);
    };
  }
});
const _sfc_setup$5 = _sfc_main$5.setup;
_sfc_main$5.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layouts/AppFooter.vue");
  return _sfc_setup$5 ? _sfc_setup$5(props, ctx) : void 0;
};
const _sfc_main$4 = {};
function _sfc_ssrRender$1(_ctx, _push, _parent, _attrs) {
  _push(`<svg${ssrRenderAttrs(mergeProps({
    viewBox: "10.29 5.77 56.23 56.23",
    fill: "currentColor",
    xmlns: "http://www.w3.org/2000/svg",
    "aria-hidden": "true",
    focusable: "false"
  }, _attrs))}><path d="M62.3059 6.6895C61.6575 5.88872 60.4817 5.76763 59.6809 6.41997V6.41216C56.4426 9.07818 46.2903 20.8731 36.1926 32.836C34.6985 30.2754 33.3313 27.7774 32.1262 25.4278C26.8801 15.1954 24.4075 7.64654 24.3899 7.63286C24.0598 6.64849 23.009 6.11919 22.0325 6.44146C21.05 6.76372 20.5207 7.82427 20.843 8.79888C20.8801 8.88286 24.968 21.3106 33.6399 35.8672C25.7453 45.2501 18.2453 54.2696 14.5344 58.5625C13.8586 59.3438 13.9426 60.5235 14.7258 61.1993C15.0813 61.504 15.5149 61.6524 15.9504 61.6524C16.47 61.6524 16.9895 61.4336 17.3606 61.004C20.8996 56.8868 28.0813 48.2735 35.7004 39.2227C40.4543 46.7383 46.384 54.6016 53.5989 61.4844C53.9621 61.8282 54.427 62 54.8879 62C55.384 62 55.8723 61.8047 56.2434 61.4219C56.9504 60.6797 56.9231 59.4961 56.1809 58.7891C48.9231 51.875 42.97 43.8438 38.2356 36.211C48.6301 23.8946 59.1887 11.5821 62.0364 9.30669C62.8293 8.66411 62.9543 7.48833 62.3059 6.6895Z"></path></svg>`);
}
const _sfc_setup$4 = _sfc_main$4.setup;
_sfc_main$4.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Icons/IconClose.vue");
  return _sfc_setup$4 ? _sfc_setup$4(props, ctx) : void 0;
};
const IconClose = /* @__PURE__ */ _export_sfc(_sfc_main$4, [["ssrRender", _sfc_ssrRender$1]]);
const _sfc_main$3 = /* @__PURE__ */ defineComponent({
  __name: "MobileMenu",
  __ssrInlineRender: true,
  props: {
    open: { type: Boolean }
  },
  emits: ["close"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const page = usePage();
    const items = computed(() => page.props.navigation.header);
    const cta = computed(() => items.value.find((item) => item.isCta) ?? null);
    const links = computed(() => items.value.filter((item) => !item.isCta));
    const panel = ref(null);
    watch(
      () => props.open,
      async (open) => {
        var _a;
        document.body.style.overflow = open ? "hidden" : "";
        if (open) {
          await nextTick();
          (_a = panel.value) == null ? void 0 : _a.focus();
        }
      }
    );
    return (_ctx, _push, _parent, _attrs) => {
      if (__props.open) {
        _push(`<div${ssrRenderAttrs(mergeProps({ class: "fixed inset-0 z-menu lg:hidden" }, _attrs))}><div class="absolute inset-0 bg-ink-1000/80 backdrop-blur-sm"></div><div tabindex="-1" role="dialog" aria-modal="true"${ssrRenderAttr("aria-label", _ctx.$t("common.primary_navigation"))} class="absolute inset-y-0 end-0 flex w-full max-w-sm flex-col gap-8 overflow-y-auto border-s border-ink-800 bg-ink-1000 p-6 outline-none"><div class="flex items-center justify-between">`);
        _push(ssrRenderComponent(_sfc_main$9, {
          width: 88,
          title: unref(page).props.settings.siteName
        }, null, _parent));
        _push(`<button type="button" class="inline-flex size-11 items-center justify-center rounded-sm text-paper"${ssrRenderAttr("aria-label", _ctx.$t("common.close_menu"))}>`);
        _push(ssrRenderComponent(IconClose, { class: "size-6" }, null, _parent));
        _push(`</button></div><nav${ssrRenderAttr("aria-label", _ctx.$t("common.primary_navigation"))}><ul class="flex flex-col gap-2"><!--[-->`);
        ssrRenderList(links.value, (item) => {
          _push(`<li>`);
          _push(ssrRenderComponent(unref(Link), {
            href: item.url,
            target: item.target,
            class: "block rounded-sm py-3 text-title-lg text-ink-200 transition-colors duration-200 ease-brand hover:text-brand",
            onClick: ($event) => emit("close")
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(`${ssrInterpolate(item.label)}`);
              } else {
                return [
                  createTextVNode(toDisplayString(item.label), 1)
                ];
              }
            }),
            _: 2
          }, _parent));
          _push(`</li>`);
        });
        _push(`<!--]--></ul></nav><div class="mt-auto flex flex-col gap-6">`);
        if (cta.value) {
          _push(ssrRenderComponent(_sfc_main$8, {
            label: cta.value.label,
            href: cta.value.url,
            class: "w-full"
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(ssrRenderComponent(_sfc_main$7, null, null, _parent));
        _push(`</div></div></div>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
});
const _sfc_setup$3 = _sfc_main$3.setup;
_sfc_main$3.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layouts/MobileMenu.vue");
  return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
const _sfc_main$2 = {};
function _sfc_ssrRender(_ctx, _push, _parent, _attrs) {
  _push(`<svg${ssrRenderAttrs(mergeProps({
    viewBox: "0 0 24 24",
    fill: "none",
    stroke: "currentColor",
    "stroke-width": "1.5",
    "stroke-linecap": "round",
    "stroke-linejoin": "round",
    xmlns: "http://www.w3.org/2000/svg",
    "aria-hidden": "true",
    focusable: "false"
  }, _attrs))}><path d="M4.2 12.8C6 14.4 7.9 16.3 9.6 18.6C12.4 13.6 15.9 8.6 19.9 4.9"></path></svg>`);
}
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/Icons/IconCheck.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const IconCheck = /* @__PURE__ */ _export_sfc(_sfc_main$2, [["ssrRender", _sfc_ssrRender]]);
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "FlashMessages",
  __ssrInlineRender: true,
  setup(__props) {
    const page = usePage();
    const dismissed = ref(false);
    const flash = computed(() => page.props.flash);
    const message = computed(() => flash.value.success ?? flash.value.error ?? null);
    const isError = computed(() => flash.value.error !== null && flash.value.error !== void 0);
    watch(message, () => {
      dismissed.value = false;
    });
    return (_ctx, _push, _parent, _attrs) => {
      if (message.value && !dismissed.value) {
        _push(`<div${ssrRenderAttrs(mergeProps({
          role: "status",
          "aria-live": "polite",
          class: "fixed inset-x-0 top-[108px] z-overlay mx-auto w-fit max-w-[90vw] px-4"
        }, _attrs))}><div class="${ssrRenderClass([
          isError.value ? "border-red-500/40 bg-ink-900 text-red-300" : "border-brand/40 bg-ink-900 text-brand",
          "group flex items-center gap-3 rounded-sm border px-6 py-3 text-label-lg shadow-card"
        ])}"><span class="flex shrink-0 items-center justify-center rounded-round p-1 transition-colors duration-200 ease-brand group-hover:bg-brand group-hover:text-ink-1000">`);
        ssrRenderVNode(_push, createVNode(resolveDynamicComponent(isError.value ? IconClose : IconCheck), { class: "size-5" }, null), _parent);
        _push(`</span><p>${ssrInterpolate(message.value)}</p><button type="button" class="ms-2 shrink-0 rounded-xs text-ink-400 transition-colors hover:text-paper"${ssrRenderAttr("aria-label", _ctx.$t("common.close_menu"))}>`);
        _push(ssrRenderComponent(IconClose, { class: "size-4" }, null, _parent));
        _push(`</button></div></div>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/FlashMessages.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const siteBackgroundUrl = "/build/assets/site-background-BWHHdGLS.webp";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "AppLayout",
  __ssrInlineRender: true,
  setup(__props) {
    const page = usePage();
    const gridPages = ["Work/Index", "Services", "Insights/Index", "Contact", "About"];
    const washed = computed(() => page.component !== "Home" && !gridPages.includes(page.component));
    const gridBackground = computed(() => gridPages.includes(page.component));
    const nonHomeBackground = computed(() => page.component !== "Home");
    const menuOpen = ref(false);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: ["site-shell flex min-h-screen-safe flex-col bg-ink-1000", { "page-wash": washed.value, "grid-background": gridBackground.value, "non-home-background": nonHomeBackground.value, "error-shell": unref(page).component === "Error", "plain-mobile-background": ["Work/Index", "Insights/Show"].includes(unref(page).component) }],
        style: { "--site-background": `url(${unref(siteBackgroundUrl)})` }
      }, _attrs))} data-v-a608f036>`);
      _push(ssrRenderComponent(_sfc_main$6, {
        onOpenMenu: ($event) => menuOpen.value = true
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$3, {
        open: menuOpen.value,
        onClose: ($event) => menuOpen.value = false
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$1, null, null, _parent));
      _push(`<main id="main" class="flex-1" data-v-a608f036>`);
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      _push(`</main>`);
      if (!["Insights/Show", "Error"].includes(unref(page).component)) {
        _push(ssrRenderComponent(_sfc_main$5, null, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Layouts/AppLayout.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
const AppLayout = /* @__PURE__ */ _export_sfc(_sfc_main, [["__scopeId", "data-v-a608f036"]]);
const TranslatorKey = Symbol("translator");
function resolve(tree, key) {
  const value = key.split(".").reduce(
    (node, segment) => node !== void 0 && typeof node !== "string" ? node[segment] : void 0,
    tree
  );
  return typeof value === "string" ? value : void 0;
}
function interpolate(line, replacements) {
  return Object.entries(replacements).reduce(
    (carry, [token, value]) => carry.replaceAll(`:${token}`, String(value)),
    line
  );
}
function createTranslator(tree) {
  return (key, replacements) => {
    const line = resolve(tree, key);
    if (line === void 0) {
      return key;
    }
    return replacements ? interpolate(line, replacements) : line;
  };
}
function installTranslations(app) {
  const translator = (key, replacements) => {
    const tree = usePage().props.translations ?? {};
    return createTranslator(tree)(key, replacements);
  };
  app.config.globalProperties.$t = translator;
  app.provide(TranslatorKey, translator);
}
function useTranslations() {
  const injected = inject(TranslatorKey, void 0);
  const t = injected ?? ((key, replacements) => {
    const tree = usePage().props.translations ?? {};
    return createTranslator(tree)(key, replacements);
  });
  const locale = computed(() => {
    const page = usePage().props.locale;
    return (page == null ? void 0 : page.current) ?? "fa";
  });
  return { t, locale };
}
createServer(
  (page) => createInertiaApp({
    page,
    render: renderToString,
    resolve: (name) => resolvePageComponent(
      `./Pages/${name}.vue`,
      /* @__PURE__ */ Object.assign({ "./Pages/About.vue": () => import("./assets/About-DEBa9lzF.js"), "./Pages/Contact.vue": () => import("./assets/Contact-Bw0ax9Vk.js"), "./Pages/Error.vue": () => import("./assets/Error-HX2ouXHl.js"), "./Pages/Home.vue": () => import("./assets/Home-CRf0wSXD.js"), "./Pages/Insights/Index.vue": () => import("./assets/Index-lFlfKxKL.js"), "./Pages/Insights/Show.vue": () => import("./assets/Show-0SQkord2.js"), "./Pages/Legal.vue": () => import("./assets/Legal-yVcrgJXz.js"), "./Pages/Services.vue": () => import("./assets/Services-CG1EeUHt.js"), "./Pages/Services/Show.vue": () => import("./assets/Show-C3GzTzU_.js"), "./Pages/Work/Index.vue": () => import("./assets/Index-dl5mepaE.js"), "./Pages/Work/Show.vue": () => import("./assets/Show-B8kJ5sFw.js") })
    ).then((module) => {
      var _a;
      (_a = module.default).layout ?? (_a.layout = AppLayout);
      return module;
    }),
    setup({ App, props, plugin }) {
      const app = createSSRApp({ render: () => h(App, props) }).use(plugin).use(ZiggyVue, page.props.ziggy);
      installTranslations(app);
      return app;
    }
  })
);
export {
  IconClose as I,
  _export_sfc as _,
  _sfc_main$8 as a,
  underlineUrl as b,
  arrowUrl as c,
  useTranslations as u
};
