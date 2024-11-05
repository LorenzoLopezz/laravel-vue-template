<template>
  <Dialog
    v-model:visible="localVisible"
    modal
    :draggable="false"
    class="md:w-1/2 w-[95%]"
  >
  <template #header>
    <div class="flex justify-between items-center">
      <p class="title-2xl">Perfil</p>
    </div>
  </template>
  <template #closeicon>
    <i class="material-icons" @click="cerrarModal">close</i>
  </template>
  <div class="flex-1 space-y-5">
    <div>
      <InputText
        class="w-full input-form solo-mayusculas"
        placeholder="Acrónimo *"
        maxlength="10"
        v-model="formulario.acronimo"
        :invalid="v$.acronimo.$dirty && v$.acronimo.$invalid"
        @blur="v$.acronimo.$touch()"
        @input="replaceSpaces"
      />
      <small
        class="text-danger"
        v-if="v$.acronimo.$dirty && v$.acronimo.$invalid"
      >
        {{ v$.acronimo?.$errors[0]?.$message }}
      </small>
    </div>
    <div>
      <InputText
        class="w-full input-form"
        placeholder="Nombre *"
        v-model="formulario.nombre"
        :invalid="v$.nombre.$dirty && v$.nombre.$invalid"
        @blur="v$.nombre.$touch()"
      />
      <small
        class="text-danger"
        v-if="v$.nombre.$dirty && v$.nombre.$invalid"
      >
        {{ v$.nombre?.$errors[0]?.$message }}
      </small>
    </div>
  </div>
  <template #footer>
    <div class="w-full flex flex-col sm:flex-row justify-end gap-2">
      <Button
        type="button"
        label="Cancelar"
        class="sm:w-1/3 bg-white border border-primary text-primary shadow-none"
        @click="cerrarModal"
      ></Button>
      <Button
        type="button"
        label="Agregar"
        class="sm:w-1/3 bg-primary text-white border-primary shadow-none"
        @click="agregarPerfil"
        :loading="loading"
      ></Button>
    </div>
  </template>
  </Dialog>
</template>

<script setup>
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';

import {useVuelidate} from '@vuelidate/core'
import {required, helpers} from '@vuelidate/validators'
import { ref, computed, reactive, onMounted, watch } from 'vue';

const props = defineProps({
  visible: Boolean,
  perfilObj: Object,
  closeFunction: Function,
  tipo: String,
});

const loading = ref(false);
const emit = defineEmits(['update:visible', 'update:tipo']);
const formulario = reactive({
  acronimo: '',
  nombre: '',
});

const reglas = computed(() => {
  return {
    acronimo: {required: helpers.withMessage('El campo acrónimo es requerido', required)},
    nombre: {required: helpers.withMessage('El campo nombre es requerido', required)},
  }
});

const v$ = useVuelidate(reglas, formulario);

// Computada para manejar la visibilidad localmente
const localVisible = computed({
  get() {
    return props.visible; // Lee el valor de la prop
  },
  set(value) {
    emit('update:visible', value); // Emite el evento para actualizar la visibilidad
  }
});

const localTipo = computed({
  get() {
    return props.tipo; // Lee el valor de la prop
  },
  set(value) {
    emit('update:tipo', value); // Emite el evento para actualizar la visibilidad
  }
});

const clearPerfil = () => {
  formulario.acronimo = '';
  formulario.nombre = '';
  v$.value.$reset();
}

// Función para reemplazar los espacios por guiones bajos
const replaceSpaces = () => {
  formulario.acronimo = formulario.acronimo.replace(/\s+/g, '_');
};

const agregarPerfil = async () => {
  if (v$.value.$invalid) {
    v$.value.$touch();
    return;
  }else{
    try {
      let body = {
        acronimo: formulario.acronimo.toUpperCase(),
        nombre: formulario.nombre
      }
    } catch (error) {

    } finally {
      cerrarModal();
    }
  }
}

const cerrarModal = () => {
  clearPerfil()
  if (typeof props.closeFunction === 'function') {
    props.closeFunction(); // Verificar si la función existe
  }
  emit('update:tipo', '');
  emit('update:visible', false);
}

watch(() => props.visible, (value) => {
  localVisible.value = value;

  if (value) {
    if (localVisible.value) {
      if(localTipo.value === 'editar'){
        formulario.acronimo = props.perfilObj.acronimo;
        formulario.nombre = props.perfilObj.nombre;
      }
    }
  }
});
</script>

<style lang="scss" scoped>

</style>

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
