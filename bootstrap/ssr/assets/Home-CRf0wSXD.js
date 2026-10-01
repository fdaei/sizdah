import { defineComponent, computed, mergeProps, unref, useSSRContext, ref, withCtx, createTextVNode, toDisplayString, createVNode, openBlock, createBlock, onMounted, nextTick, onBeforeUnmount } from "vue";
import { ssrRenderAttrs, ssrRenderAttr, ssrInterpolate, ssrRenderComponent, ssrRenderList, ssrRenderStyle, ssrRenderClass, ssrIncludeBooleanAttr } from "vue/server-renderer";
import { _ as _sfc_main$b } from "./SeoHead-B0PBocbK.js";
import { b as underlineUrl, c as arrowUrl, a as _sfc_main$c } from "../ssr.js";
import { r as retentionUrl } from "./kpi-retention-JYaRFNgy.js";
import { _ as _sfc_main$a } from "./SectionHeading-BytK5z_z.js";
import { _ as _sfc_main$d } from "./LeadMagnetBanner-BasglgVi.js";
import { Link } from "@inertiajs/vue3";
import { c as calendarUrl } from "./calendar-BHdPRM2s.js";
import { _ as _sfc_main$e } from "./StartTogetherCard-DwqCyDIf.js";
import "@inertiajs/vue3/server";
import "ziggy-js";
import "./Eyebrow-BY5jAbbp.js";
import "./field-user-tphddZ0v.js";
const engagementUrl = "/build/assets/kpi-engagement-Chsh7WfS.svg";
const audienceUrl = "/build/assets/kpi-audience-CRVHEg6o.svg";
const cardBgUrl = "/build/assets/kpi-card-bg-BTW7lpHp.svg";
const _sfc_main$9 = /* @__PURE__ */ defineComponent({
  __name: "StatCard",
  __ssrInlineRender: true,
  props: {
    value: {},
    label: {},
    caption: {},
    icon: {}
  },
  setup(__props) {
    const props = __props;
    const ICONS = {
      engagement: engagementUrl,
      audience: audienceUrl,
      retention: retentionUrl
    };
    const iconUrl = computed(() => props.icon ? ICONS[props.icon] : void 0);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({
        class: "flex min-h-[153px] min-w-0 w-full flex-col items-center justify-center gap-2 bg-[length:100%_100%] bg-center bg-no-repeat px-3 py-6 text-center max-md:min-h-[83px] max-md:gap-1 max-md:px-1 max-md:py-2",
        style: { backgroundImage: `url(${unref(cardBgUrl)})` }
      }, _attrs))}><div class="flex items-center justify-center gap-2">`);
      if (iconUrl.value) {
        _push(`<img${ssrRenderAttr("src", iconUrl.value)} alt="" aria-hidden="true" width="32" height="32" class="size-8 shrink-0 max-md:size-4">`);
      } else {
        _push(`<!---->`);
      }
      _push(`<p class="latin-nums text-display-sm text-brand-50 max-md:text-[16px] max-md:leading-[20px]" dir="auto"${ssrRenderAttr("data-counter", __props.value)}>${ssrInterpolate(__props.value)}</p></div><div class="flex flex-col gap-1"><p class="text-title-md text-brand max-md:text-[10px] max-md:leading-[14px]">${ssrInterpolate(__props.label)}</p>`);
      if (__props.caption) {
        _push(`<p class="text-title-sm text-ink-300 max-md:hidden">${ssrInterpolate(__props.caption)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div>`);
    };
  }
});
const _sfc_setup$9 = _sfc_main$9.setup;
_sfc_main$9.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/StatCard.vue");
  return _sfc_setup$9 ? _sfc_setup$9(props, ctx) : void 0;
};
const parsUrl = "/build/assets/pars-OTTw562-.svg";
const allDigitallUrl = "/build/assets/alldigitall-Da1P3AES.svg";
const baghcheUrl = "/build/assets/baghche-SD5Lr7p9.svg";
const nooraUrl = "/build/assets/noora-BuLAaGTV.svg";
const vanakUrl = "/build/assets/vanak-CxTqjXOF.svg";
const tavakoliUrl = "/build/assets/tavakoli-3BTMSfex.svg";
const _sfc_main$8 = /* @__PURE__ */ defineComponent({
  __name: "ClientLogo",
  __ssrInlineRender: true,
  props: {
    client: {}
  },
  setup(__props) {
    const props = __props;
    const EXPORTS = {
      "کلینیک پارس": parsUrl,
      "allDigitall.ir": allDigitallUrl,
      باغچه: baghcheUrl,
      نورا: nooraUrl,
      ونک: vanakUrl,
      توکلی: tavakoliUrl
    };
    const source = computed(() => props.client.logo || EXPORTS[props.client.name] || null);
    return (_ctx, _push, _parent, _attrs) => {
      if (source.value) {
        _push(`<span${ssrRenderAttrs(mergeProps({ class: "flex size-32 shrink-0 flex-col items-center justify-center gap-2.5 max-md:size-20 max-md:gap-0" }, _attrs))}><img${ssrRenderAttr("src", source.value)}${ssrRenderAttr("alt", props.client.name)} width="128" loading="lazy" decoding="async" class="h-auto w-32 object-contain grayscale transition-[filter] duration-300 ease-brand hover:grayscale-0 max-md:w-20"></span>`);
      } else {
        _push(`<span${ssrRenderAttrs(mergeProps({ class: "text-title-sm text-ink-400" }, _attrs))}>${ssrInterpolate(props.client.name)}</span>`);
      }
    };
  }
});
const _sfc_setup$8 = _sfc_main$8.setup;
_sfc_main$8.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/ClientLogo.vue");
  return _sfc_setup$8 ? _sfc_setup$8(props, ctx) : void 0;
};
const centerUrl = "/build/assets/orbit-center-BMeznJtu.svg";
const wordmarkUrl = "/build/assets/orbit-wordmark-DND7sFYw.svg";
const contentUrl = "/build/assets/orbit-content-HSnlIsPM.svg";
const brandingUrl = "/build/assets/orbit-branding-Bnqw1qXZ.svg";
const marketingUrl = "/build/assets/orbit-marketing-DjJIoFkB.svg";
const socialUrl = "/build/assets/orbit-social-B3uWApki.svg";
const lineTopStartUrl = "/build/assets/orbit-line-tl-Dqouw7ca.svg";
const lineTopEndUrl = "/build/assets/orbit-line-tr-lSgmGywV.svg";
const lineBottomStartUrl = "/build/assets/orbit-line-bl-BceGsbJX.svg";
const lineBottomEndUrl = "/build/assets/orbit-line-br-Dqpctlo8.svg";
const gridDoodleUrl = "/build/assets/orbit-grid-B7rm1Vyz.svg";
const scribbleUrl$1 = "/build/assets/orbit-scribble-Dg79IHIH.svg";
const crossUrl = "/build/assets/orbit-cross-w1bzrQlq.svg";
const dotsUrl = "/build/assets/orbit-dots-B9UKw2KQ.svg";
const mobileOrbitUrl = "/build/assets/hero-mobile-ArPnf4HK.svg";
const _sfc_main$7 = /* @__PURE__ */ defineComponent({
  __name: "ServiceOrbit",
  __ssrInlineRender: true,
  props: {
    section: {},
    services: {}
  },
  setup(__props) {
    const props = __props;
    const SLOTS = [
      {
        icon: "video",
        art: contentUrl,
        size: [240, 246],
        left: "13.89%",
        top: "1.97%",
        width: "16.67%",
        line: { src: lineTopStartUrl, left: "29.76%", top: "25.86%" }
      },
      {
        icon: "palette",
        art: brandingUrl,
        size: [240, 260],
        left: "69.44%",
        top: "2.24%",
        width: "16.67%",
        line: { src: lineTopEndUrl, left: "58.98%", top: "25.98%" }
      },
      {
        icon: "share-2",
        art: socialUrl,
        size: [282, 246],
        left: "8.89%",
        top: "48.03%",
        width: "21.67%",
        line: { src: lineBottomStartUrl, left: "29.76%", top: "51.45%" }
      },
      {
        icon: "megaphone",
        art: marketingUrl,
        size: [240, 246],
        left: "69.44%",
        top: "48.42%",
        width: "16.67%",
        line: { src: lineBottomEndUrl, left: "57.71%", top: "51.45%" }
      }
    ];
    const nodes = computed(() => {
      const pool = [...props.services];
      return SLOTS.map((slot) => {
        const exact = pool.findIndex((service2) => service2.icon === slot.icon);
        const index = exact === -1 ? 0 : exact;
        const service = pool.length > 0 ? pool.splice(index, 1)[0] : null;
        return { slot, service };
      }).filter((node) => node.service !== null);
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<section${ssrRenderAttrs(mergeProps({ class: "bg-surface-raised py-16 lg:pb-20 lg:pt-[91px] max-lg:p-0" }, _attrs))}><div class="container-sizdah hidden lg:block">`);
      _push(ssrRenderComponent(_sfc_main$a, {
        "data-reveal": "",
        eyebrow: props.section.eyebrow,
        title: props.section.title,
        subtitle: props.section.subtitle || props.section.description,
        tone: "light",
        "subtitle-width": "md:max-w-[505px]"
      }, null, _parent));
      _push(`</div><div class="mt-12 hidden lg:mt-20 lg:block"><div class="relative mx-auto aspect-[1440/760] w-full max-w-frame" data-reveal><img${ssrRenderAttr("src", unref(gridDoodleUrl))} alt="" aria-hidden="true" width="160" height="143" class="absolute left-[41.25%] top-[0.39%] w-[11.11%]"><img${ssrRenderAttr("src", unref(crossUrl))} alt="" aria-hidden="true" width="24" height="24" class="absolute left-[9.58%] top-[40%] w-[1.67%]"><img${ssrRenderAttr("src", unref(crossUrl))} alt="" aria-hidden="true" width="24" height="24" class="absolute left-[75.69%] top-[96.84%] w-[1.67%]"><img${ssrRenderAttr("src", unref(dotsUrl))} alt="" aria-hidden="true" width="47" height="26" class="absolute left-[89.03%] top-[45%] w-[3.26%]"><img${ssrRenderAttr("src", unref(scribbleUrl$1))} alt="" aria-hidden="true" width="104" height="104" class="absolute left-[52.22%] top-[85.13%] w-[7.22%]"><!--[-->`);
      ssrRenderList(nodes.value, (node) => {
        _push(`<img${ssrRenderAttr("src", node.slot.line.src)} alt="" aria-hidden="true" width="160" height="97" class="absolute w-[11.48%]" style="${ssrRenderStyle({ left: node.slot.line.left, top: node.slot.line.top })}">`);
      });
      _push(`<!--]--><div class="absolute left-[39.44%] top-[26.84%] w-[21.11%]"><img${ssrRenderAttr("src", unref(centerUrl))} alt="" aria-hidden="true" width="304" height="261" class="w-full"><img${ssrRenderAttr("src", unref(wordmarkUrl))} alt="Lost Level" width="100" height="73" class="absolute left-[32.57%] top-[39.46%] w-[32.89%]"></div><!--[-->`);
      ssrRenderList(nodes.value, (node) => {
        _push(`<div class="absolute flex flex-col items-center gap-2" style="${ssrRenderStyle({ left: node.slot.left, top: node.slot.top, width: node.slot.width })}"><img${ssrRenderAttr("src", node.slot.art)} alt="" aria-hidden="true"${ssrRenderAttr("width", node.slot.size[0])}${ssrRenderAttr("height", node.slot.size[1])} class="w-full"><p class="max-w-full break-words text-center text-[clamp(16px,1.667vw,24px)] font-medium leading-[1.25] text-ink-1000">${ssrInterpolate(node.service.title)}</p></div>`);
      });
      _push(`<!--]--></div></div><div class="relative h-[793px] overflow-hidden lg:hidden"><img${ssrRenderAttr("src", unref(mobileOrbitUrl))} alt="" aria-hidden="true" width="402" height="793" class="absolute inset-y-0 start-[22.5%] block h-[793px] w-[55%] max-w-none"><div class="absolute inset-x-5 top-8">`);
      _push(ssrRenderComponent(_sfc_main$a, {
        eyebrow: props.section.eyebrow,
        title: props.section.title,
        subtitle: props.section.subtitle || props.section.description,
        tone: "light",
        layout: "stacked"
      }, null, _parent));
      _push(`</div></div></section>`);
    };
  }
});
const _sfc_setup$7 = _sfc_main$7.setup;
_sfc_main$7.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/ServiceOrbit.vue");
  return _sfc_setup$7 ? _sfc_setup$7(props, ctx) : void 0;
};
const sidewalkProjectUrl = "/build/assets/sidewalk-project-C1Gb0eF2.jpeg";
const projectCategoryUrl = "/build/assets/project-category-c7edAQXf.svg";
const _sfc_main$6 = /* @__PURE__ */ defineComponent({
  __name: "ProjectShowcase",
  __ssrInlineRender: true,
  props: {
    section: {},
    projects: {}
  },
  setup(__props) {
    const props = __props;
    const active = ref(0);
    ref(null);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<section${ssrRenderAttrs(mergeProps({ class: "max-md:bg-transparent max-md:bg-none py-12 max-md:pb-[22px] md:py-24" }, _attrs))}><div class="container-sizdah">`);
      _push(ssrRenderComponent(_sfc_main$a, {
        "data-reveal": "",
        eyebrow: props.section.eyebrow,
        title: props.section.title,
        subtitle: props.section.subtitle || props.section.description,
        "split-class": "lg:grid-cols-[minmax(0,615px)_minmax(0,530px)] lg:justify-between lg:gap-x-16"
      }, null, _parent));
      _push(`<div class="mt-8 grid gap-8 max-md:mt-[74px] max-md:gap-6 md:mt-12 md:gap-10 lg:mt-16 lg:grid-cols-[minmax(0,615px)_minmax(0,530px)] lg:justify-between lg:gap-16"><ul class="hidden flex-col justify-between gap-8 lg:flex" data-reveal-group><!--[-->`);
      ssrRenderList(props.projects, (project, index) => {
        _push(`<li><div class="flex flex-col gap-4"><div class="flex items-center justify-between gap-4"><div class="flex flex-col items-start">`);
        _push(ssrRenderComponent(unref(Link), {
          href: project.url,
          class: [
            "transition-colors duration-300 ease-brand",
            active.value === index ? "text-section-line font-semibold text-paper" : "text-heading-xl text-ink-300 hover:text-paper"
          ]
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`${ssrInterpolate(project.title)}`);
            } else {
              return [
                createTextVNode(toDisplayString(project.title), 1)
              ];
            }
          }),
          _: 2
        }, _parent));
        _push(`<span aria-hidden="true" class="${ssrRenderClass([active.value === index ? "opacity-100" : "opacity-0", "relative mt-1 block h-[5px] w-full transition-opacity duration-300"])}"><img${ssrRenderAttr("src", unref(underlineUrl))} alt="" class="absolute inset-0 h-full w-full max-w-none"></span></div>`);
        _push(ssrRenderComponent(unref(Link), {
          style: active.value === index ? null : { display: "none" },
          href: project.url,
          class: "flex shrink-0 items-center gap-1 text-body-lg text-brand transition-colors hover:text-brand-600"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`<img${ssrRenderAttr("src", unref(arrowUrl))} alt="" aria-hidden="true" width="24" height="24" class="size-6 flip-rtl"${_scopeId}><span${_scopeId}>${ssrInterpolate(props.section.content || "جزییات پروژه")}</span>`);
            } else {
              return [
                createVNode("img", {
                  src: unref(arrowUrl),
                  alt: "",
                  "aria-hidden": "true",
                  width: "24",
                  height: "24",
                  class: "size-6 flip-rtl"
                }, null, 8, ["src"]),
                createVNode("span", null, toDisplayString(props.section.content || "جزییات پروژه"), 1)
              ];
            }
          }),
          _: 2
        }, _parent));
        _push(`</div><div class="${ssrRenderClass([active.value === index ? "grid-rows-[1fr]" : "grid-rows-[0fr]", "grid transition-[grid-template-rows] duration-500 ease-brand"])}"><div class="overflow-hidden"><div class="flex flex-col gap-4">`);
        if (project.industry) {
          _push(`<p class="text-label-lg text-brand">${ssrInterpolate(project.industry)}</p>`);
        } else {
          _push(`<!---->`);
        }
        if (project.excerpt) {
          _push(`<p class="text-body-lg text-ink-200">${ssrInterpolate(project.excerpt)}</p>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div></div></div></div>`);
        if (index < props.projects.length - 1) {
          _push(`<div aria-hidden="true" class="${ssrRenderClass([
            active.value === index ? "h-0.5 bg-gradient-to-l from-transparent via-brand to-transparent" : "h-px bg-ink-800",
            "relative mt-8 w-full"
          ])}"><span class="${ssrRenderClass([active.value === index ? "border-brand bg-brand" : "border-ink-600 bg-ink-1000", "absolute inline-start-1/2 top-1/2 size-3 -translate-y-1/2 rounded-round border"])}"></span></div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</li>`);
      });
      _push(`<!--]--></ul>`);
      if (props.projects[active.value]) {
        _push(`<div class="hidden h-[112px] flex-col gap-3 max-md:flex max-md:order-none"><div class="flex flex-row-reverse items-center justify-between text-right">`);
        _push(ssrRenderComponent(unref(Link), {
          href: props.projects[active.value].url,
          class: "flex items-center gap-1 text-[14px] font-medium leading-[20px] text-paper"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`<img${ssrRenderAttr("src", unref(arrowUrl))} alt="" aria-hidden="true" width="24" height="24" class="size-6 flip-rtl"${_scopeId}><span${_scopeId}>${ssrInterpolate(props.section.content || "جزییات پروژه")}</span>`);
            } else {
              return [
                createVNode("img", {
                  src: unref(arrowUrl),
                  alt: "",
                  "aria-hidden": "true",
                  width: "24",
                  height: "24",
                  class: "size-6 flip-rtl"
                }, null, 8, ["src"]),
                createVNode("span", null, toDisplayString(props.section.content || "جزییات پروژه"), 1)
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(ssrRenderComponent(unref(Link), {
          href: props.projects[active.value].url,
          class: "text-[24px] font-semibold leading-[30px] text-paper"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`${ssrInterpolate(props.projects[active.value].title)}`);
            } else {
              return [
                createTextVNode(toDisplayString(props.projects[active.value].title), 1)
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(`</div><div class="flex flex-col items-end gap-2 text-right"><div class="flex items-center gap-1">`);
        if (props.projects[active.value].industry) {
          _push(`<p class="text-[12px] font-medium leading-[20px] text-brand">${ssrInterpolate(props.projects[active.value].industry)}</p>`);
        } else {
          _push(`<!---->`);
        }
        _push(`<img${ssrRenderAttr("src", unref(projectCategoryUrl))} alt="" aria-hidden="true" width="20" height="20" class="size-5"></div>`);
        if (props.projects[active.value].excerpt) {
          _push(`<p class="text-[14px] leading-[20px] text-ink-200">${ssrInterpolate(props.projects[active.value].excerpt)}</p>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="order-first w-full max-md:order-none max-md:-translate-x-px lg:order-none"><div class="relative aspect-[530/663] w-full touch-pan-y overflow-hidden rounded-xl bg-ink-900"><!--[-->`);
      ssrRenderList(props.projects, (project, index) => {
        var _a, _b, _c;
        _push(`<img${ssrRenderAttr("src", index === 0 ? unref(sidewalkProjectUrl) : (_a = project.image) == null ? void 0 : _a.src)}${ssrRenderAttr("srcset", index === 0 ? void 0 : (_b = project.image) == null ? void 0 : _b.srcset)}${ssrRenderAttr("alt", ((_c = project.image) == null ? void 0 : _c.alt) ?? project.title)} width="530" height="663" loading="eager" decoding="async" style="${ssrRenderStyle({ transform: `translateX(${(index - active.value) * 100}%)` })}" class="${ssrRenderClass([active.value === index ? "opacity-100" : "opacity-0", "absolute inset-0 size-full object-cover transition-[transform,opacity] duration-500 ease-brand will-change-transform"])}">`);
      });
      _push(`<!--]--></div><div class="mt-5 flex -translate-x-1 items-center justify-center gap-2 lg:hidden" aria-label="انتخاب پروژه"><!--[-->`);
      ssrRenderList(props.projects, (project, index) => {
        _push(`<button type="button" class="${ssrRenderClass([active.value === index ? "border-brand bg-brand" : "border-ink-500 bg-transparent", "size-2 rounded-full border transition-all duration-300"])}"${ssrRenderAttr("aria-label", `نمایش ${project.title}`)}${ssrRenderAttr("aria-current", active.value === index ? "true" : void 0)}></button>`);
      });
      _push(`<!--]--></div></div></div></div></section>`);
    };
  }
});
const _sfc_setup$6 = _sfc_main$6.setup;
_sfc_main$6.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/ProjectShowcase.vue");
  return _sfc_setup$6 ? _sfc_setup$6(props, ctx) : void 0;
};
const discoveryUrl = "/build/assets/step-discovery-lCcU-st_.svg";
const strategyUrl = "/build/assets/step-strategy-Dmh48yLr.svg";
const productionUrl = "/build/assets/step-production-DHgnrJdz.svg";
const publishingUrl = "/build/assets/step-publishing-CYkV199y.svg";
const campaignUrl = "/build/assets/step-campaign-hJ75HB49.svg";
const optimizationUrl = "/build/assets/step-optimization-CiPCgOdM.svg";
const approvalUrl = "/build/assets/step-approval-BfKBfWuP.svg";
const _sfc_main$5 = /* @__PURE__ */ defineComponent({
  __name: "ProcessStepCard",
  __ssrInlineRender: true,
  props: {
    item: {},
    index: {}
  },
  setup(__props) {
    const props = __props;
    const ICONS = {
      discovery: discoveryUrl,
      strategy: strategyUrl,
      production: productionUrl,
      approval: approvalUrl,
      publishing: publishingUrl,
      campaign: campaignUrl,
      optimization: optimizationUrl
    };
    const iconUrl = computed(() => props.item.icon ? ICONS[props.item.icon] : void 0);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<li${ssrRenderAttrs(mergeProps({ class: "process-wash group flex flex-col gap-6 px-8 py-10 transition-colors duration-200 ease-brand hover:bg-brand-pressed hover:bg-none max-md:h-[111px] max-md:flex-row max-md:items-center max-md:gap-0 max-md:px-2 max-md:py-6" }, _attrs))}><div class="flex items-center justify-between gap-4 max-md:hidden">`);
      if (iconUrl.value) {
        _push(`<img${ssrRenderAttr("src", iconUrl.value)} alt="" aria-hidden="true" width="64" height="64" class="size-16 shrink-0 object-contain transition duration-200 ease-brand group-hover:brightness-0">`);
      } else {
        _push(`<span aria-hidden="true" class="size-16 shrink-0"></span>`);
      }
      _push(`<p class="latin-nums font-display text-heading-xl font-normal text-brand transition-colors duration-200 ease-brand group-hover:text-ink-1000" aria-hidden="true">${ssrInterpolate(String(props.index + 1).padStart(2, "0"))}</p></div><div class="flex flex-col gap-4 max-md:hidden"><h3 class="text-heading-md text-paper transition-colors duration-200 ease-brand group-hover:text-ink-1000">${ssrInterpolate(props.item.title)}</h3>`);
      if (props.item.description) {
        _push(`<p class="text-title-sm text-ink-200 transition-colors duration-200 ease-brand group-hover:text-ink-800">${ssrInterpolate(props.item.description)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div><div class="hidden max-md:flex max-md:w-full max-md:items-center max-md:justify-between max-md:gap-4"><div class="flex min-w-0 flex-1 flex-col gap-1 text-right"><h3 class="truncate text-[18px] font-medium leading-[25px] text-paper">${ssrInterpolate(props.item.title)}</h3>`);
      if (props.item.description) {
        _push(`<p class="line-clamp-2 text-[14px] leading-[17px] text-ink-200">${ssrInterpolate(props.item.description)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div><p class="latin-nums shrink-0 font-display text-[36px] font-normal leading-[36px] text-brand" aria-hidden="true">${ssrInterpolate(String(props.index + 1).padStart(2, "0"))}</p></div></li>`);
    };
  }
});
const _sfc_setup$5 = _sfc_main$5.setup;
_sfc_main$5.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/ProcessStepCard.vue");
  return _sfc_setup$5 ? _sfc_setup$5(props, ctx) : void 0;
};
const outcomesUrl = "/build/assets/why-outcomes-Copf3jkN.svg";
const brandUrl = "/build/assets/why-brand-SRy04NlW.svg";
const endToEndUrl = "/build/assets/why-endtoend-FS67A5JL.svg";
const qualityUrl = "/build/assets/why-quality-_uKqmdnm.svg";
const ruleVerticalUrl = "/build/assets/why-rule-vertical-D-EEXUWD.svg";
const ruleTopUrl = "/build/assets/why-rule-a-DBMPgpdB.svg";
const ruleBottomUrl = "/build/assets/why-rule-a-DBMPgpdB.svg";
const _sfc_main$4 = /* @__PURE__ */ defineComponent({
  __name: "WhyUsGrid",
  __ssrInlineRender: true,
  props: {
    section: {}
  },
  setup(__props) {
    const props = __props;
    const GLYPHS = [outcomesUrl, brandUrl, endToEndUrl, qualityUrl];
    const DESKTOP_POSITIONS = [
      "xl:left-[360px] xl:top-[71px] xl:w-[283px] xl:justify-between",
      "xl:left-[46px] xl:top-[81px] xl:w-[266px] xl:justify-between",
      "xl:left-[362px] xl:top-[244px] xl:w-[275px] xl:justify-between",
      "xl:left-[46px] xl:top-[282px] xl:w-[264px] xl:justify-between"
    ];
    const claims = computed(
      () => props.section.items.map((item, index) => ({ item, glyph: GLYPHS[index % GLYPHS.length] }))
    );
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<section${ssrRenderAttrs(mergeProps({ class: "section max-md:-mt-[47px]" }, _attrs))}><div class="container-sizdah"><div class="grid gap-12 max-md:gap-[58px] xl:grid-cols-[401px_680px] xl:justify-between xl:gap-0">`);
      _push(ssrRenderComponent(_sfc_main$a, {
        "data-reveal": "",
        eyebrow: props.section.eyebrow,
        title: props.section.title,
        subtitle: props.section.subtitle || props.section.description,
        layout: "stacked"
      }, null, _parent));
      _push(`<div class="relative grid gap-x-10 gap-y-12 max-md:gap-y-4 sm:grid-cols-2 xl:mt-[51px] xl:h-[400px] xl:w-[680px] xl:block" data-reveal-group><img${ssrRenderAttr("src", unref(ruleVerticalUrl))} alt="" aria-hidden="true" width="4" height="400" class="why-rule pointer-events-none absolute top-0 hidden h-[400px] w-1 xl:left-[330px] xl:block"><img${ssrRenderAttr("src", unref(ruleTopUrl))} alt="" aria-hidden="true" width="320" height="4" class="why-rule pointer-events-none absolute hidden h-1 w-[320px] xl:left-[344px] xl:top-[190px] xl:block"><img${ssrRenderAttr("src", unref(ruleBottomUrl))} alt="" aria-hidden="true" width="320" height="4" class="why-rule pointer-events-none absolute hidden h-1 w-[320px] xl:left-[-26px] xl:top-[246px] xl:block"><!--[-->`);
      ssrRenderList(claims.value, ({ item, glyph }, index) => {
        _push(`<!--[--><div class="${ssrRenderClass([[
          index % 2 === 0 ? "sm:pe-10 xl:pe-0" : "sm:ps-0",
          DESKTOP_POSITIONS[index]
        ], "relative flex items-start gap-4 max-md:min-h-[64px] xl:absolute xl:gap-0"])}"><img${ssrRenderAttr("src", glyph)} alt="" aria-hidden="true" width="40" height="40" class="size-10 shrink-0 max-md:size-8"><div class="flex w-[212px] flex-col gap-2 text-right max-md:flex-1 max-md:gap-1 max-md:w-auto"><h3 class="text-heading-sm text-ink-50 max-md:text-[18px] max-md:leading-[25px]">${ssrInterpolate(item.title)}</h3>`);
        if (item.description) {
          _push(`<p class="text-title-sm text-ink-300 max-md:text-[12px] max-md:leading-[17px]">${ssrInterpolate(item.description)}</p>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div></div>`);
        if (index < claims.value.length - 1) {
          _push(`<img${ssrRenderAttr("src", unref(ruleTopUrl))} alt="" aria-hidden="true" width="320" height="4" class="why-rule col-span-full h-1 w-full sm:hidden">`);
        } else {
          _push(`<!---->`);
        }
        _push(`<!--]-->`);
      });
      _push(`<!--]--></div></div></div></section>`);
    };
  }
});
const _sfc_setup$4 = _sfc_main$4.setup;
_sfc_main$4.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/WhyUsGrid.vue");
  return _sfc_setup$4 ? _sfc_setup$4(props, ctx) : void 0;
};
const quoteUrl = "/build/assets/quote-mark-B0ICi5Jp.svg";
const scribbleUrl = "/build/assets/quote-scribble-CjEqN_1q.svg";
const _sfc_main$3 = /* @__PURE__ */ defineComponent({
  __name: "TestimonialCard",
  __ssrInlineRender: true,
  props: {
    testimonial: {}
  },
  setup(__props) {
    const props = __props;
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<li${ssrRenderAttrs(mergeProps({ class: "testimonial-wash sketch-frame group flex h-auto min-h-[350px] flex-col items-start gap-10 p-6 max-md:min-h-0 transition-colors duration-200 ease-brand hover:bg-brand hover:bg-none" }, _attrs))}><img${ssrRenderAttr("src", unref(quoteUrl))} alt="" aria-hidden="true" width="32" height="30" class="h-[30px] w-8 rotate-180 transition duration-200 ease-brand group-hover:brightness-0"><div class="flex flex-1 flex-col justify-between gap-12"><blockquote class="text-body-lg text-ink-50 transition-colors duration-200 ease-brand group-hover:text-ink-1000">${ssrInterpolate(props.testimonial.quote)}</blockquote><div class="flex items-center justify-between gap-4"><div class="flex items-center gap-2">`);
      if (props.testimonial.avatar) {
        _push(`<img${ssrRenderAttr("src", props.testimonial.avatar.src)}${ssrRenderAttr("srcset", props.testimonial.avatar.srcset)}${ssrRenderAttr("alt", props.testimonial.avatar.alt || props.testimonial.name)} width="48" height="48" loading="lazy" decoding="async" class="size-12 shrink-0 rounded-round object-cover">`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="flex flex-col gap-1"><p class="text-label-lg text-ink-100 transition-colors duration-200 ease-brand group-hover:text-ink-900">${ssrInterpolate(props.testimonial.name)}</p>`);
      if (props.testimonial.role) {
        _push(`<p class="text-label-md text-ink-200 transition-colors duration-200 ease-brand group-hover:text-ink-800">${ssrInterpolate(props.testimonial.role)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div><img${ssrRenderAttr("src", unref(scribbleUrl))} alt="" aria-hidden="true" width="48" height="48" class="size-12 shrink-0 transition duration-200 ease-brand group-hover:brightness-0"></div></div></li>`);
    };
  }
});
const _sfc_setup$3 = _sfc_main$3.setup;
_sfc_main$3.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/TestimonialCard.vue");
  return _sfc_setup$3 ? _sfc_setup$3(props, ctx) : void 0;
};
const dividerUrl = "/build/assets/insight-divider-BUEt4WWf.svg";
const _sfc_main$2 = /* @__PURE__ */ defineComponent({
  __name: "InsightsShowcase",
  __ssrInlineRender: true,
  props: {
    section: {},
    posts: {}
  },
  setup(__props) {
    const props = __props;
    const lead = computed(() => props.posts[0]);
    const rest = computed(() => props.posts.slice(1, 3));
    return (_ctx, _push, _parent, _attrs) => {
      if (lead.value) {
        _push(`<section${ssrRenderAttrs(mergeProps({ class: "section max-md:min-h-[1027px]" }, _attrs))}><div class="container-sizdah">`);
        _push(ssrRenderComponent(_sfc_main$a, {
          "data-reveal": "",
          eyebrow: props.section.eyebrow,
          title: props.section.title,
          subtitle: props.section.subtitle || props.section.description,
          gap: "lg",
          class: "max-md:gap-6 max-md:[&>div>p]:text-[16px] max-md:[&>div>p]:leading-[22px]"
        }, null, _parent));
        _push(`<div class="mt-12 grid items-stretch gap-6 lg:mt-16 lg:grid-cols-2 [&amp;&gt;*]:min-w-0" data-reveal-group><article class="sketch-frame flex flex-col gap-4 bg-brand-50 p-4 shadow-card sm:flex-row sm:justify-between" style="${ssrRenderStyle({ "background-image": "linear-gradient(\n              -32.7deg,\n              rgb(248 185 55 / 0) 2.3248%,\n              rgb(248 185 55 / 10%) 100%\n            )" })}">`);
        if (lead.value.image) {
          _push(`<img${ssrRenderAttr("src", lead.value.image.src)}${ssrRenderAttr("srcset", lead.value.image.srcset)}${ssrRenderAttr("alt", lead.value.image.alt || lead.value.title)} width="279" height="392" loading="eager" decoding="async" class="w-full rounded-lg object-cover max-md:h-[220px] max-md:shrink-0 sm:order-first sm:w-[279px] sm:shrink-0">`);
        } else {
          _push(`<!---->`);
        }
        _push(`<div class="flex flex-col justify-between gap-6 p-2 sm:w-[270px] sm:shrink-0"><div class="flex flex-col gap-[80px]"><p class="flex items-center gap-2 text-body-md text-ink-600"><img${ssrRenderAttr("src", unref(calendarUrl))} alt="" aria-hidden="true" width="24" height="24" class="size-6"><time${ssrRenderAttr("datetime", lead.value.publishedAtIso)}>${ssrInterpolate(lead.value.publishedAt)}</time></p><div class="flex flex-col gap-[32px]"><h3 class="text-title-md text-gold">`);
        _push(ssrRenderComponent(unref(Link), {
          href: lead.value.url,
          class: "transition-opacity hover:opacity-80"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`${ssrInterpolate(lead.value.title)}`);
            } else {
              return [
                createTextVNode(toDisplayString(lead.value.title), 1)
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(`</h3>`);
        if (lead.value.excerpt) {
          _push(`<p class="text-body-md text-warm-700">${ssrInterpolate(lead.value.excerpt)}</p>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div></div>`);
        _push(ssrRenderComponent(unref(Link), {
          href: lead.value.url,
          class: "flex size-12 shrink-0 self-end items-center justify-center rounded-round border border-warm-800 bg-warm-1000 text-warm-100 transition-colors hover:border-brand hover:bg-brand hover:text-warm-1000",
          "aria-label": lead.value.title
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(`<svg aria-hidden="true" focusable="false" width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="size-8"${_scopeId}><path d="M7.32131 13.3255C7.14474 11.9476 7.11965 11.0563 6.74489 9.27071C6.77457 9.26219 8.41058 11.0239 8.42029 11.0343C10.1043 12.825 10.3523 13.1304 12.0632 14.8949C13.208 16.0756 14.444 17.4 15.5198 18.4359C16.5327 19.4112 17.0675 19.7174 17.6117 19.5478C19.0394 19.1031 17.3031 17.4361 16.8822 16.9452C16.143 16.083 15.3405 15.2825 14.5717 14.448C13.5459 13.3346 12.5055 12.2347 11.4507 11.1487C11.2006 10.8913 9.76502 9.46905 8.71327 8.4698C10.0957 8.47707 11.3546 8.50634 11.9254 8.49913C12.7961 8.4881 13.4024 8.5873 14.2841 8.36686C15.2594 8.12302 15.1933 6.47067 14.4384 6.49307C13.3252 6.52614 12.1911 6.58145 10.9962 6.52132C9.47161 6.4446 7.94381 6.24591 6.49246 5.75562C4.63171 5.12705 3.53382 5.67838 4.1589 7.7275C4.64258 9.31316 4.97542 10.9448 5.15141 12.5932C5.25957 13.6064 5.28993 14.0764 5.66469 14.7157C6.27327 15.7539 7.51642 14.848 7.32131 13.3255Z" fill="currentColor"${_scopeId}></path></svg>`);
            } else {
              return [
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
        _push(`</div></article><ul class="flex flex-col justify-center gap-6"><!--[-->`);
        ssrRenderList(rest.value, (post, index) => {
          _push(`<li class="flex flex-col gap-6"><article class="flex items-center gap-6">`);
          if (post.image) {
            _push(`<img${ssrRenderAttr("src", post.image.src)}${ssrRenderAttr("srcset", post.image.srcset)}${ssrRenderAttr("alt", post.image.alt || post.title)} width="188" height="188" loading="eager" decoding="async" class="size-[120px] shrink-0 rounded-lg object-cover max-md:size-[80px] sm:size-[188px]">`);
          } else {
            _push(`<!---->`);
          }
          _push(`<div class="flex min-w-0 flex-col gap-6"><p class="flex items-center gap-2 text-body-md text-brand-50"><img${ssrRenderAttr("src", unref(calendarUrl))} alt="" aria-hidden="true" width="24" height="24" class="size-6"><time${ssrRenderAttr("datetime", post.publishedAtIso)}>${ssrInterpolate(post.publishedAt)}</time></p><h3 class="text-title-md text-brand-50">`);
          _push(ssrRenderComponent(unref(Link), {
            href: post.url,
            class: "transition-colors hover:text-brand"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(`${ssrInterpolate(post.title)}`);
              } else {
                return [
                  createTextVNode(toDisplayString(post.title), 1)
                ];
              }
            }),
            _: 2
          }, _parent));
          _push(`</h3></div></article>`);
          if (index < rest.value.length - 1) {
            _push(`<img${ssrRenderAttr("src", unref(dividerUrl))} alt="" aria-hidden="true" width="612" height="3" class="h-[3px] w-full">`);
          } else {
            _push(`<!---->`);
          }
          _push(`</li>`);
        });
        _push(`<!--]--></ul></div></div></section>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
});
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/InsightsShowcase.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const illustrationUrl = "/build/assets/faq-illustration-8ch9_OEW.svg";
const plusUrl = "/build/assets/faq-plus-BuOqfxWS.svg";
const minusUrl = "/build/assets/faq-minus-Bok7j_Zk.svg";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "FaqAccordion",
  __ssrInlineRender: true,
  props: {
    section: {},
    faqs: {}
  },
  setup(__props) {
    const props = __props;
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<section${ssrRenderAttrs(mergeProps({ class: "section" }, _attrs))}><div class="container-sizdah"><div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_minmax(0,719px)] lg:gap-16"><div class="flex flex-col gap-10">`);
      _push(ssrRenderComponent(_sfc_main$a, {
        "data-reveal": "",
        eyebrow: props.section.eyebrow,
        title: props.section.title,
        subtitle: props.section.subtitle || props.section.description,
        layout: "stacked"
      }, null, _parent));
      _push(`<img${ssrRenderAttr("src", unref(illustrationUrl))} alt="" aria-hidden="true" width="320" height="320" class="hidden size-80 self-center lg:block"></div><ul class="flex flex-col gap-6" data-reveal-group><!--[-->`);
      ssrRenderList(props.faqs, (faq, index) => {
        _push(`<li><details name="faq-accordion" class="group rounded-lg border border-gold-200 bg-gold-100 p-8 transition-colors duration-200 ease-brand open:bg-brand max-md:p-4"${ssrIncludeBooleanAttr(index === 0) ? " open" : ""}><summary class="flex cursor-pointer list-none items-center justify-between gap-6 [&amp;::-webkit-details-marker]:hidden"><span class="text-title-md text-ink-1000 max-md:text-[14px] max-md:leading-[20px]">${ssrInterpolate(faq.question)}</span><img${ssrRenderAttr("src", unref(plusUrl))} alt="" aria-hidden="true" width="24" height="24" class="size-6 shrink-0 rounded-full bg-white group-open:hidden max-md:size-5"><img${ssrRenderAttr("src", unref(minusUrl))} alt="" aria-hidden="true" width="24" height="24" class="hidden size-6 shrink-0 rounded-full bg-brand group-open:block max-md:size-5"></summary><p class="mt-4 text-body-lg text-warm-700 max-md:mt-2 max-md:text-[12px] max-md:leading-[17px]">${ssrInterpolate(faq.answer)}</p></details></li>`);
      });
      _push(`<!--]--></ul></div></div></section>`);
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/FaqAccordion.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const heroBgUrl = "/build/assets/hero-bg-D7ENDJTy.jpg";
const trustMarkUrl = "/build/assets/trust-divider-D4XN_txw.svg";
const heroNoteArrowUrl = "/build/assets/hero-note-icon-67K87ui5.svg";
const CLIENT_LOOP_SECONDS = 32;
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Home",
  __ssrInlineRender: true,
  props: {
    sections: {},
    services: {},
    projects: {},
    clients: {},
    testimonials: {},
    posts: {},
    faqs: {},
    seo: {}
  },
  setup(__props) {
    const props = __props;
    const hero = computed(() => props.sections.hero);
    const heroBackground = computed(() => {
      var _a, _b;
      return ((_b = (_a = hero.value) == null ? void 0 : _a.image) == null ? void 0 : _b.src) ?? heroBgUrl;
    });
    const kpi = computed(() => props.sections.kpi);
    const trustProof = computed(() => props.sections.trust_proof);
    const servicesCloud = computed(() => props.sections.services_cloud);
    const leadMagnet = computed(() => props.sections.lead_magnet);
    const projectsShowcase = computed(() => props.sections.projects_showcase);
    const process = computed(() => props.sections.process);
    const whyUs = computed(() => props.sections.why_us);
    const reviews = computed(() => props.sections.reviews);
    const insights = computed(() => props.sections.insights);
    const faq = computed(() => props.sections.faq);
    const finalCta = computed(() => props.sections.final_cta);
    const clientTrack = ref(null);
    const clientDragging = ref(false);
    let clientPosition = 0;
    let clientLoopWidth = 0;
    let clientFrame = 0;
    let clientLastTime = 0;
    let clientResize = null;
    function clientDirection() {
      return document.documentElement.dir === "rtl" ? 1 : -1;
    }
    function renderClients() {
      if (!clientTrack.value) return;
      clientPosition = (clientPosition % clientLoopWidth + clientLoopWidth) % clientLoopWidth;
      clientTrack.value.style.transform = `translate3d(${clientDirection() * clientPosition}px, 0, 0)`;
    }
    function tickClients(time) {
      const delta = clientLastTime ? Math.min(time - clientLastTime, 100) : 0;
      clientLastTime = time;
      if (!clientDragging.value && clientLoopWidth > 0) {
        clientPosition += clientLoopWidth / CLIENT_LOOP_SECONDS * (delta / 1e3);
        renderClients();
      }
      clientFrame = requestAnimationFrame(tickClients);
    }
    onMounted(() => {
      void nextTick(() => {
        var _a;
        const first = (_a = clientTrack.value) == null ? void 0 : _a.querySelector("ul");
        if (!first || props.clients.length < 2) return;
        const measure = () => {
          clientLoopWidth = first.getBoundingClientRect().width;
          renderClients();
        };
        measure();
        clientResize = new ResizeObserver(measure);
        clientResize.observe(first);
        if (!window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
          clientFrame = requestAnimationFrame(tickClients);
        }
      });
    });
    onBeforeUnmount(() => {
      cancelAnimationFrame(clientFrame);
      clientResize == null ? void 0 : clientResize.disconnect();
    });
    const activeTestimonial = ref(0);
    ref(null);
    return (_ctx, _push, _parent, _attrs) => {
      var _a, _b, _c, _d, _e;
      _push(`<!--[-->`);
      _push(ssrRenderComponent(_sfc_main$b, {
        seo: props.seo
      }, null, _parent));
      _push(`<section class="home-hero section-first relative overflow-hidden bg-cover bg-top bg-no-repeat pb-12 sm:pb-16 max-md:h-[619px] lg:min-h-[783px] lg:pb-24" style="${ssrRenderStyle({ backgroundImage: `url(${heroBackground.value})` })}"><div class="container-sizdah relative"><div class="flex min-w-0 flex-col gap-5 sm:gap-6 lg:max-w-[566px] lg:gap-0 lg:pt-4" data-reveal-group>`);
      if ((_a = hero.value) == null ? void 0 : _a.eyebrow) {
        _push(`<p class="flex w-fit max-w-full flex-col gap-px text-label-lg text-ink-100 lg:max-w-[221px] lg:whitespace-nowrap max-md:hidden"><span>${ssrInterpolate(hero.value.eyebrow)}</span><img${ssrRenderAttr("src", unref(underlineUrl))} alt="" aria-hidden="true" class="mt-1 h-[5px] w-full max-w-none"></p>`);
      } else {
        _push(`<!---->`);
      }
      if (hero.value) {
        _push(`<h1 class="me-4 flex w-auto flex-col gap-4 text-start max-md:mt-[32px] sm:gap-3 lg:me-0 lg:mt-[47.5px] lg:gap-4"><span class="text-hero-line text-paper max-md:text-[32px] max-md:leading-[40px]">${ssrInterpolate(hero.value.title)}</span><span class="text-hero-accent text-brand max-md:text-[40px] max-md:leading-[51px]">${ssrInterpolate(hero.value.subtitle)}</span><span class="text-hero-line text-paper max-md:text-[32px] max-md:leading-[40px]">${ssrInterpolate(hero.value.description)}</span></h1>`);
      } else {
        _push(`<!---->`);
      }
      if ((_b = hero.value) == null ? void 0 : _b.content) {
        _push(`<p class="max-w-[506px] text-title-sm text-ink-200 max-md:mt-[42px] max-md:text-[14px] max-md:leading-[20px] sm:leading-relaxed lg:mt-[59px]">${ssrInterpolate(hero.value.content)}</p>`);
      } else {
        _push(`<!---->`);
      }
      if (hero.value) {
        _push(`<div dir="ltr" class="flex flex-nowrap items-center justify-end gap-2 max-md:mt-[52px] sm:gap-4 lg:mt-[109px]">`);
        if (hero.value.primaryCta) {
          _push(ssrRenderComponent(_sfc_main$c, {
            label: hero.value.primaryCta.label,
            href: hero.value.primaryCta.url,
            variant: "light",
            size: "lg",
            "with-arrow": "",
            class: "ring-1 ring-brand max-md:w-[134px] max-md:px-0 max-md:py-3 max-md:text-title-sm max-md:[&>img]:hidden"
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        if (hero.value.secondaryCta) {
          _push(ssrRenderComponent(_sfc_main$c, {
            label: hero.value.secondaryCta.label,
            href: hero.value.secondaryCta.url,
            size: "lg",
            class: "max-md:w-[137px] max-md:px-0 max-md:py-3 max-md:text-title-sm"
          }, null, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      if (hero.value) {
        _push(`<p class="flex items-start gap-2 text-body-lg text-ink-300 max-md:hidden lg:ms-[65px] lg:mt-[49px] lg:gap-[13px]"><img${ssrRenderAttr("src", unref(heroNoteArrowUrl))} alt="" aria-hidden="true" width="56" height="56" class="size-10 shrink-0 lg:-mt-[35px] lg:size-14"><span class="max-w-[173px]">${ssrInterpolate(_ctx.$t("home.hero.note"))}</span></p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div></section>`);
      if ((_c = kpi.value) == null ? void 0 : _c.items.length) {
        _push(`<section class="pb-16 max-md:pb-[80px] md:pb-24"><div class="container-sizdah">`);
        if (kpi.value.title) {
          _push(`<h2 class="sr-only">${ssrInterpolate(kpi.value.title)}</h2>`);
        } else {
          _push(`<!---->`);
        }
        _push(`<ul class="mx-auto grid w-full max-w-[1036px] grid-cols-3 gap-2 sm:gap-4" data-reveal-group><!--[-->`);
        ssrRenderList(kpi.value.items, (item, index) => {
          _push(ssrRenderComponent(_sfc_main$9, {
            key: item.id,
            value: ["+40%", "+70K", "+90%"][index] ?? item.value,
            label: item.title,
            caption: item.description,
            icon: item.icon
          }, null, _parent));
        });
        _push(`<!--]--></ul></div></section>`);
      } else {
        _push(`<!---->`);
      }
      if (trustProof.value) {
        _push(`<section class="pb-16 max-md:pb-[76px] md:pb-24 lg:pb-[117px]"><div class="container-sizdah flex flex-col items-center gap-6 lg:gap-0"><p class="flex flex-wrap items-center justify-center gap-1 text-center max-md:h-[54px] max-md:w-[258px] max-md:flex-nowrap max-md:whitespace-nowrap max-md:text-[16px]" data-reveal><span class="text-title-lg text-brand-50">${ssrInterpolate(trustProof.value.title)}</span><img${ssrRenderAttr("src", unref(trustMarkUrl))} alt="" aria-hidden="true" width="72" height="34" class="h-auto w-[72px] max-md:size-[54px]">`);
        if (trustProof.value.subtitle) {
          _push(`<span class="text-title-md text-brand-50">${ssrInterpolate(trustProof.value.subtitle)}</span>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</p>`);
        if (props.clients.length) {
          _push(`<div class="${ssrRenderClass([{ "is-dragging": clientDragging.value }, "marquee-mask client-carousel w-full max-w-[1036px] overflow-hidden select-none max-md:h-[80px]"])}" data-reveal><div class="marquee-track client-carousel-track max-md:h-[80px]"><ul class="flex shrink-0 items-center gap-x-12 pe-12 max-md:h-[80px]"><!--[-->`);
          ssrRenderList(props.clients, (client) => {
            _push(`<li class="shrink-0">`);
            _push(ssrRenderComponent(_sfc_main$8, { client }, null, _parent));
            _push(`</li>`);
          });
          _push(`<!--]--></ul><ul aria-hidden="true" class="flex shrink-0 items-center gap-x-12 pe-12 max-md:h-[80px]"><!--[-->`);
          ssrRenderList(props.clients, (client) => {
            _push(`<li class="shrink-0">`);
            _push(ssrRenderComponent(_sfc_main$8, { client }, null, _parent));
            _push(`</li>`);
          });
          _push(`<!--]--></ul></div></div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div></section>`);
      } else {
        _push(`<!---->`);
      }
      if (servicesCloud.value) {
        _push(ssrRenderComponent(_sfc_main$7, {
          section: servicesCloud.value,
          services: props.services
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      if (leadMagnet.value) {
        _push(`<section class="section max-md:pb-2 max-md:pt-10 lg:pb-[146px] lg:pt-[124px]"><div class="container-sizdah">`);
        _push(ssrRenderComponent(_sfc_main$d, {
          section: leadMagnet.value,
          size: "lg",
          source: "home",
          "data-reveal": ""
        }, null, _parent));
        _push(`</div></section>`);
      } else {
        _push(`<!---->`);
      }
      if (projectsShowcase.value && props.projects.length) {
        _push(ssrRenderComponent(_sfc_main$6, {
          section: projectsShowcase.value,
          projects: props.projects
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      if ((_d = process.value) == null ? void 0 : _d.items.length) {
        _push(`<section class="section"><div class="container-sizdah">`);
        _push(ssrRenderComponent(_sfc_main$a, {
          eyebrow: process.value.eyebrow,
          title: process.value.title,
          subtitle: process.value.subtitle || process.value.description
        }, null, _parent));
        _push(`<ul class="mt-12 grid gap-[2px] border-2 border-ink-300 bg-ink-300 max-md:gap-0 max-md:border-0 sm:grid-cols-2 lg:mt-16 lg:grid-cols-3" data-reveal-group><!--[-->`);
        ssrRenderList(process.value.items, (item, index) => {
          _push(ssrRenderComponent(_sfc_main$5, {
            key: item.id,
            item,
            index
          }, null, _parent));
        });
        _push(`<!--]--></ul></div></section>`);
      } else {
        _push(`<!---->`);
      }
      if ((_e = whyUs.value) == null ? void 0 : _e.items.length) {
        _push(ssrRenderComponent(_sfc_main$4, { section: whyUs.value }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      if (reviews.value && props.testimonials.length) {
        _push(`<section class="section max-md:pb-[50px]"><div class="container-sizdah">`);
        _push(ssrRenderComponent(_sfc_main$a, {
          eyebrow: reviews.value.eyebrow,
          title: reviews.value.title,
          subtitle: reviews.value.subtitle || reviews.value.description,
          gap: "lg",
          class: "max-md:gap-6 max-md:[&>div>h2]:whitespace-nowrap max-md:[&>div>h2]:text-[26px] max-md:[&>div>h2]:leading-[36px] max-md:[&>div>p]:text-[16px] max-md:[&>div>p]:leading-[22px]"
        }, null, _parent));
        _push(`<ul style="${ssrRenderStyle({ "--testimonial-index": activeTestimonial.value })}" class="${ssrRenderClass([[
          props.testimonials.length > 1 && "sm:grid-cols-2",
          props.testimonials.length > 2 && "lg:grid-cols-3",
          props.testimonials.length > 3 && "lg:grid-cols-4",
          props.testimonials.length === 1 && "max-w-[300px] max-md:max-w-none max-md:w-full"
        ], "testimonial-carousel mt-2 flex h-auto touch-pan-y gap-[26px] overflow-hidden max-md:mt-10 lg:mt-2 lg:grid lg:h-[297px] lg:overflow-visible"])}" data-reveal-group><!--[-->`);
        ssrRenderList(props.testimonials, (testimonial) => {
          _push(ssrRenderComponent(_sfc_main$3, {
            key: testimonial.name,
            testimonial
          }, null, _parent));
        });
        _push(`<!--]--></ul>`);
        if (props.testimonials.length > 1) {
          _push(`<div class="testimonial-controls mt-6 flex items-center justify-center gap-4 max-md:mt-0 lg:hidden"><button type="button" class="testimonial-arrow" aria-label="نظر قبلی">←</button><div class="flex items-center gap-2" role="tablist" aria-label="انتخاب نظر مشتری"><!--[-->`);
          ssrRenderList(props.testimonials, (_, index) => {
            _push(`<button type="button" role="tab"${ssrRenderAttr("aria-selected", activeTestimonial.value === index)}${ssrRenderAttr("aria-label", `نمایش نظر ${index + 1}`)} class="${ssrRenderClass([activeTestimonial.value === index && "is-active", "testimonial-dot"])}"></button>`);
          });
          _push(`<!--]--></div><button type="button" class="testimonial-arrow" aria-label="نظر بعدی">→</button></div>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div></section>`);
      } else {
        _push(`<!---->`);
      }
      if (insights.value && props.posts.length) {
        _push(ssrRenderComponent(_sfc_main$2, {
          section: insights.value,
          posts: props.posts
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      if (faq.value && props.faqs.length) {
        _push(ssrRenderComponent(_sfc_main$1, {
          section: faq.value,
          faqs: props.faqs
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      if (finalCta.value) {
        _push(`<section class="section"><div class="container-sizdah">`);
        _push(ssrRenderComponent(_sfc_main$e, {
          section: finalCta.value,
          "data-reveal": ""
        }, null, _parent));
        _push(`</div></section>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Home.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
