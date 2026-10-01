import { defineComponent, computed, mergeProps, useSSRContext, ref, unref } from "vue";
import { ssrRenderAttrs, ssrRenderList, ssrRenderStyle, ssrRenderAttr, ssrInterpolate, ssrRenderComponent, ssrRenderClass } from "vue/server-renderer";
import { a as _sfc_main$5 } from "./BlogCard-C2Vr3W2e.js";
import { u as useTranslations, _ as _export_sfc } from "../ssr.js";
import { c as calendarUrl } from "./calendar-BHdPRM2s.js";
import { x as xUrl, l as linkedinUrl, r as relatedRuleUrl } from "./related-rule-H34RaeRD.js";
import { _ as _sfc_main$4 } from "./LeadMagnetBanner-BasglgVi.js";
import { _ as _sfc_main$3 } from "./SeoHead-B0PBocbK.js";
import { _ as _sfc_main$6 } from "./StartTogetherCard-DwqCyDIf.js";
import "@inertiajs/vue3";
import "@inertiajs/vue3/server";
import "ziggy-js";
import "./field-user-tphddZ0v.js";
const subjectUrl = "/build/assets/article-subject-D_eKVGWc.svg";
const authorUrl = "/build/assets/article-author-P9wUvX3Y.svg";
const _sfc_main$2 = /* @__PURE__ */ defineComponent({
  __name: "ArticleMeta",
  __ssrInlineRender: true,
  props: {
    post: {}
  },
  setup(__props) {
    const props = __props;
    const { t } = useTranslations();
    const chips = computed(
      () => [
        { key: "date", icon: calendarUrl, label: t("blog.date"), value: props.post.publishedAt },
        props.post.category ? {
          key: "subject",
          icon: subjectUrl,
          label: t("blog.subject"),
          value: props.post.category.name
        } : null,
        props.post.author ? {
          key: "author",
          icon: authorUrl,
          label: t("blog.written_by"),
          value: props.post.author.name
        } : null
      ].filter(
        (chip) => chip !== null
      )
    );
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<ul${ssrRenderAttrs(mergeProps({ class: "flex flex-wrap items-center gap-4 max-md:w-[362px] max-md:gap-2" }, _attrs))} data-v-950bc935><!--[-->`);
      ssrRenderList(chips.value, (chip) => {
        _push(`<li class="relative flex items-start gap-2 rounded-lg px-[26px] py-[14px] max-md:h-[57px] max-md:px-4 max-md:py-3" style="${ssrRenderStyle({ "background-image": "linear-gradient(\n          -15deg,\n          rgb(248 185 55 / 0) 2.32%,\n          rgb(248 185 55 / 7%) 100%\n        )" })}" data-v-950bc935><span aria-hidden="true" class="sketch-frame-chip-cap-end pointer-events-none absolute inset-y-0 start-0 w-6 bg-brand-300" data-v-950bc935></span><span aria-hidden="true" class="sketch-frame-chip-middle pointer-events-none absolute inset-y-0 inset-x-6 bg-brand-300" data-v-950bc935></span><span aria-hidden="true" class="sketch-frame-chip-cap-start pointer-events-none absolute inset-y-0 end-0 w-6 bg-brand-300" data-v-950bc935></span><img${ssrRenderAttr("src", chip.icon)} alt="" aria-hidden="true" width="24" height="24" class="size-6 shrink-0" data-v-950bc935><span class="flex flex-col justify-center gap-2 whitespace-nowrap" data-v-950bc935><span class="text-label-lg text-ink-50" data-v-950bc935>${ssrInterpolate(chip.label)}</span><span class="text-body-md text-ink-200" data-v-950bc935>${ssrInterpolate(chip.value)}</span></span></li>`);
      });
      _push(`<!--]--></ul>`);
    };
  }
});
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/ArticleMeta.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const ArticleMeta = /* @__PURE__ */ _export_sfc(_sfc_main$2, [["__scopeId", "data-v-950bc935"]]);
const copyLinkUrl = "/build/assets/copy-link-BYZWpMZy.svg";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "ArticleShare",
  __ssrInlineRender: true,
  props: {
    url: {},
    title: {}
  },
  setup(__props) {
    const props = __props;
    const { t } = useTranslations();
    const copied = ref(false);
    const xShareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(props.url)}&text=${encodeURIComponent(props.title)}`;
    const linkedinShareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(props.url)}`;
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "flex flex-col items-center gap-6 max-md:gap-4" }, _attrs))}><p class="text-label-lg text-paper max-md:text-[14px] max-md:leading-[17px]">${ssrInterpolate(unref(t)("blog.share"))}</p><ul class="flex flex-col items-center gap-4 max-md:flex-row max-md:gap-4"><li class="relative"><button type="button" class="group flex size-[68px] items-center justify-center rounded-full bg-brand-100 transition-colors duration-200 ease-brand hover:bg-brand max-md:size-12"><span class="sr-only">${ssrInterpolate(unref(t)("blog.copy_link"))}</span><img${ssrRenderAttr("src", unref(copyLinkUrl))} alt="" aria-hidden="true" width="24" height="9.93" class="h-[9.93px] w-6 transition-[filter] duration-200 ease-brand group-hover:brightness-0"></button>`);
      if (copied.value) {
        _push(`<span role="status" class="absolute inline-end-full top-1/2 me-2 -translate-y-1/2 whitespace-nowrap rounded-sm bg-ink-1000 px-2 py-1 text-label-md text-paper">${ssrInterpolate(unref(t)("blog.link_copied"))}</span>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</li><li><a${ssrRenderAttr("href", xShareUrl)} target="_blank" rel="noopener noreferrer" class="group flex size-[68px] items-center justify-center rounded-full bg-brand-100 transition-colors duration-200 ease-brand hover:bg-brand max-md:size-12"><span class="sr-only">X</span><img${ssrRenderAttr("src", unref(xUrl))} alt="" aria-hidden="true" width="24" height="24" class="size-6 transition-[filter] duration-200 ease-brand group-hover:brightness-0"></a></li><li><a${ssrRenderAttr("href", linkedinShareUrl)} target="_blank" rel="noopener noreferrer" class="group flex size-[68px] items-center justify-center rounded-full bg-brand-100 transition-colors duration-200 ease-brand hover:bg-brand max-md:size-12"><span class="sr-only">LinkedIn</span><img${ssrRenderAttr("src", unref(linkedinUrl))} alt="" aria-hidden="true" width="24" height="24" class="size-6 transition-[filter] duration-200 ease-brand group-hover:brightness-0"></a></li></ul></div>`);
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/ArticleShare.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const figmaArticleCoverUrl = "/build/assets/article-1077-raw-1-BzIeFcCS.png";
const figmaRelatedCoverUrl = "/build/assets/article-1077-related-DrvLJc3e.png";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Show",
  __ssrInlineRender: true,
  props: {
    post: {},
    leadMagnet: {},
    finalCta: {},
    seo: {}
  },
  setup(__props) {
    const props = __props;
    const { t } = useTranslations();
    const figmaArticle = computed(() => {
      if (!["محتوا-بدون-جهت", "content-without-direction"].includes(props.post.slug)) {
        return props.post;
      }
      return {
        ...props.post,
        title: "هر برند داستان خودش را دارد",
        subtitle: "وقتی محتوای شما هدف و جهت روشنی نداشته باشد، پراکنده و بی‌اثر می‌شود؛ در نتیجه نمی‌تواند مخاطب را جذب کند یا به رشد واقعی کسب‌وکارتان کمک کند",
        image: {
          src: figmaArticleCoverUrl,
          alt: "",
          width: 1024,
          height: 1536
        },
        content: `
      <h3>بیشتر برندها در تولید محتوا مشکل ندارند؛ مشکل اصلی‌شان نداشتن مسیر مشخص است</h3>
      <p>در ابتدا همه‌چیز شبیه پیشرفت به نظر می‌رسد؛ محتوا تولید می‌شود، صفحات فعال هستند و برند در پلتفرم‌های مختلف حضور دارد. اما بعد از مدتی، تغییر ظریفی اتفاق می‌افتد و با وجود تمام این فعالیت‌ها، احساس می‌کنید برند واقعاً در مسیر معناداری رو به جلو حرکت نمی‌کند.</p>
      <h3>شما مشغول می‌مانید، اما برندتان پیشرفت نمی‌کند</h3>
      <p>در ابتدا همه‌چیز شبیه پیشرفت به نظر می‌رسد؛ محتوا تولید می‌شود، صفحات فعال هستند و برند در پلتفرم‌های مختلف حضور دارد. اما بعد از مدتی، تغییر ظریفی اتفاق می‌افتد و با وجود تمام این فعالیت‌ها، احساس می‌کنید برند واقعاً در مسیر معناداری رو به جلو حرکت نمی‌کند.</p>
    `
      };
    });
    const isFigmaArticle = computed(() => figmaArticle.value !== props.post);
    const figmaRelatedPosts = computed(() => props.post.related.slice(0, 2).map((post) => ({
      ...post,
      image: { src: figmaRelatedCoverUrl, alt: "", width: 1086, height: 1448 }
    })));
    const articleContent = computed(() => {
      const marker = "[[lead_magnet]]";
      const content = figmaArticle.value.content;
      const index = content.indexOf(marker);
      if (index < 0) {
        return { before: content, after: "", hasMarker: false };
      }
      return {
        before: content.slice(0, index),
        after: content.slice(index + marker.length),
        hasMarker: true
      };
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(_sfc_main$3, {
        seo: props.seo
      }, null, _parent));
      _push(`<article class="${ssrRenderClass([isFigmaArticle.value ? "pb-[149px]" : "pb-[123px]", "section-first overflow-x-hidden max-md:pt-[188px] md:pt-[207px]"])}"><div class="container-sizdah relative isolate flex flex-col"><div class="grid-mesh pointer-events-none absolute inline-start-0 block-start-[-5px] -z-10 hidden h-[872px] w-full max-w-container lg:block" style="${ssrRenderStyle({ "--mesh-cell-x": "109.095px", "--mesh-cell-y": "109.095px" })}" aria-hidden="true"></div><header class="mx-auto flex max-w-[670px] flex-col items-center gap-[88px] max-md:gap-[30px]" data-reveal><div class="flex w-full max-w-measure flex-col items-center gap-6 text-center max-md:gap-6"><h1 class="text-display-md text-ink-50 max-md:text-[26px] max-md:leading-[36px]">`);
      if (isFigmaArticle.value) {
        _push(`<!--[--><span class="block">هر برند</span><span class="block">داستان خودش را دارد</span><!--]-->`);
      } else {
        _push(`<!--[-->${ssrInterpolate(figmaArticle.value.title)}<!--]-->`);
      }
      _push(`</h1>`);
      if (figmaArticle.value.subtitle) {
        _push(`<p class="text-title-sm text-ink-200 max-md:text-[16px] max-md:leading-[22px]">${ssrInterpolate(figmaArticle.value.subtitle)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
      _push(ssrRenderComponent(ArticleMeta, {
        post: props.post
      }, null, _parent));
      _push(`</header>`);
      if (figmaArticle.value.image) {
        _push(`<img${ssrRenderAttr("src", figmaArticle.value.image.src)}${ssrRenderAttr("srcset", figmaArticle.value.image.srcset)}${ssrRenderAttr("alt", figmaArticle.value.image.alt)}${ssrRenderAttr("width", figmaArticle.value.image.width)}${ssrRenderAttr("height", figmaArticle.value.image.height)} class="mt-16 aspect-[1248/624] w-full rounded-lg object-cover max-md:mt-[32px] max-md:aspect-[362/272]" data-reveal>`);
      } else {
        _push(`<!---->`);
      }
      if (isFigmaArticle.value) {
        _push(`<div class="ms-auto mt-10 hidden w-[176px] max-md:block">`);
        _push(ssrRenderComponent(_sfc_main$1, {
          url: props.seo.canonical,
          title: figmaArticle.value.title
        }, null, _parent));
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="relative mx-auto mt-[113px] w-full max-w-container max-md:mt-[36px]"><div class="pointer-events-none absolute inset-y-0 inline-start-[33px] hidden min-[1336px]:block">`);
      _push(ssrRenderComponent(_sfc_main$1, {
        url: props.seo.canonical,
        title: figmaArticle.value.title,
        class: "pointer-events-auto sticky top-32"
      }, null, _parent));
      _push(`</div><div class="mx-auto w-full max-w-[831px]"><div class="${ssrRenderClass([isFigmaArticle.value ? "figma-article-prose" : "", "rich-prose rich-prose-article"])}">${articleContent.value.before ?? ""}</div></div></div>`);
      if (props.leadMagnet && articleContent.value.hasMarker && !isFigmaArticle.value) {
        _push(`<div class="mx-auto mt-[72px] w-full max-w-[826px]">`);
        _push(ssrRenderComponent(_sfc_main$4, {
          section: props.leadMagnet,
          source: "article"
        }, null, _parent));
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      if (articleContent.value.hasMarker && articleContent.value.after) {
        _push(`<div class="mx-auto mt-[72px] w-full max-w-[831px]"><div class="${ssrRenderClass([isFigmaArticle.value ? "figma-article-prose" : "", "rich-prose rich-prose-article"])}">${articleContent.value.after ?? ""}</div></div>`);
      } else {
        _push(`<!---->`);
      }
      if (props.leadMagnet && !articleContent.value.hasMarker && !isFigmaArticle.value) {
        _push(`<div class="mx-auto mt-[72px] w-full max-w-[826px]">`);
        _push(ssrRenderComponent(_sfc_main$4, {
          section: props.leadMagnet,
          source: "article"
        }, null, _parent));
        _push(`</div>`);
      } else {
        _push(`<!---->`);
      }
      if (props.post.related.length) {
        _push(`<section class="${ssrRenderClass([isFigmaArticle.value ? "mt-[244px]" : "mt-[81px]", "flex flex-col gap-12"])}">`);
        if (!isFigmaArticle.value) {
          _push(`<h2 class="relative w-fit text-display-sm font-semibold text-ink-50">${ssrInterpolate(unref(t)("blog.related"))} <img${ssrRenderAttr("src", unref(relatedRuleUrl))} alt="" aria-hidden="true" width="188" height="5" class="pointer-events-none absolute inline-end-0 top-full mt-1 hidden w-32 sm:block"></h2>`);
        } else {
          _push(`<!---->`);
        }
        _push(`<div class="${ssrRenderClass([isFigmaArticle.value ? "max-md:flex max-md:w-max" : "", "grid gap-6 sm:grid-cols-2 lg:grid-cols-3"])}"${ssrRenderAttr("data-reveal-group", !isFigmaArticle.value ? true : void 0)}><!--[-->`);
        ssrRenderList(isFigmaArticle.value ? figmaRelatedPosts.value : props.post.related, (related) => {
          _push(ssrRenderComponent(_sfc_main$5, {
            key: related.slug,
            post: related,
            class: isFigmaArticle.value ? "max-md:w-[262px]" : ""
          }, null, _parent));
        });
        _push(`<!--]--></div></section>`);
      } else {
        _push(`<!---->`);
      }
      if (props.finalCta && !isFigmaArticle.value) {
        _push(ssrRenderComponent(_sfc_main$6, {
          section: props.finalCta,
          class: "mt-[224px]",
          "data-reveal": ""
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`</div></article><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Insights/Show.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
