import type { Empresa } from "@/interfaces/Empresa";
import { defineStore } from "pinia";
import { ref } from "vue";
import { useAuthStore } from "./auth";

export const useEmpresasStore = defineStore("empresas", () => {
  const empresas = ref<Empresa[]>([]);
  const loading = ref(false);
  const error = ref<string | null>(null);

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

  async function fetchEmpresas() {
    const response = await fetch("http://localhost:8000/api/empresas", {
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
    empresas.value = data as Empresa[];
  }

  async function fetchMiEmpresa() {
    loading.value = true;
    error.value = null;

    try {
      const response = await fetch("http://localhost:8000/api/me/empresa", {
        headers: authStore.token
          ? {
              Authorization: `Bearer ${authStore.token}`,
              Accept: "application/json",
            }
          : {
              Accept: "application/json",
            },
      });

      if (!response.ok) {
        const data = await response.json();
        throw new Error(data.message || "Error al cargar empresa");
      }

      const data = await response.json();

      empresas.value = Array.isArray(data) ? (data as Empresa[]) : ([data] as Empresa[]);
    } catch (err: any) {
      error.value = err.message ?? "Error desconocido";
      empresas.value = [];
    } finally {
      loading.value = false;
    }
  }

  async function createEmpresa(
    nombre: string,
    cif: string,
    telefono: number,
    email: string,
    direccion: string,
  ) {
    const response = await fetch("http://localhost:8000/api/empresas", {
      method: "POST",
      headers: {
        Authorization: authStore.token ? `Bearer ${authStore.token}` : "",
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ nombre, cif, telefono, email, direccion }),
    });

    const data = await response.json();

    if (!response.ok) {
      setMessage(
        data.message || "Error desconocido, inténtalo más tarde",
        "error",
      );
      return false;
    }

    setMessage(data.message || "Empresa creada correctamente", "success");
    return true;
  }

  return { empresas, message, messageType, fetchEmpresas, createEmpresa, fetchMiEmpresa };
});