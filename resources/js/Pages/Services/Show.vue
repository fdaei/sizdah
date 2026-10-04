<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import SeoHead from '@/Components/SeoHead.vue'
import { useTranslations } from '@/Composables/useTranslations'
import type { SeoMeta } from '@/types'

defineProps<{ service: { title: string; description: string; features: string[] }; seo: SeoMeta }>()

const { t } = useTranslations()
</script>

<template>
  <SeoHead :seo="seo" />
  <main id="main" class="section-first container-sizdah py-32">
    <article class="mx-auto max-w-3xl">
      <h1 class="text-display-lg text-ink-50">{{ service.title }}</h1>
      <p class="mt-8 text-title-lg text-ink-200">{{ service.description }}</p>
      <ul v-if="service.features.length" class="mt-10 list-disc space-y-4 ps-6 text-body-lg text-ink-200">
        <li v-for="feature in service.features" :key="feature">{{ feature }}</li>
      </ul>
      <nav class="mt-12 flex gap-6">
        <Link :href="route('services')" class="underline">{{ t('work.services') }}</Link>
        <Link :href="route('contact')" class="underline">{{ t('common.start_conversation') }}</Link>
      </nav>
    </article>
  </main>
</template>
