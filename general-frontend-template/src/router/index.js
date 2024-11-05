import { createRouter, createWebHistory } from "vue-router"
import dayJS from "dayjs"
import jwtDecode from "jwt-decode"
import { canAccessRoute } from "@/utils/global-functions"
import { useAuthStore } from "../store/auth.store"

const routes = [
  {
    path: "/",
    name: "login",
    meta: { requiresAuth: false, title: "Iniciar sesión" },
    component: () => import("../views/auth/LoginView.vue"),
  },
  {
    path: "/login",
    name: "login",
    meta: { requiresAuth: false, title: "Iniciar sesión" },
    component: () => import("../views/auth/LoginView.vue"),
  },
  {
    path: "/",
    name: "layout",
    meta: { requiresAuth: true, title: "Layout" },
    component: () => import("@/views/layouts/DefaultLayout.vue"),
    children: [],
  },
  {
    path: "/forbidden",
    name: "forbidden",
    meta: { requiresAuth: false, title: "Acceso denegado" },
    component: () => import("../views/error/ForbiddenView.vue"),
  },
  {
    path: "/notFound",
    name: "not-found",
    meta: { requiresAuth: false, title: "Página no encontrada" },
    component: () => import("../views/error/NotFoundView.vue"),
  },
  {
    path: "/conexion-socket",
    name: "conexion-socket",
    meta: { requiresAuth: false, title: "Ejemplo de socket" },
    component: () => import("../views/ejemplos/SocketView.vue"),
  },
]

const router = new createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(async (to, from, next) => {
  document.title = to.meta?.title || "Template";
  const routesList = router.getRoutes();
  let token = null;

  const routeExists = routesList.find((route) => route.name === to.name);
  if (!routeExists) {
    next({ name: "not-found" });
    return;
  }

  const authStore = useAuthStore();

  if (to.meta?.requiresAuth) {
    token = localStorage.getItem("token");

    if (!token || (token && dayJS().unix() > jwtDecode(token).exp)) {
      localStorage.removeItem('token'); // Elimina el token si no existe o ha expirado
      if (to.name !== 'login') {
        next({ name: 'login' });
      } else {
        next();
      }
      return;
    }

    authStore.setAuth({ token });

    const authorized = await canAccessRoute(to);
    if (!authorized) {
      next({ name: "not-found" });
    } else {
      window.scrollTo(0, 0);
      next();
    }
  } else if (to.name === "login") {
    token = localStorage.getItem("token");
    if (token) {
      next({ name: "dashboard" });
    } else {
      next();
    }
  } else {
    next();
  }
});


export default router
