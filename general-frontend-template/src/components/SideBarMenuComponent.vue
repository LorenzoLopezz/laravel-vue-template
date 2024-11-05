<script setup>
import { onMounted, watch } from "vue";
import { useMenuStore } from "@/store/menu.store";
import { useAuthStore } from "@/store/auth.store";
import { storeToRefs } from "pinia";
import Button from "primevue/button";

const useMenu = useMenuStore();
const { setMenuOptions } = useMenu;
const { menu_options, mostrar_sidebar } = storeToRefs(useMenu);

const useAuth = useAuthStore();
const { options_menu } = storeToRefs(useAuth);

//detecta algun cambio en el menú cuando se hace alguna modificación
// en el apartado de rutas del sistema
watch(options_menu, (newVal) => {
  setMenuOptions(newVal);
}, { immediate: true });

onMounted(() => {
  setMenuOptions(options_menu);
});
</script>
<template>
  <aside
    v-show="mostrar_sidebar"
    class="fixed top-0 left-0 z-40 sm:w-64 w-screen h-full bg-primary-tag overflow-y-auto"
  >
    <div
      class="h-full px-3 py-4 overflow-y-auto bg-primary md:rounded-r-xl text-white"
    >
      <div class="px-2 py-1 sm:hidden">
        <button
          @click="mostrar_sidebar =! mostrar_sidebar"
          type="button"
          class="inline-flex items-center text-sm rounded-lg focus:outline-none"
        >
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      <div class="pl-2 pr-4 pb-4 mt-8">
        <img
          src="@/assets/images/square-glasses.png"
          alt="Sin logo"
          class="mx-auto"
        />
      </div>
      <ul class="space-y-2 font-medium">
        <li v-for="item in options_menu" :key="item.id">
          <router-link
            v-if=" (!item?.children || item?.children?.length === 0) && item.meta.show "
            :to="item?.path"
            class="flex items-center p-2 rounded-lg dark:text-white hover:bg-info-500 dark:hover:bg-gray-700 group"
          >
            <span class="material-symbols-outlined">{{ item?.meta?.icon }}</span>
            <span class="ml-3">{{ item?.meta?.title }}</span>
          </router-link>
          <div v-else-if="item.meta.show">
            <button
              @click="item.mostrar =! item.mostrar"
              type="button"
              class="flex items-center w-full p-2 text-base transition duration-75 rounded-lg group hover:bg-info-500 dark:text-white dark:hover:bg-gray-700"
            >
              <span class="material-symbols-outlined">{{ item?.meta?.icon }}</span>
              <span class="flex-1 ml-3 text-left whitespace-nowrap">{{
                item?.meta?.title
              }}</span>
              <span class="material-symbols-outlined text-xl">{{
                item.mostrar ? "expand_less" : "expand_more"
              }}</span>
            </button>
            <ul
              id="dropdown-example"
              class="py-2 space-y-2"
              v-show="item.mostrar"
            >
              <li v-for="subitem in item?.children">
                <router-link
                  :to="subitem.path"
                  class="flex items-center w-full p-2 transition duration-75 rounded-lg pl-11 group hover:bg-info-500 dark:text-white dark:hover:bg-gray-700"
                >
                  {{ subitem?.meta?.title }}
                </router-link>
              </li>
            </ul>
          </div>
        </li>
      </ul>
      <div class="flex justify-center">
        <Button
          label="Cerrar sesión"
          icon="pi pi-power-off"
          class="btn btn--primary mt-4 absolute bottom-4 w-10/12 place-self-center"
          @click="useAuth.logout()"
        />
      </div>
    </div>
  </aside>
</template>
