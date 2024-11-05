<script setup>
import {ref,  watch} from 'vue';
import Dialog from 'primevue/dialog';
import Button from "primevue/button";

const props = defineProps({
  visibleModal: Boolean,
  titulo: {
    type: String,
    default: 'Titulo de alerta [props "titulo"]',
  },
  descripcion: {
    type: String,
    default: 'Descripción de alerta [props "descripcion"]',
  },
  textoBtnConfirm: {
    type: String,
    default: 'Aceptar',
  },
  textoBtnCancelar: {
    type: String,
    default: 'Cancelar',
  },
});

const emit = defineEmits();
const localVisible = ref(props.visibleModal);
watch(() => props.visibleModal, (newVal) => {
  localVisible.value = newVal;
});

const aceptar = () => {
  emit('update:visible', false);
  emit('aceptar');
};

const cancelar = () => {
  emit('update:visible', false);
  emit('cancelar');
};

</script>

<template>
    <Dialog
      v-model:visible="localVisible"
      modal
      :closable="false"
      block-scroll
      :draggable="false"
      :auto-z-index="false"
      class="md:w-[35%] lg:w-[25%] w-[95%]"
    >

      <template #header>
        <h2 class="text-primary text-xl font-bold text-center break-words">
          {{ props.titulo }}
        </h2>
      </template>

      <template #default>
        <p class="text-justify break-words">
          {{ props.descripcion }}
        </p>
      </template>

      <template #footer>
        <div class="flex gap-5 w-full">
          <Button
            type="button"
            :label="props.textoBtnCancelar"
            outlined
            class="btn btn-outline--primary"
            @click="cancelar"
            size="small"
          />
          <Button
            type="button"
            :label="props.textoBtnConfirm"
            class="btn btn--primary"
            size="small"
            @click="aceptar"
          />
        </div>
      </template>
    </Dialog>

</template>

<style scoped>

</style>
