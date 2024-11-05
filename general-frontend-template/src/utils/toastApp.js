import { useToast } from "primevue/usetoast";

export const useToastService = () => {
  const toast = useToast();

  const successToast = (summary = 'Success Message', detail = 'Message Content', life = 3000) => {
    toast.add({ severity: 'success', summary: summary, detail: detail, life: life });
  }

  const infoToast = (summary = 'Info Message', detail = 'Message Content', life = 3000) => {
    toast.add({ severity: 'info', summary: summary, detail: detail, life: life });
  }

  const warnToast = (summary = 'Warn Message', detail = 'Message Content', life = 3000) => {
    toast.add({ severity: 'warn', summary: summary, detail: detail, life: life });
  }

  const errorToast = (summary = 'Error Message', detail = 'Message Content', life = 5000) => {
    toast.add({ severity: 'error', summary: summary, detail: detail, life: life });
  }

  const secondaryToast = (summary = 'Secondary Message', detail = 'Message Content', life = 3000) => {
    toast.add({ severity: 'secondary', summary: summary, detail: detail, life: life });
  }

  const contrastToast = (summary = 'Contrast Message', detail = 'Message Content', life = 3000) => {
    toast.add({ severity: 'contrast', summary: summary, detail: detail, life: life });
  }

  return { successToast, infoToast, warnToast, errorToast, secondaryToast, contrastToast };
};
