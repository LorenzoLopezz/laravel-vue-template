<template>
  <div
    class="w-full h-screen bg-primary flex justify-center items-center flex-col"
  >
    <Toast />
  </div>
</template>
<script setup>
import Toast from "primevue/toast";
import { io } from "socket.io-client";

import { onMounted } from "vue";
import { useToastService } from "../../utils/toastApp";
const { errorToast } = useToastService();

onMounted(() => {
  const socket = io(import.meta.env.VITE_VUE_APP_SOCKET_URL, {
    extraHeaders: {
      Authorization: 'Bearer ACB2244'
    }
  });

  socket.connect();

  socket.on("connect_error", (error) => {
    console.log(error);
    if (error == "Error: Unauthorized") {
      errorToast("Usuario no autorizado", "Sesión vencida o token inválido");
      return;
    }

    errorToast("Error", error);
    socket.disconnect();
  });

  socket.on("validationFailed", (errors) => {
    if (typeof errors === 'object' && errors !== null) {
      errorToast("Error", errors?.errors?.join(', '));
    } else {
      errorToast("Error", errors);
    }
  });

  socket.on("error", (error) => {
    errorToast("Error", error);
  });

  setTimeout(() => {
    socket.emit("newMessage", {
      message: "Hola mundo desde sip planner",
    });
  }, 1000);
});
</script>
<style scoped>
.bg-login {
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  height: 100vh;
}
</style>
