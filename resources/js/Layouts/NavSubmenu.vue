<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import type { NavItem } from '@/types'

/**
 * Recursive desktop dropdown (WordPress-style): each level opens on
 * hover/focus-within; deeper levels fly out to the inline end.
 *
 * UX details:
 *  - a transparent bridge (pt-3 / ps-2) covers the gap between the trigger
 *    and the panel, so the pointer can travel into it without it closing;
 *  - closing is delayed 150ms (opening is instant), which forgives a
 *    slightly diagonal mouse path into a fly-out;
 *  - keyboard: Tab into any item opens its level via focus-within;
 *  - the fly-out chevron is mirrored for RTL.
 */
defineProps<{ items: NavItem[]; depth?: number; isActive?: (item: NavItem) => boolean }>()
</script>

<template>
  <div
    class="invisible absolute z-header opacity-0 transition-[opacity,visibility,transform] delay-150 duration-200 ease-brand
           group-focus-within/nav:visible group-focus-within/nav:translate-y-0 group-focus-within/nav:opacity-100 group-focus-within/nav:delay-0
           group-hover/nav:visible group-hover/nav:translate-y-0 group-hover/nav:opacity-100 group-hover/nav:delay-0"
    :class="(depth ?? 0) === 0 ? 'top-full start-0 translate-y-1 pt-3' : 'top-0 start-full -mt-2 translate-y-0 ps-2'"
  >
    <ul class="min-w-[220px] rounded-md border border-ink-800 bg-ink-1000/95 p-2 shadow-lg backdrop-blur-header">
      <li v-for="child in items" :key="child.id" class="group/nav relative">
        <component
          :is="child.url ? Link : 'span'"
          :href="child.url || undefined"
          :target="child.url ? child.target : undefined"
          :aria-current="isActive?.(child) ? 'page' : undefined"
          class="flex items-center justify-between gap-6 whitespace-nowrap rounded-sm px-3 py-2.5 text-body-lg transition-colors duration-200 ease-brand
                 hover:bg-ink-900 hover:text-brand focus-visible:bg-ink-900 focus-visible:text-brand focus-visible:outline-none"
          :class="isActive?.(child) ? 'text-brand' : 'text-ink-200'"
          :tabindex="child.url ? undefined : 0"
        >
          {{ child.label }}
          <svg
            v-if="child.children.length"
            class="size-3 shrink-0 opacity-70 rtl:-scale-x-100"
            viewBox="0 0 12 12"
            fill="none"
            aria-hidden="true"
          >
            <path d="M4.5 2.5 8 6l-3.5 3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
          </svg>
        </component>
        <NavSubmenu
          v-if="child.children.length"
          :items="child.children"
          :depth="(depth ?? 0) + 1"
          :is-active="isActive"
        />
      </li>
    </ul>
  </div>
</template>
