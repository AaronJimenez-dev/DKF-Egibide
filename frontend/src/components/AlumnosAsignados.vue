<script setup lang="ts">
import type { Alumno } from "@/interfaces/Alumno";
import { useTutorEgibideStore } from "@/stores/tutorEgibide";
import { useTutorEmpresaStore } from "@/stores/tutorEmpresa";
import { ref, onMounted } from "vue";

const props = defineProps<{
  tipoTutor: "egibide" | "empresa";
  tutorId: string;
}>();

const tutorEgibideStore = useTutorEgibideStore();
const tutorEmpresaStore = useTutorEmpresaStore();

const alumnosAsignados = ref<Alumno[]>([]);

onMounted(async () => {
  if (props.tipoTutor === "egibide") {
    await tutorEgibideStore.fetchAlumnosAsignados(props.tutorId);
    alumnosAsignados.value = tutorEgibideStore.alumnosAsignados;
  } else {
    await tutorEmpresaStore.fetchAlumnosAsignados(props.tutorId);
    alumnosAsignados.value = tutorEmpresaStore.alumnosAsignados;
  }
});
</script>

<template>
  <ul class="list-group list-group-flush">
    <li
      v-for="alumno in alumnosAsignados"
      :key="alumno.id"
      class="list-group-item"
    >
      {{ alumno.nombre }}
    </li>
  </ul>
</template>
