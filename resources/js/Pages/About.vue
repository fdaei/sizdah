<script setup lang="ts">
import { computed } from 'vue'
import Eyebrow from '@/Components/Eyebrow.vue'
import SeoHead from '@/Components/SeoHead.vue'
import { useTranslations } from '@/Composables/useTranslations'
import type { PageSectionData, SectionMap, SeoMeta, TeamMemberItem } from '@/types'

/**
 * About.
 *
 * DERIVED — no Sizdah frame exists for this route (see FIGMA/state.json
 * derivedScreens). Rather than invent a bespoke layout, this renders whatever
 * sections the `about` Page has, in their authored order, through one generic
 * block: eyebrow, title, description, optional rich content, optional items as
 * cards. Editors keep full control and nothing is hardcoded. Replace when a
 * frame is designed.
 */
const props = defineProps<{
  sections: SectionMap
  team: TeamMemberItem[]
  seo: SeoMeta
}>()

const { t } = useTranslations()

const blocks = computed<PageSectionData[]>(() =>
  Object.values(props.sections).filter(
    (section): section is PageSectionData => section !== undefined,
  ),
)
</script>

<template>
  <SeoHead :seo="props.seo" />

  <div class="section-first pb-24">
    <div class="container-sizdah flex flex-col gap-24">
      <section
        v-for="(section, index) in blocks"
        :key="section.type"
        class="flex flex-col gap-6"
      >
        <Eyebrow v-if="section.eyebrow" :text="section.eyebrow" />

        <component
          :is="index === 0 ? 'h1' : 'h2'"
          v-if="section.title"
          class="max-w-[831px]"
          :class="index === 0 ? 'text-display-lg text-ink-50' : 'text-heading-lg text-ink-50'"
        >
          {{ section.title }}
        </component>

        <p v-if="section.description" class="max-w-[831px] text-title-md text-ink-200">
          {{ section.description }}
        </p>

        <!-- eslint-disable-next-line vue/no-v-html -- admin-authored rich text -->
        <div v-if="section.content" class="rich-prose max-w-[831px]" v-html="section.content" />

        <img
          v-if="section.image"
          :src="section.image.src"
          :srcset="section.image.srcset"
          :alt="section.image.alt"
          :width="section.image.width"
          :height="section.image.height"
          loading="lazy"
          class="w-full rounded-lg object-cover"
        />

        <div v-if="section.items.length" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
          <div
            v-for="item in section.items"
            :key="item.id"
            class="card-surface flex flex-col gap-2 p-6"
          >
            <p v-if="item.value" class="text-display-sm text-brand latin-nums">
              {{ item.value }}<span v-if="item.suffix">{{ item.suffix }}</span>
            </p>
            <h3 v-if="item.title" class="text-title-lg text-ink-50">{{ item.title }}</h3>
            <p v-if="item.description" class="text-body-md text-ink-300">{{ item.description }}</p>
          </div>
        </div>
      </section>

      <section v-if="props.team.length" class="flex flex-col gap-6">
        <h2 class="text-heading-lg text-ink-50">{{ t('common.team') }}</h2>
        <ul class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
          <li v-for="member in props.team" :key="member.name" class="flex flex-col gap-4">
            <img
              v-if="member.image"
              :src="member.image.src"
              :srcset="member.image.srcset"
              :alt="member.image.alt"
              :width="member.image.width"
              :height="member.image.height"
              loading="lazy"
              class="aspect-square w-full rounded-lg object-cover"
            />
            <div class="flex flex-col gap-1">
              <p class="text-title-lg text-ink-50">{{ member.name }}</p>
              <p class="text-body-md text-ink-300">{{ member.role }}</p>
            </div>
          </li>
        </ul>
      </section>
    </div>
  </div>
</template>
