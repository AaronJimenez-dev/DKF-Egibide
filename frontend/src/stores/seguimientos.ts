import { defineStore } from "pinia";
import { ref } from "vue";
import { useAuthStore } from "./auth";
import type { Seguimiento } from "@/interfaces/Seguimiento";

export const useSeguimientosStore = defineStore("seguimientos", () => {
  const seguimientos = ref<Seguimiento[]>([]);
  const loading = ref(false);
  const error = ref<string | null>(null);
  const authStore = useAuthStore();

  // Traer seguimientos de un alumno
  async function fetchSeguimientos(alumnoId: number) {
    loading.value = true;
    error.value = null;
    try {
      const res = await fetch(`http://localhost:8000/api/seguimientos/alumno/${alumnoId}`, {
        headers: authStore.token
          ? { Authorization: `Bearer ${authStore.token}`, Accept: "application/json" }
          : { Accept: "application/json" },
      });
      if (!res.ok) throw new Error(`Error ${res.status}`);
      const data = await res.json();
      seguimientos.value = data as Seguimiento[];
    } catch (err: any) {
      error.value = err.message;
      seguimientos.value = [];
    } finally {
      loading.value = false;
    }
  }

  // Crear un nuevo seguimiento
  async function nuevoSeguimiento() {
    
  }

  return { seguimientos, loading, error, fetchSeguimientos, nuevoSeguimiento };
});
