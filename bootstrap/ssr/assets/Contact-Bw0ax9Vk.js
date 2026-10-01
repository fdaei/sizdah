import { defineComponent, computed, mergeProps, useSSRContext, useModel, ref, unref } from "vue";
import { ssrRenderAttrs, ssrInterpolate, ssrRenderAttr, ssrRenderClass, ssrRenderStyle, ssrRenderList, ssrRenderComponent, ssrIncludeBooleanAttr, ssrLooseContain } from "vue/server-renderer";
import { usePage, useForm } from "@inertiajs/vue3";
import "ziggy-js";
import { _ as _sfc_main$4 } from "./Eyebrow-BY5jAbbp.js";
import { _ as _sfc_main$3 } from "./SeoHead-B0PBocbK.js";
import { x as xUrl, l as linkedinUrl, r as relatedRuleUrl } from "./related-rule-H34RaeRD.js";
import { u as useTranslations } from "../ssr.js";
import { r as retentionUrl } from "./kpi-retention-JYaRFNgy.js";
import { u as userFieldUrl } from "./field-user-tphddZ0v.js";
import "@inertiajs/vue3/server";
const instagramUrl = "/build/assets/instagram-CYSz5x0b.svg";
const whatsappUrl = "/build/assets/whatsapp-I7Gfo290.svg";
const telegramUrl = "/build/assets/telegram-B4VjByNk.svg";
const youtubeUrl = "/build/assets/youtube-CEBi89S1.svg";
const _sfc_main$2 = /* @__PURE__ */ defineComponent({
  __name: "SocialIcon",
  __ssrInlineRender: true,
  props: {
    link: {}
  },
  setup(__props) {
    const props = __props;
    const glyphs = {
      instagram: instagramUrl,
      linkedin: linkedinUrl,
      whatsapp: whatsappUrl,
      telegram: telegramUrl,
      x: xUrl,
      twitter: xUrl,
      youtube: youtubeUrl
    };
    const glyph = computed(() => glyphs[props.link.platform.toLowerCase()]);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<a${ssrRenderAttrs(mergeProps({
        href: props.link.url,
        target: "_blank",
        rel: "noopener noreferrer",
        class: "group relative inline-flex size-14 shrink-0 items-center justify-center rounded-lg"
      }, _attrs))}><span aria-hidden="true" class="sketch-frame-social pointer-events-none absolute inset-0 bg-ink-400 transition-colors duration-200 ease-brand group-hover:bg-brand"></span><span class="sr-only">${ssrInterpolate(props.link.label)}</span>`);
      if (glyph.value) {
        _push(`<img${ssrRenderAttr("src", glyph.value)} alt="" aria-hidden="true" width="24" class="relative block h-auto w-6">`);
      } else {
        _push(`<span aria-hidden="true" class="relative text-title-md text-paper">${ssrInterpolate(props.link.label.charAt(0))}</span>`);
      }
      _push(`</a>`);
    };
  }
});
const _sfc_setup$2 = _sfc_main$2.setup;
_sfc_main$2.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/SocialIcon.vue");
  return _sfc_setup$2 ? _sfc_setup$2(props, ctx) : void 0;
};
const __vite_glob_0_0 = "/build/assets/ad-B18i8NGa.svg";
const __vite_glob_0_1 = "/build/assets/ae-CZRtWSox.svg";
const __vite_glob_0_2 = "/build/assets/af-C77Rf6cE.svg";
const __vite_glob_0_3 = "/build/assets/ag-C8MykuG2.svg";
const __vite_glob_0_4 = "/build/assets/ai-Dmedkf4v.svg";
const __vite_glob_0_5 = "/build/assets/al-10QRkakw.svg";
const __vite_glob_0_6 = "/build/assets/am-DMt4_dd4.svg";
const __vite_glob_0_7 = "/build/assets/ao-tXuRa6vm.svg";
const __vite_glob_0_8 = "/build/assets/aq-CF5jO-0h.svg";
const __vite_glob_0_9 = "/build/assets/ar-Be8Ju1cG.svg";
const __vite_glob_0_10 = "/build/assets/arab-C4CYPgyC.svg";
const __vite_glob_0_11 = "/build/assets/as-Dekqy8Of.svg";
const __vite_glob_0_12 = "/build/assets/asean-WMtZ-US_.svg";
const __vite_glob_0_13 = "/build/assets/at-DGA_6m5E.svg";
const __vite_glob_0_14 = "/build/assets/au-DAHDIuPI.svg";
const __vite_glob_0_15 = "/build/assets/aw-W0PWLK5p.svg";
const __vite_glob_0_16 = "/build/assets/ax-DvLIy84U.svg";
const __vite_glob_0_17 = "/build/assets/az-Bk-bYNxy.svg";
const __vite_glob_0_18 = "/build/assets/ba-WdDiSMvP.svg";
const __vite_glob_0_19 = "/build/assets/bb-DJxbaxmT.svg";
const __vite_glob_0_20 = "/build/assets/bd-BF9t1-60.svg";
const __vite_glob_0_21 = "/build/assets/be-CLLkK3PN.svg";
const __vite_glob_0_22 = "/build/assets/bf-YclsoDuF.svg";
const __vite_glob_0_23 = "/build/assets/bg-GUQenraa.svg";
const __vite_glob_0_24 = "/build/assets/bh-BQqEGq6F.svg";
const __vite_glob_0_25 = "/build/assets/bi-CRmKY6RQ.svg";
const __vite_glob_0_26 = "/build/assets/bj-14PhO9bM.svg";
const __vite_glob_0_27 = "/build/assets/bl-4CI2YcwX.svg";
const __vite_glob_0_28 = "/build/assets/bm-BeYgB2z9.svg";
const __vite_glob_0_29 = "/build/assets/bn-B6T3O78g.svg";
const __vite_glob_0_30 = "/build/assets/bo-CcUiMqkJ.svg";
const __vite_glob_0_31 = "/build/assets/bq-BYpdxEeT.svg";
const __vite_glob_0_32 = "/build/assets/br-Cu5YU29T.svg";
const __vite_glob_0_33 = "/build/assets/bs-7Gd_oriM.svg";
const __vite_glob_0_34 = "/build/assets/bt-BTo4qm10.svg";
const __vite_glob_0_35 = "/build/assets/bv-wM9JLv4R.svg";
const __vite_glob_0_36 = "/build/assets/bw-n5ZaAnGL.svg";
const __vite_glob_0_37 = "/build/assets/by-C621sBpd.svg";
const __vite_glob_0_38 = "/build/assets/bz-BCKHR4_q.svg";
const __vite_glob_0_39 = "/build/assets/ca-PYUrLVUV.svg";
const __vite_glob_0_40 = "/build/assets/cc-BNT6Xjzk.svg";
const __vite_glob_0_41 = "/build/assets/cd-BGclsrP6.svg";
const __vite_glob_0_42 = "/build/assets/cefta-2dDBYygd.svg";
const __vite_glob_0_43 = "/build/assets/cf-DRetLmp-.svg";
const __vite_glob_0_44 = "/build/assets/cg-CwIyG6SE.svg";
const __vite_glob_0_45 = "/build/assets/ch-sfriZoF1.svg";
const __vite_glob_0_46 = "/build/assets/ci-C8Q8IYTn.svg";
const __vite_glob_0_47 = "/build/assets/ck-DfXMUOTo.svg";
const __vite_glob_0_48 = "/build/assets/cl-BgYYb4qP.svg";
const __vite_glob_0_49 = "/build/assets/cm-D4yjdmKT.svg";
const __vite_glob_0_50 = "/build/assets/cn-DifnnI3t.svg";
const __vite_glob_0_51 = "/build/assets/co-DV591zMm.svg";
const __vite_glob_0_52 = "/build/assets/cp-K_ay05Q_.svg";
const __vite_glob_0_53 = "/build/assets/cr-BlYVN-_Q.svg";
const __vite_glob_0_54 = "/build/assets/cu-L6XVZNgo.svg";
const __vite_glob_0_55 = "/build/assets/cv-CPsfcOfk.svg";
const __vite_glob_0_56 = "/build/assets/cw-BbrnximR.svg";
const __vite_glob_0_57 = "/build/assets/cx-DpYD6n6U.svg";
const __vite_glob_0_58 = "/build/assets/cy-bZuP8hmf.svg";
const __vite_glob_0_59 = "/build/assets/cz-WWBC5Aeb.svg";
const __vite_glob_0_60 = "/build/assets/de-B-2o-4Z9.svg";
const __vite_glob_0_61 = "/build/assets/dg-CJPJrjiZ.svg";
const __vite_glob_0_62 = "/build/assets/dj-hp_BwbmO.svg";
const __vite_glob_0_63 = "/build/assets/dk-DmS9BCZB.svg";
const __vite_glob_0_64 = "/build/assets/dm-Cbhezfe1.svg";
const __vite_glob_0_65 = "/build/assets/do-B86d445t.svg";
const __vite_glob_0_66 = "/build/assets/dz-Dytc1TFu.svg";
const __vite_glob_0_67 = "/build/assets/eac-CwGQsyAM.svg";
const __vite_glob_0_68 = "/build/assets/ec-CaVOFQ3t.svg";
const __vite_glob_0_69 = "/build/assets/ee-DufrxGIR.svg";
const __vite_glob_0_70 = "/build/assets/eg-YC70hswZ.svg";
const __vite_glob_0_71 = "/build/assets/eh-0awM4TVj.svg";
const __vite_glob_0_72 = "/build/assets/er-X83uml6t.svg";
const __vite_glob_0_73 = "/build/assets/es-ct-CVyhLp7O.svg";
const __vite_glob_0_74 = "/build/assets/es-ga-D9xG2hYr.svg";
const __vite_glob_0_75 = "/build/assets/es-pv-CO3NM2SE.svg";
const __vite_glob_0_76 = "/build/assets/es-d5m8M5h8.svg";
const __vite_glob_0_77 = "/build/assets/et-DwdlzOIx.svg";
const __vite_glob_0_78 = "/build/assets/eu-Brdgz8ab.svg";
const __vite_glob_0_79 = "/build/assets/fi-DWUIkfjL.svg";
const __vite_glob_0_80 = "/build/assets/fj-DEAVMg38.svg";
const __vite_glob_0_81 = "/build/assets/fk-nuUF_Ak3.svg";
const __vite_glob_0_82 = "/build/assets/fm-B4Z83GL0.svg";
const __vite_glob_0_83 = "/build/assets/fo-DFjwyWur.svg";
const __vite_glob_0_84 = "/build/assets/fr-DVvUyOqI.svg";
const __vite_glob_0_85 = "/build/assets/ga-BklUhLH_.svg";
const __vite_glob_0_86 = "/build/assets/gb-eng-C8iDhGHN.svg";
const __vite_glob_0_87 = "/build/assets/gb-nir-D4gikpNq.svg";
const __vite_glob_0_88 = "/build/assets/gb-sct-fW5q01ek.svg";
const __vite_glob_0_89 = "/build/assets/gb-wls-Bxz9hxvX.svg";
const __vite_glob_0_90 = "/build/assets/gb-DTXiLQoe.svg";
const __vite_glob_0_91 = "/build/assets/gd-CO-whzUe.svg";
const __vite_glob_0_92 = "/build/assets/ge-B2RiL-Ih.svg";
const __vite_glob_0_93 = "/build/assets/gf-DWl5zcw0.svg";
const __vite_glob_0_94 = "/build/assets/gg-jdOQS2nU.svg";
const __vite_glob_0_95 = "/build/assets/gh-r3LP_X7q.svg";
const __vite_glob_0_96 = "/build/assets/gi-BJKE9SzW.svg";
const __vite_glob_0_97 = "/build/assets/gl-CHaBnMib.svg";
const __vite_glob_0_98 = "/build/assets/gm-Bu99atwn.svg";
const __vite_glob_0_99 = "/build/assets/gn-1dJNy9oQ.svg";
const __vite_glob_0_100 = "/build/assets/gp-DNyt_wTA.svg";
const __vite_glob_0_101 = "/build/assets/gq-Cag8QTk2.svg";
const __vite_glob_0_102 = "/build/assets/gr-C5PU0p9p.svg";
const __vite_glob_0_103 = "/build/assets/gs-DiiNa0F5.svg";
const __vite_glob_0_104 = "/build/assets/gt-CJo5DI-7.svg";
const __vite_glob_0_105 = "/build/assets/gu-Di1JYREk.svg";
const __vite_glob_0_106 = "/build/assets/gw-D249VY33.svg";
const __vite_glob_0_107 = "/build/assets/gy-CcVYUM2E.svg";
const __vite_glob_0_108 = "/build/assets/hk-CUNIaSX0.svg";
const __vite_glob_0_109 = "/build/assets/hm-Dh6t_Tj3.svg";
const __vite_glob_0_110 = "/build/assets/hn-CRjyS_bm.svg";
const __vite_glob_0_111 = "/build/assets/hr-fzLfaANM.svg";
const __vite_glob_0_112 = "/build/assets/ht-DIMg4gti.svg";
const __vite_glob_0_113 = "/build/assets/hu-7Q5wwIIi.svg";
const __vite_glob_0_114 = "/build/assets/ic-CSo4d8tH.svg";
const __vite_glob_0_115 = "/build/assets/id-DiSP6Fmm.svg";
const __vite_glob_0_116 = "/build/assets/ie-ChAXClx3.svg";
const __vite_glob_0_117 = "/build/assets/il-_56OEGLa.svg";
const __vite_glob_0_118 = "/build/assets/im--VPIqfkF.svg";
const __vite_glob_0_119 = "/build/assets/in-Cdwu6Bq7.svg";
const __vite_glob_0_120 = "/build/assets/io-13HOfeJD.svg";
const __vite_glob_0_121 = "/build/assets/iq-Dp8HDzo2.svg";
const __vite_glob_0_122 = "/build/assets/ir-cCIgaNf6.svg";
const __vite_glob_0_123 = "/build/assets/is-CZjefTNV.svg";
const __vite_glob_0_124 = "/build/assets/it-Br7q0Zh6.svg";
const __vite_glob_0_125 = "/build/assets/je-DyWbhIiC.svg";
const __vite_glob_0_126 = "/build/assets/jm-CItSr3iX.svg";
const __vite_glob_0_127 = "/build/assets/jo-BAF1FGbm.svg";
const __vite_glob_0_128 = "/build/assets/jp-BIMmfxpO.svg";
const __vite_glob_0_129 = "/build/assets/ke-C8foqndp.svg";
const __vite_glob_0_130 = "/build/assets/kg-B0FsxZiL.svg";
const __vite_glob_0_131 = "/build/assets/kh-BeWfuE30.svg";
const __vite_glob_0_132 = "/build/assets/ki-p_fAQGbS.svg";
const __vite_glob_0_133 = "/build/assets/km-B5tqtGG7.svg";
const __vite_glob_0_134 = "/build/assets/kn-DVPxDkNY.svg";
const __vite_glob_0_135 = "/build/assets/kp-CrDKzoxe.svg";
const __vite_glob_0_136 = "/build/assets/kr-BCXH1Hao.svg";
const __vite_glob_0_137 = "/build/assets/kw-fSBzmd30.svg";
const __vite_glob_0_138 = "/build/assets/ky-Dpsu1myA.svg";
const __vite_glob_0_139 = "/build/assets/kz-CwKXYZ8s.svg";
const __vite_glob_0_140 = "/build/assets/la-CuJflhIW.svg";
const __vite_glob_0_141 = "/build/assets/lb-BSjpYEoo.svg";
const __vite_glob_0_142 = "/build/assets/lc-CNvab8Ae.svg";
const __vite_glob_0_143 = "/build/assets/li-CHdhvNcr.svg";
const __vite_glob_0_144 = "/build/assets/lk-DUkgV9Tq.svg";
const __vite_glob_0_145 = "/build/assets/lr-B84vu3Ds.svg";
const __vite_glob_0_146 = "/build/assets/ls-5Xk3Mxq5.svg";
const __vite_glob_0_147 = "/build/assets/lt-DoukV-Sm.svg";
const __vite_glob_0_148 = "/build/assets/lu-DOI02Msy.svg";
const __vite_glob_0_149 = "/build/assets/lv-C-KfY8Yc.svg";
const __vite_glob_0_150 = "/build/assets/ly-BWpTK3ux.svg";
const __vite_glob_0_151 = "/build/assets/ma-BTRNTRUj.svg";
const __vite_glob_0_152 = "/build/assets/mc-PK078JHl.svg";
const __vite_glob_0_153 = "/build/assets/md-DRlxvNwm.svg";
const __vite_glob_0_154 = "/build/assets/me-Cv4Gwqah.svg";
const __vite_glob_0_155 = "/build/assets/mf-BaAGWwAq.svg";
const __vite_glob_0_156 = "/build/assets/mg-C168LHXW.svg";
const __vite_glob_0_157 = "/build/assets/mh-gxuIp6Wk.svg";
const __vite_glob_0_158 = "/build/assets/mk-D9SIMr-a.svg";
const __vite_glob_0_159 = "/build/assets/ml-DVf6ujpi.svg";
const __vite_glob_0_160 = "/build/assets/mm-lwT09MQ0.svg";
const __vite_glob_0_161 = "/build/assets/mn-CgXyw0O9.svg";
const __vite_glob_0_162 = "/build/assets/mo-BAtCjuYA.svg";
const __vite_glob_0_163 = "/build/assets/mp-CrOApEqW.svg";
const __vite_glob_0_164 = "/build/assets/mq-BFnJ22KI.svg";
const __vite_glob_0_165 = "/build/assets/mr-D6G1wpeZ.svg";
const __vite_glob_0_166 = "/build/assets/ms-DxciGbUu.svg";
const __vite_glob_0_167 = "/build/assets/mt-YqzKx9xl.svg";
const __vite_glob_0_168 = "/build/assets/mu-mcq7cUFl.svg";
const __vite_glob_0_169 = "/build/assets/mv-BynAllfM.svg";
const __vite_glob_0_170 = "/build/assets/mw-C15nc1xZ.svg";
const __vite_glob_0_171 = "/build/assets/mx-Cc8Ccfe8.svg";
const __vite_glob_0_172 = "/build/assets/my-Co4JeeyE.svg";
const __vite_glob_0_173 = "/build/assets/mz-Drlr_USV.svg";
const __vite_glob_0_174 = "/build/assets/na-D79ffb4Z.svg";
const __vite_glob_0_175 = "/build/assets/nc-5j7wEmDR.svg";
const __vite_glob_0_176 = "/build/assets/ne-B1jPOYkl.svg";
const __vite_glob_0_177 = "/build/assets/nf-Dl00mlk2.svg";
const __vite_glob_0_178 = "/build/assets/ng-su4NM9If.svg";
const __vite_glob_0_179 = "/build/assets/ni-CcFCSQxm.svg";
const __vite_glob_0_180 = "/build/assets/nl-BnOa6UiA.svg";
const __vite_glob_0_181 = "/build/assets/no-qf2JPO73.svg";
const __vite_glob_0_182 = "/build/assets/np-CIuq5GKd.svg";
const __vite_glob_0_183 = "/build/assets/nr-DERIdzkN.svg";
const __vite_glob_0_184 = "/build/assets/nu-BfgWvGcd.svg";
const __vite_glob_0_185 = "/build/assets/nz-5vODdBjz.svg";
const __vite_glob_0_186 = "/build/assets/om-DcqxRdQL.svg";
const __vite_glob_0_187 = "/build/assets/pa-BLNN9G2-.svg";
const __vite_glob_0_188 = "/build/assets/pc-BJpYiA9b.svg";
const __vite_glob_0_189 = "/build/assets/pe-BLqhuu1C.svg";
const __vite_glob_0_190 = "/build/assets/pf-C8ahG68q.svg";
const __vite_glob_0_191 = "/build/assets/pg-BAYpbp9Z.svg";
const __vite_glob_0_192 = "/build/assets/ph-BEzTn62K.svg";
const __vite_glob_0_193 = "/build/assets/pk-CWMEc3ad.svg";
const __vite_glob_0_194 = "/build/assets/pl-o38JROoc.svg";
const __vite_glob_0_195 = "/build/assets/pm-CHiP5UmZ.svg";
const __vite_glob_0_196 = "/build/assets/pn-DgxdtieE.svg";
const __vite_glob_0_197 = "/build/assets/pr-B4tMV0xm.svg";
const __vite_glob_0_198 = "/build/assets/ps-DO8YKYeS.svg";
const __vite_glob_0_199 = "/build/assets/pt-DZ2ADgIR.svg";
const __vite_glob_0_200 = "/build/assets/pw-CQP52WMX.svg";
const __vite_glob_0_201 = "/build/assets/py-mNzh0mZC.svg";
const __vite_glob_0_202 = "/build/assets/qa-Dkmpc78M.svg";
const __vite_glob_0_203 = "/build/assets/re-Bk4ipYK1.svg";
const __vite_glob_0_204 = "/build/assets/ro-CoSeqKY1.svg";
const __vite_glob_0_205 = "/build/assets/rs-BfwKwXtn.svg";
const __vite_glob_0_206 = "/build/assets/ru-D-4tNwXt.svg";
const __vite_glob_0_207 = "/build/assets/rw-D7nbSYKI.svg";
const __vite_glob_0_208 = "/build/assets/sa-Dh79zbT9.svg";
const __vite_glob_0_209 = "/build/assets/sb-BDTYjcbk.svg";
const __vite_glob_0_210 = "/build/assets/sc-CRNsSLg9.svg";
const __vite_glob_0_211 = "/build/assets/sd-ClziNjGr.svg";
const __vite_glob_0_212 = "/build/assets/se-8C923vhy.svg";
const __vite_glob_0_213 = "/build/assets/sg-DGYIMG0G.svg";
const __vite_glob_0_214 = "/build/assets/sh-ac-FjwY7RYr.svg";
const __vite_glob_0_215 = "/build/assets/sh-hl-CqtQPzWZ.svg";
const __vite_glob_0_216 = "/build/assets/sh-ta-CPJublpi.svg";
const __vite_glob_0_217 = "/build/assets/sh-DNImvbrE.svg";
const __vite_glob_0_218 = "/build/assets/si-BMKT-Tec.svg";
const __vite_glob_0_219 = "/build/assets/sj-BFFEGknm.svg";
const __vite_glob_0_220 = "/build/assets/sk-K9BNIYAO.svg";
const __vite_glob_0_221 = "/build/assets/sl-DxLJY5vJ.svg";
const __vite_glob_0_222 = "/build/assets/sm-DGBIRFB_.svg";
const __vite_glob_0_223 = "/build/assets/sn-S8ipNF1U.svg";
const __vite_glob_0_224 = "/build/assets/so-DlzA2Fco.svg";
const __vite_glob_0_225 = "/build/assets/sr-Co7OKBh3.svg";
const __vite_glob_0_226 = "/build/assets/ss-CxVEpdPD.svg";
const __vite_glob_0_227 = "/build/assets/st-C1Nd1c3V.svg";
const __vite_glob_0_228 = "/build/assets/sv-CJIHhYwF.svg";
const __vite_glob_0_229 = "/build/assets/sx-nDhIaDNb.svg";
const __vite_glob_0_230 = "/build/assets/sy-DwSud114.svg";
const __vite_glob_0_231 = "/build/assets/sz-qxMwa2gs.svg";
const __vite_glob_0_232 = "/build/assets/tc-dtelpZmc.svg";
const __vite_glob_0_233 = "/build/assets/td-BsuVhZpT.svg";
const __vite_glob_0_234 = "/build/assets/tf-Co33RhQH.svg";
const __vite_glob_0_235 = "/build/assets/tg-CP1-sc35.svg";
const __vite_glob_0_236 = "/build/assets/th-tzq84hgd.svg";
const __vite_glob_0_237 = "/build/assets/tj-b-aWfOTb.svg";
const __vite_glob_0_238 = "/build/assets/tk-CDucsEss.svg";
const __vite_glob_0_239 = "/build/assets/tl-wpo93AGk.svg";
const __vite_glob_0_240 = "/build/assets/tm-C_WSgUcv.svg";
const __vite_glob_0_241 = "/build/assets/tn-BcKCZULf.svg";
const __vite_glob_0_242 = "/build/assets/to-D8uVsoxb.svg";
const __vite_glob_0_243 = "/build/assets/tr-Cd6FO9Bg.svg";
const __vite_glob_0_244 = "/build/assets/tt-CTnr7aY5.svg";
const __vite_glob_0_245 = "/build/assets/tv-DDqkVT-n.svg";
const __vite_glob_0_246 = "/build/assets/tw-d-Mf-0VT.svg";
const __vite_glob_0_247 = "/build/assets/tz-BjLtHeil.svg";
const __vite_glob_0_248 = "/build/assets/ua-Bq0XgQqK.svg";
const __vite_glob_0_249 = "/build/assets/ug-ByL2ejGl.svg";
const __vite_glob_0_250 = "/build/assets/um-Bhke9Eic.svg";
const __vite_glob_0_251 = "/build/assets/un-Bqg4Cbbh.svg";
const __vite_glob_0_252 = "/build/assets/us-C73uVeEr.svg";
const __vite_glob_0_253 = "/build/assets/uy-DD6peej-.svg";
const __vite_glob_0_254 = "/build/assets/uz-C2f-Cubn.svg";
const __vite_glob_0_255 = "/build/assets/va-B9-hqIE-.svg";
const __vite_glob_0_256 = "/build/assets/vc-COpmFovN.svg";
const __vite_glob_0_257 = "/build/assets/ve-BSqnIB9l.svg";
const __vite_glob_0_258 = "/build/assets/vg-C7xY6pic.svg";
const __vite_glob_0_259 = "/build/assets/vi-BC_zcciE.svg";
const __vite_glob_0_260 = "/build/assets/vn-BEAEijd0.svg";
const __vite_glob_0_261 = "/build/assets/vu-D6k0NQlg.svg";
const __vite_glob_0_262 = "/build/assets/wf-DrxpOO_G.svg";
const __vite_glob_0_263 = "/build/assets/ws-vzJNwdVm.svg";
const __vite_glob_0_264 = "/build/assets/xk-Bj15g7cp.svg";
const __vite_glob_0_265 = "/build/assets/xx-zm_JmrXl.svg";
const __vite_glob_0_266 = "/build/assets/ye-BwST9gXC.svg";
const __vite_glob_0_267 = "/build/assets/yt-DIfEG0ex.svg";
const __vite_glob_0_268 = "/build/assets/za-Jz40JTrv.svg";
const __vite_glob_0_269 = "/build/assets/zm-BmsW91ne.svg";
const __vite_glob_0_270 = "/build/assets/zw-U0m7oJ5e.svg";
const countries = [
  { iso2: "AW", dialCode: "+297", nameFa: "آروبا", nameEn: "Aruba" },
  { iso2: "AR", dialCode: "+54", nameFa: "آرژانتین", nameEn: "Argentina" },
  { iso2: "ZA", dialCode: "+27", nameFa: "آفریقای جنوبی", nameEn: "South Africa" },
  { iso2: "AL", dialCode: "+355", nameFa: "آلبانی", nameEn: "Albania" },
  { iso2: "DE", dialCode: "+49", nameFa: "آلمان", nameEn: "Germany" },
  { iso2: "AG", dialCode: "+1268", nameFa: "آنتیگوا و باربودا", nameEn: "Antigua and Barbuda" },
  { iso2: "AD", dialCode: "+376", nameFa: "آندورا", nameEn: "Andorra" },
  { iso2: "AO", dialCode: "+244", nameFa: "آنگولا", nameEn: "Angola" },
  { iso2: "AI", dialCode: "+1264", nameFa: "آنگویلا", nameEn: "Anguilla" },
  { iso2: "KM", dialCode: "+269", nameFa: "اتحاد قُمُر", nameEn: "Comoros" },
  { iso2: "AT", dialCode: "+43", nameFa: "اتریش", nameEn: "Austria" },
  { iso2: "JO", dialCode: "+962", nameFa: "اردن", nameEn: "Jordan" },
  { iso2: "AM", dialCode: "+374", nameFa: "ارمنستان", nameEn: "Armenia" },
  { iso2: "UY", dialCode: "+598", nameFa: "اروگوئه", nameEn: "Uruguay" },
  { iso2: "ER", dialCode: "+291", nameFa: "اریتره", nameEn: "Eritrea" },
  { iso2: "UZ", dialCode: "+998", nameFa: "ازبکستان", nameEn: "Uzbekistan" },
  { iso2: "AU", dialCode: "+61", nameFa: "استرالیا", nameEn: "Australia" },
  { iso2: "SI", dialCode: "+386", nameFa: "اسلوونی", nameEn: "Slovenia" },
  { iso2: "SZ", dialCode: "+268", nameFa: "اسواتینی", nameEn: "Eswatini" },
  { iso2: "ES", dialCode: "+34", nameFa: "اسپانیا", nameEn: "Spain" },
  { iso2: "AF", dialCode: "+93", nameFa: "افغانستان", nameEn: "Afghanistan" },
  { iso2: "DZ", dialCode: "+213", nameFa: "الجزایر", nameEn: "Algeria" },
  { iso2: "SV", dialCode: "+503", nameFa: "السالوادور", nameEn: "El Salvador" },
  { iso2: "AE", dialCode: "+971", nameFa: "امارات", nameEn: "United Arab Emirates" },
  { iso2: "ID", dialCode: "+62", nameFa: "اندونزی", nameEn: "Indonesia" },
  { iso2: "GB", dialCode: "+44", nameFa: "انگلیس", nameEn: "United Kingdom" },
  { iso2: "UA", dialCode: "+380", nameFa: "اوکراین", nameEn: "Ukraine" },
  { iso2: "UG", dialCode: "+256", nameFa: "اوگاندا", nameEn: "Uganda" },
  { iso2: "ET", dialCode: "+251", nameFa: "اِتیوپی", nameEn: "Ethiopia" },
  { iso2: "EE", dialCode: "+372", nameFa: "اِستونی", nameEn: "Estonia" },
  { iso2: "SK", dialCode: "+421", nameFa: "اِسلُواکی", nameEn: "Slovakia" },
  { iso2: "EC", dialCode: "+593", nameFa: "اکوادور", nameEn: "Ecuador" },
  { iso2: "US", dialCode: "+1", nameFa: "ایالات متحده آمریکا", nameEn: "United States" },
  { iso2: "IT", dialCode: "+39", nameFa: "ایتالیا", nameEn: "Italy" },
  { iso2: "IR", dialCode: "+98", nameFa: "ایران", nameEn: "Iran" },
  { iso2: "IE", dialCode: "+353", nameFa: "ایرلند", nameEn: "Ireland" },
  { iso2: "IS", dialCode: "+354", nameFa: "ایسلند", nameEn: "Iceland" },
  { iso2: "BB", dialCode: "+1246", nameFa: "باربادوس", nameEn: "Barbados" },
  { iso2: "BS", dialCode: "+1242", nameFa: "باهاما", nameEn: "Bahamas" },
  { iso2: "BH", dialCode: "+973", nameFa: "بحرین", nameEn: "Bahrain" },
  { iso2: "BR", dialCode: "+55", nameFa: "برزیل", nameEn: "Brazil" },
  { iso2: "BM", dialCode: "+1441", nameFa: "برمودا", nameEn: "Bermuda" },
  { iso2: "BN", dialCode: "+673", nameFa: "برونئی", nameEn: "Brunei" },
  { iso2: "BY", dialCode: "+375", nameFa: "بلاروس", nameEn: "Belarus" },
  { iso2: "BG", dialCode: "+359", nameFa: "بلغارستان", nameEn: "Bulgaria" },
  { iso2: "BE", dialCode: "+32", nameFa: "بلژیک", nameEn: "Belgium" },
  { iso2: "BZ", dialCode: "+501", nameFa: "بلیز", nameEn: "Belize" },
  { iso2: "BD", dialCode: "+880", nameFa: "بنگلادش", nameEn: "Bangladesh" },
  { iso2: "BJ", dialCode: "+229", nameFa: "بنین", nameEn: "Benin" },
  { iso2: "BT", dialCode: "+975", nameFa: "بوتان", nameEn: "Bhutan" },
  { iso2: "BW", dialCode: "+267", nameFa: "بوتسوانا", nameEn: "Botswana" },
  { iso2: "BI", dialCode: "+257", nameFa: "بوروندی", nameEn: "Burundi" },
  { iso2: "BF", dialCode: "+226", nameFa: "بورکینافاسو", nameEn: "Burkina Faso" },
  { iso2: "BA", dialCode: "+387", nameFa: "بوسنی و هرزگوین", nameEn: "Bosnia and Herzegovina" },
  { iso2: "BO", dialCode: "+591", nameFa: "بولیوی", nameEn: "Bolivia" },
  { iso2: "TJ", dialCode: "+992", nameFa: "تاجیکِستان", nameEn: "Tajikistan" },
  { iso2: "TZ", dialCode: "+255", nameFa: "تانزانیا", nameEn: "Tanzania" },
  { iso2: "TH", dialCode: "+66", nameFa: "تایلند", nameEn: "Thailand" },
  { iso2: "TW", dialCode: "+886", nameFa: "تایوان", nameEn: "Taiwan" },
  { iso2: "TM", dialCode: "+993", nameFa: "ترکمنستان", nameEn: "Turkmenistan" },
  { iso2: "TR", dialCode: "+90", nameFa: "ترکیه", nameEn: "Türkiye" },
  { iso2: "TT", dialCode: "+1868", nameFa: "ترینیداد و توباگو", nameEn: "Trinidad and Tobago" },
  { iso2: "TN", dialCode: "+216", nameFa: "تونس", nameEn: "Tunisia" },
  { iso2: "TO", dialCode: "+676", nameFa: "تونگا", nameEn: "Tonga" },
  { iso2: "TV", dialCode: "+688", nameFa: "تووالو", nameEn: "Tuvalu" },
  { iso2: "TK", dialCode: "+690", nameFa: "توکلائو", nameEn: "Tokelau" },
  { iso2: "TG", dialCode: "+228", nameFa: "توگو", nameEn: "Togo" },
  { iso2: "TL", dialCode: "+670", nameFa: "تیمور شرقی", nameEn: "Timor-Leste" },
  { iso2: "JM", dialCode: "+1876", nameFa: "جامائیکا", nameEn: "Jamaica" },
  { iso2: "GI", dialCode: "+350", nameFa: "جبل طارق", nameEn: "Gibraltar" },
  { iso2: "JE", dialCode: "+44", nameFa: "جرزی", nameEn: "Jersey" },
  { iso2: "AX", dialCode: "+35818", nameFa: "جزایر الند", nameEn: "Åland Islands" },
  { iso2: "TC", dialCode: "+1649", nameFa: "جزایر تورکس و کایکوس", nameEn: "Turks and Caicos Islands" },
  { iso2: "GS", dialCode: "+500", nameFa: "جزایر جورجیای جنوبی و ساندویچ جنوبی", nameEn: "South Georgia" },
  { iso2: "SB", dialCode: "+677", nameFa: "جزایر سلیمان", nameEn: "Solomon Islands" },
  { iso2: "FO", dialCode: "+298", nameFa: "جزایر فاروئه", nameEn: "Faroe Islands" },
  { iso2: "FK", dialCode: "+500", nameFa: "جزایر فالکلند", nameEn: "Falkland Islands" },
  { iso2: "MH", dialCode: "+692", nameFa: "جزایر مارشال", nameEn: "Marshall Islands" },
  { iso2: "MP", dialCode: "+1670", nameFa: "جزایر ماریانای شمالی", nameEn: "Northern Mariana Islands" },
  { iso2: "VI", dialCode: "+1340", nameFa: "جزایر ویرجین ایالات متحده آمریکا", nameEn: "United States Virgin Islands" },
  { iso2: "VG", dialCode: "+1284", nameFa: "جزایر ویرجین بریتانیا", nameEn: "British Virgin Islands" },
  { iso2: "PN", dialCode: "+64", nameFa: "جزایر پیت‌کرن", nameEn: "Pitcairn Islands" },
  { iso2: "BQ", dialCode: "+599", nameFa: "جزایر کارائیب هلند", nameEn: "Caribbean Netherlands" },
  { iso2: "UM", dialCode: "+268", nameFa: "جزایر کوچک حاشیه‌ای ایالات متحده آمریکا", nameEn: "United States Minor Outlying Islands" },
  { iso2: "CK", dialCode: "+682", nameFa: "جزایر کوک", nameEn: "Cook Islands" },
  { iso2: "CC", dialCode: "+61", nameFa: "جزایر کوکوس", nameEn: "Cocos (Keeling) Islands" },
  { iso2: "KY", dialCode: "+1345", nameFa: "جزایر کیمن", nameEn: "Cayman Islands" },
  { iso2: "NF", dialCode: "+672", nameFa: "جزیره نورفک", nameEn: "Norfolk Island" },
  { iso2: "BV", dialCode: "+47", nameFa: "جزیرهٔ بووه", nameEn: "Bouvet Island" },
  { iso2: "IM", dialCode: "+44", nameFa: "جزیرهٔ مَن", nameEn: "Isle of Man" },
  { iso2: "CX", dialCode: "+61", nameFa: "جزیرهٔ کریسمس", nameEn: "Christmas Island" },
  { iso2: "AZ", dialCode: "+994", nameFa: "جمهوری آذربایجان", nameEn: "Azerbaijan" },
  { iso2: "CF", dialCode: "+236", nameFa: "جمهوری آفریقای مرکزی", nameEn: "Central African Republic" },
  { iso2: "DO", dialCode: "+1", nameFa: "جمهوری دومینیکن", nameEn: "Dominican Republic" },
  { iso2: "CZ", dialCode: "+420", nameFa: "جمهوری چک", nameEn: "Czechia" },
  { iso2: "CG", dialCode: "+242", nameFa: "جمهوری کُنگو", nameEn: "Congo" },
  { iso2: "DJ", dialCode: "+253", nameFa: "جیبوتی", nameEn: "Djibouti" },
  { iso2: "DK", dialCode: "+45", nameFa: "دانمارک", nameEn: "Denmark" },
  { iso2: "CV", dialCode: "+238", nameFa: "دماغهٔ سبز", nameEn: "Cape Verde" },
  { iso2: "DM", dialCode: "+1767", nameFa: "دومینیکا", nameEn: "Dominica" },
  { iso2: "RE", dialCode: "+262", nameFa: "رئونیون", nameEn: "Réunion" },
  { iso2: "RW", dialCode: "+250", nameFa: "رواندا", nameEn: "Rwanda" },
  { iso2: "RU", dialCode: "+7", nameFa: "روسیه", nameEn: "Russia" },
  { iso2: "RO", dialCode: "+40", nameFa: "رومانی", nameEn: "Romania" },
  { iso2: "ZM", dialCode: "+260", nameFa: "زامبیا", nameEn: "Zambia" },
  { iso2: "ZW", dialCode: "+263", nameFa: "زیمبابوه", nameEn: "Zimbabwe" },
  { iso2: "ST", dialCode: "+239", nameFa: "سائوتومه و پرنسیپ", nameEn: "São Tomé and Príncipe" },
  { iso2: "CI", dialCode: "+225", nameFa: "ساحل عاج", nameEn: "Ivory Coast" },
  { iso2: "WS", dialCode: "+685", nameFa: "ساموآ", nameEn: "Samoa" },
  { iso2: "AS", dialCode: "+1684", nameFa: "ساموآی آمریکا", nameEn: "American Samoa" },
  { iso2: "SM", dialCode: "+378", nameFa: "سان مارینو", nameEn: "San Marino" },
  { iso2: "TF", dialCode: "+262", nameFa: "سرزمین‌های جنوبی و جنوبگانی فرانسه", nameEn: "French Southern and Antarctic Lands" },
  { iso2: "LK", dialCode: "+94", nameFa: "سری‌لانکا", nameEn: "Sri Lanka" },
  { iso2: "BL", dialCode: "+590", nameFa: "سن بارتلمی", nameEn: "Saint Barthélemy" },
  { iso2: "MF", dialCode: "+590", nameFa: "سن مارتن", nameEn: "Saint Martin" },
  { iso2: "SX", dialCode: "+1721", nameFa: "سن مارتن", nameEn: "Sint Maarten" },
  { iso2: "PM", dialCode: "+508", nameFa: "سن-پیِر و میکلُن", nameEn: "Saint Pierre and Miquelon" },
  { iso2: "LC", dialCode: "+1758", nameFa: "سنت لوسیا", nameEn: "Saint Lucia" },
  { iso2: "SH", dialCode: "+2", nameFa: "سنت هلن", nameEn: "Saint Helena, Ascension and Tristan da Cunha" },
  { iso2: "VC", dialCode: "+1784", nameFa: "سنت وینسنت و گرنادین‌ها", nameEn: "Saint Vincent and the Grenadines" },
  { iso2: "KN", dialCode: "+1869", nameFa: "سنت کیتس و نویس", nameEn: "Saint Kitts and Nevis" },
  { iso2: "SN", dialCode: "+221", nameFa: "سنگال", nameEn: "Senegal" },
  { iso2: "SG", dialCode: "+65", nameFa: "سنگاپور", nameEn: "Singapore" },
  { iso2: "SE", dialCode: "+46", nameFa: "سوئد", nameEn: "Sweden" },
  { iso2: "CH", dialCode: "+41", nameFa: "سوئیس", nameEn: "Switzerland" },
  { iso2: "SJ", dialCode: "+4779", nameFa: "سوالبارد و یان ماین", nameEn: "Svalbard and Jan Mayen" },
  { iso2: "SD", dialCode: "+249", nameFa: "سودان", nameEn: "Sudan" },
  { iso2: "SS", dialCode: "+211", nameFa: "سودان جنوبی", nameEn: "South Sudan" },
  { iso2: "SR", dialCode: "+597", nameFa: "سورینام", nameEn: "Suriname" },
  { iso2: "SY", dialCode: "+963", nameFa: "سوریه", nameEn: "Syria" },
  { iso2: "SO", dialCode: "+252", nameFa: "سومالی", nameEn: "Somalia" },
  { iso2: "SL", dialCode: "+232", nameFa: "سیرالئون", nameEn: "Sierra Leone" },
  { iso2: "SC", dialCode: "+248", nameFa: "سیشل", nameEn: "Seychelles" },
  { iso2: "CL", dialCode: "+56", nameFa: "شیلی", nameEn: "Chile" },
  { iso2: "EH", dialCode: "+2", nameFa: "صحرای غربی", nameEn: "Western Sahara" },
  { iso2: "RS", dialCode: "+381", nameFa: "صربستان", nameEn: "Serbia" },
  { iso2: "IQ", dialCode: "+964", nameFa: "عراق", nameEn: "Iraq" },
  { iso2: "SA", dialCode: "+966", nameFa: "عربستان سعودی", nameEn: "Saudi Arabia" },
  { iso2: "OM", dialCode: "+968", nameFa: "عمان", nameEn: "Oman" },
  { iso2: "GH", dialCode: "+233", nameFa: "غنا", nameEn: "Ghana" },
  { iso2: "FR", dialCode: "+33", nameFa: "فرانسه", nameEn: "France" },
  { iso2: "IL", dialCode: "+972", nameFa: "فلسطين اشغالی", nameEn: "Israel" },
  { iso2: "PS", dialCode: "+970", nameFa: "فلسطین", nameEn: "Palestine" },
  { iso2: "FI", dialCode: "+358", nameFa: "فنلاند", nameEn: "Finland" },
  { iso2: "FJ", dialCode: "+679", nameFa: "فیجی", nameEn: "Fiji" },
  { iso2: "PH", dialCode: "+63", nameFa: "فیلیپین", nameEn: "Philippines" },
  { iso2: "KG", dialCode: "+996", nameFa: "قرقیزستان", nameEn: "Kyrgyzstan" },
  { iso2: "KZ", dialCode: "+7", nameFa: "قزاقستان", nameEn: "Kazakhstan" },
  { iso2: "QA", dialCode: "+974", nameFa: "قطر", nameEn: "Qatar" },
  { iso2: "IO", dialCode: "+246", nameFa: "قلمرو بریتانیا در اقیانوس هند", nameEn: "British Indian Ocean Territory" },
  { iso2: "CY", dialCode: "+357", nameFa: "قِبرِس", nameEn: "Cyprus" },
  { iso2: "LA", dialCode: "+856", nameFa: "لائوس", nameEn: "Laos" },
  { iso2: "LB", dialCode: "+961", nameFa: "لبنان", nameEn: "Lebanon" },
  { iso2: "LV", dialCode: "+371", nameFa: "لتونی", nameEn: "Latvia" },
  { iso2: "LS", dialCode: "+266", nameFa: "لسوتو", nameEn: "Lesotho" },
  { iso2: "PL", dialCode: "+48", nameFa: "لهستان", nameEn: "Poland" },
  { iso2: "LU", dialCode: "+352", nameFa: "لوکزامبورگ", nameEn: "Luxembourg" },
  { iso2: "LR", dialCode: "+231", nameFa: "لیبـِریا", nameEn: "Liberia" },
  { iso2: "LY", dialCode: "+218", nameFa: "لیبی", nameEn: "Libya" },
  { iso2: "LT", dialCode: "+370", nameFa: "لیتوانیایی‌ها", nameEn: "Lithuania" },
  { iso2: "LI", dialCode: "+423", nameFa: "لیختن‌اشتاین", nameEn: "Liechtenstein" },
  { iso2: "MG", dialCode: "+261", nameFa: "ماداگاسکار", nameEn: "Madagascar" },
  { iso2: "MQ", dialCode: "+596", nameFa: "مارتینیک", nameEn: "Martinique" },
  { iso2: "MW", dialCode: "+265", nameFa: "مالاوی", nameEn: "Malawi" },
  { iso2: "MT", dialCode: "+356", nameFa: "مالت", nameEn: "Malta" },
  { iso2: "MV", dialCode: "+960", nameFa: "مالدیو", nameEn: "Maldives" },
  { iso2: "MY", dialCode: "+60", nameFa: "مالزی", nameEn: "Malaysia" },
  { iso2: "ML", dialCode: "+223", nameFa: "مالی", nameEn: "Mali" },
  { iso2: "MO", dialCode: "+853", nameFa: "ماکائو", nameEn: "Macau" },
  { iso2: "YT", dialCode: "+262", nameFa: "مایوت", nameEn: "Mayotte" },
  { iso2: "HU", dialCode: "+36", nameFa: "مجارستان", nameEn: "Hungary" },
  { iso2: "MA", dialCode: "+212", nameFa: "مراکش", nameEn: "Morocco" },
  { iso2: "EG", dialCode: "+20", nameFa: "مصر", nameEn: "Egypt" },
  { iso2: "MN", dialCode: "+976", nameFa: "مغولستان", nameEn: "Mongolia" },
  { iso2: "MK", dialCode: "+389", nameFa: "مقدونیه شمالی", nameEn: "North Macedonia" },
  { iso2: "MR", dialCode: "+222", nameFa: "موریتانی", nameEn: "Mauritania" },
  { iso2: "MU", dialCode: "+230", nameFa: "موریس", nameEn: "Mauritius" },
  { iso2: "MZ", dialCode: "+258", nameFa: "موزامبیک", nameEn: "Mozambique" },
  { iso2: "MD", dialCode: "+373", nameFa: "مولداوی", nameEn: "Moldova" },
  { iso2: "MC", dialCode: "+377", nameFa: "موناکو", nameEn: "Monaco" },
  { iso2: "MS", dialCode: "+1664", nameFa: "مونتسرات", nameEn: "Montserrat" },
  { iso2: "ME", dialCode: "+382", nameFa: "مونته‌نگرو", nameEn: "Montenegro" },
  { iso2: "MX", dialCode: "+52", nameFa: "مکزیک", nameEn: "Mexico" },
  { iso2: "MM", dialCode: "+95", nameFa: "میانمار", nameEn: "Myanmar" },
  { iso2: "FM", dialCode: "+691", nameFa: "میکرونزی", nameEn: "Micronesia" },
  { iso2: "NR", dialCode: "+674", nameFa: "نائورو", nameEn: "Nauru" },
  { iso2: "NA", dialCode: "+264", nameFa: "نامیبیا", nameEn: "Namibia" },
  { iso2: "NO", dialCode: "+47", nameFa: "نروژ", nameEn: "Norway" },
  { iso2: "NP", dialCode: "+977", nameFa: "نپال", nameEn: "Nepal" },
  { iso2: "NE", dialCode: "+227", nameFa: "نیجر", nameEn: "Niger" },
  { iso2: "NG", dialCode: "+234", nameFa: "نیجریه", nameEn: "Nigeria" },
  { iso2: "NZ", dialCode: "+64", nameFa: "نیوزیلند", nameEn: "New Zealand" },
  { iso2: "NU", dialCode: "+683", nameFa: "نیووی", nameEn: "Niue" },
  { iso2: "NI", dialCode: "+505", nameFa: "نیکاراگوئه", nameEn: "Nicaragua" },
  { iso2: "HT", dialCode: "+509", nameFa: "هائیتی", nameEn: "Haiti" },
  { iso2: "NL", dialCode: "+31", nameFa: "هلند", nameEn: "Netherlands" },
  { iso2: "IN", dialCode: "+91", nameFa: "هند", nameEn: "India" },
  { iso2: "HN", dialCode: "+504", nameFa: "هُندوراس", nameEn: "Honduras" },
  { iso2: "HK", dialCode: "+852", nameFa: "هُنگ کُنگ", nameEn: "Hong Kong" },
  { iso2: "VA", dialCode: "+3", nameFa: "واتیکان", nameEn: "Vatican City" },
  { iso2: "WF", dialCode: "+681", nameFa: "والیس و فوتونا", nameEn: "Wallis and Futuna" },
  { iso2: "VU", dialCode: "+678", nameFa: "وانواتو", nameEn: "Vanuatu" },
  { iso2: "VE", dialCode: "+58", nameFa: "ونزوئلا", nameEn: "Venezuela" },
  { iso2: "VN", dialCode: "+84", nameFa: "ویتنام", nameEn: "Vietnam" },
  { iso2: "PY", dialCode: "+595", nameFa: "پاراگوئه", nameEn: "Paraguay" },
  { iso2: "PW", dialCode: "+680", nameFa: "پالائو", nameEn: "Palau" },
  { iso2: "PA", dialCode: "+507", nameFa: "پاناما", nameEn: "Panama" },
  { iso2: "GN", dialCode: "+224", nameFa: "پاپوآ گینه نو", nameEn: "Guinea" },
  { iso2: "PG", dialCode: "+675", nameFa: "پاپوآ گینه نو", nameEn: "Papua New Guinea" },
  { iso2: "PK", dialCode: "+92", nameFa: "پاکستان", nameEn: "Pakistan" },
  { iso2: "PT", dialCode: "+351", nameFa: "پرتغال", nameEn: "Portugal" },
  { iso2: "PE", dialCode: "+51", nameFa: "پرو", nameEn: "Peru" },
  { iso2: "PR", dialCode: "+1", nameFa: "پورتوریکو", nameEn: "Puerto Rico" },
  { iso2: "PF", dialCode: "+689", nameFa: "پُلی‌نِزی فرانسه", nameEn: "French Polynesia" },
  { iso2: "TD", dialCode: "+235", nameFa: "چاد", nameEn: "Chad" },
  { iso2: "CN", dialCode: "+86", nameFa: "چین", nameEn: "China" },
  { iso2: "JP", dialCode: "+81", nameFa: "ژاپن", nameEn: "Japan" },
  { iso2: "CR", dialCode: "+506", nameFa: "کاستاریکا", nameEn: "Costa Rica" },
  { iso2: "NC", dialCode: "+687", nameFa: "کالدونیای جدید", nameEn: "New Caledonia" },
  { iso2: "KH", dialCode: "+855", nameFa: "کامبوج", nameEn: "Cambodia" },
  { iso2: "CM", dialCode: "+237", nameFa: "کامِرون", nameEn: "Cameroon" },
  { iso2: "CA", dialCode: "+1", nameFa: "کانادا", nameEn: "Canada" },
  { iso2: "KR", dialCode: "+82", nameFa: "کرهٔ جنوبی", nameEn: "South Korea" },
  { iso2: "HR", dialCode: "+385", nameFa: "کرُواسی", nameEn: "Croatia" },
  { iso2: "CO", dialCode: "+57", nameFa: "کلمبیا", nameEn: "Colombia" },
  { iso2: "CD", dialCode: "+243", nameFa: "کنگو دموکراتیک", nameEn: "DR Congo" },
  { iso2: "KE", dialCode: "+254", nameFa: "کنیا", nameEn: "Kenya" },
  { iso2: "CU", dialCode: "+53", nameFa: "کوبا", nameEn: "Cuba" },
  { iso2: "CW", dialCode: "+599", nameFa: "کوراسائو", nameEn: "Curaçao" },
  { iso2: "XK", dialCode: "+383", nameFa: "کوزوو", nameEn: "Kosovo" },
  { iso2: "KP", dialCode: "+850", nameFa: "کُره شمالی", nameEn: "North Korea" },
  { iso2: "KW", dialCode: "+965", nameFa: "کُویت", nameEn: "Kuwait" },
  { iso2: "KI", dialCode: "+686", nameFa: "کیریباتی", nameEn: "Kiribati" },
  { iso2: "GA", dialCode: "+241", nameFa: "گابن", nameEn: "Gabon" },
  { iso2: "GM", dialCode: "+220", nameFa: "گامبیا", nameEn: "Gambia" },
  { iso2: "GE", dialCode: "+995", nameFa: "گرجستان", nameEn: "Georgia" },
  { iso2: "GD", dialCode: "+1473", nameFa: "گرنادا", nameEn: "Grenada" },
  { iso2: "GG", dialCode: "+44", nameFa: "گرنزی", nameEn: "Guernsey" },
  { iso2: "GL", dialCode: "+299", nameFa: "گرینلند", nameEn: "Greenland" },
  { iso2: "GU", dialCode: "+1671", nameFa: "گوآم", nameEn: "Guam" },
  { iso2: "GT", dialCode: "+502", nameFa: "گواتِمالا", nameEn: "Guatemala" },
  { iso2: "GP", dialCode: "+590", nameFa: "گوادلوپ", nameEn: "Guadeloupe" },
  { iso2: "GY", dialCode: "+592", nameFa: "گویان", nameEn: "Guyana" },
  { iso2: "GF", dialCode: "+594", nameFa: "گویان فرانسه", nameEn: "French Guiana" },
  { iso2: "GQ", dialCode: "+240", nameFa: "گینه استوایی", nameEn: "Equatorial Guinea" },
  { iso2: "GW", dialCode: "+245", nameFa: "گینه بیسائو", nameEn: "Guinea-Bissau" },
  { iso2: "YE", dialCode: "+967", nameFa: "یمن", nameEn: "Yemen" },
  { iso2: "GR", dialCode: "+30", nameFa: "یونان", nameEn: "Greece" }
];
const defaultCountry = countries.find((c) => c.iso2 === "OM") ?? countries[0];
const flagUrls = /* @__PURE__ */ Object.assign({
  "../../../node_modules/flag-icons/flags/4x3/ad.svg": __vite_glob_0_0,
  "../../../node_modules/flag-icons/flags/4x3/ae.svg": __vite_glob_0_1,
  "../../../node_modules/flag-icons/flags/4x3/af.svg": __vite_glob_0_2,
  "../../../node_modules/flag-icons/flags/4x3/ag.svg": __vite_glob_0_3,
  "../../../node_modules/flag-icons/flags/4x3/ai.svg": __vite_glob_0_4,
  "../../../node_modules/flag-icons/flags/4x3/al.svg": __vite_glob_0_5,
  "../../../node_modules/flag-icons/flags/4x3/am.svg": __vite_glob_0_6,
  "../../../node_modules/flag-icons/flags/4x3/ao.svg": __vite_glob_0_7,
  "../../../node_modules/flag-icons/flags/4x3/aq.svg": __vite_glob_0_8,
  "../../../node_modules/flag-icons/flags/4x3/ar.svg": __vite_glob_0_9,
  "../../../node_modules/flag-icons/flags/4x3/arab.svg": __vite_glob_0_10,
  "../../../node_modules/flag-icons/flags/4x3/as.svg": __vite_glob_0_11,
  "../../../node_modules/flag-icons/flags/4x3/asean.svg": __vite_glob_0_12,
  "../../../node_modules/flag-icons/flags/4x3/at.svg": __vite_glob_0_13,
  "../../../node_modules/flag-icons/flags/4x3/au.svg": __vite_glob_0_14,
  "../../../node_modules/flag-icons/flags/4x3/aw.svg": __vite_glob_0_15,
  "../../../node_modules/flag-icons/flags/4x3/ax.svg": __vite_glob_0_16,
  "../../../node_modules/flag-icons/flags/4x3/az.svg": __vite_glob_0_17,
  "../../../node_modules/flag-icons/flags/4x3/ba.svg": __vite_glob_0_18,
  "../../../node_modules/flag-icons/flags/4x3/bb.svg": __vite_glob_0_19,
  "../../../node_modules/flag-icons/flags/4x3/bd.svg": __vite_glob_0_20,
  "../../../node_modules/flag-icons/flags/4x3/be.svg": __vite_glob_0_21,
  "../../../node_modules/flag-icons/flags/4x3/bf.svg": __vite_glob_0_22,
  "../../../node_modules/flag-icons/flags/4x3/bg.svg": __vite_glob_0_23,
  "../../../node_modules/flag-icons/flags/4x3/bh.svg": __vite_glob_0_24,
  "../../../node_modules/flag-icons/flags/4x3/bi.svg": __vite_glob_0_25,
  "../../../node_modules/flag-icons/flags/4x3/bj.svg": __vite_glob_0_26,
  "../../../node_modules/flag-icons/flags/4x3/bl.svg": __vite_glob_0_27,
  "../../../node_modules/flag-icons/flags/4x3/bm.svg": __vite_glob_0_28,
  "../../../node_modules/flag-icons/flags/4x3/bn.svg": __vite_glob_0_29,
  "../../../node_modules/flag-icons/flags/4x3/bo.svg": __vite_glob_0_30,
  "../../../node_modules/flag-icons/flags/4x3/bq.svg": __vite_glob_0_31,
  "../../../node_modules/flag-icons/flags/4x3/br.svg": __vite_glob_0_32,
  "../../../node_modules/flag-icons/flags/4x3/bs.svg": __vite_glob_0_33,
  "../../../node_modules/flag-icons/flags/4x3/bt.svg": __vite_glob_0_34,
  "../../../node_modules/flag-icons/flags/4x3/bv.svg": __vite_glob_0_35,
  "../../../node_modules/flag-icons/flags/4x3/bw.svg": __vite_glob_0_36,
  "../../../node_modules/flag-icons/flags/4x3/by.svg": __vite_glob_0_37,
  "../../../node_modules/flag-icons/flags/4x3/bz.svg": __vite_glob_0_38,
  "../../../node_modules/flag-icons/flags/4x3/ca.svg": __vite_glob_0_39,
  "../../../node_modules/flag-icons/flags/4x3/cc.svg": __vite_glob_0_40,
  "../../../node_modules/flag-icons/flags/4x3/cd.svg": __vite_glob_0_41,
  "../../../node_modules/flag-icons/flags/4x3/cefta.svg": __vite_glob_0_42,
  "../../../node_modules/flag-icons/flags/4x3/cf.svg": __vite_glob_0_43,
  "../../../node_modules/flag-icons/flags/4x3/cg.svg": __vite_glob_0_44,
  "../../../node_modules/flag-icons/flags/4x3/ch.svg": __vite_glob_0_45,
  "../../../node_modules/flag-icons/flags/4x3/ci.svg": __vite_glob_0_46,
  "../../../node_modules/flag-icons/flags/4x3/ck.svg": __vite_glob_0_47,
  "../../../node_modules/flag-icons/flags/4x3/cl.svg": __vite_glob_0_48,
  "../../../node_modules/flag-icons/flags/4x3/cm.svg": __vite_glob_0_49,
  "../../../node_modules/flag-icons/flags/4x3/cn.svg": __vite_glob_0_50,
  "../../../node_modules/flag-icons/flags/4x3/co.svg": __vite_glob_0_51,
  "../../../node_modules/flag-icons/flags/4x3/cp.svg": __vite_glob_0_52,
  "../../../node_modules/flag-icons/flags/4x3/cr.svg": __vite_glob_0_53,
  "../../../node_modules/flag-icons/flags/4x3/cu.svg": __vite_glob_0_54,
  "../../../node_modules/flag-icons/flags/4x3/cv.svg": __vite_glob_0_55,
  "../../../node_modules/flag-icons/flags/4x3/cw.svg": __vite_glob_0_56,
  "../../../node_modules/flag-icons/flags/4x3/cx.svg": __vite_glob_0_57,
  "../../../node_modules/flag-icons/flags/4x3/cy.svg": __vite_glob_0_58,
  "../../../node_modules/flag-icons/flags/4x3/cz.svg": __vite_glob_0_59,
  "../../../node_modules/flag-icons/flags/4x3/de.svg": __vite_glob_0_60,
  "../../../node_modules/flag-icons/flags/4x3/dg.svg": __vite_glob_0_61,
  "../../../node_modules/flag-icons/flags/4x3/dj.svg": __vite_glob_0_62,
  "../../../node_modules/flag-icons/flags/4x3/dk.svg": __vite_glob_0_63,
  "../../../node_modules/flag-icons/flags/4x3/dm.svg": __vite_glob_0_64,
  "../../../node_modules/flag-icons/flags/4x3/do.svg": __vite_glob_0_65,
  "../../../node_modules/flag-icons/flags/4x3/dz.svg": __vite_glob_0_66,
  "../../../node_modules/flag-icons/flags/4x3/eac.svg": __vite_glob_0_67,
  "../../../node_modules/flag-icons/flags/4x3/ec.svg": __vite_glob_0_68,
  "../../../node_modules/flag-icons/flags/4x3/ee.svg": __vite_glob_0_69,
  "../../../node_modules/flag-icons/flags/4x3/eg.svg": __vite_glob_0_70,
  "../../../node_modules/flag-icons/flags/4x3/eh.svg": __vite_glob_0_71,
  "../../../node_modules/flag-icons/flags/4x3/er.svg": __vite_glob_0_72,
  "../../../node_modules/flag-icons/flags/4x3/es-ct.svg": __vite_glob_0_73,
  "../../../node_modules/flag-icons/flags/4x3/es-ga.svg": __vite_glob_0_74,
  "../../../node_modules/flag-icons/flags/4x3/es-pv.svg": __vite_glob_0_75,
  "../../../node_modules/flag-icons/flags/4x3/es.svg": __vite_glob_0_76,
  "../../../node_modules/flag-icons/flags/4x3/et.svg": __vite_glob_0_77,
  "../../../node_modules/flag-icons/flags/4x3/eu.svg": __vite_glob_0_78,
  "../../../node_modules/flag-icons/flags/4x3/fi.svg": __vite_glob_0_79,
  "../../../node_modules/flag-icons/flags/4x3/fj.svg": __vite_glob_0_80,
  "../../../node_modules/flag-icons/flags/4x3/fk.svg": __vite_glob_0_81,
  "../../../node_modules/flag-icons/flags/4x3/fm.svg": __vite_glob_0_82,
  "../../../node_modules/flag-icons/flags/4x3/fo.svg": __vite_glob_0_83,
  "../../../node_modules/flag-icons/flags/4x3/fr.svg": __vite_glob_0_84,
  "../../../node_modules/flag-icons/flags/4x3/ga.svg": __vite_glob_0_85,
  "../../../node_modules/flag-icons/flags/4x3/gb-eng.svg": __vite_glob_0_86,
  "../../../node_modules/flag-icons/flags/4x3/gb-nir.svg": __vite_glob_0_87,
  "../../../node_modules/flag-icons/flags/4x3/gb-sct.svg": __vite_glob_0_88,
  "../../../node_modules/flag-icons/flags/4x3/gb-wls.svg": __vite_glob_0_89,
  "../../../node_modules/flag-icons/flags/4x3/gb.svg": __vite_glob_0_90,
  "../../../node_modules/flag-icons/flags/4x3/gd.svg": __vite_glob_0_91,
  "../../../node_modules/flag-icons/flags/4x3/ge.svg": __vite_glob_0_92,
  "../../../node_modules/flag-icons/flags/4x3/gf.svg": __vite_glob_0_93,
  "../../../node_modules/flag-icons/flags/4x3/gg.svg": __vite_glob_0_94,
  "../../../node_modules/flag-icons/flags/4x3/gh.svg": __vite_glob_0_95,
  "../../../node_modules/flag-icons/flags/4x3/gi.svg": __vite_glob_0_96,
  "../../../node_modules/flag-icons/flags/4x3/gl.svg": __vite_glob_0_97,
  "../../../node_modules/flag-icons/flags/4x3/gm.svg": __vite_glob_0_98,
  "../../../node_modules/flag-icons/flags/4x3/gn.svg": __vite_glob_0_99,
  "../../../node_modules/flag-icons/flags/4x3/gp.svg": __vite_glob_0_100,
  "../../../node_modules/flag-icons/flags/4x3/gq.svg": __vite_glob_0_101,
  "../../../node_modules/flag-icons/flags/4x3/gr.svg": __vite_glob_0_102,
  "../../../node_modules/flag-icons/flags/4x3/gs.svg": __vite_glob_0_103,
  "../../../node_modules/flag-icons/flags/4x3/gt.svg": __vite_glob_0_104,
  "../../../node_modules/flag-icons/flags/4x3/gu.svg": __vite_glob_0_105,
  "../../../node_modules/flag-icons/flags/4x3/gw.svg": __vite_glob_0_106,
  "../../../node_modules/flag-icons/flags/4x3/gy.svg": __vite_glob_0_107,
  "../../../node_modules/flag-icons/flags/4x3/hk.svg": __vite_glob_0_108,
  "../../../node_modules/flag-icons/flags/4x3/hm.svg": __vite_glob_0_109,
  "../../../node_modules/flag-icons/flags/4x3/hn.svg": __vite_glob_0_110,
  "../../../node_modules/flag-icons/flags/4x3/hr.svg": __vite_glob_0_111,
  "../../../node_modules/flag-icons/flags/4x3/ht.svg": __vite_glob_0_112,
  "../../../node_modules/flag-icons/flags/4x3/hu.svg": __vite_glob_0_113,
  "../../../node_modules/flag-icons/flags/4x3/ic.svg": __vite_glob_0_114,
  "../../../node_modules/flag-icons/flags/4x3/id.svg": __vite_glob_0_115,
  "../../../node_modules/flag-icons/flags/4x3/ie.svg": __vite_glob_0_116,
  "../../../node_modules/flag-icons/flags/4x3/il.svg": __vite_glob_0_117,
  "../../../node_modules/flag-icons/flags/4x3/im.svg": __vite_glob_0_118,
  "../../../node_modules/flag-icons/flags/4x3/in.svg": __vite_glob_0_119,
  "../../../node_modules/flag-icons/flags/4x3/io.svg": __vite_glob_0_120,
  "../../../node_modules/flag-icons/flags/4x3/iq.svg": __vite_glob_0_121,
  "../../../node_modules/flag-icons/flags/4x3/ir.svg": __vite_glob_0_122,
  "../../../node_modules/flag-icons/flags/4x3/is.svg": __vite_glob_0_123,
  "../../../node_modules/flag-icons/flags/4x3/it.svg": __vite_glob_0_124,
  "../../../node_modules/flag-icons/flags/4x3/je.svg": __vite_glob_0_125,
  "../../../node_modules/flag-icons/flags/4x3/jm.svg": __vite_glob_0_126,
  "../../../node_modules/flag-icons/flags/4x3/jo.svg": __vite_glob_0_127,
  "../../../node_modules/flag-icons/flags/4x3/jp.svg": __vite_glob_0_128,
  "../../../node_modules/flag-icons/flags/4x3/ke.svg": __vite_glob_0_129,
  "../../../node_modules/flag-icons/flags/4x3/kg.svg": __vite_glob_0_130,
  "../../../node_modules/flag-icons/flags/4x3/kh.svg": __vite_glob_0_131,
  "../../../node_modules/flag-icons/flags/4x3/ki.svg": __vite_glob_0_132,
  "../../../node_modules/flag-icons/flags/4x3/km.svg": __vite_glob_0_133,
  "../../../node_modules/flag-icons/flags/4x3/kn.svg": __vite_glob_0_134,
  "../../../node_modules/flag-icons/flags/4x3/kp.svg": __vite_glob_0_135,
  "../../../node_modules/flag-icons/flags/4x3/kr.svg": __vite_glob_0_136,
  "../../../node_modules/flag-icons/flags/4x3/kw.svg": __vite_glob_0_137,
  "../../../node_modules/flag-icons/flags/4x3/ky.svg": __vite_glob_0_138,
  "../../../node_modules/flag-icons/flags/4x3/kz.svg": __vite_glob_0_139,
  "../../../node_modules/flag-icons/flags/4x3/la.svg": __vite_glob_0_140,
  "../../../node_modules/flag-icons/flags/4x3/lb.svg": __vite_glob_0_141,
  "../../../node_modules/flag-icons/flags/4x3/lc.svg": __vite_glob_0_142,
  "../../../node_modules/flag-icons/flags/4x3/li.svg": __vite_glob_0_143,
  "../../../node_modules/flag-icons/flags/4x3/lk.svg": __vite_glob_0_144,
  "../../../node_modules/flag-icons/flags/4x3/lr.svg": __vite_glob_0_145,
  "../../../node_modules/flag-icons/flags/4x3/ls.svg": __vite_glob_0_146,
  "../../../node_modules/flag-icons/flags/4x3/lt.svg": __vite_glob_0_147,
  "../../../node_modules/flag-icons/flags/4x3/lu.svg": __vite_glob_0_148,
  "../../../node_modules/flag-icons/flags/4x3/lv.svg": __vite_glob_0_149,
  "../../../node_modules/flag-icons/flags/4x3/ly.svg": __vite_glob_0_150,
  "../../../node_modules/flag-icons/flags/4x3/ma.svg": __vite_glob_0_151,
  "../../../node_modules/flag-icons/flags/4x3/mc.svg": __vite_glob_0_152,
  "../../../node_modules/flag-icons/flags/4x3/md.svg": __vite_glob_0_153,
  "../../../node_modules/flag-icons/flags/4x3/me.svg": __vite_glob_0_154,
  "../../../node_modules/flag-icons/flags/4x3/mf.svg": __vite_glob_0_155,
  "../../../node_modules/flag-icons/flags/4x3/mg.svg": __vite_glob_0_156,
  "../../../node_modules/flag-icons/flags/4x3/mh.svg": __vite_glob_0_157,
  "../../../node_modules/flag-icons/flags/4x3/mk.svg": __vite_glob_0_158,
  "../../../node_modules/flag-icons/flags/4x3/ml.svg": __vite_glob_0_159,
  "../../../node_modules/flag-icons/flags/4x3/mm.svg": __vite_glob_0_160,
  "../../../node_modules/flag-icons/flags/4x3/mn.svg": __vite_glob_0_161,
  "../../../node_modules/flag-icons/flags/4x3/mo.svg": __vite_glob_0_162,
  "../../../node_modules/flag-icons/flags/4x3/mp.svg": __vite_glob_0_163,
  "../../../node_modules/flag-icons/flags/4x3/mq.svg": __vite_glob_0_164,
  "../../../node_modules/flag-icons/flags/4x3/mr.svg": __vite_glob_0_165,
  "../../../node_modules/flag-icons/flags/4x3/ms.svg": __vite_glob_0_166,
  "../../../node_modules/flag-icons/flags/4x3/mt.svg": __vite_glob_0_167,
  "../../../node_modules/flag-icons/flags/4x3/mu.svg": __vite_glob_0_168,
  "../../../node_modules/flag-icons/flags/4x3/mv.svg": __vite_glob_0_169,
  "../../../node_modules/flag-icons/flags/4x3/mw.svg": __vite_glob_0_170,
  "../../../node_modules/flag-icons/flags/4x3/mx.svg": __vite_glob_0_171,
  "../../../node_modules/flag-icons/flags/4x3/my.svg": __vite_glob_0_172,
  "../../../node_modules/flag-icons/flags/4x3/mz.svg": __vite_glob_0_173,
  "../../../node_modules/flag-icons/flags/4x3/na.svg": __vite_glob_0_174,
  "../../../node_modules/flag-icons/flags/4x3/nc.svg": __vite_glob_0_175,
  "../../../node_modules/flag-icons/flags/4x3/ne.svg": __vite_glob_0_176,
  "../../../node_modules/flag-icons/flags/4x3/nf.svg": __vite_glob_0_177,
  "../../../node_modules/flag-icons/flags/4x3/ng.svg": __vite_glob_0_178,
  "../../../node_modules/flag-icons/flags/4x3/ni.svg": __vite_glob_0_179,
  "../../../node_modules/flag-icons/flags/4x3/nl.svg": __vite_glob_0_180,
  "../../../node_modules/flag-icons/flags/4x3/no.svg": __vite_glob_0_181,
  "../../../node_modules/flag-icons/flags/4x3/np.svg": __vite_glob_0_182,
  "../../../node_modules/flag-icons/flags/4x3/nr.svg": __vite_glob_0_183,
  "../../../node_modules/flag-icons/flags/4x3/nu.svg": __vite_glob_0_184,
  "../../../node_modules/flag-icons/flags/4x3/nz.svg": __vite_glob_0_185,
  "../../../node_modules/flag-icons/flags/4x3/om.svg": __vite_glob_0_186,
  "../../../node_modules/flag-icons/flags/4x3/pa.svg": __vite_glob_0_187,
  "../../../node_modules/flag-icons/flags/4x3/pc.svg": __vite_glob_0_188,
  "../../../node_modules/flag-icons/flags/4x3/pe.svg": __vite_glob_0_189,
  "../../../node_modules/flag-icons/flags/4x3/pf.svg": __vite_glob_0_190,
  "../../../node_modules/flag-icons/flags/4x3/pg.svg": __vite_glob_0_191,
  "../../../node_modules/flag-icons/flags/4x3/ph.svg": __vite_glob_0_192,
  "../../../node_modules/flag-icons/flags/4x3/pk.svg": __vite_glob_0_193,
  "../../../node_modules/flag-icons/flags/4x3/pl.svg": __vite_glob_0_194,
  "../../../node_modules/flag-icons/flags/4x3/pm.svg": __vite_glob_0_195,
  "../../../node_modules/flag-icons/flags/4x3/pn.svg": __vite_glob_0_196,
  "../../../node_modules/flag-icons/flags/4x3/pr.svg": __vite_glob_0_197,
  "../../../node_modules/flag-icons/flags/4x3/ps.svg": __vite_glob_0_198,
  "../../../node_modules/flag-icons/flags/4x3/pt.svg": __vite_glob_0_199,
  "../../../node_modules/flag-icons/flags/4x3/pw.svg": __vite_glob_0_200,
  "../../../node_modules/flag-icons/flags/4x3/py.svg": __vite_glob_0_201,
  "../../../node_modules/flag-icons/flags/4x3/qa.svg": __vite_glob_0_202,
  "../../../node_modules/flag-icons/flags/4x3/re.svg": __vite_glob_0_203,
  "../../../node_modules/flag-icons/flags/4x3/ro.svg": __vite_glob_0_204,
  "../../../node_modules/flag-icons/flags/4x3/rs.svg": __vite_glob_0_205,
  "../../../node_modules/flag-icons/flags/4x3/ru.svg": __vite_glob_0_206,
  "../../../node_modules/flag-icons/flags/4x3/rw.svg": __vite_glob_0_207,
  "../../../node_modules/flag-icons/flags/4x3/sa.svg": __vite_glob_0_208,
  "../../../node_modules/flag-icons/flags/4x3/sb.svg": __vite_glob_0_209,
  "../../../node_modules/flag-icons/flags/4x3/sc.svg": __vite_glob_0_210,
  "../../../node_modules/flag-icons/flags/4x3/sd.svg": __vite_glob_0_211,
  "../../../node_modules/flag-icons/flags/4x3/se.svg": __vite_glob_0_212,
  "../../../node_modules/flag-icons/flags/4x3/sg.svg": __vite_glob_0_213,
  "../../../node_modules/flag-icons/flags/4x3/sh-ac.svg": __vite_glob_0_214,
  "../../../node_modules/flag-icons/flags/4x3/sh-hl.svg": __vite_glob_0_215,
  "../../../node_modules/flag-icons/flags/4x3/sh-ta.svg": __vite_glob_0_216,
  "../../../node_modules/flag-icons/flags/4x3/sh.svg": __vite_glob_0_217,
  "../../../node_modules/flag-icons/flags/4x3/si.svg": __vite_glob_0_218,
  "../../../node_modules/flag-icons/flags/4x3/sj.svg": __vite_glob_0_219,
  "../../../node_modules/flag-icons/flags/4x3/sk.svg": __vite_glob_0_220,
  "../../../node_modules/flag-icons/flags/4x3/sl.svg": __vite_glob_0_221,
  "../../../node_modules/flag-icons/flags/4x3/sm.svg": __vite_glob_0_222,
  "../../../node_modules/flag-icons/flags/4x3/sn.svg": __vite_glob_0_223,
  "../../../node_modules/flag-icons/flags/4x3/so.svg": __vite_glob_0_224,
  "../../../node_modules/flag-icons/flags/4x3/sr.svg": __vite_glob_0_225,
  "../../../node_modules/flag-icons/flags/4x3/ss.svg": __vite_glob_0_226,
  "../../../node_modules/flag-icons/flags/4x3/st.svg": __vite_glob_0_227,
  "../../../node_modules/flag-icons/flags/4x3/sv.svg": __vite_glob_0_228,
  "../../../node_modules/flag-icons/flags/4x3/sx.svg": __vite_glob_0_229,
  "../../../node_modules/flag-icons/flags/4x3/sy.svg": __vite_glob_0_230,
  "../../../node_modules/flag-icons/flags/4x3/sz.svg": __vite_glob_0_231,
  "../../../node_modules/flag-icons/flags/4x3/tc.svg": __vite_glob_0_232,
  "../../../node_modules/flag-icons/flags/4x3/td.svg": __vite_glob_0_233,
  "../../../node_modules/flag-icons/flags/4x3/tf.svg": __vite_glob_0_234,
  "../../../node_modules/flag-icons/flags/4x3/tg.svg": __vite_glob_0_235,
  "../../../node_modules/flag-icons/flags/4x3/th.svg": __vite_glob_0_236,
  "../../../node_modules/flag-icons/flags/4x3/tj.svg": __vite_glob_0_237,
  "../../../node_modules/flag-icons/flags/4x3/tk.svg": __vite_glob_0_238,
  "../../../node_modules/flag-icons/flags/4x3/tl.svg": __vite_glob_0_239,
  "../../../node_modules/flag-icons/flags/4x3/tm.svg": __vite_glob_0_240,
  "../../../node_modules/flag-icons/flags/4x3/tn.svg": __vite_glob_0_241,
  "../../../node_modules/flag-icons/flags/4x3/to.svg": __vite_glob_0_242,
  "../../../node_modules/flag-icons/flags/4x3/tr.svg": __vite_glob_0_243,
  "../../../node_modules/flag-icons/flags/4x3/tt.svg": __vite_glob_0_244,
  "../../../node_modules/flag-icons/flags/4x3/tv.svg": __vite_glob_0_245,
  "../../../node_modules/flag-icons/flags/4x3/tw.svg": __vite_glob_0_246,
  "../../../node_modules/flag-icons/flags/4x3/tz.svg": __vite_glob_0_247,
  "../../../node_modules/flag-icons/flags/4x3/ua.svg": __vite_glob_0_248,
  "../../../node_modules/flag-icons/flags/4x3/ug.svg": __vite_glob_0_249,
  "../../../node_modules/flag-icons/flags/4x3/um.svg": __vite_glob_0_250,
  "../../../node_modules/flag-icons/flags/4x3/un.svg": __vite_glob_0_251,
  "../../../node_modules/flag-icons/flags/4x3/us.svg": __vite_glob_0_252,
  "../../../node_modules/flag-icons/flags/4x3/uy.svg": __vite_glob_0_253,
  "../../../node_modules/flag-icons/flags/4x3/uz.svg": __vite_glob_0_254,
  "../../../node_modules/flag-icons/flags/4x3/va.svg": __vite_glob_0_255,
  "../../../node_modules/flag-icons/flags/4x3/vc.svg": __vite_glob_0_256,
  "../../../node_modules/flag-icons/flags/4x3/ve.svg": __vite_glob_0_257,
  "../../../node_modules/flag-icons/flags/4x3/vg.svg": __vite_glob_0_258,
  "../../../node_modules/flag-icons/flags/4x3/vi.svg": __vite_glob_0_259,
  "../../../node_modules/flag-icons/flags/4x3/vn.svg": __vite_glob_0_260,
  "../../../node_modules/flag-icons/flags/4x3/vu.svg": __vite_glob_0_261,
  "../../../node_modules/flag-icons/flags/4x3/wf.svg": __vite_glob_0_262,
  "../../../node_modules/flag-icons/flags/4x3/ws.svg": __vite_glob_0_263,
  "../../../node_modules/flag-icons/flags/4x3/xk.svg": __vite_glob_0_264,
  "../../../node_modules/flag-icons/flags/4x3/xx.svg": __vite_glob_0_265,
  "../../../node_modules/flag-icons/flags/4x3/ye.svg": __vite_glob_0_266,
  "../../../node_modules/flag-icons/flags/4x3/yt.svg": __vite_glob_0_267,
  "../../../node_modules/flag-icons/flags/4x3/za.svg": __vite_glob_0_268,
  "../../../node_modules/flag-icons/flags/4x3/zm.svg": __vite_glob_0_269,
  "../../../node_modules/flag-icons/flags/4x3/zw.svg": __vite_glob_0_270
});
function flagUrl(iso2) {
  return flagUrls[`../../../node_modules/flag-icons/flags/4x3/${iso2.toLowerCase()}.svg`];
}
const caretUrl = "/build/assets/field-caret-C_Rk2J8j.svg";
const _sfc_main$1 = /* @__PURE__ */ defineComponent({
  __name: "CountrySelect",
  __ssrInlineRender: true,
  props: {
    "modelValue": { required: true },
    "modelModifiers": {}
  },
  emits: ["update:modelValue"],
  setup(__props) {
    const model = useModel(__props, "modelValue");
    const { t } = useTranslations();
    const open = ref(false);
    const query = ref("");
    const filtered = computed(() => {
      const q = query.value.trim().toLowerCase();
      if (!q) return countries;
      const digits = q.replace(/[^\d]/g, "");
      return countries.filter(
        (country) => country.nameFa.includes(query.value.trim()) || country.nameEn.toLowerCase().includes(q) || digits !== "" && country.dialCode.replace("+", "").startsWith(digits)
      );
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "relative flex shrink-0 border-e-2 border-brand-200" }, _attrs))}><button type="button" class="flex items-center gap-1.5 px-3 text-body-md text-ink-1000"${ssrRenderAttr("aria-expanded", open.value)} aria-controls="country-options"><img${ssrRenderAttr("src", unref(flagUrl)(model.value.iso2))} alt="" aria-hidden="true" width="24" height="18" class="h-[18px] w-6 shrink-0 rounded-sm object-cover"><span class="sr-only">${ssrInterpolate(model.value.nameFa)}</span><img${ssrRenderAttr("src", unref(caretUrl))} alt="" aria-hidden="true" width="16" height="16" class="${ssrRenderClass([open.value && "rotate-180", "size-4 shrink-0 transition-transform duration-200"])}"></button><div id="country-options" class="absolute inline-start-0 top-full z-20 mt-2 w-72 rounded-lg border-3 border-brand-200 bg-brand-50 p-2 shadow-[4px_4px_12px_0px_rgba(0,0,0,0.15)]" style="${ssrRenderStyle(open.value ? null : { display: "none" })}"><input${ssrRenderAttr("value", query.value)} type="text"${ssrRenderAttr("aria-label", unref(t)("forms.contact.country_search"))}${ssrRenderAttr("placeholder", unref(t)("forms.contact.country_search"))} class="mb-2 w-full rounded-md border border-ink-300 bg-brand-50 px-3 py-2 text-body-sm text-ink-1000 placeholder:text-ink-600 focus:border-brand focus:outline-none focus:ring-0"><ul class="max-h-56 overflow-y-auto" role="listbox"><!--[-->`);
      ssrRenderList(filtered.value, (country) => {
        _push(`<li><button type="button" role="option"${ssrRenderAttr("aria-selected", country.iso2 === model.value.iso2)} class="${ssrRenderClass([country.iso2 === model.value.iso2 && "bg-brand-200", "flex w-full items-center gap-2 rounded-md px-2 py-1.5 text-start text-body-sm text-ink-1000 hover:bg-brand-200"])}"><img${ssrRenderAttr("src", unref(flagUrl)(country.iso2))} alt="" aria-hidden="true" width="20" height="15" loading="lazy" class="h-[15px] w-5 shrink-0 rounded-sm object-cover"><span class="flex-1 truncate">${ssrInterpolate(country.nameFa)}</span><span class="latin-nums text-ink-600" dir="ltr">${ssrInterpolate(country.dialCode)}</span></button></li>`);
      });
      _push(`<!--]-->`);
      if (filtered.value.length === 0) {
        _push(`<li class="px-2 py-3 text-center text-body-sm text-ink-600">${ssrInterpolate(unref(t)("forms.contact.country_not_found"))}</li>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</ul></div></div>`);
    };
  }
});
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/CountrySelect.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const phoneIconUrl = "/build/assets/footer-phone-Dh0WcHp0.svg";
const locationIconUrl = "/build/assets/footer-location-DkCZa55k.svg";
const emailIconUrl = "/build/assets/contact-email-LtHSxup0.svg";
const pushpinUrl = "/build/assets/pushpin-BfAKrv2_.svg";
const brandFieldUrl = "/build/assets/field-brand-ClRz1b5E.svg";
const serviceFieldUrl = "/build/assets/field-service-CZ4Ff9JD.svg";
const wordmarkUrl = "/build/assets/wordmark-inline-2UnGoTG4.svg";
const followSwooshUrl = "/build/assets/follow-swoosh-Lsac-_1O.svg";
const fieldClass = "w-full min-w-0 rounded-lg border-3 border-brand-200 bg-white/80 p-3 text-body-md text-ink-1000 placeholder:text-ink-600 focus:border-brand-200 focus:bg-white/80 focus:outline-none focus:ring-0 focus:ring-offset-0";
const _sfc_main = /* @__PURE__ */ defineComponent({
  __name: "Contact",
  __ssrInlineRender: true,
  props: {
    heading: {},
    services: {},
    seo: {}
  },
  setup(__props) {
    const props = __props;
    const { t } = useTranslations();
    const page = usePage();
    const contact = computed(() => page.props.settings.contact);
    const socialLinks = computed(() => page.props.settings.socialLinks);
    const details = computed(() => [
      { key: "whatsapp", icon: phoneIconUrl, value: contact.value.whatsapp },
      { key: "location", icon: locationIconUrl, value: contact.value.location },
      { key: "email", icon: emailIconUrl, value: contact.value.email },
      { key: "working_with", icon: retentionUrl, value: contact.value.workingWith }
    ]);
    const form = useForm({
      brand_name: "",
      name: "",
      service_ids: [],
      phone: "",
      message: "",
      website: "",
      form_started_at: Math.floor(Date.now() / 1e3)
    });
    const servicesOpen = ref(false);
    const selectedServices = computed(
      () => props.services.filter((service) => form.service_ids.includes(service.id)).map((service) => service.title)
    );
    const selectedCountry = ref(defaultCountry);
    const phoneNumber = ref("");
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(_sfc_main$3, {
        seo: props.seo
      }, null, _parent));
      _push(`<section class="section-first pb-[153.24px]"><div class="container-sizdah relative isolate flex flex-col justify-between lg:min-h-[920px]"><header class="mx-auto flex w-[612px] max-w-full flex-col items-center gap-10 text-center" data-reveal>`);
      if (props.heading.eyebrow) {
        _push(ssrRenderComponent(_sfc_main$4, {
          text: props.heading.eyebrow
        }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="flex w-full flex-col gap-6"><h1 class="text-display-lg text-ink-50 max-md:text-[40px] max-md:leading-[51px]">${ssrInterpolate(props.heading.title)}</h1>`);
      if (props.heading.description) {
        _push(`<p class="text-title-md text-ink-200 max-md:text-[16px] max-md:leading-[22px]">${ssrInterpolate(props.heading.description)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></header><div class="flex flex-col gap-12"><div class="grid gap-6 lg:grid-cols-[801fr_423fr]" data-reveal-group><form class="sketch-frame sketch-frame-fill sketch-frame-blur flex min-w-0 flex-col gap-6 p-8" novalidate><div class="hidden" aria-hidden="true"><label for="website">Website</label><input id="website"${ssrRenderAttr("value", unref(form).website)} type="text" tabindex="-1" autocomplete="off"></div><div class="flex flex-col gap-[33px]"><div class="flex flex-col gap-6"><div class="grid gap-6 md:grid-cols-2"><div class="flex min-w-0 flex-col gap-2"><label for="name" class="text-label-lg text-brand-50">${ssrInterpolate(unref(t)("forms.contact.name"))}</label><div class="relative"><img${ssrRenderAttr("src", unref(userFieldUrl))} alt="" aria-hidden="true" width="24" height="24" class="pointer-events-none absolute block-start-1/2 inline-start-3 size-6 -translate-y-1/2"><input id="name"${ssrRenderAttr("value", unref(form).name)} type="text" required class="${ssrRenderClass([fieldClass, "ps-12"])}"${ssrRenderAttr("placeholder", unref(t)("forms.contact.name_placeholder"))} style="${ssrRenderStyle({ "background-color": "#FDFCFA" })}" autocomplete="name"></div>`);
      if (unref(form).errors.name) {
        _push(`<p class="text-label-md text-brand">${ssrInterpolate(unref(form).errors.name)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div><div class="flex min-w-0 flex-col gap-2"><label for="brand_name" class="text-label-lg text-brand-50">${ssrInterpolate(unref(t)("forms.contact.brand"))}</label><div class="relative"><img${ssrRenderAttr("src", unref(brandFieldUrl))} alt="" aria-hidden="true" width="24" height="24" class="pointer-events-none absolute block-start-1/2 inline-start-3 size-6 -translate-y-1/2"><input id="brand_name"${ssrRenderAttr("value", unref(form).brand_name)} type="text" class="${ssrRenderClass([fieldClass, "ps-12"])}"${ssrRenderAttr("placeholder", unref(t)("forms.contact.brand_placeholder"))} style="${ssrRenderStyle({ "background-color": "#FDFCFA" })}" autocomplete="organization"></div>`);
      if (unref(form).errors.brand_name) {
        _push(`<p class="text-label-md text-brand">${ssrInterpolate(unref(form).errors.brand_name)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div><div class="grid gap-6 md:grid-cols-2"><div class="flex min-w-0 flex-col gap-2"><label for="phone" class="text-label-lg text-brand-50">${ssrInterpolate(unref(t)("forms.contact.phone"))}</label><div dir="ltr" class="flex items-stretch rounded-lg border-3 border-brand-200 bg-white/80 focus-within:border-brand-200 focus-within:bg-white/80" style="${ssrRenderStyle({ "background-color": "#FDFCFA" })}"><span class="flex shrink-0 items-center gap-2 ps-3">`);
      _push(ssrRenderComponent(_sfc_main$1, {
        modelValue: selectedCountry.value,
        "onUpdate:modelValue": ($event) => selectedCountry.value = $event
      }, null, _parent));
      _push(`<span class="text-body-md text-ink-1000 latin-nums" dir="ltr">${ssrInterpolate(selectedCountry.value.dialCode)}</span></span><input id="phone"${ssrRenderAttr("value", phoneNumber.value)} type="tel" dir="ltr" class="w-full min-w-0 bg-transparent p-3 text-body-md text-ink-1000 placeholder:text-ink-600 focus:outline-none focus:ring-0 focus:ring-offset-0"${ssrRenderAttr("placeholder", unref(t)("forms.contact.phone_placeholder"))} autocomplete="tel"></div>`);
      if (unref(form).errors.phone) {
        _push(`<p class="text-label-md text-brand">${ssrInterpolate(unref(form).errors.phone)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div><div class="flex min-w-0 flex-col gap-2"><span class="text-label-lg text-brand-50">${ssrInterpolate(unref(t)("forms.contact.services"))}</span><div class="relative"><button type="button" class="${ssrRenderClass([fieldClass, "flex items-center gap-3 text-start"])}" style="${ssrRenderStyle({ "background-color": "#FDFCFA" })}"${ssrRenderAttr("aria-expanded", servicesOpen.value)} aria-controls="service-options"><img${ssrRenderAttr("src", unref(serviceFieldUrl))} alt="" aria-hidden="true" width="24" height="24" class="size-6 shrink-0"><span class="${ssrRenderClass([selectedServices.value.length ? "text-ink-1000" : "text-ink-600", "min-w-0 flex-1 truncate"])}">${ssrInterpolate(selectedServices.value.length ? selectedServices.value.join("، ") : unref(t)("forms.contact.services_placeholder"))}</span><img${ssrRenderAttr("src", unref(caretUrl))} alt="" aria-hidden="true" width="24" height="24" class="${ssrRenderClass([servicesOpen.value && "rotate-180", "size-6 shrink-0 transition-transform duration-200"])}"></button><fieldset id="service-options" class="absolute inset-inline-0 z-10 mt-2 flex max-h-64 flex-col gap-2 overflow-y-auto rounded-lg border-3 border-brand-200 bg-brand-50 p-3" style="${ssrRenderStyle(servicesOpen.value ? null : { display: "none" })}"><legend class="sr-only">${ssrInterpolate(unref(t)("forms.contact.services"))}</legend><!--[-->`);
      ssrRenderList(props.services, (service) => {
        _push(`<label class="flex items-center gap-2 text-body-md text-ink-1000"><input${ssrIncludeBooleanAttr(Array.isArray(unref(form).service_ids) ? ssrLooseContain(unref(form).service_ids, service.id) : unref(form).service_ids) ? " checked" : ""} type="checkbox"${ssrRenderAttr("value", service.id)} class="size-4 rounded-xs border-ink-400 text-brand focus:ring-brand"> ${ssrInterpolate(service.title)}</label>`);
      });
      _push(`<!--]--></fieldset></div>`);
      if (unref(form).errors.service_ids) {
        _push(`<p class="text-label-md text-brand">${ssrInterpolate(unref(form).errors.service_ids)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div></div><div class="flex min-w-0 flex-col gap-2"><label for="message" class="text-label-lg text-brand-50">${ssrInterpolate(unref(t)("forms.contact.message"))}</label><textarea id="message" rows="5" class="${ssrRenderClass([fieldClass, "h-[125.5px] resize-none"])}"${ssrRenderAttr("placeholder", unref(t)("forms.contact.message_placeholder"))} style="${ssrRenderStyle({ "background-color": "#FDFCFA" })}">${ssrInterpolate(unref(form).message)}</textarea>`);
      if (unref(form).errors.message) {
        _push(`<p class="text-label-md text-brand">${ssrInterpolate(unref(form).errors.message)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div><button type="submit"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""} class="w-full rounded-md bg-brand px-8 py-4 text-title-md text-ink-1000 transition-colors duration-200 ease-brand hover:bg-brand-900 disabled:opacity-50">${ssrInterpolate(unref(form).processing ? unref(t)("common.sending") : unref(t)("forms.contact.submit"))}</button></form><div class="sketch-frame sketch-frame-fill sketch-frame-blur flex min-w-0 flex-col gap-10 p-8"><img${ssrRenderAttr("src", unref(pushpinUrl))} alt="" aria-hidden="true" width="40" height="50" class="pointer-events-none absolute z-10 block-start-[-35px] inline-start-[-1px] h-[50px] w-10 -scale-x-100"><h2 class="text-title-lg text-brand-50">${ssrInterpolate(unref(t)("forms.details.title"))}</h2><ul class="flex flex-col gap-6"><!--[-->`);
      ssrRenderList(details.value, (detail, index) => {
        _push(`<li class="flex flex-col gap-6"><div class="flex items-center gap-4"><img${ssrRenderAttr("src", detail.icon)} alt="" aria-hidden="true" width="24" height="24" class="size-6 shrink-0"><span class="flex flex-col gap-1"><span class="text-label-lg text-brand-50">${ssrInterpolate(unref(t)(`forms.details.${detail.key}`))}</span><span class="text-body-md text-ink-200 latin-nums">${ssrInterpolate(detail.value)}</span></span></div>`);
        if (index < details.value.length - 1) {
          _push(`<img${ssrRenderAttr("src", unref(relatedRuleUrl))} alt="" aria-hidden="true" class="h-[5px] w-full">`);
        } else {
          _push(`<!---->`);
        }
        _push(`</li>`);
      });
      _push(`<!--]--></ul></div></div><div class="flex flex-col gap-8 md:flex-row md:items-center md:justify-between" data-reveal><div class="relative"><p class="flex items-center gap-2 text-body-lg font-medium text-ink-100"><img${ssrRenderAttr("src", unref(wordmarkUrl))} alt="" aria-hidden="true" width="56" height="20" class="h-5 w-14"> ${ssrInterpolate(unref(t)("forms.details.follow"))}</p><img${ssrRenderAttr("src", unref(followSwooshUrl))} alt="" aria-hidden="true" width="92" height="88" class="pointer-events-none absolute block-start-[-35px] inline-end-[-92px] hidden h-[88px] w-[92px] lg:block"></div><ul class="flex flex-wrap items-center gap-6"><!--[-->`);
      ssrRenderList(socialLinks.value, (link) => {
        _push(`<li>`);
        _push(ssrRenderComponent(_sfc_main$2, { link }, null, _parent));
        _push(`</li>`);
      });
      _push(`<!--]--></ul></div></div></div></section><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Contact.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
