<template>
  <Tag
    :class="tagColorToClasses[tag.color]"
    :value="tag.name"
  />
</template>

<script setup lang="ts">
import { TagColor, type Tag as TagModel } from '@/types/models/Tag';
import Tag from 'primevue/tag';

const props = defineProps<{
  tag: TagModel;
}>();

const tagColorToClasses: Record<TagColor, string> = {
  [TagColor.Red]: '!bg-red-200 !text-red-700',
  [TagColor.Green]: '!bg-green-200 !text-green-700',
  [TagColor.Blue]: '!bg-blue-200 !text-blue-700',
  [TagColor.Yellow]: '!bg-yellow-200 !text-yellow-700',
  [TagColor.Purple]: '!bg-purple-200 !text-purple-700',
  [TagColor.Orange]: '!bg-orange-200 !text-orange-700',
  [TagColor.Black]: '!bg-gray-200 !text-gray-700',
};
</script>