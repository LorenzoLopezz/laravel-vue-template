<template>
  <Menubar style="border-radius: 0px;" class="border-0 border-b bg-background" >
    <template #start>
      <div class="inline-flex align-items-center gap-2 px-2 py-2">
        <Button
          @click="mostrar_sidebar = !mostrar_sidebar"
          text rounded
          class="text-sm rounded-lg focus:outline-none mx-4"
        >
          <Icon name="menu" class="text-primary" />
        </Button>
        <template v-if="mostrarIcono">
          <IconField iconPosition="left" v-if="isSM == false">
            <InputIcon class="pi pi-search"> </InputIcon>
            <InputText placeholder="Buscar asignación" type="text" class="w-8rem sm:w-auto input-form" />
          </IconField>
        </template>
        <template v-else>
          <Button  v-if="isSM == false"
            @click="goToRedirect()"
            text rounded
            class="text-sm rounded-lg focus:outline-none mx-4"
          >
            <Icon name="arrow-back" class="text-primary" />
          </Button>
        </template>
      </div>
    </template>
    <template #end>
      <div class="flex align-items-center gap-2">
        <div class="my-auto mx-5">
          <p class="text-xs sm:text-sm font-bold text-right">{{ nombreCompleto }}</p>
          <p class="text-xs sm:text-sm text-gray-400 text-right">{{ perfiles }}</p>
        </div>
        <div class="aspect-w-1 aspect-h-1">
          <Avatar class="object-cover" image="https://primefaces.org/cdn/primevue/images/avatar/amyelsner.png"
          :class="isSM == true ? 'cursor-pointer' : 'cursor-auto'"
          :size="isSM == false ? 'xlarge' : 'medium'"
          @click="handleClick"
          shape="circle" />
        </div>
        <OverlayPanel ref="op" class="pa-0 ma-0">
          <Menu :model="items" class="w-full md:w-15rem border-none">
            <template #start>
              <div class="align-items-center gap-2">
                <span class="text-primary font-bold block mb-2">Buscar asignación</span>
                <IconField iconPosition="left">
                  <InputIcon class="pi pi-search"> </InputIcon>
                  <InputText type="text" class="max-w-[264px] input-form" />
                </IconField>
              </div>
            </template>
            <template #end>
              <div class="align-items-center gap-2 mt-4">
                <a v-ripple class="flex align-items-center p-ripple" @click="goToRedirect()">
                    <Icon name="arrow-back" class="text-primary" />
                    <span class="ml-2 text-primary">Regresar</span>
                </a>
              </div>
            </template>
          </Menu>
        </OverlayPanel>
      </div>
    </template>
  </Menubar>
</template>

<script setup>
import Menubar from 'primevue/menubar';
import Avatar from 'primevue/avatar';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Menu from 'primevue/menu';
import OverlayPanel from 'primevue/overlaypanel';
import Button from "primevue/button";

import {useAuthStore} from "@/store/auth.store.js";
import { useMenuStore } from "@/store/menu.store";
import { storeToRefs } from "pinia";
import { useRoute } from 'vue-router';
import { useRouter } from 'vue-router'

import {ref, onMounted, onUnmounted, computed} from "vue";

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

const handleClick = (event) => {
  if (isSM.value == true) {
    op.value.toggle(event);
  }
};

const mostrarIcono = computed(() => route.path === rutaEspecifica);

// Computed para verificar si la ruta actual coincide con las rutas especificadas
const esRutaValida = computed(() => {
    const path = route.path;

    // Comprobar las rutas específicas y retornar un booleano
    const esDashboard = /^\/proyecto\/[^/]+\/dashboard$/.test(path);
    const esConfiguracionConId = /^\/configuracion-proyecto\/[^/]+$/.test(path);
    const esConfiguracion = path === '/configuracion-proyecto';

    // Retorna true si alguna de las condiciones se cumple, false en caso contrario
    return esDashboard || esConfiguracionConId || esConfiguracion;
});

const nombreCompleto = computed(() => {
  return `
    ${user_info.value.primer_nombre} ${user_info.value.segundo_nombre}
    ${user_info.value.primer_apellido} ${user_info.value.segundo_apellido}`
});
const perfiles = computed(() => {
  return user_info.value.perfiles?.map(perfil => perfil.nombre)
  .slice(0, 2).join(', ') + (user_info.value.perfiles?.length > 2 ? '...' : '');
});

const goToRedirect = async () => {
  // Aquí puedes realizar una acción específica si la ruta es válida
  if (esRutaValida.value) {
    // Acción a realizar si la ruta coincide
    router.replace({ name: 'dashboard' });
  }else{
    router.go(-1);
  }
}
</script>
