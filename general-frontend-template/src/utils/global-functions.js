import { useAuthStore } from "@/store/auth.store";
import dayjs from "dayjs";
let debounceTimeout;

export const checkUserRoles = (...rolesList) => {
  const auth = useAuthStore();
  const permissions = auth?.permissions || [];

  if (permissions.length === 0) return false;
  const permissionNames = permissions.map((perm) => perm.nombre);

  if (permissionNames.includes('ADMIN_SUPER_USER')) return true;

  return rolesList.some((role) => permissionNames.includes(role));
}

export const userHasRole = (roleName) => {
  const auth = useAuthStore();
  return auth?.permissions.some((perm) => perm.nombre === roleName) || false;
}

export const formatToCurrency = (amount, withSymbol = true) => {
  return new Intl.NumberFormat("en-US", {
    style: withSymbol ? "currency" : "decimal",
    currency: "USD",
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(amount);
}

export const formatDate = (date) => {
  return date ? dayjs(date).format("DD/MM/YYYY") : dayjs().format("DD/MM/YYYY");
}

export const formatDateWithTime = (date, pattern = "DD/MM/YYYY HH:mm:ss") => {
  return date ? dayjs(date).format(pattern) : dayjs().format(pattern);
}

export const canAccessRoute = async (route) => {
  const rolesNeeded = route?.meta?.canAccess || [];
  return checkUserRoles(...rolesNeeded);
}

export const onlyLetters = (text) => {
  if (!text) return true;
  return /^[A-Za-zÀ-ÿ\u00f1\u00d1\s\-\']+$/.test(text);
}

export const debounceFunction = (callback, delay = 800) => {
  if (debounceTimeout) clearTimeout(debounceTimeout);
  debounceTimeout = setTimeout(callback, delay);
}

export const formatoNombrePermiso = (value) => {
  if (!value) return true;
  return value.toUpperCase().match(/^[A-Z_\u00d1\s\-]+$/);
}
