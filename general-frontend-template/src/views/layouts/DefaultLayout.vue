<template>
  <SideBarMenu />
  <div
    class="relative padding-custom-course"
    :class="[
      mostrar_sidebar ? 'sm:ml-64' : ''
    ]"
  >
    <NavBar />
    <router-view />
  </div>
  <div
    class="fixed bottom-0 w-full z-[99] mt-12 bg-white footer-custom"
    :class="[mostrar_sidebar ? 'sm:ml-36' : '']"
  >
  </div>
</template>

<script setup>
import { useMenuStore } from "@/store/menu.store";
import { storeToRefs } from "pinia";

const useMenu = useMenuStore();
const { mostrar_sidebar } = storeToRefs(useMenu);
</script>
