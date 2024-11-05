<script setup>
import {computed, onMounted, ref} from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';


const props = defineProps({
  listaDatos: {
    type: Array,
    default: () => [],
    required: true
  },
  datosEncabezados: {
    type: Array, // [{ name: 'Nombre', value: 'nombre', classCol: 'w-1/2' }]
    required: true
  },
  listarMas: {
    type: Boolean,
    default: true,
  },
  estiloTabla: {
    type: String,
    default: 'min-width: 50rem',
  },
  tamanio: {
    type: String,
    default: 'null',
  },
  correlativo: {
    type: Boolean,
    default: false,
  },
  loading: {
    type: Boolean,
    default: false,
  },
  requiereBusqueda: {
    type: Boolean,
    default: false,
  },
  delayBusqueda: {
    type: Number,
    default: 500,
  },
  clasesBuscqueda: {
    type: String,
    default: 'w-full border-0',
  },
  requiredAccion: {
    type: Boolean,
    default: false,
  },
  paginacion: {
    type: Boolean,
    default: false,
  },

  cantidadPorPagina: {
    type: Array,
    default: () => [10, 20, 50, 100],
  },

  infoPaginacion: {
    type: Object,
    default: () => ({
      page: 1,
      per_page: 10,
      ultimaPagina: 1,
      totalRegistros: 1,
    }),
  },

  accionBtn: {
    type: Object,
    default: () => ({
      icon: 'pi pi-plus-circle',
      text: 'Agregar',
    })
  },

  filtrosPersonalizados: {
    type: Boolean,
    default: false,
  },

  placeholderBusqueda: {
    type: String,
    default: 'Buscar...',
  }
});

const emit = defineEmits();
const buscar = ref('');
const timeOut = ref(null);

const handleCargarMas = () => emit('cargarMas');
const handleChangePage = (event) => emit('cambioPagina', {
  page: event.page + 1,
  rowsPerPage: event.rows,
  from: event.first,
  to: event.first + event.rows - 1
});
const EmitBusqueda = () => {
  timeOut.value && clearTimeout(timeOut.value);
  timeOut.value = setTimeout(() => {
    emit('buscar', buscar.value)
  }, props.delayBusqueda);
};

const cargaDatos = computed(() => {
  if (props.listaDatos.lenght === 0) return;
  return props.listaDatos.map((item, index) => {
    return {
      correlativo: index + 1,
      ...item
    }
  });
});

onMounted(() => {
  if (props.correlativo && !props.datosEncabezados.some(header => header.value === 'correlativo')) {
    props.datosEncabezados.unshift({ name: 'N°', value: 'correlativo', classCol: 'w-1/12' });
  }
})
</script>

<template>
  <div class="card space-y-5">
    <div v-if="!filtrosPersonalizados" :class="requiredAccion ? 'grid grid-cols-1 md:grid-cols-5 md:gap-x-5 gap-y-2 md:gap-y-0':'md:md:w-[50%]'">
      <div class="md:col-span-3">
        <IconField iconPosition="left" class="col-auto" v-if="requiereBusqueda">
          <InputIcon class="pi pi-search"></InputIcon>
          <InputText
            v-model="buscar"
            :placeholder="placeholderBusqueda"
            size="small"
            :class="clasesBuscqueda"
            @input="EmitBusqueda"
            maxlength="75"
          />
        </IconField>
      </div>
      <div v-if="requiredAccion" class="md:col-start-4 md:col-span-2">
        <Button class="btn text-center bg-primary !float-end w-full md:w-auto" @click="emit('accion')">
          <div class="flex justify-center w-full items-center space-x-5">
            <i :class="accionBtn.icon"></i>
            <span>{{ accionBtn.text }}</span>
          </div>
        </Button>
      </div>
    </div>

    <div v-if="filtrosPersonalizados">
      <slot name="filtros"></slot>
    </div>

    <DataTable
      :value="cargaDatos"
      dataKey="id"
      :tableStyle="estiloTabla"
      :size="tamanio"
      scrollHeight="flex"
      table-class="text-sm"
      lazy
      :paginator="paginacion"
      :rows="infoPaginacion.per_page"
      :rows-per-page-options="cantidadPorPagina"
      :totalRecords="infoPaginacion.totalRegistros"
      :pageLinkSize="infoPaginacion.ultimaPagina"
      :currentPage="infoPaginacion.page"
      @page="handleChangePage"
    >
      <template #header>
        <div class="rounded-xl"></div>
      </template>
      <template #empty>
        <div class="flex justify-center items-center">
          <span class="text-gray-400 font-semibold">No hay registros</span>
        </div>
      </template>
      <Column
        v-for="(header, index) in datosEncabezados"
        :key="index"
        :field="header.value"
        :class="header.classCol"
        headerClass="text-red-500"
      >
        <template #header>
          <div class="flex justify-between items-center font-bold text-primary">
            <span>{{ header.name }}</span>
          </div>
        </template>
        <template #body="slotProps">
          <slot :name="header.value" :item="slotProps.data">
            {{ slotProps.data[header.value] }}
          </slot>
        </template>
      </Column>

      <template #footer v-if="listarMas">
        <div class="w-full grid place-items-center themeColor1" v-if="listarMas">
          <Button
            @click="handleCargarMas"
            fluid
            class="bg-primary gap-x-2 border-0 hover:bg-primary hover:bg-opacity-80 !rounded-xl text-xs"
            size="small"
            :loading="loading"
          >
            <div v-if="!loading" class="flex place-items-center">
              <span class="text-white font-semibold">Cargar más...</span>
            </div>
          </Button>
        </div>
      </template>
    </DataTable>
  </div>
</template>

<style scoped></style>
