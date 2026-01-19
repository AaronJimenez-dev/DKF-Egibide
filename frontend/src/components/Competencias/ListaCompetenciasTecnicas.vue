<script setup lang="ts">
import type { Competencia } from "@/interfaces/Competencia";
import { useCompetenciasStore } from "@/stores/competencias";
import { onMounted, ref } from "vue";
import Toast from "../Notification/Toast.vue";
import router from "@/router";

const props = defineProps<{
  alumnoId: number;
}>();

const competenciaStore = useCompetenciasStore();

const competencias = ref<Competencia[]>([]);
const competenciasSeleccionadas = ref<number[]>([]);
const isLoading = ref(true);

onMounted(async () => {
  try {
    await competenciaStore.fetchCompetenciasTecnicasByAlumno(props.alumnoId);
    competencias.value = competenciaStore.competencias;
  } catch (error) {
    console.error("Error al cargar alumnos:", error);
  } finally {
    isLoading.value = false;
  }
});

const volver = () => {
  router.back();
};

async function guardar() {
  let ok = false;

  ok = await competenciaStore.asignarCompetenciasTecnica(
    props.alumnoId,
    competenciasSeleccionadas.value,
  );

  if (ok) {
    setTimeout(() => {
      volver();
    }, 1000);
  }
}
</script>

<template>
  <Toast
    v-if="competenciaStore.message"
    :message="competenciaStore.message"
    :messageType="competenciaStore.messageType"
  />

  <!-- Loading -->
  <div v-if="isLoading" class="text-center py-5">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Cargando...</span>
    </div>
    <p class="mt-3 text-muted">Cargando alumnos asignados...</p>
  </div>
  <!-- Sin competencias -->
  <div
    v-else-if="competencias.length === 0"
    class="alert alert-info d-flex align-items-center"
    role="alert"
  >
    <i class="bi bi-info-circle-fill me-2"></i>
    <div>El ciclo del alumno no tiene competencias asignadas.</div>
  </div>
  <!-- Lista de competencias -->
  <div v-else>
    <ul class="list-group">
      <li
        class="list-group-item my-2"
        v-for="competencia in competencias"
        :key="competencia.id"
      >
        <input
          class="form-check-input me-1"
          type="checkbox"
          :id="`competencia-${competencia.id}`"
          :value="competencia.id"
          v-model="competenciasSeleccionadas"
        />

        <label
          class="form-check-label stretched-link"
          :for="`competencia-${competencia.id}`"
        >
          {{ competencia.descripcion }}
        </label>
      </li>
    </ul>
    <button class="btn btn-primary" @click="guardar">Guardar</button>
  </div>
</template>

<style scoped>
label {
  cursor: pointer;
}
</style>
