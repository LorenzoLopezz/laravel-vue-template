<template>
  <Dialog
    v-model:visible="localVisible"
    modal
    :auto-z-index="false"
    :draggable="false"
    class="md:w-1/2 w-[95%]"
  >
  <template #header>
    <div class="flex justify-between items-center">
      <p class="title-2xl">Rol</p>
    </div>
  </template>
  <template #closeicon>
    <i class="material-icons" @click="cerrarModal">close</i>
  </template>
  <div class="flex-1 space-y-5">
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
        label="Guardar"
        class="sm:w-1/3 bg-primary text-white border-primary shadow-none"
        @click="agregarRol"
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
import { useToastService } from '../../../../utils/toastApp';

// services
import rolService from "@/services/rol.services"

const props = defineProps({
  visible: Boolean,
  rolObj: Object,
  closeFunction: Function,
  tipo: String,
});

const loading = ref(false);
const { errorToast, successToast } = useToastService();
const emit = defineEmits(['update:visible', 'update:tipo']);

const formulario = reactive({
  nombre: '',
});

const reglas = computed(() => {
  return {
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

const clearRol = () => {
  formulario.nombre = '';
  v$.value.$reset();
}

const agregarRol = async () => {
  if (v$.value.$invalid) {
    v$.value.$touch();
    return;
  }else{
    try {
      let body = {
        nombre: formulario.nombre
      }
      let response;
      if(localTipo.value === 'editar'){
        response = await rolService.actualizarRol(props.rolObj.id, body);
      }else{
        response = await rolService.crearRol(body);
      }
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
  clearRol()
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
        formulario.nombre = props.rolObj.nombre;
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
