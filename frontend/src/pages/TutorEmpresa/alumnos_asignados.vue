<script setup>
import AlumnosAsignados from "@/components/AlumnosAsignados.vue";
import { useAuthStore } from "@/stores/auth";

const authStore = useAuthStore();

const tutorId = authStore.currentUser.id;
</script>

<template>
  <h2>ALUMNOS ASIGNADOS</h2>
  <hr />
  <AlumnosAsignados tipoTutor="empresa" :tutorId="tutorId.toString()" />
</template>

<style scoped></style>
