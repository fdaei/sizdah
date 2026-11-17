<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import type { NavItem } from '@/types'

/**
 * Recursive desktop dropdown (WordPress-style): each level opens on
 * hover/focus-within; deeper levels fly out to the inline end.
 */
defineProps<{ items: NavItem[]; depth?: number }>()
</script>

<template>
  <ul
    class="invisible absolute z-header min-w-[200px] rounded-sm border border-ink-800 bg-ink-1000/95 py-2 opacity-0 shadow-lg backdrop-blur-header transition-opacity duration-200 ease-brand group-focus-within/nav:visible group-focus-within/nav:opacity-100 group-hover/nav:visible group-hover/nav:opacity-100"
    :class="(depth ?? 0) === 0 ? 'top-full start-0 mt-2' : 'top-0 start-full ms-1'"
  >
    <li v-for="child in items" :key="child.id" class="group/nav relative">
      <component
        :is="child.url ? Link : 'span'"
        :href="child.url || undefined"
        :target="child.url ? child.target : undefined"
        class="flex items-center justify-between gap-4 whitespace-nowrap px-4 py-2 text-body-lg text-ink-200 transition-colors duration-200 ease-brand hover:text-brand"
        :tabindex="child.url ? undefined : 0"
      >
        {{ child.label }}
        <span v-if="child.children.length" aria-hidden="true" class="text-xs">‹</span>
      </component>
      <NavSubmenu v-if="child.children.length" :items="child.children" :depth="(depth ?? 0) + 1" />
    </li>
  </ul>
</template>
