<template>
  <div class="w-full h-screen bg-primary flex justify-center items-center flex-col">
    <Toast />

    <div class="w-full max-w-[400px] h-auto p-10 bg-white border border-gray-200 rounded-2xl flex flex-col">
      <div class="flex justify-center items-center mb-5">
        <div class="text-text-primary text-xl font-medium">Iniciar sesión</div>
      </div>

      <!-- Usuario -->
      <div class="flex flex-col mb-5">
        <label for="username" class="font-semibold mb-1"
          :class="isValidUsername == false && showErrorUsername == true ? 'text-danger' : 'text-text-primary'">Usuario</label>
        <span class="relative w-full">
          <InputText placeholder="Ingrese su usuario" v-model="state.username" class="w-full bg-transparent input-form"
            :class="{
              'input-form-error': !isValidUsername && showErrorUsername,
              'text-primary': isValidUsername || !showErrorUsername,
            }" @blur="validateUsername()" />
          <small v-if="isValidUsername == false && showErrorUsername == true" class="text-danger">{{ errorUsername
            }}</small>
        </span>
      </div>

      <!-- Contraseña -->
      <div class="flex flex-col mb-10">
        <label for="username" class="font-semibold mb-1"
          :class="isValidPassword == false && showErrorPassword == true ? 'text-danger' : 'text-text-primary'">Contraseña</label>
        <span class="relative w-full">
          <i class="absolute top-2/4 -mt-3 right-3 cursor-pointer" @click="visibilityPassword = !visibilityPassword"
            :class="isValidPassword == false && showErrorPassword == true ? 'text-danger' : 'text-black'">
            <span class="material-symbols-outlined opacity-50">{{ visibilityPassword ? 'visibility' : 'visibility_off'
              }}</span>
          </i>
          <InputText placeholder="Ingrese su contraseña" :type="visibilityPassword ? 'text' : 'password'"
            v-model="state.password" class="w-full bg-transparent input-form" :class="{
              'input-form-error': !isValidPassword && showErrorPassword,
              'text-primary': isValidPassword || !showErrorPassword,
            }" @blur="validatePassword()" />
        </span>
        <small v-if="isValidPassword == false && showErrorPassword == true" class="text-danger">{{ errorPassword
          }}</small>
      </div>

      <Button label="Ingresar" class="btn w-full text-md shadow-none" style="background-color: #000a65; color: white;"
        @click="handleLogin()" />
    </div>
  </div>
</template>

<script setup>
import Button from "primevue/button"
import InputText from 'primevue/inputtext';
import Toast from 'primevue/toast';

import { ref, computed, reactive } from "vue";
import { useAuthStore } from "../../store/auth.store"
import { useVuelidate } from '@vuelidate/core'
import { required, email, helpers } from '@vuelidate/validators'
import { useRouter } from 'vue-router'
import { useToastService } from '../../utils/toastApp';

const router = useRouter();
const isValidUsername = ref(false);
const showErrorUsername = ref(false);
const errorUsername = ref('');
const isValidPassword = ref(false);
const showErrorPassword = ref(false);
const errorPassword = ref('');
const visibilityPassword = ref(false);
const { errorToast } = useToastService();

const state = reactive({
  username: '',
  password: '',
})

const rules = computed(() => (
  {
    username: {
      required: helpers.withMessage('El correo es requerido', required),
      email: helpers.withMessage('El correo ingresado no posee un formato válido', email),
    },
    password: {
      required: helpers.withMessage('La contraseña es requerida', required),
    }
  }
));

const v$ = useVuelidate(rules, state);

const authStore = useAuthStore();
const { login } = authStore;

const validateUsername = () => {
  let isValid = false;
  v$.value.username.$touch();

  if (v$.value.username.$invalid) {
    errorUsername.value = v$.value.username.$errors[0].$message;
    isValidUsername.value = false;
    showErrorUsername.value = true;
  } else {
    errorUsername.value = null;
    isValidUsername.value = true;
    showErrorUsername.value = false
    isValid = true;
  }
  return isValid;
}

const validatePassword = () => {
  let isValid = false;
  v$.value.password.$touch();

  if (v$.value.password.$invalid) {
    errorPassword.value = v$.value.password.$errors[0].$message;
    isValidPassword.value = false;
    showErrorPassword.value = true;
  } else {
    errorPassword.value = null;
    isValidPassword.value = true;
    showErrorPassword.value = false
    isValid = true;
  }
  return isValid;
}

const handleLogin = async () => {
  let isValidUser = validateUsername();
  let isValidPass = validatePassword();

  if (!isValidUser || !isValidPass) {
    errorToast('Error', 'El usuario o la contraseña no contiene un valor válido')
    return;
  }

  try {
    await authStore.login(state);
    if (authStore.permissions.length === 0) {
      errorToast('Error', 'No tienes permisos para acceder a la aplicación');
      return;
    }

    (router.currentRoute.value.redirectedFrom)
      ? router.push(router.currentRoute.value.redirectedFrom)
      : router.push({ name: 'dashboard', replace: true });

  } catch (error) {
    errorToast(
      'Error',
      'Ocurrió un error al intentar iniciar sesión. Intenta nuevamente mas tarde',
      5000
    );
  }
};
</script>

<style scoped>
.bg-login {
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  height: 100vh;
}
</style>
