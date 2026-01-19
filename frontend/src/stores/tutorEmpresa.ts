import { defineStore } from "pinia";
import { ref } from "vue";
import { useAuthStore } from "./auth";

export const useTutorEmpresaStore = defineStore("tutorEmpresa", () => {
  const alumnosAsignados = ref([]);

  const authStore = useAuthStore();

  const message = ref<string | null>(null);
  const messageType = ref<"success" | "error">("success");

  function setMessage(text: string, type: "success" | "error", timeout = 5000) {
    message.value = text;
    messageType.value = type;

    setTimeout(() => {
      message.value = null;
      messageType.value = "success";
    }, timeout);
  }

  async function fetchAlumnosAsignados(tutorId: string) {
    const response = await fetch(
      `http://localhost:8000/api/tutorEmpresa/${tutorId}/alumnos`,
      {
        method: "GET",
        headers: {
          Authorization: authStore.token ? `Bearer ${authStore.token}` : "",
          Accept: "application/json",
        },
      },
    );

    const data = await response.json();

    if (!response.ok) {
      setMessage(
        data.message || "Error desconocido, inténtalo más tarde",
        "error",
      );
      return false;
    }

    alumnosAsignados.value = data;

    return true;
  }

  return { alumnosAsignados, message, messageType, fetchAlumnosAsignados };
});
