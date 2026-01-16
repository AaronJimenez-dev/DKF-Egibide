<script setup lang="ts">
import { onMounted } from 'vue'
import { useAlumnosStore } from '@/stores/alumnos'

const alumnosStore = useAlumnosStore()

onMounted(() => {
  alumnosStore.fetchAlumno()
})
</script>

<template>
  <div class="container mt-4">
    <h2 class="mb-4">Datos del Alumno</h2>

    <div v-if="alumnosStore.loading" class="text-muted">Cargando...</div>

    <div v-else-if="alumnosStore.error" class="alert alert-danger">
      {{ alumnosStore.error }}
    </div>

    <div v-else-if="alumnosStore.Alumno" class="card">
      <div class="card-body">
        <p><strong>Nombre:</strong> {{ alumnosStore.Alumno.nombre }}</p>
        <p><strong>Apellidos:</strong> {{ alumnosStore.Alumno.apellidos }}</p>
        <p><strong>Correo electrónico:</strong> {{ alumnosStore.Alumno.email }}</p>
        <p><strong>Teléfono:</strong> {{ alumnosStore.Alumno.telefono}}</p>
        <p><strong>Ciudad:</strong> {{ alumnosStore.Alumno.ciudad }}</p>
      </div>
    </div>

    <div v-else class="text-muted">Sin datos</div>
  </div>
</template>