<template>
  <span :class="`material-symbols-outlined text-[28px]`">{{ name?.replaceAll('-', '_') }}</span>
</template>

<script setup>
const props = defineProps({
  name: {
    type: String,
    required: true
  },
})
</script>
