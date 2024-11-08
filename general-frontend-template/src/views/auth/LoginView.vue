<template>
  <div class="h-[105vh] bg-primary">
    <Toast />
    <div class="flex flex-row flex-wrap lg:flex-nowrap justify-center items-center h-screen"
      style="position: relative; z-index: 2;">

      <div class="w-full lg:w-2/5 xl:w-1/3">
        <Card>
          <template #content>
            <div class="flex flex-col gap-2 mb-4">
              <InputText name="username" type="text" placeholder="Usuario *" v-model="state.username" :class="{
                'input-form-error': !isValidUsername && showErrorUsername,
                'text-primary': isValidUsername || !showErrorUsername,
              }" @blur="validateUsername()" variant="filled" />
              <small v-if="isValidUsername == false && showErrorUsername == true" class="text-danger">
                {{ errorUsername }}
              </small>
            </div>

            <div class="flex flex-col gap-2 mb-4">
              <Password v-model="state.password" placeholder="Contraseña *" @blur="validatePassword()" :feedback="false"
                toggleMask variant="filled" />
              <small v-if="isValidPassword == false && showErrorPassword == true" class="text-danger block">
                {{ errorPassword }}
              </small>
            </div>

            <Button label="Ingresar" class="w-full mt-8" @click="handleLogin()" severity="success" size="small" />
          </template>
        </Card>
      </div>
    </div>
  </div>
</template>

<script setup>
import Button from "primevue/button"
import InputText from 'primevue/inputtext'
import Card from 'primevue/card'
import Password from 'primevue/password'
import Toast from 'primevue/toast'

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
