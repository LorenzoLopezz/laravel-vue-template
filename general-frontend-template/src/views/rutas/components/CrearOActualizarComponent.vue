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
      <h1 class="title-2xl">Ruta</h1>
    </template>

    <template #closeicon>
      <i class="material-icons" @click="cerrarModal">close</i>
    </template>

    <div class="w-full space-y-5 mb-8">
      <div class="grid xl:grid-cols-3 gap-4">
        <div class="w-full">
          <InputText
            class="input-form w-full"
            placeholder="Título *"
            v-model="state.nombre"
            :invalid="v$.nombre.$dirty && v$.nombre.$invalid"
            @blur="v$.nombre.$touch()"
          />
          <small
            class="text-danger block"
            v-if="v$.nombre.$dirty && v$.nombre.$invalid"
          >
            {{ v$.nombre?.$errors[0]?.$message }}
          </small>
        </div>
        <div class="w-full">
          <InputText
            class="input-form w-full"
            placeholder="Nombre único (sin espacios) *"
            v-model="state.nombre_unico"
            :invalid="v$.nombre_unico.$dirty && v$.nombre_unico.$invalid"
            @blur="v$.nombre_unico.$touch()"
            @input="state.nombre_unico = state.nombre_unico.replace(/[^a-zA-Z0-9_-]/g, '_').toLowerCase()"
          />
          <small
            class="text-danger block"
            v-if="v$.nombre_unico.$dirty && v$.nombre_unico.$invalid">
            {{ v$.nombre_unico?.$errors[0]?.$message }}
          </small>
        </div>
        <div class="w-full">
          <InputText
            class="input-form w-full"
            placeholder="Ícono "
            v-model="state.icono"
            :invalid="v$.icono.$dirty && v$.icono.$invalid"
            @blur="v$.icono.$touch()"
          />
          <small
            class="text-danger block"
            v-if="v$.icono.$dirty && v$.icono.$invalid"
          >
            {{ v$.icono?.$errors[0].$message }}
          </small>
        </div>
      </div>
      <div class="grid xl:grid-cols-3 gap-4">
        <div class="w-full">
          <Dropdown
            :options="listaModulos"
            optionLabel="nombre"
            optionValue="id"
            class="input-form w-full"
            placeholder="Módulo *"
            v-model="state.id_modulo"
            :invalid="v$.id_modulo.$dirty && v$.id_modulo.$invalid"
            @blur="v$.id_modulo.$touch()"
          />
          <small
            class="text-danger block"
            v-if="v$.id_modulo.$dirty && v$.id_modulo.$invalid"
          >
            {{ v$.id_modulo?.$errors[0].$message }}
          </small>
        </div>
        <div class="flex items-center space-x-2">
          <Checkbox
            class="mr-2"
            v-model="state.mostrar"
            :binary="true"
          />
          <label for="mostrar">Mostrar en menú</label>
        </div>
        <div class="flex items-center space-x-2">
          <Checkbox
            class="mr-2"
            v-model="state.requiere_autenticacion"
            :binary="true"
          />
          <label for="mostrar">Requiere autenticación</label>
        </div>
      </div>
      <div class="grid xl:grid-cols-6 xl:items-center space-y-4 xl:space-y-0">
        <div class="col-start-1 col-end-1">
          <Checkbox
            v-model="state.enrutable"
            class="mr-2"
            :binary="true"
          />
          <label for="mostrar">Contiene ruta</label>
        </div>
        <div class="flex-1 space-y-2 text-text-secondary-2 xl:col-start-2 xl:col-span-5">
          <div>
            <InputText
              class="input-form w-full"
              placeholder="Path *"
              v-model="state.uri"
              :disabled="!state.enrutable"
              :invalid="v$.uri.$dirty && v$.uri.$invalid"
              @blur="v$.uri.$touch()"
            />
            <small
              class="text-danger block"
              v-if="v$.uri.$dirty && v$.uri.$invalid"
            >
              {{ v$.uri?.$errors[0].$message }}
            </small>
            <p class="text-xs">
              Puede usar <b> &#60;&#60;:variable&#62;&#62;</b>
              para establecer variables dentro de la ruta, ejemplo
              <b> /ruta/:id</b>
            </p>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-1 gap-2">
        <InputText
          class="input-form w-full"
          placeholder="Componente a importar *"
          v-model="state.componente"
          :invalid="v$.componente.$dirty && v$.componente.$invalid"
          @blur="v$.componente.$touch()"
          :disabled="!state.enrutable"
        />
        <small
          class="text-danger block"
          v-if="v$.componente.$dirty && v$.componente.$invalid"
        >
          {{ v$.componente?.$errors[0].$message }}
        </small>
        <p class="text-xs text-text-secondary-2">
          Iniciar con <b> &#60;&#60;../&#62;&#62; </b>
          para evitar problemas del uri, ejemplo>
          <b>../views/DashboardView.vue</b>
        </p>
      </div>
      <div class="grid xl:grid-cols-6 items-center space-y-4 xl:space-y-0">
        <div class="col-start-1 col-end-3">
          <Checkbox
            v-model="state.dependencia_menu_padre"
            class="mr-2"
            :binary="true"
          />
          <label for="mostrar">Depende de otra ruta</label>
        </div>
        <div class="w-full lg:col-start-3 lg:col-end-7 col-span-7">
          <Dropdown
            filter
            filterPlaceholder="Buscar ruta padre"
            empty-filter-message="No se encontraron rutas"
            class="input-form w-full"
            placeholder="Ruta *"
            :options="listaRutas"
            option-label="nombre"
            option-value="id"
            :disabled="!state.dependencia_menu_padre"
            v-model="state.id_menu_opcion_padre"
            :invalid="v$.id_menu_opcion_padre.$dirty && v$.id_menu_opcion_padre.$invalid"
            @blur="v$.id_menu_opcion_padre.$touch()"
          />
          <small
            class="text-danger block"
            v-if="v$.id_menu_opcion_padre.$dirty && v$.id_menu_opcion_padre.$invalid"
          >
            {{ v$.id_menu_opcion_padre?.$errors[0].$message }}
          </small>
        </div>
      </div>
    </div>
    <div class="w-full flex md:justify-end">
      <div class="md:w-1/2 flex justify-center w-full md:justify-end space-x-5">
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
          label="Agregar"
          class="btn btn--primary"
          size="small"
          @click="guardarDatos"
        />
      </div>
    </div>

  </Dialog>
</template>

<script setup>
import {ref,  watch, reactive, watchEffect} from "vue";
import {helpers, maxLength, minLength, required, requiredIf} from '@vuelidate/validators';
import {useVuelidate} from '@vuelidate/core';
//components primevue
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import Checkbox from "primevue/checkbox";
import Button from "primevue/button";
import Dropdown from "primevue/dropdown";
//services
import rutasServices from "@/services/rutas.services.js";
import { useCatalogos } from "@/composable/useCatalogos.js";
import { useToastService} from "@/utils/toastApp.js";
import { useAuthStore } from "@/store/auth.store.js";

const props = defineProps({
  visible: {
    type: Boolean,
    required: true,
  },
  rutaId: {
    type: Number,
    default: null,
  },

  editar: {
    type: Boolean,
    default: false,
  },

  listaRutas: {
    type: Array,
    required: true,
  }
});

const localVisible = ref(props.visible);
const emit = defineEmits(["update:visible", 'recargar']);
const regexComponente = helpers.regex(/^..\/[a-zA-Z0-9/]+\.vue$/)

const { obtenerModulos, listaModulos } = useCatalogos();
const { successToast } = useToastService();
const rutaPadre = ref(null);
const { getMenu } = useAuthStore();

const state = reactive({
  nombre: '',
  nombre_unico: '',
  icono: '',
  id_modulo: '',
  mostrar: false,
  requiere_autenticacion: false,
  enrutable: false,
  uri: '',
  componente: '',
  dependencia_menu_padre: false,
  id_menu_opcion_padre: '',
})

const validaciones = ref({
  nombre: {
    required: helpers.withMessage('El campo es requerido', required),
    minLength: helpers.withMessage('El campo debe tener al menos 3 caracteres', minLength(3)),
    maxLength: helpers.withMessage('El campo debe tener máximo 50 caracteres', maxLength(50)),
    withI18nMessage: helpers.withMessage(
      'El campo solo puede contener letras y números',
      helpers.regex(/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]*$/)
    ),
  },
  nombre_unico: {
    required: helpers.withMessage('El campo es requerido', required),
    minLength: helpers.withMessage('El campo debe tener al menos 3 caracteres', minLength(3)),
    maxLength: helpers.withMessage('El campo debe tener máximo 50 caracteres', maxLength(50)),
    withI18nMessage: helpers.withMessage(
      'El campo solo puede contener letras, números, guiones bajos y medios, sin espacios',
      helpers.regex(/^[a-zA-Z0-9_-]*$/)
    ),

  },
  icono: {regex: helpers.withMessage('El campo solo puede contener letras y números', /^[a-zA-Z0-9]*$/)},
  id_modulo: {required: helpers.withMessage('El campo es requerido', required)},
  uri: {
    requiredIf: helpers.withMessage('El campo es requerido', requiredIf(() => state.enrutable)),
    maxLength: helpers.withMessage('El campo debe tener máximo 150 caracteres', maxLength(150)),
    withI18nMessage: helpers.withMessage(
      'El campo solo puede contener letras, números, /, :, y -',
      helpers.regex(/^[a-zA-Z0-9\/:_-]*$/)
    ),
  },
  componente: {
    requiredIf: helpers.withMessage('El campo es requerido', requiredIf(() => state.enrutable)),
    minLength: helpers.withMessage('El campo debe tener al menos 3 caracteres', minLength(3)),
    maxLength: helpers.withMessage('El campo debe tener máximo 150 caracteres', maxLength(150)),
    withI18nMessage: helpers.withMessage(
      'El campo debe iniciar con ../ y terminar con .vue', regexComponente)
  },
  id_menu_opcion_padre: {requiredIf: helpers.withMessage('El campo es requerido', requiredIf(() => state.dependencia_menu_padre))},
})

const v$ = useVuelidate(validaciones, state);


watch(() => props.visible, (value) => {
  localVisible.value = value;
  value && obtenerModulos();
});

watch(() => props.editar, (value) => {
  if (value && props.rutaId) {
    obtenerRuta();
  }
})

watchEffect(() => {
  if (!state.enrutable) {
    state.uri = '';
  }

  if (!state.dependencia_menu_padre) {
    state.id_menu_opcion_padre = '';
  }
})


const obtenerRuta = async () => {
  const { data } = await rutasServices.obtenerDetalleRuta(props.rutaId);
  const { modulo, mostrar, icono, enrutable, nombre, parent, uri,
    requiere_autenticacion, nombre_unico, componente, dependencia_menu_padre } = data[0];

  state.nombre = nombre;
  state.nombre_unico = nombre_unico;
  state.id_modulo = modulo?.id;
  state.icono = icono;
  state.mostrar = mostrar;
  state.uri = uri;
  state.componente = componente;
  state.enrutable = enrutable;
  state.id_menu_opcion_padre = parent?.id;
  state.dependencia_menu_padre = dependencia_menu_padre;
  state.requiere_autenticacion = requiere_autenticacion;

  rutaPadre.value = parent?.id;
}

const guardarDatos = async () => {
  if (v$.value.$invalid) {
    v$.value.$touch();
    return;
  }

  const respuesta = props.editar ? await actualizarRuta() : await crearRuta();
  if (respuesta) {
    emit('recargar');
    rutaPadre.value !== state.id_menu_opcion_padre && await getMenu();
    cerrarModal();
  }
}


const actualizarRuta = async () => {
  const { status } = await rutasServices.actualizarRuta(props.rutaId, state);
  if (status === 200 || status === 204) {
    successToast('Ruta actualizada', 'La ruta se actualizó correctamente');
    return true;
  }

  return false;
}

const crearRuta = async () => {
  const { status } = await rutasServices.crearRuta(state);
  if (status === 200 || status === 201) {
    successToast('Ruta creada', 'La ruta se creó correctamente');
    return true;
  }

  return false;
}

const limpiarState = () => {
  state.nombre = '';
  state.nombre_unico = '';
  state.icono = '';
  state.id_modulo = '';
  state.mostrar = false;
  state.requiere_autenticacion = false;
  state.enrutable = false;
  state.uri = '';
  state.componente = '';
  state.dependencia_menu_padre = false;
  state.id_menu_opcion_padre = '';
  rutaPadre.value = null;
  v$.value.$reset();
}

const cerrarModal = () => {
  localVisible.value = false;
  limpiarState();
  emit('update:visible', false);
}

</script>


<style scoped lang="scss">

</style>
