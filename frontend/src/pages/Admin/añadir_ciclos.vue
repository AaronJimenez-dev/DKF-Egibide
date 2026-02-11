<script setup lang="ts">
import Toast from "@/components/Notification/Toast.vue";
import { useCiclosStore } from "@/stores/ciclos";
import { useFamiliaProfesionalesStore } from "@/stores/familiasProfesionales";
import { onMounted, ref } from "vue";

const familiaProfesionalStore = useFamiliaProfesionalesStore();
const ciclosStore = useCiclosStore();

const nombreCiclo = ref<string>("");
const familiaProfesional = ref<number>(0);
const codigoCiclo = ref<string>("");
  
onMounted(async () => {
  await familiaProfesionalStore.fetchFamiliasProfesionales();
});

function agregarCiclo() {
  if(!/^[0-9]{3}[A-Z]{2}$/.test(codigoCiclo.value)){
    alert("El codigo consta de 3 numeros y 2 letras en Mayúsculas")
  }
  ciclosStore.createCiclo(nombreCiclo.value, familiaProfesional.value, codigoCiclo.value);
}
</script>

<template>
  <h2>NUEVO CICLO</h2>
  <hr />
  <Toast
    v-if="ciclosStore.message"
    :message="ciclosStore.message"
    :messageType="ciclosStore.messageType"
  />
  <form @submit.prevent="agregarCiclo" class="row-cols-1">
    <div class="mb-3 col-6">
      <label for="nombre" class="form-label">Nombre:</label>
      <input
        type="text"
        class="form-control"
        placeholder="Desarrollo de aplicaciones web, Soldadura..."
        v-model="nombreCiclo"
        aria-label="Nombre"
        id="nombre"
        required
      />
    </div>
    <div class="mb-3 col-6">
      <label for="codigo" class="form-label">Código:</label>
      <input
        type="text"
        class="form-control"
        placeholder="142GA"
        v-model="codigoCiclo"
        aria-label="codigo"
        id="codigo"
        required
      />
    </div>

    <div class="mb-3 col-5">
      <label for="familia" class="form-label">Familia profesional:</label>
      <select
        class="form-select"
        v-model.number="familiaProfesional"
        id="familia"
        required
      >
        <option :value="0" disabled>-- Selecciona una opción --</option>
        <option
          v-for="familia in familiaProfesionalStore.familiasProfesionales"
          :key="familia.id"
          :value="familia.id"
        >
          {{ familia.nombre }}
        </option>
      </select>
    </div>

    <button type="submit" class="btn btn-primary col-2">Agregar</button>
  </form>
</template>

<style scoped></style>
