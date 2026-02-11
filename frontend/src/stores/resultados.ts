import type { Resultado } from "@/interfaces/Resultado";
import { defineStore } from "pinia";
import { ref } from "vue";
import { useAuthStore } from "./auth";
import type { TutorEgibide } from "@/interfaces/TutorEgibide";

const baseURL = import.meta.env.VITE_API_BASE_URL;

export const useResultadosStore = defineStore("resultado", () => {
  const resultados = ref<Resultado[]>([]);

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

  // Obtener todos los resultados
  async function fetchAsignaturas(){
    const response = await fetch(`${baseURL}/api/asignaturas`,{
      method: "GET",
      headers: authStore.token
        ? {
            Authorization: `Bearer ${authStore.token}`,
            Accept: "application/json",
          }
        : {
            Accept: "application/json",
          },
    });
    const data = await response.json();
    resultados.value = data as Resultado[];
  }
  async function fetchResultados() {
    const response = await fetch(`${baseURL}/api/resultados`, {
      method: "GET",
      headers: authStore.token
        ? {
            Authorization: `Bearer ${authStore.token}`,
            Accept: "application/json",
          }
        : {
            Accept: "application/json",
          },
    });

    const data = await response.json();
    resultados.value = data as Resultado[];
  }


  async function createResultado(descripcion: string, id_asignatura: number) {
    const response = await fetch(`${baseURL}/api/resultado`, {
      method: "POST",
      headers: {
        Authorization: authStore.token ? `Bearer ${authStore.token}` : "",
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ descripcion, id_asignatura}),
    });

    const data = await response.json();

    if (!response.ok) {
      setMessage(
        data.message || "Error desconocido, inténtalo más tarde",
        "error",
      );
      return false;
    } else {
      setMessage(data.message || "Resultado creado correctamente", "success");
      return true;
    }
  }
  return {
    resultados,
    message,
    messageType,
    fetchResultados,
    fetchAsignaturas,
    createResultado
  };
});
