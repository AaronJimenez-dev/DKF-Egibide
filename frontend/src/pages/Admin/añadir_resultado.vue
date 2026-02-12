<script setup>
import Toast from "@/components/Notification/Toast.vue";
import { useResultadosStore } from "@/stores/resultados";
import { ref, computed, onMounted, watch } from "vue";

const resultadosStore = useResultadosStore();

let descripcionResultado = ref("");
let asignatura_id = ref(null);        // Guardará el ID de la asignatura
let filtroAsignatura = ref("");       // Input para filtrar nombres
const competencias = ref([]);         // Todas las competencias de todos los ciclos
const competenciasSeleccionadas = ref([]);
const mostrarLista = ref(false);
const asignaturas = ref([]);          // Todas las asignaturas

// Cargar datos
onMounted(async () => {
  await resultadosStore.fetchAsignaturas();
  asignaturas.value = resultadosStore.resultados || []; // Guardamos todas las asignaturas

  await resultadosStore.fetchCompetencias();
  competencias.value = resultadosStore.competencias || []; // Guardamos todas las competencias
});

// Computed: filtrar competencias según la asignatura seleccionada
const competenciasFiltradas = computed(() => {
  if (!asignatura_id.value) return [];
  
  const asignatura = asignaturas.value.find(a => a.id === asignatura_id.value);
  console.log("Asignatura seleccionada:", asignatura);
  console.log("ciclo_id de la asignatura:", asignatura?.ciclo_id);
  console.log("Todas las competencias:", competencias.value);
  
  if (!asignatura) return [];
  
  const filtradas = competencias.value.filter(c => c.ciclo_id === asignatura.ciclo_id);
  console.log("Competencias filtradas:", filtradas);
  
  return filtradas;
});

// Computed: filtrar asignaturas según el input
const asignaturasFiltradas = computed(() => {
  if (!filtroAsignatura.value) return asignaturas.value;
  return asignaturas.value.filter(a =>
    a.nombre_asignatura.toLowerCase().includes(filtroAsignatura.value.toLowerCase())
  );
});

// Limpiar competencias al cambiar la asignatura
watch(asignatura_id, () => {
  competenciasSeleccionadas.value = [];
});

// Función al seleccionar asignatura del buscador
function seleccionarAsignatura(asig) {
  asignatura_id.value = asig.id;                    // Guardar ID
  filtroAsignatura.value = asig.nombre_asignatura; // Mostrar nombre
  competenciasSeleccionadas.value = [];            // Limpiar selección anterior
  mostrarLista.value = false;
}

// Guardar resultado
function agregarResultado() {
  if (!asignatura_id.value) {
    alert("Selecciona una asignatura válida");
    return;
  }

  resultadosStore.createResultado(
    descripcionResultado.value,
    asignatura_id.value,
    competenciasSeleccionadas.value
  );

  // Limpiar campos después de guardar
  descripcionResultado.value = "";
  filtroAsignatura.value = "";
  asignatura_id.value = null;
  competenciasSeleccionadas.value = [];
}
function ocultarLista() {
  setTimeout(() => mostrarLista.value = false, 150);
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

  <!-- Descripción -->
  <div class="mb-3 col-6">
    <label for="descripcion" class="form-label">Descripción:</label>
    <input
      type="text"
      class="form-control"
      placeholder="Evalúa sistemas informáticos, identificando sus componentes..."
      v-model="descripcionResultado"
      id="descripcion"
      required
    />
  </div>

  <!-- Buscador de asignatura -->
  <div class="mb-3 col-6 position-relative">
    <label for="asignatura" class="form-label">Asignatura relacionada:</label>
    <input
      type="text"
      class="form-control"
      v-model="filtroAsignatura"
      placeholder="Escribe para filtrar..."
      autocomplete="off"
      @input="mostrarLista = true"
      @blur="ocultarLista"
    />
    <ul v-if="asignaturasFiltradas.length && filtroAsignatura && mostrarLista" class="list-group position-absolute z-10 w-100">
      <li
        v-for="asig in asignaturasFiltradas"
        :key="asig.id"
        class="list-group-item list-group-item-action"
        @click="seleccionarAsignatura(asig)"
        style="cursor:pointer;"
      >
        {{ asig.nombre_asignatura }}
      </li>
    </ul>
  </div>

  <!-- Competencias técnicas -->
  <div class="mb-3 col-6">
    <label class="form-label">Competencias técnicas relacionadas:</label>

    <div v-if="competenciasFiltradas.length">
      <div
        v-for="comp in competenciasFiltradas"
        :key="comp.id"
        class="form-check"
      >
        <input
          class="form-check-input"
          type="checkbox"
          :id="'comp-' + comp.id"
          :value="comp.id"
          v-model="competenciasSeleccionadas"
        />
        <label class="form-check-label" :for="'comp-' + comp.id">
          {{ comp.descripcion }}
        </label>
      </div>
    </div>

    <div v-else>
      <small>No hay competencias para esta asignatura.</small>
    </div>
  </div>

  <button type="submit" class="btn btn-primary col-2 mt-3">Agregar</button>
</form>
</template>
