import { ref } from 'vue'
import catalogosServices from "@/services/catalogos.services.js";

export const useCatalogos = () => {
  const listaPerfiles = ref([])
  const obtenerPerfiles = async () => {
    const { data } = await catalogosServices.obtenerPerfiles()
    listaPerfiles.value = data
  }

  const listaModulos = ref([])
  const obtenerModulos = async ( params = {} ) => {
    const { data } = await catalogosServices.obtenerModulos( params )
    listaModulos.value = data
  }

  return {
    obtenerPerfiles,
    obtenerModulos,
  }
}
