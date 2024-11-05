<template>
  <div
    :class="`overflow-y-auto fixed inset-0 z-10 ${
      show ? 'modal--show' : 'hidden'
    }`"
  >
    <div
      class="flex items-end justify-center min- px-4 pt-4 pb-20 text-center sm:block sm:p-0"
    >
      <div
        class="transition-opacity bg-slate-700 bg-opacity-75 fixed inset-0 backdrop-blur-sm"
        aria-hidden="true"
      ></div>
      <span class="sm:inline-block sm:align-middle"></span>

      <!-- https://tailwindcss.com/docs/max-width -->
      <div
        :class="`inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-2xl sm:my-8 sm:align-middle ${size} w-full p-4`"
      >
        <div class="card--header">
          <slot name="title"></slot>
        </div>

        <div class="card--body">
          <slot name="content"></slot>
        </div>

        <div class="card--footer px-3">
          <slot name="footer"></slot>
        </div>

        <div class="card--actions">
          <slot name="buttons"></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    default: null,
  },
  show: {
    type: Boolean,
    default: false,
  },
  size: {
    type: String,
    default: 'max-w-xl',
  },
});
</script>
