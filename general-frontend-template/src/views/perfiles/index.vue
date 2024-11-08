<template>
  <div class="w-full flex-1 flex justify-center items-center">
    <div class="w-full min-h-screen flex-1 flex justify-start items-start flex-col bg-background">
      <div class="w-full flex flex-col sm:flex-row sm:justify-between sm:items-center text-left px-8 py-8">
        <h1 class="text-4xl">Perfiles</h1>
        <Button
          aria-label="Nuevo perfil"
          icon="pi pi-plus-circle"
          label="Nuevo perfil"
          class="btn--primary px-8 bg-primary border-0 rounded-lg hover:bg-primary shadow-none"
          @click="showPerfil('crear')"
        />
      </div>
      <div class="w-full flex flex-col space-y-8 place-items-center gap-x-8 px-8 pb-8">
        <template v-for="(item, index) in listaPerfiles" :key="index">
          <Card class="w-full rounded-xl pa-0 ma-0">
            <template #content>
            <div class="pa-0 ma-0">
              <Accordion class="pa-0 ma-0" expandIcon="pi pi-angle-down" collapseIcon="pi pi-angle-up">
                <AccordionTab class="pa-0 ma-0">
                    <template #header>
                      <span class="flex align-items-center gap-2 w-full">
                        <span class="font-bold white-space-nowrap">{{ item.nombre }}</span>
                      </span>
                    </template>
                    <p class="pa-0 ma-0">
                      <perfiles-data-table :idPerfil="item.id" />
                    </p>
                </AccordionTab>
            </Accordion>
            </div>
          </template>
          </Card>
        </template>
      </div>
    </div>
  </div>
  <modal-perfil
    :visible="perfilModal"
    @update:visible="perfilModal = $event"
    :perfilObj="perfilObj"
    :tipo="type"
  />
  <modal-confirmacion
    :visibleModal="modalEliminar"
    titulo="¿Estás seguro de eliminar este perfil?"
    descripcion="Una vez eliminado, no se podrán revertir los cambios."
    textoBtnConfirm="Sí, eliminar"
    textoBtnCancelar="Cancelar"
    @update:visible="modalEliminar = $event"
    @aceptar="eliminarPerfil"
    @cancelar="modalEliminar = false"
  />
</template>

<script setup>
import Card from "primevue/card";
import Button from "primevue/button";
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';
import ModalPerfil from "@/views/perfiles/components/modals/ModalPerfilComponent.vue";
import PerfilesDataTable from "@/views/perfiles/components/PerfilesDataTableComponent.vue";

import { ref, onMounted, watch } from 'vue';

// services
import perfilesService from "@/services/perfiles.services"

const listaPerfiles = ref([]);
const loading = ref(false);
const perfilObj = ref({});
const modalEliminar = ref(false);
const perfilModal = ref(false);
const type = ref('');
const overlayAbierto = ref(null); // Estado para almacenar el id del OverlayPanel abierto
const refs = ref({}); // Para almacenar las referencias de OverlayPanel

const listaAcciones = ref([
  { label: 'Actualizar perfil', classIcon: 'text-primary', icon: 'edit', accion: (item) => showPerfil('editar', item) },
  { label: 'Borrar perfil', classIcon: 'text-danger', icon: 'delete', accion: (item) => showEliminar(item) },
]);

function showPerfil(tipo, item){
  type.value = tipo;
  if(tipo === 'editar'){
    perfilObj.value = item;
    perfilModal.value = true;
  }else{
    perfilModal.value = true;
  }
}

const listarPerfiles = async () => {
  // Cierra todos los OverlayPanels antes de hacer cualquier cambio
  closeAllOverlays();

  // Resetear el valor de overlayAbierto
  overlayAbierto.value = null;

  // Vaciamos listaRoles
  listaPerfiles.value = [];

  const response = await perfilesService.obtenerPerfiles();

  listaPerfiles.value = response.data.map((item) => {
    return {
      id: item.id,
      nombre: item.nombre,
    };
  });

  // Actualiza refs para los nuevos elementos si es necesario
  updateOverlayRefs();
}

// Función para cerrar todos los OverlayPanels
const closeAllOverlays = () => {
  Object.keys(refs.value).forEach(key => {
    if (refs.value[key]) {
      refs.value[key].hide(); // Oculta cualquier OverlayPanel abierto
    }
  });
};

// Función para actualizar las referencias de los OverlayPanels
const updateOverlayRefs = () => {
  // Asegúrate de limpiar las referencias
  for (const key in refs.value) {
    if (refs.value[key]) {
      refs.value[key] = null; // Limpia las referencias
    }
  }
};

function showEliminar(item){
  perfilObj.value = item.item;
  modalEliminar.value = true;
}

const eliminarPerfil = async () => {
  perfilObj.value = {};
  modalEliminar.value = false
}

onMounted(() => {
  listarPerfiles();
})

// Método para establecer la referencia del OverlayPanel
const setOverlayRef = (el, id) => {
  if (!refs.value[id]) {
    refs.value[id] = el;
  }
};

// Función para abrir/cerrar el OverlayPanel
const toggleOverlay = (id, event) => {
  // Si hay otro OverlayPanel abierto, ciérralo
  if (overlayAbierto.value && overlayAbierto.value !== id) {
    refs.value[overlayAbierto.value].hide(); // Cierra el panel anterior
  }

  // Establece el nuevo OverlayPanel abierto
  overlayAbierto.value = id;

  // Muestra el nuevo OverlayPanel
  refs.value[id].toggle(event);
};

// Watch para manejar el cierre automático del OverlayPanel anterior
watch(overlayAbierto, (newId, oldId) => {
  if (oldId && oldId !== newId && refs.value[oldId]) {
    refs.value[oldId].hide(); // Cierra el panel anterior
  }
});

// Watch para cerrar el OverlayPanel cuando se abre el modal
watch(modalEliminar, (newVal) => {
  if (newVal && overlayAbierto.value) {
    refs.value[overlayAbierto.value].hide(); // Cierra el OverlayPanel si está abierto
  }
});

// Watch para cerrar el OverlayPanel cuando se abre el modal
watch(perfilModal, (newVal) => {
  if (newVal && overlayAbierto.value) {
    refs.value[overlayAbierto.value].hide(); // Cierra el OverlayPanel si está abierto
  }
});
</script>

<style lang="scss" scoped>

</style>

<style scoped>
/* Aumentando la especificidad del selector */
:deep(.p-accordion .p-accordion-header .p-accordion-header-link) {
    background-color: transparent; /* Ejemplo de color de fondo */
    color: #000a65; /* Ejemplo de color del texto */
    border-color: transparent;
}

:deep(.p-accordion .p-accordion-content){
  border-color: transparent;
}

:deep(.p-card .p-card-content), :deep(.p-card .p-card-body) {
  padding: 0;
}

</style>
