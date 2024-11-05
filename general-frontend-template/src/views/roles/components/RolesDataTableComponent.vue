<template>
  <data-table
    :datos-encabezados="encabezados"
    :lista-datos="permisosLocal"
    :loading="loading"
    requiereBusqueda
    requiredAccion
    :accionBtn="{ icon: 'pi pi-plus-circle', class:'bg-primary !float-end lg:w-4/12 xl:w-3/12' , text: 'Agregar permisos' }"
    :clasesBuscqueda="'w-full'"
    @accion="()=>{ modalAgregar = true }"
    @buscar="buscarPermiso"
    :listarMas="cargarMas"
    @cargarMas="obtenerPermisosRoles"
  >
    <template #acciones="item">
      <div class="flex justify-center">
        <Button
          rounded
          text
          class="!px-0 !rounded-full aspect-ratio w-8 h-8 my-auto flex justify-center"
          @click="showEliminar(item)"
          >
          <i class="material-icons text-danger">delete</i>
        </Button>
      </div>
    </template>
  </data-table>
  <modal-agregar-permisos
    :visible="modalAgregar"
    @update:visible="modalAgregar = $event"
    :idRol="idRol"
    :closeFunction="() => {
      obtenerPermisosRoles(true);
    }"
  />
  <modal-confirmacion
    :visibleModal="modalEliminar"
    titulo="¿Estás seguro de eliminar este permiso?"
    descripcion="Una vez eliminado, no se podrán revertir los cambios."
    textoBtnConfirm="Sí, eliminar"
    textoBtnCancelar="Cancelar"
    @update:visible="modalEliminar = $event"
    @aceptar="eliminarPermiso"
    @cancelar="modalEliminar = false"
  />
</template>

<script setup>
import Button from "primevue/button";
import { ref, computed, reactive, onMounted, watch } from 'vue';
import ModalAgregarPermisos from "@/views/roles/components/modals/ModalAgregarPermisosComponent.vue";
import { useToastService } from '../../../utils/toastApp';

// services
import rolService from "@/services/rol.services"

const props = defineProps({
  idRol: String,
});

const encabezados = ref([
  { name: 'Permiso', value: 'nombre', classCol: 'w-3/12' },
  { name: 'Descripción', value: 'descripcion', classCol: 'w-5/12' },
  { name: 'Módulo', value: 'nombre_modulo', classCol: 'w-2/12' },
  { name: 'Acciones', value: 'acciones', classCol: 'w-2/12' },
]);

const loading = ref(false);
const modalAgregar = ref(false);
const modalEliminar = ref(false);
const permisoObj = ref({})
const permisosTablas = ref([])
const filtros = reactive({ page: 1, per_page: 5, search: '' });
const { errorToast, successToast } = useToastService();
const permisosLocal = ref([]);
const cargarMas = ref(false)

function showEliminar(item){
  permisoObj.value = item.item;
  modalEliminar.value = true;
}

const buscarPermiso = async (busqueda) => {
  filtros.search = busqueda;
  filtros.page = 1;
  await obtenerPermisosRoles(true)
}

const eliminarPermiso = async () => {
  try {
    const response = await rolService.eliminarPermiso(props.idRol, {
      permiso: permisoObj.value.id
    });
    if(response.status == 201 || response.status == 200){
      successToast('Rol', response.data.message)
      permisoObj.value = {};
      await obtenerPermisosRoles(true)
    }else{
      errorToast('Error', response.data.message)
    }
  } catch (error) {

  } finally {
    modalEliminar.value = false
  }
}

const obtenerPermisosRoles = async (mantenerPagina = false) => {
  if (mantenerPagina) {
    filtros.page = 1
    permisosLocal.value = []
  }

  const { data: { data, page, last_page}, status }  = await rolService.obtenerPermisos(props.idRol, {
    ...filtros
  });

  if(status == 201 || status == 200){
    if (data ?? false) {
      filtros.page = Number(page) + 1;
      cargarMas.value = page < last_page;
      permisosLocal.value = mantenerPagina ? data : [...permisosLocal.value, ...data];
    } else {
      permisosLocal.value = [];
      cargarMas.value = false
    }
  }
}

onMounted(() => {
  obtenerPermisosRoles();
});
</script>

<style lang="scss" scoped>

</style>
