<template>
  <Dialog
    v-model:visible="localVisible"
    modal
    closable
    :auto-z-index="false"
    block-scroll
    :draggable="false"
    class="md:w-8/12 w-[95%]"
  >

    <template #header>
      <h1 class="title-2xl">{{ tituloProceso }}</h1>
    </template>

    <template #closeicon>
      <i class="material-icons" @click="cerrarModal">close</i>
    </template>

    <div class="space-y-6">
      <div class="grid grid-cols-2 gap-5">
        <div>
          <InputText
            class="w-full input-form"
            placeholder="Permiso *"
            v-model="formulario.permiso"
            :invalid="v$.permiso.$dirty && v$.permiso.$invalid"
            @blur="v$.permiso.$touch()"
            @input="formulario.permiso = $event.target.value.toUpperCase()"
          />
          <small
            class="text-danger"
            v-if="v$.permiso.$dirty && v$.permiso.$invalid"
          >
            {{ v$.permiso?.$errors[0]?.$message }}
          </small>
        </div>
        <div>
          <InputText
            class="w-full input-form"
            placeholder="Descripción"
            v-model="formulario.descripcion"
            :invalid="v$.descripcion.$dirty && v$.descripcion.$invalid"
            @blur="v$.descripcion.$touch()"
          />
          <small
            class="text-danger"
            v-if="v$.descripcion.$dirty && v$.descripcion.$invalid"
          >
            {{ v$.descripcion?.$errors[0]?.$message }}
          </small>
        </div>
        <div>
          <Dropdown
            class="!w-full input-form"
            placeholder="módulo *"
            v-model="formulario.id_modulo"
            :options="listaModulos"
            optionLabel="nombre"
            optionValue="id"
            :invalid="v$.id_modulo.$dirty && v$.id_modulo.$invalid"
            @blur="v$.id_modulo.$touch()"
          />
          <small
            class="text-danger"
            v-if="v$.id_modulo.$dirty && v$.id_modulo.$invalid"
          >
            {{ v$.id_modulo?.$errors[0]?.$message }}
          </small>
        </div>
      </div>
      <div class="w-full flex justify-end">
        <div class="w-1/2 flex justify-end space-x-5">
          <Button
            type="button"
            label="Cancelar"
            outlined
            class="btn btn-outline--primary"
            @click="cerrarModal"
            size="small"
          />
          <Button
            type="button"
            label="Guardar"
            class="btn btn--primary"
            size="small"
            @click="guardarDatos"
          />
        </div>
      </div>
    </div>
  </Dialog>
  <Toast/>
</template>

<script setup>

import {ref, reactive, computed, watch, watchEffect, onMounted } from 'vue';
import {useVuelidate} from '@vuelidate/core';
import {required, helpers, maxLength, minLength } from '@vuelidate/validators';

//components primevue
import InputText from "primevue/inputtext";
import Dialog from "primevue/dialog";
import Dropdown from "primevue/dropdown";
import Button from "primevue/button";

//services
import permisosServices from "@/services/permisos.services.js";

//utils
import {useToastService} from "@/utils/toastApp.js";
import {formatoNombrePermiso, onlyLetters} from "@/utils/global-functions.js";
import {useCatalogos} from "@/composable/useCatalogos.js";

const props = defineProps({
  visible: {
    type: Boolean,
    default: false,
  },
  editar: {
    type: Boolean,
    default: false,
  },
  permisoId: {
    type: Number,
    default: null,
  },
});

const tituloProceso = computed(() => props.editar ? 'Editar permiso' : 'Crear permiso');

const { obtenerModulos, listaModulos } = useCatalogos();

const {
  crearPermiso,
  actualizarPermiso,
} = permisosServices

const emit = defineEmits(['update:visible', 'recargar']);
const toast = useToastService();

const localVisible = ref(props.visible);
watch(() => props.visible, (newVal) => {
  localVisible.value = newVal;
});

watchEffect(() => {
  props.editar && verPermiso();
});

const formulario = reactive({
  permiso: '',
  descripcion: '',
  id_modulo: '',
});

const validaciones = ref({
  permiso: {
    required: helpers.withMessage('El campo es requerido', required),
    formatoNombrePermiso: helpers.withMessage('El campo solo puede contener letras y guiones bajos', formatoNombrePermiso),
    maxLength: helpers.withMessage('El campo debe tener menos de 25 caracteres', maxLength(25)),
    minLength: helpers.withMessage('El campo debe tener más de 4 caracteres', minLength(4)),
  },
  descripcion: {
    onlyLetters: helpers.withMessage('El campo solo puede contener letras', onlyLetters),
    maxLength: helpers.withMessage('El campo debe tener menos de 50 caracteres', maxLength(50)),
    minLength: helpers.withMessage('El campo debe tener más de 2 caracteres', minLength(2)),
  },
  id_modulo: {required: helpers.withMessage('El campo módulo es requerido', required)},
});

const v$ = useVuelidate(validaciones, formulario);

const verPermiso = async () => {
  const {data, status} = await permisosServices.verPermiso(props.permisoId);
  if (status !== 200) {
    cerrarModal();
    return;
  }

  const { permiso, descripcion, modulo} = data

  formulario.id_modulo = modulo;
  formulario.permiso = permiso;
  formulario.descripcion = descripcion;

  await obtenerModulos();
}


const crear = async () => {
  const {status} = await crearPermiso(formulario);
  if (status !== 201) return false;

  toast.successToast(
    'Permiso creado',
    'El permiso ha sido creado correctamente'
  );

  return true;
}

const actualizar = async () => {
  const {status} = await actualizarPermiso(props.permisoId, formulario);
  if (status !== 200) return false;

  toast.successToast(
    'Permiso actualizado',
    'El permiso ha sido actualizado correctamente'
  );

  return true;
}

const guardarDatos = async () => {
  if (v$.value.$invalid) {
    v$.value.$touch();
    return;
  }

  formulario.nombre = formulario.permiso;
  const payload = formulario;
  const respuesta = props.editar ? await actualizar(payload) : await crear(payload);
  if (respuesta) {
    emit('recargar')
    cerrarModal();
  }
}

const limpiarFormulario = () => {
  formulario.permiso = '';
  formulario.descripcion = '';
  formulario.id_modulo = '';
  v$.value.$reset();
}

const cerrarModal = () => {
  localVisible.value = false;
  props.editar = false;
  props.permisoId = null;
  limpiarFormulario();
  emit('update:visible', false);
}

onMounted(() => {
    Promise.all([
      obtenerModulos(),
    ]);
});

</script>
