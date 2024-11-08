<template>
  <div class="h-screen p-8">
    <h1 class="block text-center text-xl">Bienvenido a la plantilla frontend</h1>
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
