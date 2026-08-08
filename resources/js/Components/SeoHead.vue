<script setup lang="ts">
import { computed } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import type { SeoMeta, SharedProps } from '@/types'

/**
 * Per-page meta. `seo` is built server-side by SeoBuilder, which already
 * falls back from entity meta to the site defaults — this component only
 * renders what it is handed.
 *
 * hreflang links are emitted by the root Blade view from the same
 * `alternates` prop, so they are not repeated here.
 */
const props = defineProps<{ seo: SeoMeta }>()

const page = usePage<SharedProps>()

const settings = computed(() => page.props.settings)
const ogLocale = computed(() => page.props.locale.htmlLang.replace('-', '_'))
</script>

<template>
  <Head :title="props.seo.title">
    <meta name="description" :content="props.seo.description" />
    <link rel="canonical" :href="props.seo.canonical" />
    <meta v-if="props.seo.noindex" name="robots" content="noindex, nofollow" />

    <meta property="og:type" :content="props.seo.type" />
    <meta property="og:title" :content="props.seo.title" />
    <meta property="og:description" :content="props.seo.description" />
    <meta property="og:url" :content="props.seo.canonical" />
    <meta property="og:site_name" :content="settings.seo.organizationName" />
    <meta property="og:locale" :content="ogLocale" />
    <meta v-if="props.seo.image" property="og:image" :content="props.seo.image" />

    <meta name="twitter:card" :content="props.seo.image ? 'summary_large_image' : 'summary'" />
    <meta name="twitter:title" :content="props.seo.title" />
    <meta name="twitter:description" :content="props.seo.description" />
    <meta v-if="props.seo.image" name="twitter:image" :content="props.seo.image" />

    <template v-if="props.seo.type === 'article'">
      <meta v-if="props.seo.publishedAt" property="article:published_time" :content="props.seo.publishedAt" />
      <meta v-if="props.seo.modifiedAt" property="article:modified_time" :content="props.seo.modifiedAt" />
      <meta v-if="props.seo.author" property="article:author" :content="props.seo.author" />
    </template>
  </Head>
</template>
