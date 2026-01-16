import type { Alumno } from "@/interfaces/Alumno"
import { defineStore } from "pinia"
import { useAuthStore } from "./auth";

export const useAlumnosStore = defineStore("alumnos", () => {
  const alumnos = ref<Alumno[]>([]);
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

  async function fetchAlumnos() {
    const response = await fetch("http://localhost:8000/api/alumnos", {
      method: "GET",
      headers: {
        Authorization: authStore.token ? `Bearer ${authStore.token}` : "",
        Accept: "application/json",
      },
    });

      this.loading = true
      this.error = null

      try {
        const response = await fetch("http://localhost:8000/api/me/alumno", {
          method: "GET",
          headers: {
            Authorization: authStore.token ? `Bearer ${authStore.token}` : "",
            Accept: "application/json",
          },
        })

        if (!response.ok) {
          const err = await response.json().catch(() => ({}))
          throw { status: response.status, data: err }
        }

        const data: Alumno = await response.json()
        this.Alumno = data
      } catch (e: any) {
        this.Alumno = null
        this.error = e?.data?.message ?? "Error al cargar alumno"
      } finally {
        this.loading = false
      }
    },
  },
})
  async function createAlumno(
    nombre: string,
    apellidos: string,
    telefono: number,
    ciudad: string,
  ) {
    const response = await fetch("http://localhost:8000/api/alumnos", {
      method: "POST",
      headers: {
        Authorization: authStore.token ? `Bearer ${authStore.token}` : "",
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ nombre, apellidos, telefono, ciudad }),
    });

    const data = await response.json();

    if (!response.ok) {
      setMessage(
        data.message || "Error desconocido, inténtalo más tarde",
        "error",
      );
      return false;
    }

    setMessage(data.message || "Alumno creado correctamente", "success");
    return true;
  }

  return { alumnos, message, messageType, fetchAlumnos, createAlumno };
});
