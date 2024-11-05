<template>
  <Toast />
  <div class="w-full h-screen bg-[#ebf3ff] p-8 space-y-6">
    <div class="space-y-5 md:space-y-0 md:flex md:justify-between">
      <h1 class="title-4xl">Usuarios</h1>
      <div class="flex justify-end">
        <Button
          icon="pi pi-plus-circle"
          label="Nuevo usuario"
          class="btn btn--primary"
          @click="dialogForm = true"
        />
      </div>
    </div>
    <div>
      <data-table
        :listaDatos="listaUsuarios"
        :datosEncabezados="encabezados"
        :listarMas="false"
        @cambioPagina="cambioPagina"
        requiereBusqueda
        @buscar="buscarUsuarios"
        paginacion
        @changePerPage="porPagina = $event"
        :infoPaginacion="{
          page: paginaActual,
          per_page: porPagina,
          ultimaPagina,
          totalRegistros
        }"
      >
        <template #institucion_area="{ item }">
          {{ item.institucion_area?.nombre }}
        </template>
        <template #perfiles="{ item }">
          {{ joinPerfiles(item?.perfiles) }}
        </template>
        <template #created_at="{ item }">
          {{ formatDate(item.created_at) }}
        </template>
        <template #verificado="{ item }">
          <Tag
            :value="item.verificado ? 'Verificado' : 'Pendiente'"
            class="w-10/12 rounded-xl !font-bold"
            :class="colorVerificaion(item.verificado)"/>
        </template>
        <template #acciones="{item}">
          <div class="flex justify-evenly">
            <Button
              text
              rounded
              v-tooltip.bottom="'Editar usuario'"
              aria-label="Editar usuario"
              size="small"
              @click="handlerModalEditar(item)"
              class="!px-0 aspect-square flex justify-center border-0"
            >
              <i class="material-icons text-primary">edit</i>
            </Button>
            <Button
              text
              rounded
              v-tooltip.bottom="'Bloquear usuario'"
              aria-label="Bloquer usuario"
              size="small"
              @click="handlerModalBloquear(item.id)"
              class="!px-0 aspect-square flex justify-center border-0"
            >
              <i class="material-icons text-danger">block</i>
            </Button>
            <Button
              text
              rounded
              v-tooltip.bottom="'Cambiar contraseña'"
              aria-label="Cambiar contraseña"
              size="small"
              @click="handlerModalRecuperacion(item.email)"
              class="!px-0 aspect-square flex justify-center border-0"
            >
              <i class="material-icons text-primary">password</i>
            </Button>
          </div>
        </template>
      </data-table>
    </div>

    <crear-o-actualizar-component
      :visible="dialogForm"
      :editar="editar"
      :usuarioId="usuarioId"
      @update:visible="handlerModalEditar"
      @recargar="listarUsuarios()"
    />

    <bloquear-usuario-component
      :visible="modalBloquear"
      :id="bloquearId"
      @update:visible="modalBloquear = $event"
    />

    <Dialog
      v-model:visible="modalRecuperarContra"
      modal
      closable
      block-scroll
      :auto-z-index="false"
      :draggable="false"
      class="md:w-[30%] w-[95%]"
    >
      <template #header>
        <h1 class="title-2xl">Recuperación de  contraseña</h1>
      </template>
      <template #closeicon>
        <i class="material-icons" @click="modalRecuperarContra = false">close</i>
      </template>

      <div class="space-y-6">
        <div>
          <p class="break-words text-justify">
            Se cerrarán todas las sesiones existentes y se enviará un enlace
            de restauración de contraseña al correo al correo de la cuenta
          </p>
        </div>
        <div class="flex gap-5">
          <Button
            type="button"
            label="Cancelar"
            outlined
            class="btn btn-outline--primary"
            @click="handlerModalRecuperacion"
            size="small"
          />
          <Button
            type="button"
            label="Restaurar"
            class="btn btn--primary"
            size="small"
            @click="restaurar"
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
import Tag from "primevue/tag";
import {useToastService} from '@/utils/toastApp.js';
import {formatDate} from "@/utils/global-functions.js";
import CrearOActualizarComponent from "@/views/usuarios/components/CrearOActualizarComponent.vue";
import BloquearUsuarioComponent from "@/views/usuarios/components/BloquearUsuarioComponent.vue";
import usuariosServices from "@/services/usuarios.services.js";

const dialogForm = ref(false)
const editar = ref(false)
const modalBloquear = ref(false)
const bloquearId = ref(null)
const emailUsuario = ref('')
const modalRecuperarContra = ref(false)
const listaUsuarios = ref([])
const usuarioId = ref(null)
const toast = useToastService()
const paginaActual = ref(1)
const porPagina = ref(10)
const cargarMas = ref(false)
const bUsuario = ref('')
const ultimaPagina = ref(1)
const totalRegistros = ref(0)

const encabezados = ref([
  {name: 'Nombre', value: 'nombre' },
  {name: 'Correo', value: 'email' },
  {name: 'Área', value: 'institucion_area'},
  {name: 'Perfiles', value: 'perfiles', classCol: 'max-w-[400px]'},
  {name: 'Fecha de creación', value: 'created_at',},
  {name: 'Verificación', value: 'verificado'},
  {name: 'Acciones', value: 'acciones', classCol: '!text-start'},
])

const cambioPagina = (paginacion) => {
  const { page, rowsPerPage } = paginacion
  paginaActual.value = page
  porPagina.value = rowsPerPage
  listarUsuarios()
}

const buscarUsuarios = async (busqueda) => {
  bUsuario.value = busqueda
  paginaActual.value = 1
  listaUsuarios.value = []
  await listarUsuarios()
}


const listarUsuarios = async () => {
  const { data: { data, page, last_page, total_data} } = await usuariosServices.obtenerUsuarios({
    page: paginaActual.value,
    per_page: porPagina.value,
    search: bUsuario.value
  })

  listaUsuarios.value = data
  ultimaPagina.value = last_page
  paginaActual.value = page
  totalRegistros.value = total_data
}

const joinPerfiles = (perfiles) => {
  return perfiles.map((perfil) => perfil.nombre).join(', ')
}

const handlerModalBloquear = (idUsuario) => {
  bloquearId.value = idUsuario
  modalBloquear.value = !modalBloquear.value
}

const handlerModalEditar = (item = null) => {
  if (item) {
    usuarioId.value = item.id
    dialogForm.value = !dialogForm.value
    editar.value = true
  } else {
    usuarioId.value = null
    dialogForm.value = !dialogForm.value
    editar.value = false
  }
}

const handlerModalRecuperacion = (email = null) => {
  (email)
    ? emailUsuario.value = email
    : emailUsuario.value = ''
  modalRecuperarContra.value = !modalRecuperarContra.value
}

const restaurar = async () => {
  const { status } = await usuariosServices.recuperacionContra({ email: emailUsuario.value })
  if (status !== 200) return;
  toast.successToast(
    'Enviado correctamente',
    `Se ha enviado un enlace de restauración de contraseña al correo ${emailUsuario.value}`,
    7000
  )
  handlerModalRecuperacion()
}

const colorVerificaion = computed(() => {
  return (verificado) => {
    return verificado ? 'bg-green-200 text-green-700' : 'bg-red-200 text-red-700'
  }
})

onMounted(() => {
  listarUsuarios()
})

</script>
