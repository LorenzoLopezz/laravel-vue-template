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
</template>

<script setup>
import { useMenuStore } from "@/store/menu.store";
import { storeToRefs } from "pinia";

const useMenu = useMenuStore();
const { mostrar_sidebar } = storeToRefs(useMenu);
</script>
