<template>
  <Toast />
  <div class="w-full h-screen bg-[#ebf3ff] p-8 space-y-6">
    <div class="flex justify-between">
      <h1 class="title-4xl">Permisos</h1>
      <div class="flex justify-end">
        <Button
          icon="pi pi-plus-circle"
          label="Nuevo permiso"
          class="btn btn--primary"
          @click="dialogForm = true"
        />
      </div>
    </div>
    <div>
      <data-table
        :listaDatos="listaPermisos"
        :datosEncabezados="encabezados"
        :listarMas="cargarMas"
        @cargarMas="listarPermisos"
        requiereBusqueda
        @buscar="buscarUsuarios"
        placeholder-buscador="Buscar permiso"
      >
        <template #acciones="{item}">
          <div class="flex justify-evenly">
            <Button
              text
              rounded
              v-tooltip.bottom="'Editar perfil'"
              aria-label="Editar perfil"
              size="small"
              @click="handlerModalEditar(item)"
              class="!px-0 aspect-square flex justify-center border-0"
            >
              <i class="material-icons text-primary">edit</i>
            </Button>
            <Button
              text
              rounded
              v-tooltip.bottom="'Eliminar perfil'"
              aria-label="Eliminar perfil"
              size="small"
              @click="handlerModalEliminar(item.id)"
              class="!px-0 delete flex justify-center border-0"
            >
              <i class="material-icons text-danger">delete</i>
            </Button>
          </div>
        </template>
      </data-table>
    </div>

    <formulario-component
      :visible="dialogForm"
      :editar="editar"
      :permisoId="permisoId"
      @update:visible="handlerModalEditar"
      @recargar="listarPermisos(true)"
    />

    <Dialog
      v-model:visible="modalEliminar"
      modal
      closable
      block-scroll
      :auto-z-index="false"
      :draggable="false"
      class="md:w-[30%] w-[95%]"
    >
      <template #header>
        <h1 class="title-2xl">Eliminar permiso</h1>
      </template>
      <template #closeicon>
        <i class="material-icons" @click="modalEliminar = false">close</i>
      </template>

      <div class="space-y-6">
        <div>
          <p class="break-words text-justify">
            Se eliminará el permiso seleccionado.
          </p>
        </div>
        <div class="flex gap-5">
          <Button
            type="button"
            label="Cancelar"
            outlined
            class="btn btn-outline--primary"
            @click="handlerModalEliminar"
            size="small"
          />
          <Button
            type="button"
            label="Eliminar"
            class="btn btn--primary"
            size="small"
            @click="eliminarPermiso"
          />
        </div>
      </div>
    </Dialog>
  </div>
</template>

<script setup>
import {computed, onMounted, ref} from 'vue'
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import { useToastService } from '@/utils/toastApp.js';
import formularioComponent from "@/views/permisos/components/formularioComponent.vue";
import permisosServices from "@/services/permisos.services.js";

const dialogForm = ref(false)
const editar = ref(false)
const modalEliminar = ref(false)
const eliminarID = ref(null)
const listaPermisos = ref([])
const permisoId = ref(null)
const toast = useToastService()
const paginaActual = ref(1)
const porPagina = ref(10)
const cargarMas = ref(false)
const bUsuario = ref('')

const encabezados = ref([
  {name: 'Permiso', value: 'nombre' },
  {name: 'Descripción', value: 'descripcion' },
  {name: 'Módulo', value: 'modulo'},
  {name: 'Acciones', value: 'acciones', classCol: '!text-start'},
])

const buscarUsuarios = async (busqueda) => {
  bUsuario.value = busqueda
  paginaActual.value = 1
  listaPermisos.value = []
  await listarPermisos()
}

const listarPermisos = async (mantenerPagina = false) => {
  if (mantenerPagina) {
    paginaActual.value = 1
    listaPermisos.value = []
  }

  const { data: { data, page, last_page} } = await permisosServices.obtenerPermisos({
    page: paginaActual.value,
    per_page: porPagina.value,
    search: bUsuario.value
  })

  if (data) {
    paginaActual.value = Number(page) + 1;
    cargarMas.value = page < last_page;
    listaPermisos.value = mantenerPagina ? data : [...listaPermisos.value, ...data];
  } else {
    listaPermisos.value = [];
    cargarMas.value = false
  }
}

const handlerModalEliminar = (id) => {
  eliminarID.value = id
  modalEliminar.value = !modalEliminar.value
}

const handlerModalEditar = (item = null) => {
  if (item) {
    permisoId.value = item.id
    dialogForm.value = !dialogForm.value
    editar.value = true
  } else {
    permisoId.value = null
    dialogForm.value = !dialogForm.value
    editar.value = false
  }
}

const colorVerificaion = computed(() => {
  return (verificado) => {
    return verificado ? 'bg-green-200 text-green-700' : 'bg-red-200 text-red-700'
  }
})

const eliminarPermiso = async () => {
  const { status, message } = await permisosServices.eliminarPermiso({ id: eliminarID.value });
  if (status !== 200) return;
  toast.successToast(
    'Exito!',
    message ?? `Se ha eliminado el permiso correctamente`,
    5e3
  );
  handlerModalEliminar();
  await listarPermisos();
}

onMounted(() => {
  listarPermisos()
})

</script>
