<template>
  <div class="w-full flex justify-center items-center">
    <div class="w-full min-h-screen flex-1 flex justify-start items-start flex-col bg-background">
      <h1>Hola mundo</h1>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/store/auth.store'

const authStore = useAuthStore();
const { $reset } = authStore;
const router = useRouter();

const pantallaPeq = computed(() => {
  return window.innerWidth < 768;
});

const logout = () => {
  $reset();
  router.replace({ name: 'login' });
}
</script>
