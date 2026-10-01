import { defineComponent, ref, watch, nextTick, mergeProps, unref, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderAttr, ssrRenderStyle, ssrRenderComponent, ssrInterpolate, ssrIncludeBooleanAttr, ssrRenderClass } from "vue/server-renderer";
import { u as useTranslations, I as IconClose, a as _sfc_main$2 } from "../ssr.js";
import { useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { u as userFieldUrl } from "./field-user-tphddZ0v.js";
const envelopeIconUrl = "/build/assets/lead-magnet-envelope-Bgs8xQTw.svg";
const successIconUrl = "/build/assets/lead-magnet-success-CEULdhOu.svg";
const successScribbleUrl = "/build/assets/lead-magnet-success-scribble-D2XYmmS9.svg";
const tickMarksUrl = "/build/assets/lead-magnet-tick-marks-zltx4Bqo.svg";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "LeadMagnetModal",
  __ssrInlineRender: true,
  props: {
    open: { type: Boolean },
    source: {}
  },
  emits: ["close"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const { t } = useTranslations();
    const panel = ref(null);
    const nameInput = ref(null);
    const succeeded = ref(false);
    const form = useForm({
      name: "",
      email: "",
      source: props.source,
      website: ""
    });
    function close() {
      emit("close");
    }
    watch(
      () => props.open,
      async (open) => {
        var _a;
        document.body.style.overflow = open ? "hidden" : "";
        if (open) {
          succeeded.value = false;
          form.clearErrors();
          await nextTick();
          if (nameInput.value) {
            nameInput.value.focus();
          } else {
            (_a = panel.value) == null ? void 0 : _a.focus();
          }
        }
      }
    );
    return (_ctx, _push, _parent, _attrs) => {
      if (props.open) {
        _push(`<div${ssrRenderAttrs(mergeProps({ class: "fixed inset-0 z-menu flex items-center justify-center p-4" }, _attrs))}><div class="absolute inset-0 bg-ink-1000/80 backdrop-blur-sm"></div><div tabindex="-1" role="dialog" aria-modal="true"${ssrRenderAttr("aria-label", unref(t)("forms.newsletter.dialog_label"))} class="relative flex w-full max-w-[706px] flex-col items-center gap-4 rounded-lg p-8 outline-none md:p-12" style="${ssrRenderStyle({ "background-image": "linear-gradient(-32.38deg, rgb(248 185 55 / 0) 2.3248%, rgb(248 185 55 / 10%) 100%),\n            linear-gradient(0deg, #141414, #141414)" })}"><button type="button" class="absolute end-4 top-4 inline-flex size-9 items-center justify-center rounded-full bg-brand text-ink-1000 transition-colors duration-200 ease-brand hover:bg-brand-900"${ssrRenderAttr("aria-label", unref(t)("common.close"))}>`);
        _push(ssrRenderComponent(IconClose, { class: "size-4" }, null, _parent));
        _push(`</button>`);
        if (!succeeded.value) {
          _push(`<!--[--><div class="flex flex-col items-center gap-6 text-center"><p class="text-heading-xl text-ink-50">${ssrInterpolate(unref(t)("forms.newsletter.title"))}</p><p class="max-w-[612px] text-body-md text-ink-300">${ssrInterpolate(unref(t)("forms.newsletter.description"))}</p></div><form novalidate class="mt-2 flex w-full max-w-[396px] flex-col items-center gap-8"><div class="hidden" aria-hidden="true"><label for="lead-magnet-website">Website</label><input id="lead-magnet-website"${ssrRenderAttr("value", unref(form).website)} type="text" tabindex="-1" autocomplete="off"></div><div class="flex w-full flex-col gap-2"><label for="lead-magnet-name" class="text-label-lg text-ink-100">${ssrInterpolate(unref(t)("forms.newsletter.name"))}</label><div class="flex items-center gap-2 rounded-lg border-3 border-brand-300 bg-white/80 p-3"><input id="lead-magnet-name"${ssrRenderAttr("value", unref(form).name)} type="text" autocomplete="name"${ssrRenderAttr("placeholder", unref(t)("forms.newsletter.name_placeholder"))} class="w-full min-w-0 border-0 bg-transparent p-0 text-start text-body-md text-ink-1000 placeholder:text-ink-600 focus:outline-none focus:ring-0 focus:ring-offset-0"><img${ssrRenderAttr("src", unref(userFieldUrl))} alt="" aria-hidden="true" width="24" height="24" class="size-6 shrink-0"></div>`);
          if (unref(form).errors.name) {
            _push(`<p class="text-label-md text-brand">${ssrInterpolate(unref(form).errors.name)}</p>`);
          } else {
            _push(`<!---->`);
          }
          _push(`</div><div class="flex w-full flex-col gap-2"><label for="lead-magnet-email" class="text-label-lg text-ink-100">${ssrInterpolate(unref(t)("forms.newsletter.email"))}</label><div class="flex items-center gap-2 rounded-lg border-3 border-brand-300 bg-white/80 p-3"><input id="lead-magnet-email"${ssrRenderAttr("value", unref(form).email)} type="email" autocomplete="email"${ssrRenderAttr("placeholder", unref(t)("forms.newsletter.email_placeholder"))} class="w-full min-w-0 border-0 bg-transparent p-0 text-start text-body-md text-ink-1000 placeholder:text-ink-600 focus:outline-none focus:ring-0 focus:ring-offset-0"><img${ssrRenderAttr("src", unref(envelopeIconUrl))} alt="" aria-hidden="true" width="24" height="18" class="h-[18px] w-6 shrink-0"></div>`);
          if (unref(form).errors.email) {
            _push(`<p class="text-label-md text-brand">${ssrInterpolate(unref(form).errors.email)}</p>`);
          } else {
            _push(`<!---->`);
          }
          _push(`</div><button type="submit"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""} class="inline-flex items-center justify-center rounded-sm bg-brand px-6 py-3 text-title-sm text-ink-1000 transition-colors duration-200 ease-brand hover:bg-brand-900 disabled:pointer-events-none disabled:opacity-50">${ssrInterpolate(unref(form).processing ? unref(t)("common.sending") : unref(t)("forms.newsletter.submit"))}</button><p class="text-label-md text-ink-500">${ssrInterpolate(unref(t)("forms.newsletter.disclaimer"))}</p></form><img${ssrRenderAttr("src", unref(tickMarksUrl))} alt="" aria-hidden="true" width="56" height="38" class="pointer-events-none absolute end-[47px] top-[355px] hidden w-14 h-[38px] md:block"><img${ssrRenderAttr("src", unref(successScribbleUrl))} alt="" aria-hidden="true" width="64" height="64" class="pointer-events-none absolute start-[25px] top-[218px] hidden size-16 md:block"><!--]-->`);
        } else {
          _push(`<!--[--><div class="flex flex-col items-center gap-6 py-4 text-center" role="status"><img${ssrRenderAttr("src", unref(successIconUrl))} alt="" aria-hidden="true" width="96" height="96" class="size-24"><p class="whitespace-pre-line text-heading-sm text-ink-50">${ssrInterpolate(unref(t)("forms.newsletter.success"))}</p>`);
          _push(ssrRenderComponent(_sfc_main$2, {
            label: unref(t)("common.start_conversation"),
            href: unref(route)("contact"),
            onClick: close
          }, null, _parent));
          _push(`</div><img${ssrRenderAttr("src", unref(successScribbleUrl))} alt="" aria-hidden="true" width="64" height="64" class="pointer-events-none absolute start-[33px] top-[111px] hidden size-16 md:block"><img${ssrRenderAttr("src", unref(tickMarksUrl))} alt="" aria-hidden="true" width="56" height="38" class="pointer-events-none absolute end-[48px] top-[224px] hidden w-14 h-[38px] md:block"><!--]-->`);
        }
        _push(`</div></div>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/LeadMagnetModal.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const doodleUrl = "/build/assets/checklist-doodle-BC4mNtmt.svg";
const articleAccentUrl = "/build/assets/lead-magnet-accent-ChyN63lX.svg";
const leadMagnetDotsUrl = "/build/assets/lead-magnet-dots-BYONUPPl.svg";
const leadMagnetGridUrl = "/build/assets/lead-magnet-grid-Dy-W-I12.svg";
const leadMagnetScribbleUrl = "/build/assets/lead-magnet-scribble-PgR0OkNO.svg";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "LeadMagnetBanner",
  __ssrInlineRender: true,
  props: {
    section: {},
    size: { default: "sm" },
    source: {}
  },
  setup(__props) {
    const props = __props;
    const modalOpen = ref(false);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[--><aside class="${ssrRenderClass([
        props.size === "lg" ? "sketch-frame mx-auto max-w-[1036px] bg-brand-50 p-8 max-md:h-[148px] max-md:flex-row max-md:items-center max-md:gap-4 max-md:px-4 max-md:py-6 lg:flex-row lg:justify-between lg:p-16" : "w-full max-w-[826px] rounded-sm bg-brand-50 p-8 md:flex-row md:items-center md:justify-between md:gap-6",
        "relative flex flex-col items-center gap-6 overflow-hidden"
      ])}">`);
      if (props.size === "sm") {
        _push(`<img${ssrRenderAttr("src", unref(doodleUrl))} alt="" aria-hidden="true" width="64" height="50" class="relative z-20 hidden h-[49.419px] w-16 shrink-0 md:block">`);
      } else {
        _push(`<!---->`);
      }
      if (props.size === "lg") {
        _push(`<!--[--><img${ssrRenderAttr("src", unref(leadMagnetGridUrl))} alt="" aria-hidden="true" width="400" height="400" class="pointer-events-none absolute left-[699px] top-[-85px] hidden size-[400px] max-w-none lg:block"><img${ssrRenderAttr("src", unref(leadMagnetDotsUrl))} alt="" aria-hidden="true" width="48" height="26" class="pointer-events-none absolute left-[66px] top-[26px] hidden h-[26px] w-[48px] lg:block"><img${ssrRenderAttr("src", unref(leadMagnetScribbleUrl))} alt="" aria-hidden="true" width="56" height="56" class="pointer-events-none absolute left-[308px] top-[130px] hidden size-14 lg:block"><!--]-->`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="${ssrRenderClass([
        props.size === "lg" ? "max-w-[580px] text-center gap-4 max-md:min-w-0 max-md:max-w-none max-md:flex-1 max-md:text-right lg:w-[580px]" : "w-full max-w-[458px] text-start md:w-[458px]",
        "relative z-10 flex flex-col gap-2"
      ])}">`);
      if (props.size === "sm") {
        _push(`<img${ssrRenderAttr("src", unref(leadMagnetGridUrl))} alt="" aria-hidden="true" width="400" height="400" class="pointer-events-none absolute left-[305px] top-[-102px] -z-10 hidden size-[400px] max-w-none md:block">`);
      } else {
        _push(`<!---->`);
      }
      _push(`<p class="${ssrRenderClass(
        props.size === "lg" ? "text-heading-sm text-ink-1000 max-md:text-[18px] max-md:leading-[22px]" : "text-title-md text-ink-900"
      )}">${ssrInterpolate(props.section.title)}</p>`);
      if (props.section.description) {
        _push(`<p class="${ssrRenderClass(
          props.size === "lg" ? "text-body-lg text-ink-600 max-md:text-[12px] max-md:leading-[17px] lg:text-start" : "text-body-md text-ink-600 md:text-start"
        )}">${ssrInterpolate(props.section.description)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
      if (props.section.primaryCta) {
        _push(`<div class="relative z-10 shrink-0">`);
        _push(ssrRenderComponent(_sfc_main$2, {
          label: props.size === "lg" ? "شروع گفتگو" : props.section.primaryCta.label,
          variant: props.size === "lg" ? "brand-outline" : "solid",
          onClick: ($event) => modalOpen.value = true,
          class: "max-md:px-3 max-md:py-3 max-md:text-[14px]"
        }, null, _parent));
        if (props.size === "sm") {
          _push(`<img${ssrRenderAttr("src", unref(articleAccentUrl))} alt="" aria-hidden="true" width="16" height="20" class="pointer-events-none absolute -left-[13px] -top-[12.5px] hidden h-5 w-4 md:block">`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</aside>`);
      _push(ssrRenderComponent(_sfc_main$1, {
        open: modalOpen.value,
        source: props.source,
        onClose: ($event) => modalOpen.value = false
      }, null, _parent));
      _push(`<!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/LeadMagnetBanner.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};
