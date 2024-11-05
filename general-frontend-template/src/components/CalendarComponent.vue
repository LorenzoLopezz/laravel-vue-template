<template>
  <VDatePicker
    v-model="date"
    :mode="mode"
    :select-attributes="attributes"
    :popover="false"
    @update:modelValue="$emit('update:modelValue', dayjs(date).format('YYYY-MM-DD HH:mm:ss'))"
  >
    <template #default="{ togglePopover, inputValue, inputEvents }">
      <div
        class="flex overflow-hidden"
      >
        <input
          readonly
          :value="inputValue"
          v-on="inputEvents"
          :class="`flex-grow px-2 p-2.5 bg-white rounded-l-lg ${inputClass}`"
          :placeholder="placeholder"
          @focus="() => togglePopover()"
        />
        <button
          type="button"
          class="flex justify-center items-center p-2.5 text-accent-700 border-r border-primary-500 bg-primary-500 hover:bg-primary-700 rounded-r-lg"
          @click="() => togglePopover()"
        >
          <Icon name="calendar-month" class="text-white text-base"/>
        </button>
      </div>
    </template>
  </VDatePicker>

  <p class="mt-1 text-xs text-danger-500">
    <span v-for="(error, index) in errors" :key="index" class="block py-1">{{ error.$message }}</span>
  </p>
</template>

<script setup>
import { ref, computed, onMounted, getCurrentInstance } from "vue";

const props = defineProps({
  modelValue: {
    type: String,
    required: true,
  },
  placeholder: {
    type: String,
    default: "",
  },
  class: {
    type: String,
    default: "input-group--field",
  },
  mode: {
    type: String,
    default: "single",
  },
  attributes: {
    type: Object,
    default: () => [
      {
        key: "today",
        highlight: true,
        dates: new Date(),
      },
    ],
  },
  errors: {
    type: Array,
    default: [],
  },
});

const inputClass = computed(() => {
  const classes = [];
  if (props.errors?.length > 0) classes.push("border-danger-500");
  return `${classes.join(" ")} ${props.class}`;
});

const dayjs = (getCurrentInstance()).appContext.config.globalProperties.$dayjs;

const date = ref(new Date());

const emit = defineEmits(['update:modelValue']);

onMounted(() => {
  emit('update:modelValue', dayjs(date.value).format('YYYY-MM-DD HH:mm:ss'))
})
</script>
