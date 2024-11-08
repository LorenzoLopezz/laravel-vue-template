<template>
  <BlockUI
    v-if="loading"
    class="fixed content !w-full !h-full flex !z-[3110010] justify-center items-center
      backdrop-blur-sm bg-gray-900 bg-opacity-30"
  >
    <template #default>
      <div class="spinner">
        <div class="dot1"></div>
        <div class="dot2"></div>
      </div>
    </template>
  </BlockUI>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { useRootStore } from "../store/index.store.js";
import { storeToRefs } from "pinia";
import BlockUI from 'primevue/blockui';

const useLoader = useRootStore();
const { loader } = storeToRefs(useLoader);

const loading = ref(false);

onMounted(() => {
  if (loader.value) {
    loading.value = true
    document.body.style.overflow = 'hidden';
  }
});

watch(loader, (newVal) => {
  if (newVal) {
    loading.value = true
    document.body.style.overflow = 'hidden';

  } else {
    loading.value = false
    document.body.style.overflow = 'auto';

  }
});
</script>
<style>

.spinner {
  margin: 100px auto;
  width: 40px;
  height: 40px;
  position: relative;
  text-align: center;

  -webkit-animation: sk-rotate 2.0s infinite linear;
  animation: sk-rotate 1.0s infinite linear;
}

.dot1, .dot2 {
  width: 60%;
  height: 60%;
  display: inline-block;
  position: absolute;
  top: 0;
  background-color: #222559;
  border-radius: 100%;

  -webkit-animation: sk-bounce 5.0s infinite ease-in-out;
  animation: sk-bounce 2.0s infinite ease-in-out;
}

.dot2 {
  top: auto;
  bottom: 0;
  -webkit-animation-delay: -1.0s;
  animation-delay: -1.0s;
}

@-webkit-keyframes sk-rotate { 100% { -webkit-transform: rotate(360deg) }}
@keyframes sk-rotate { 100% { transform: rotate(360deg); -webkit-transform: rotate(360deg) }}

@-webkit-keyframes sk-bounce {
  0%, 100% { -webkit-transform: scale(0.0) }
  50% { -webkit-transform: scale(1.0) }
}

@keyframes sk-bounce {
  0%, 100% {
    transform: scale(0.0);
    -webkit-transform: scale(0.0);
  } 50% {
      transform: scale(1.0);
      -webkit-transform: scale(1.0);
    }
}
</style>
