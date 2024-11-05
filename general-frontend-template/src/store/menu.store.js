import { defineStore } from "pinia"
import { ref } from "vue"

export const useMenuStore = defineStore('menu', () => {
  const menu_options = ref([]);
  const mostrar_sidebar = ref(false);

  const setMenuOptions = (options) => {
    menu_options.value = options;
  }

  const getMenuOptions = () => menu_options.value

  return {
    menu_options,
    mostrar_sidebar,
    setMenuOptions,
    getMenuOptions,
  }
})
