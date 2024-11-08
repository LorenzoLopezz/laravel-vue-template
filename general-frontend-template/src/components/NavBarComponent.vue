<template>
  <Menubar class="border-0 rounded-none">
    <template #start>
      <div class="inline-flex align-items-center items-center gap-2 px-2 py-2">
        <Button @click="mostrar_sidebar = !mostrar_sidebar" text rounded
          class="text-sm rounded-lg focus:outline-none mx-4">
          <Icon name="menu" class="text-primary" />
        </Button>

        <img src="@/assets/images/square-glasses.png" alt="Sin logo" class="h-14 w-14" />
      </div>
    </template>
    <template #end>
      <div class="flex align-items-center gap-2">
        <div class="my-auto mx-5" v-if="isSM == false">
          <p class="text-xs sm:text-sm font-bold text-right">{{ nombreCompleto }}</p>
          <p class="text-xs sm:text-sm text-gray-400 text-right">{{ perfiles }}</p>
        </div>
        <div class="aspect-w-1 aspect-h-1">
          <Avatar class="object-cover" image="/src/assets/images/usuario.png" size="xlarge" @click="handleClick" />
        </div>
      </div>
    </template>
  </Menubar>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue"
import { useRouter, useRoute } from "vue-router"

import Menubar from 'primevue/menubar'
import Avatar from 'primevue/avatar'
import Button from "primevue/button"

import { useAuthStore } from "@/store/auth.store.js"
import { useMenuStore } from "@/store/menu.store"
import { storeToRefs } from "pinia"

const useMenu = useMenuStore();
const { mostrar_sidebar } = storeToRefs(useMenu);

const useAuth = useAuthStore();
const { user_info } = storeToRefs(useAuth)
const route = useRoute();
const router = useRouter();

const menu = ref();
const op = ref();
const isSM = ref(false);

const items = ref([
  {
    separator: true
  }
]);

const rutaEspecifica = '/dashboard';

function handleResize() {
  isSM.value = window.innerWidth < 640; // 640px es el breakpoint para 'sm'
}

onMounted(() => {
  handleResize(); // Establecer el valor inicial
  window.addEventListener('resize', handleResize); // Escuchar cambios en el tamaño de la pantalla
});

onUnmounted(() => {
  window.removeEventListener('resize', handleResize); // Limpiar el listener cuando el componente se desmonte
});

const nombreCompleto = computed(() => {
  return `
    ${user_info.value.primer_nombre} ${user_info.value.segundo_nombre ?? ''}
    ${user_info.value.primer_apellido} ${user_info.value.segundo_apellido ?? ''}`
});
const perfiles = computed(() => {
  return user_info.value.perfiles?.map(perfil => perfil.nombre)
    .slice(0, 2).join(', ') + (user_info.value.perfiles?.length > 2 ? '...' : '');
});
</script>
