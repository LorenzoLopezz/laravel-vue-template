<template>
  <Dialog v-model:visible="localVisible" modal closable :auto-z-index="false" block-scroll :draggable="false"
    class="md:w-8/12 w-[95%]">

    <template #header>
      <h1 class="title-2xl">Usuario</h1>
    </template>

    <template #closeicon>
      <i class="material-icons" @click="cerrarModal">close</i>
    </template>

    <div class="space-y-6">
      <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-5">
        <div>
          <InputText class="w-full input-form" placeholder="Primer nombre *" v-model="formulario.primer_nombre"
            :invalid="v$.primer_nombre.$dirty && v$.primer_nombre.$invalid" @blur="v$.primer_nombre.$touch()" />
          <small class="text-danger" v-if="v$.primer_nombre.$dirty && v$.primer_nombre.$invalid">
            {{ v$.primer_nombre?.$errors[0]?.$message }}
          </small>
        </div>
        <div>
          <InputText class="w-full input-form" placeholder="Segundo nombre " v-model="formulario.segundo_nombre"
            :invalid="v$.segundo_nombre.$dirty && v$.segundo_nombre.$invalid" @blur="v$.segundo_nombre.$touch()" />
          <small class="text-danger" v-if="v$.segundo_nombre.$dirty && v$.segundo_nombre.$invalid">
            {{ v$.segundo_nombre?.$errors[0]?.$message }}
          </small>
        </div>
        <div>
          <InputText class="w-full input-form" placeholder="Primer apellido *" v-model="formulario.primer_apellido"
            :invalid="v$.primer_apellido.$dirty && v$.primer_apellido.$invalid" @blur="v$.primer_apellido.$touch()" />
          <small class="text-danger" v-if="v$.primer_apellido.$dirty && v$.primer_apellido.$invalid">
            {{ v$.primer_apellido?.$errors[0]?.$message }}
          </small>
        </div>
        <div>
          <InputText class="w-full input-form" placeholder="Segundo apellido " v-model="formulario.segundo_apellido"
            :invalid="v$.segundo_apellido.$dirty && v$.segundo_apellido.$invalid"
            @blur="v$.segundo_apellido.$touch()" />
          <small class="text-danger" v-if="v$.segundo_apellido.$dirty && v$.segundo_apellido.$invalid">
            {{ v$.segundo_apellido?.$errors[0]?.$message }}
          </small>
        </div>
      </div>
      <div class="grid md:grid-cols-2 gap-5">
        <div>
          <Calendar v-model="formulario.fecha_nacimiento" dateFormat="dd/mm/yy" class="!w-full" input-class="input-form"
            placeholder="Fecha de nacimiento" :max-date="new Date()" :touchUI="pantallaPeq" :manualInput="false"
            :invalid="v$.fecha_nacimiento.$dirty && v$.fecha_nacimiento.$invalid"
            @blur="v$.fecha_nacimiento.$touch()" />
          <small class="text-danger" v-if="v$.fecha_nacimiento.$dirty && v$.fecha_nacimiento.$invalid">
            {{ v$.fecha_nacimiento?.$errors[0]?.$message }}
          </small>
        </div>
        <div>
          <InputText class="w-full input-form" placeholder="Correo *" v-model="formulario.email"
            :invalid="v$.email.$dirty && v$.email.$invalid" @blur="v$.email.$touch()" />
          <small class="text-danger" v-if="v$.email.$dirty && v$.email.$invalid">
            {{ v$.email?.$errors[0]?.$message }}
          </small>
        </div>
      </div>
      <div class="grid md:grid-cols-2 gap-5">
        <div>
          <MultiSelect class="!w-full input-form" placeholder="Perfiles *" v-model="formulario.perfiles"
            :options="listaPerfiles" optionLabel="nombre" optionValue="id" display="chip"
            :invalid="v$.perfiles.$dirty && v$.perfiles.$invalid" @blur="v$.perfiles.$touch()" />
          <small class="text-danger" v-if="v$.perfiles.$dirty && v$.perfiles.$invalid">
            {{ v$.perfiles?.$errors[0]?.$message }}
          </small>
        </div>
      </div>
      <div class="w-full flex justify-end">
        <div class="w-1/2 flex justify-end space-x-5">
          <Button type="button" label="Cancelar" outlined class="btn btn-outline--primary" @click="cerrarModal"
            size="small" />
          <Button type="button" label="Guardar" class="btn btn--primary" size="small" @click="guardarDatos" />
        </div>
      </div>
    </div>
  </Dialog>
  <Toast />
</template>

<script setup>

import { computed, reactive, ref, watch, watchEffect } from 'vue';
import { useVuelidate } from '@vuelidate/core';
import { email, helpers, maxLength, minLength, required, requiredIf } from '@vuelidate/validators';

//components primevue
import InputText from "primevue/inputtext";
import Dialog from "primevue/dialog";
import Calendar from "primevue/calendar";
import Button from "primevue/button";
import MultiSelect from 'primevue/multiselect';

//services
import usuariosServices from "@/services/usuarios.services.js";

//utils
import { useToastService } from "@/utils/toastApp.js";
import dayJS from "dayjs";
import { formatDateWithTime, userHasRole, onlyLetters } from "@/utils/global-functions.js";
import { useCatalogos } from "@/composable/useCatalogos.js";

const props = defineProps({
  visible: {
    type: Boolean,
    default: false,
  },
  editar: {
    type: Boolean,
    default: false,
  },
  usuarioId: {
    type: String,
    default: null,
  },
});

const {
  obtenerPerfiles, listaPerfiles
} = useCatalogos();

const {
  crearUsuario,
  actualizarUsuario,
} = usuariosServices

const emit = defineEmits(['update:visible', 'recargar']);
const toast = useToastService();

const localVisible = ref(props.visible);
watch(() => props.visible, (newVal) => {
  localVisible.value = newVal;
  if (!newVal) return
  if (listaPerfiles.value.length > 0) return;
  Promise.all([
    obtenerPerfiles(),
  ]);
});


watchEffect(() => {
  props.editar && verUsuario();
});

const formulario = reactive({
  primer_nombre: '',
  segundo_nombre: '',
  primer_apellido: '',
  segundo_apellido: '',
  fecha_nacimiento: '',
  email: '',
  perfiles: [],
});


const validaciones = ref({
  primer_nombre: {
    required: helpers.withMessage('El campo es requerido', required),
    onlyLetters: helpers.withMessage('El campo solo puede contener letras', onlyLetters),
    maxLength: helpers.withMessage('El campo debe tener menos de 50 caracteres', maxLength(50)),
    minLength: helpers.withMessage('El campo debe tener más de 2 caracteres', minLength(3)),
  },
  segundo_nombre: {
    onlyLetters: helpers.withMessage('El campo solo puede contener letras', onlyLetters),
    maxLength: helpers.withMessage('El campo debe tener menos de 50 caracteres', maxLength(50)),
    minLength: helpers.withMessage('El campo debe tener más de 2 caracteres', minLength(3)),
  },
  primer_apellido: {
    required: helpers.withMessage('El campo es requerido', required),
    onlyLetters: helpers.withMessage('El campo solo puede contener letras', onlyLetters),
    maxLength: helpers.withMessage('El campo debe tener menos de 50 caracteres', maxLength(50)),
    minLength: helpers.withMessage('El campo debe tener más de 2 caracteres', minLength(3)),
  },
  segundo_apellido: {
    onlyLetters: helpers.withMessage('El campo solo puede contener letras', onlyLetters),
    maxLength: helpers.withMessage('El campo debe tener menos de 50 caracteres', maxLength(50)),
    minLength: helpers.withMessage('El campo debe tener más de 2 caracteres', minLength(3)),
  },
  fecha_nacimiento: { required: helpers.withMessage('El campo es requerido', required) },
  email: {
    required: helpers.withMessage('El campo es requerido', required),
    email: helpers.withMessage('El correo ingresado no posee un formato válido', email),
    maxLength: helpers.withMessage('El campo debe tener menos de 50 caracteres', maxLength(150)),
  },
  perfiles: { required: helpers.withMessage('El campo es requerido', required) },
});

const v$ = useVuelidate(validaciones, formulario);

const pantallaPeq = computed(() => {
  return window.innerWidth < 768;
});

const verUsuario = async () => {
  const { data, status } = await usuariosServices.verUsuario(props.usuarioId);
  if (status !== 200) {
    cerrarModal();
    return;
  }

  const {
    primer_nombre, segundo_nombre,
    primer_apellido, segundo_apellido,
    fecha_nacimiento, email, perfiles,
  } = data

  formulario.email = email;
  formulario.primer_nombre = primer_nombre;
  formulario.segundo_nombre = segundo_nombre;
  formulario.primer_apellido = primer_apellido;
  formulario.segundo_apellido = segundo_apellido;
  formulario.fecha_nacimiento = new Date(dayJS(fecha_nacimiento));
  formulario.perfiles = data.perfiles.map((perfil) => perfil.id_encriptado);
}

const crear = async () => {
  const { data, status } = await crearUsuario(formulario);
  if (status !== 201) return false;

  toast.successToast(
    'Usuario creado',
    'El usuario ha sido creado correctamente'
  );

  return true;
}

const actualizar = async () => {
  const { data, status } = await actualizarUsuario(props.usuarioId, formulario);
  if (status !== 200) return false;

  toast.successToast(
    'Usuario actualizado',
    'El usuario ha sido actualizado correctamente'
  );

  return true;
}


const guardarDatos = async () => {
  if (v$.value.$invalid) {
    v$.value.$touch();
    return;
  }

  const payload = formulario;
  payload.fecha_nacimiento = formatDateWithTime(formulario.fecha_nacimiento, 'YYYY-MM-DD');
  const respuesta = props.editar ? await actualizar() : await crear();
  if (respuesta) {
    emit('recargar')
    cerrarModal();
  }
}

const limpiarFormulario = () => {
  formulario.primer_nombre = '';
  formulario.segundo_nombre = '';
  formulario.primer_apellido = '';
  formulario.segundo_apellido = '';
  formulario.fecha_nacimiento = '';
  formulario.email = '';
  formulario.perfiles = '';
  v$.value.$reset();
}

const cerrarModal = () => {
  localVisible.value = false;
  limpiarFormulario();
  emit('update:visible', false);
}


</script>
