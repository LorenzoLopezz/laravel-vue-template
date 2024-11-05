<script setup>
import { ref, onMounted } from 'vue';
import lottie from 'lottie-web';
import jsonData from '@/assets/images/Animation404.json';

const estaAutenticado = ref(false);

onMounted(() => {
  const token = localStorage.getItem('token');
  if (token) {
    estaAutenticado.value = true;
  }

  lottie.loadAnimation({
    container: document.getElementById('lottie-animation'),
    animationData: jsonData,
    renderer: 'svg',
    loop: true,
    autoplay: true,
    name: '404 Animation',
  });
});

</script>

<template>
  <div class="w-screen h-screen flex-1 items-center justify-center">
    <router-link to="/dashboard" class="flex items-center absolute mt-5 mx-4 md:m-20 z-20" v-if="estaAutenticado">
      <i class="material-icons text-4xl text-primary">arrow_back</i>
      <h1 class="lg:text-2xl font-bold text-primary">Regresar al dashboard</h1>
    </router-link>
    <router-link to="/login" class="flex items-center absolute mt-5 mx-4 md:m-20 z-20" v-else>
      <i class="material-icons text-4xl text-primary">arrow_back</i>
      <h1 class="lg:text-2xl font-bold text-primary">Ir a Login</h1>
    </router-link>
    <div id="lottie-animation" class="h-full w-full z-10"></div>
  </div>
</template>
