<template>
  <div class="w-full h-screen bg-[#ebf3ff] p-8 space-y-6">
    <div class="space-y-5 md:space-y-0 md:flex md:justify-between">
      <h1 class="title-4xl">Rutas</h1>
      <div class="flex justify-end">
        <Button
          icon="pi pi-plus-circle"
          label="Nueva ruta"
          class="btn btn--primary"
          @click="dialogForm = true"
        />
      </div>
    </div>
    <data-table-component
      :datos-encabezados="encabezados"
      :lista-datos="listadoRutas"
      requiere-busqueda
      placeholder-busqueda="Buscar por path o por nombre"
      @buscar="busquedaRutas"
      :listar-mas="false"
    >
      <template #uri="{ item }">
        <span v-if="item.uri !== ''">{{ item.uri }}</span>
        <span v-else class="text-text-secondary">N/A</span>
      </template>
      <template #parent="{ item }">
        <span :class="item.parent === 'N/A' ? 'text-text-secondary': ''">{{ item.parent }}</span>
      </template>
      <template #acciones="{ item }">
        <div class="flex justify-evenly space-x-4">
          <Button
            text
            rounded
            v-tooltip.bottom="'Editar ruta'"
            aria-label="Editar ruta"
            size="small"
            @click="handlerModalEditar(item)"
            class="!px-0 aspect-square flex justify-center border-0"
          >
            <i class="material-icons text-primary">edit</i>
          </Button>
          <Button
            text rounded
            v-tooltip.bottom="'No mostrar'"
            aria-label="No mostrar"
            size="small"
            @click="toggleMostrarRuta(item)"
            class="!px-0 aspect-square flex justify-center border-0"
          >
            <i v-if="item.mostrar == 'Si'" class="material-icons text-primary">visibility_off</i>
            <i v-else class="material-icons text-primary">visibility</i>
          </Button>
          <Button
            text
            rounded
            v-tooltip.bottom="'Eliminar ruta'"
            aria-label="Eliminar ruta"
            size="small"
            @click="handlerModalEliminar(item)"
            class="!px-0 aspect-square flex justify-center border-0"
          >
            <i class="material-icons text-danger">delete</i>
          </Button>
        </div>
      </template>
    </data-table-component>
  </div>

  <crear-o-actualizar-component
    :visible="dialogForm"
    :rutaId="rutaId"
    :editar="editar"
    @update:visible="handlerModalEditar"
    @recargar="listarRutas()"
    :listaRutas="listadoRutas"
  />

  <modal-confirmacion-component
    titulo="Eliminar ruta"
    descripcion="¿Está seguro de eliminar la ruta?"
    :visible-modal="alertaEliminar"
    @update:visible="alertaEliminar = $event"
    @aceptar="eliminarRuta()"
    @cancelar="alertaEliminar = false; rutaId = null"
  />

</template>

<script setup>
import {defineComponent, reactive, ref, onMounted} from "vue";

//servicios
import rutaServices from "@/services/rutas.services.js";
import { useToastService } from "@/utils/toastApp.js";

//components primevue
import Button from "primevue/button";
import DataTableComponent from "@/components/DataTableComponent.vue";
import CrearOActualizarComponent from "@/views/rutas/components/CrearOActualizarComponent.vue";

//componentes locales
import modalConfirmacionComponent from "@/components/modals/ModalConfirmacionComponent.vue";

defineComponent({
  modalConfirmacionComponent,
})

const { successToast } = useToastService();
const dialogForm = ref(false);
const editar = ref(false);
const rutaId = ref(null);
const alertaEliminar = ref(false);
const filtro = reactive({
  page: 1,
  busqueda: '',
})

const encabezados = [
  { name:'Nombre', value: 'nombre', classCol: 'w-1/4' },
  { name:'Path', value: 'uri', classCol: 'w-1/4' },
  { name:'Depende de', value: 'parent', classCol: 'w-1/4' },
  { name: 'Mostrar', value: 'mostrar', classCol: 'w-1/4' },
  { name:'Modulo', value: 'modulo', classCol: 'w-1/4' },
  { name:'Acciones', value: 'acciones', classCol: 'w-1/4' },
]
const listadoRutas = ref([])

onMounted(() => {
  listarRutas()
})

const handlerModalEditar = (item = null) => {
  if (item) {
    rutaId.value = item.id
    dialogForm.value = !dialogForm.value
    editar.value = true
  } else {
    rutaId.value = null
    dialogForm.value = !dialogForm.value
    editar.value = false
  }
};

const eliminarRuta = async () => {
  const { status } = await rutaServices.eliminarRuta(rutaId.value)

  if (status === 200 || status === 204) {
    successToast('Ruta eliminada', 'La ruta ha sido eliminada correctamente')
    alertaEliminar.value = false
    listarRutas();
  }

  rutaId.value = null
}

const toggleMostrarRuta = async (item) => {
  const { status } = await rutaServices.toogleMostarRuta(item.id, { mostrar: item.mostrar === 'Si' ? false : true })

  if (status === 200) {
    successToast('Ruta actualizada', 'La ruta ha sido actualizada correctamente')
    listarRutas()
  }
}

const busquedaRutas = async (busqueda) => {
  filtro.busqueda = busqueda
  filtro.page = 1
  await listarRutas()
}

const cargarMasRutas = async () => {
  filtro.page++
  await listarRutas()
}

const listarRutas = async () => {
  const { data } = await rutaServices.listarRutas(filtro)
  listadoRutas.value = data
}

const handlerModalEliminar = (item) => {
  alertaEliminar.value = true
  rutaId.value = item.id
}

</script>

<style scoped lang="scss">

</style>
