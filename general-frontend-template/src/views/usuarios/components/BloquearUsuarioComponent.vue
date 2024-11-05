<template>
  <Dialog
    v-model:visible="localVisible"
    modal
    closable
    block-scroll
    :auto-z-index="false"
    :draggable="false"
    class="md:w-8/12 w-[95%]"
  >

    <template #header>
      <h1 class="title-2xl">Bloquear usuario</h1>
    </template>

    <template #closeicon>
      <i class="material-icons" @click="cerrarModal">close</i>
    </template>
    <div class="space-y-10">
      <div class="space-y-4">
          <p>Para realizar el bloqueo del usuario por favor ingrese una justificación</p>
          <div>
            <Textarea
              autoResize
              rows="5"
              cols="30"
              maxlength="100"
              class="w-full input-form"
              placeholder="Ingrese justificación"
              v-model="formulario.justificaion"
              :invalid="v$.justificaion.$dirty && v$.justificaion.$invalid"
              @blur="v$.justificaion.$touch()"
            />
            <div>
              <small class="float-right">
                {{ contadorTexto }} / 100
              </small>
              <small
                class="text-danger"
                v-if="v$.justificaion.$dirty && v$.justificaion.$invalid"
              >
                {{ v$.justificaion?.$errors[0]?.$message }}
              </small>
            </div>
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
            @click="enviarJustificacion"
          />
        </div>
      </div>
    </div>
  </Dialog>
</template>

<script setup>

import {computed,  reactive, ref, watch} from 'vue';
import {useVuelidate} from '@vuelidate/core';
import {helpers, maxLength, minLength, required} from '@vuelidate/validators';
import Textarea from "primevue/textarea";
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import usuariosServices from "@/services/usuarios.services.js";

const props = defineProps({
  visible: {
    type: Boolean,
    default: false,
  },
  id: {
    type: String,
    default: null,
  },
});

const localVisible = ref(props.visible);
const emit = defineEmits(['update:visible']);
const formulario = reactive({
  justificaion: '',
});

const {
  bloquearUsuario,
} = usuariosServices;

const validaciones = ref({
  justificaion: {
    required: helpers.withMessage('Debe de ingresar una justificacón', required),
    maxLength: helpers.withMessage('El campo debe tener menos de 50 caracteres', maxLength(100)),
    minLength: helpers.withMessage('El campo debe tener más de 2 caracteres', minLength(5)),
  }
});

const v$ = useVuelidate(validaciones, formulario);
const contadorTexto = computed(() => formulario.justificaion.length);

watch(() => props.visible, (value) => {
  if (value) {
    localVisible.value = value;
  }
});

const enviarJustificacion = async () => {
  if (v$.value.$invalid) {
    v$.value.$touch();
    return;
  }

  const { data } = await bloquearUsuario(props.id, {justificacion: formulario.justificaion});
  cerrarModal();
}

const limpiarFormulario = () => {
  formulario.justificaion = '';
  v$.value.$reset();
}

const cerrarModal = () => {
  emit('update:visible', false);
  localVisible.value = false;
  limpiarFormulario();
}

</script>
