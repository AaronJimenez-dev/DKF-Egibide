<script setup>
import Toast from "@/components/Notification/Toast.vue";
import { useResultadosStore } from "@/stores/resultados";
import { ref } from "vue";


const resultadosStore = useResultadosStore();

let descripcionResultado = ref("");
let asignatura_id = ref(0);
const asignaturas = resultadosStore.fetchAsignaturas();
console.log(asignaturas);

function agregarResultado() {

  resultadosStore.createResultado(descripcionResultado.value, asignatura_id.value);
}

</script>

<template>

<h2>Nuevo Resultado de Aprendizaje</h2>
  <hr />
  <Toast
    v-if="resultadosStore.message"
    :message="resultadosStore.message"
    :messageType="resultadosStore.messageType"
  />
  <form @submit.prevent="agregarResultado" class="row-cols-1">
    <div class="mb-3 col-6">
      <label for="descripcion" class="form-label">Descripcion:</label>
      <input
        type="text"
        class="form-control"
        placeholder="Evalúa sistemas informáticos, identificando sus componentes..."
        v-model="descripcionResultado"
        aria-label="descripcion"
        id="descripcion"
        required
      />
    </div>
    <div class="mb-3 col-6">
      <label for="asignatura" class="form-label">Asignatura relacionada:</label>
      <select
        class="form-select"
        v-model="asignatura_id"
        id="asignatura"
        required
      >
        <option :value="0" disabled>-- Selecciona una opción --</option>
        <option
          v-for="asignatura in asignaturas"
          :key="asignatura.id"
          :value="asignatura.id"
        >
          {{ asignatura.nombre_asignatura }}
        </option>
      </select>      
    </div>

    
    

    <button type="submit" class="btn btn-primary col-2">Agregar</button>
  </form>
</template>