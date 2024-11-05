<template>
  <Dialog
    v-model:visible="localVisible"
    modal
    :draggable="false"
    :auto-z-index="false"
    class="md:w-1/2 w-[95%]"
  >
    <template #header>
        <div class="flex justify-between items-center">
        <p class="title-2xl">Agregar permisos a rol</p>
        </div>
    </template>
    <template #closeicon>
      <i class="material-icons" @click="cerrarModal">close</i>
    </template>
    <div class="flex-1 space-y-5">
      <div>
        <div  class="pr-2 pb-4">
          <p class="text-sm">
            Seleccione uno o varios permisos para agregar al rol
          </p>
        </div>
        <v-select :options="options" label="nombre" :filterable="false"
        v-model="formulario.permiso"
        @search="enBuscar" :multiple="true" placeholder="Permiso *">
          <template #no-options="{ search, searching, loading }">
            Escriba para buscar permiso a agregar...
          </template>
          <template #open-indicator="{ attributes }">
            <i v-bind="attributes" class="pi pi-angle-down"></i>
          </template>
          <template #option="{ nombre }">
            <div class="d-center">
            {{ nombre }}
            </div>
          </template>
          <template #selected-option="{ nombre }">
            <div style="display: flex; align-items: baseline">
              {{ nombre }}
            </div>
          </template>
          <template #search="{ attributes, events }">
            <InputText
              class="vs__search solo-mayusculas"
              v-bind="attributes"
              v-on="events"
            />
          </template>
        </v-select>
        <small
          class="text-danger"
          v-if="v$.permiso.$dirty && v$.permiso.$invalid"
        >
          {{ v$.permiso?.$errors[0]?.$message }}
        </small>
        <!-- <MultiSelect
          filter
          class="w-full input-form"
          :options="listaPermisos"
          optionLabel="nombre"
          optionValue="id"
          placeholder="Permiso *"
          v-model="formulario.permiso"
          :invalid="v$.permiso.$dirty && v$.permiso.$invalid"
          @blur="v$.permiso.$touch()"
        />
        <small
          class="text-danger"
          v-if="v$.permiso.$dirty && v$.permiso.$invalid"
        >
          {{ v$.permiso?.$errors[0]?.$message }}
        </small> -->
      </div>
      <div class="grid lg:grid-cols-4 gap-4">
        <Button
          type="button"
          label="Cancelar"
          outlined
          class="btn-outline--primary lg:col-start-3"
          @click="cerrarModal"
          size="small"
        />
        <Button
          type="button"
          label="Agregar"
          @click="agregarPermisos"
          class="btn btn--primary"
          size="small"
        />
      </div>
    </div>
  </Dialog>
</template>

<script setup>
import Dialog from 'primevue/dialog';
import MultiSelect from 'primevue/multiselect';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import {useVuelidate} from '@vuelidate/core'
import {required, helpers} from '@vuelidate/validators'
import { computed, reactive, onMounted, ref} from 'vue';
import debounce from "lodash/debounce"
import { useToastService } from '../../../../utils/toastApp';

// services
import rolService from "@/services/rol.services"
import permisosServices from "@/services/permisos.services.js";

const props = defineProps({
  visible: Boolean,
  idRol: String,
  closeFunction: Function,
});

const emit = defineEmits(['update:visible']);
const formulario = reactive({
  permiso: '',
});

const reglas = computed(() => {
  return {
    permiso: {required: helpers.withMessage('El campo permiso es requerido', required)},
  }
});

// Computada para manejar la visibilidad localmente
const localVisible = computed({
  get() {
    return props.visible; // Lee el valor de la prop
  },
  set(value) {
    emit('update:visible', value); // Emite el evento para actualizar la visibilidad
  }
});

const v$ = useVuelidate(reglas, formulario);

const permisos = ref()
const listaPermisos = ref([])
const paginaActual = ref(1)
const porPagina = ref(10)
const options = ref([])
const { errorToast, successToast } = useToastService();

const clearPermisos = () => {
  formulario.permiso = '';
  v$.value.$reset();
}

const agregarPermisos = async () => {
  if (v$.value.$invalid) {
    v$.value.$touch();
    return;
  }else{
    try {
      let arrayPermisos = formulario.permiso.map((item) => {
        return item.id
      });
      let body = {
        permisos: arrayPermisos
      }
      const response = await rolService.agregarPermiso(props.idRol, body);
      if(response.status == 201 || response.status == 200){
        successToast('Rol', response.data.message)
      }else{
        errorToast('Error', response.data.message)
      }
    } catch (error) {

    } finally {
      cerrarModal();
    }
  }
}

const cerrarModal = () => {
  clearPermisos()
  if (typeof props.closeFunction === 'function') {
    props.closeFunction(); // Verificar si la función existe
  }
  emit('update:visible', false);
}

const enBuscar = async (search, loading) => {
  if(search.length >=3) {
    loading(true);
    enEscribir(loading, search.toUpperCase());
  }
}

const enEscribir = debounce(async (loading, search) => {
  const { data: { data } } = await permisosServices.obtenerPermisos({
    paginate: false,
    search: search
  })
  options.value = data;
  loading(false);
}, 350)
</script>

<style scoped>
/* Aplica mayúsculas solo al texto ingresado, pero no al placeholder */
.solo-mayusculas {
  text-transform: uppercase;
}

/* Aplica el estilo normal al placeholder */
.solo-mayusculas::placeholder {
  text-transform: none;
}
</style>
