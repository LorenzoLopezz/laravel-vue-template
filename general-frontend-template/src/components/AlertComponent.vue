<template>
  <div :class="`alert ${type}`">
    <Icon :name="icon" class="alert--icon" />
    <div>
      {{ content }}
    </div>
  </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  content: {
    type: String,
    default: '',
  },
  type: {
    type: String,
    default: "alert-info",
  },
});

const icon = computed(() => {
  switch (`alert-${props.type}`) {
    case "info":
      return "info";
    case "success":
      return "done";
    case "warning":
      return "warning";
    case "danger":
      return "error";
    default:
      return "info";
  }
});

</script>
