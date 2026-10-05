<script setup lang="ts">
import { computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import type { SeoMeta, SharedProps } from '@/types'

/**
 * Per-page meta. `seo` is built server-side by SeoBuilder, which already
 * falls back from entity meta to the site defaults — this component only
 * renders what it is handed.
 *
 * The initial HTML gets these tags from resources/views/partials/seo.blade.php
 * (so they exist without JS or SSR). This component owns them only after
 * hydration: every tag's `head-key` matches the Blade partial's `inertia`
 * attribute, so Inertia's head manager swaps them in place on each client-side
 * navigation. It renders nothing during SSR — the Blade partial already did,
 * and emitting both would duplicate every tag.
 */
const props = defineProps<{ seo: SeoMeta }>()

const isServer = import.meta.env.SSR

const page = usePage<SharedProps>()

const settings = computed(() => page.props.settings)
const ogLocale = computed(() => page.props.locale.htmlLang.replace('-', '_'))
const htmlLangFor = (code: string) =>
  page.props.locale.supported.find((l) => l.code === code)?.htmlLang ?? code
const xDefault = computed(() => {
  const alternates = page.props.alternates ?? {}
  return alternates[page.props.locale.default] ?? Object.values(alternates)[0]
})
const schemaJson = computed(() => {
  if (!props.seo.schema) {
    return undefined
  }

  // Keep JSON-LD from terminating the script element if a value contains HTML.
  return JSON.stringify(props.seo.schema).replace(/</g, '\\u003c')
})
</script>

<template>
  <Head v-if="!isServer" :title="props.seo.title">
    <meta head-key="description" name="description" :content="props.seo.description" />
    <link head-key="canonical" rel="canonical" :href="props.seo.canonical" />
    <meta
      head-key="robots"
      name="robots"
      :content="props.seo.noindex ? 'noindex, follow' : 'index, follow'"
    />

    <link
      v-for="(url, code) in page.props.alternates"
      :key="code"
      :head-key="`hreflang-${code}`"
      rel="alternate"
      :hreflang="htmlLangFor(String(code))"
      :href="url"
    />
    <link
      v-if="xDefault"
      head-key="hreflang-x-default"
      rel="alternate"
      hreflang="x-default"
      :href="xDefault"
    />

    <!-- eslint-disable-next-line vue/no-v-html, vue/no-v-text-v-html-on-component -- JSON-LD from SeoBuilder, `<` escaped above; <script> can't be written literally in a template -->
    <component :is="'script'" v-if="schemaJson" head-key="schema" type="application/ld+json" v-html="schemaJson" />

    <meta head-key="og:type" property="og:type" :content="props.seo.type" />
    <meta head-key="og:title" property="og:title" :content="props.seo.title" />
    <meta head-key="og:description" property="og:description" :content="props.seo.description" />
    <meta head-key="og:url" property="og:url" :content="props.seo.canonical" />
    <meta head-key="og:site_name" property="og:site_name" :content="settings.seo.organizationName" />
    <meta head-key="og:locale" property="og:locale" :content="ogLocale" />
    <meta v-if="props.seo.image" head-key="og:image" property="og:image" :content="props.seo.image" />

    <meta
      head-key="twitter:card"
      name="twitter:card"
      :content="props.seo.image ? 'summary_large_image' : 'summary'"
    />
    <meta head-key="twitter:title" name="twitter:title" :content="props.seo.title" />
    <meta head-key="twitter:description" name="twitter:description" :content="props.seo.description" />
    <meta v-if="props.seo.image" head-key="twitter:image" name="twitter:image" :content="props.seo.image" />

    <template v-if="props.seo.type === 'article'">
      <meta
        v-if="props.seo.publishedAt"
        head-key="article:published_time"
        property="article:published_time"
        :content="props.seo.publishedAt"
      />
      <meta
        v-if="props.seo.modifiedAt"
        head-key="article:modified_time"
        property="article:modified_time"
        :content="props.seo.modifiedAt"
      />
      <meta
        v-if="props.seo.author"
        head-key="article:author"
        property="article:author"
        :content="props.seo.author"
      />
    </template>
  </Head>
</template>
