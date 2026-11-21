<script setup lang="ts">
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import type { NavItem } from '@/types'

/** Recursive collapsible tree for the off-canvas menu. */
defineProps<{ items: NavItem[]; depth?: number }>()
const emit = defineEmits<{ navigate: [] }>()

const open = ref<Record<number | string, boolean>>({})
</script>

<template>
  <ul class="flex flex-col" :class="(depth ?? 0) === 0 ? 'gap-2' : 'gap-1 border-s border-ink-800 ps-4'">
    <li v-for="item in items" :key="item.id">
      <div class="flex items-center justify-between gap-2">
        <Link
          v-if="item.url"
          :href="item.url"
          :target="item.target"
          class="block flex-1 rounded-sm py-3 text-ink-200 transition-colors duration-200 ease-brand hover:text-brand"
          :class="(depth ?? 0) === 0 ? 'text-title-lg' : 'text-body-lg'"
          @click="emit('navigate')"
        >
          {{ item.label }}
        </Link>
        <button
          v-else
          type="button"
          class="block flex-1 py-3 text-start text-ink-200"
          :class="(depth ?? 0) === 0 ? 'text-title-lg' : 'text-body-lg'"
          @click="open[item.id] = !open[item.id]"
        >
          {{ item.label }}
        </button>
        <button
          v-if="item.children.length"
          type="button"
          class="inline-flex size-10 items-center justify-center text-paper transition-transform duration-200"
          :class="open[item.id] ? '-rotate-90' : ''"
          :aria-expanded="!!open[item.id]"
          :aria-label="item.label"
          @click="open[item.id] = !open[item.id]"
        >
          ‹
        </button>
      </div>
      <MobileNavTree
        v-if="item.children.length && open[item.id]"
        :items="item.children"
        :depth="(depth ?? 0) + 1"
        @navigate="emit('navigate')"
      />
    </li>
  </ul>
</template>
