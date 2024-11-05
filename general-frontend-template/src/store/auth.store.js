// utilities
import { reactive, defineAsyncComponent } from "vue";
import { defineStore } from "pinia";
import router from "@/router";

// services
import authService from "@/services/auth.services";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user_info: {},
    options_menu: reactive([]),
    paths_st: [],
    permissions: [],
    dynamicRoutes: reactive([]),
  }),
  persist: true,
  actions: {
    async login(payload) {
      const response = await authService.login(payload?.username, payload?.password);

      if (response?.data) {
        this.setAuth(response?.data);
        await Promise.all([this.getInfoUser(), this.getPermissions(), this.getMenu()]);
      }
    },

    setAuth(payload) {
      const { token, refresh_token } = payload;
      localStorage.setItem("token", token);
      if (!localStorage.getItem("refresh_token")) {
        localStorage.setItem("refresh_token", refresh_token);
      }
    },

    // Función para clasificar y obtener solo las rutas enrutables
    classifyRoutes(routes) {
      return routes.reduce((enrutables, route) => {
        if (route.path_component && route.path) {
          enrutables.push(route); // Agrega ruta enrutable
        }

        if (route.children && route.children.length > 0) {
          const childRoutes = this.classifyRoutes(route.children); // Recursividad para hijos
          enrutables.push(...childRoutes); // Agrega rutas enrutables de los hijos
        }

        return enrutables;
      }, []);
    },

    // Función para procesar las rutas y establecer rutas dinámicas
    setDynamicRoutes() {
      this.dynamicRoutes = this.classifyRoutes(this.options_menu); // Clasifica solo rutas enrutables
      if (this.dynamicRoutes.length) {
        this.addRoutesToRouter();
      }
    },

    // Agregar dinámicamente las rutas al router
    addRoutesToRouter() {
      if (!this.dynamicRoutes.length) return;
      this.dynamicRoutes.forEach((route) => {
        route.component = route.path_component && (() => import(/* @vite-ignore */ `${route.path_component}`));
        if (route.path && route.component) {
          router.addRoute('layout', route); // Agrega la ruta bajo el layout principal
        }
      });
    },

    async getMenu() {
      try {
        const { data, status } = await authService.getMenu();
        if (status !== 200) return;

        // Función para procesar los elementos del menú y construir el menú dinámico
        // de la aplicacion y las ruta dinámicas de VueRouter
        const processMenuItem = (item) => ({
          id: item.id,
          path: item.uri,
          name: item.nombre_unico,
          path_component: item.componente || null,
          meta: {
            show: item.mostrar_menu,
            icon: item?.icono,
            requiresAuth: item.requiere_autenticacion,
            title: item.nombre,
            canAccess: item?.permisos?.map((permiso) => permiso.nombre) || [],
          },
          children: item?.children?.map(processMenuItem) || [],
        });

        this.options_menu = data.map(processMenuItem);

        if (this.options_menu.length) {
          this.setDynamicRoutes(); // Procesa y establece rutas dinámicas después de obtener el menú
        }
      } catch (error) { }
    },

    async getInfoUser() {
      const { data, status } = await authService.getInfoUser();
      if (status !== 200) return;
      this.user_info = data;
    },

    async getPermissions() {
      const { data, status } = await authService.getPermissions();
      if (status !== 200) return;
      this.permissions = data;
    },

    async logout() {
      const { status } = await authService.logout();
      if (status !== 200) return;
      this.$reset();
      router.push({ name: "login" });
    },

    $reset() {
      localStorage.removeItem("token");
      localStorage.removeItem("refresh_token");
      localStorage.removeItem("user_info");
      localStorage.removeItem("permissions");
      localStorage.removeItem("auth");
      this.user_info = {};
      this.permissions = [];
      this.options_menu = [];
      this.paths_st = [];
      this.dynamicRoutes = [];
    },
  },
});
