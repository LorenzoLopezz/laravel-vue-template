import { createApp } from 'vue'
import dayjs from 'dayjs'
import router from './router'
import App from './App.vue'
import PrimeVue from 'primevue/config'
import { createPinia } from "pinia"
import { setupCalendar, Calendar, DatePicker } from 'v-calendar'
import ToastService from 'primevue/toastservice';
import Toast from 'primevue/toast';
import Tooltip from 'primevue/tooltip';
import Ripple from 'primevue/ripple';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import vSelect from "vue-select";

// Estilos globales
import "@/assets/styles/main.css"
import 'primevue/resources/themes/lara-light-teal/theme.css'
import "vue-select/dist/vue-select.css";
import {useAuthStore} from "./store/auth.store.js";


const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
pinia.use(piniaPluginPersistedstate)

const authStore = useAuthStore()
authStore.addRoutesToRouter();

app.use(router)

app.use(PrimeVue, {
  pt: '',
  ripple: true,
  locale: {
    monthNames: [
        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio",
        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
    ],
    monthNamesShort: [
        "Ene", "Feb", "Mar", "Abr", "May", "Jun",
        "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"
    ],
    firstDayOfWeek: 0,
    dayNames: [
        "Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"
    ],
    dayNamesShort: [
        "Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"
    ],
    dayNamesMin: [
        "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"
    ],
    today: 'Hoy',
    clear: 'Limpiar',
    emptyMessage: 'No hay datos',
}
})
app.use(setupCalendar, {})
app.use(ToastService);
// THIS IS IMPORTANT
app.component('Toast', Toast);
app.directive('tooltip', Tooltip);
app.directive('ripple', Ripple);
app.component("v-select", vSelect);

// Registro de componentes generales
const components = import.meta.globEager('./components/**/*.vue')
Object.entries(components).forEach(([path, definition]) => {
  const componentName = path.split('/').pop().replace(/\.\w+$/, '')
  app.component(componentName.replace('Component', ''), definition.default)
})

app.component('VCalendar', Calendar)
app.component('VDatePicker', DatePicker)

// Funciones globales
app.config.globalProperties.$dayjs = dayjs

app.config.errorHandler = (err, vm, info) => {
  console.error('¡ADVERTENCIA!', err);
  console.error('Información de ViewModel:', vm);
  console.error('Información adicional:', info);
}

app.mount('#app')
