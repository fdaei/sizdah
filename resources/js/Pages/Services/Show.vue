<script setup lang="ts">
import SeoHead from '@/Components/SeoHead.vue'
import type { SeoMeta } from '@/types'

defineProps<{ service: { title: string; description: string; features: string[] }; seo: SeoMeta }>()
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
      <nav class="mt-12 flex gap-6" aria-label="Related pages">
        <a href="/en/services" class="underline">All services</a>
        <a href="/en/contact" class="underline">Contact us</a>
      </nav>
    </article>
  </main>
</template>
