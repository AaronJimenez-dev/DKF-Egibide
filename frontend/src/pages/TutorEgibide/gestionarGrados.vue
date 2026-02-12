<script setup>
import { useTutorEgibideStore } from '@/stores/tutorEgibide';
import { useAuthStore } from '@/stores/auth';
import Toast from '@/components/Notification/Toast.vue';
import { onMounted, ref } from 'vue';

const storeTutor = useTutorEgibideStore();
const authStore  = useAuthStore();
const baseURL    = import.meta.env.VITE_API_BASE_URL;

// ── Toast ─────────────────────────────────────────────────────────────────────
const message     = ref(null);
const messageType = ref('success');
function setMessage(text, type = 'success', timeout = 4000) {
  message.value     = text;
  messageType.value = type;
  setTimeout(() => { message.value = null; }, timeout);
}

// ── Estado ────────────────────────────────────────────────────────────────────
const gradosDisponibles  = ref([]);
const loadingDisponibles = ref(false);
const loadingAccion      = ref(false);
const gradoADesasignar   = ref(null); // ciclo pendiente de confirmar desasignación

// ── Headers comunes ───────────────────────────────────────────────────────────
function authHeaders() {
  return {
    'Content-Type': 'application/json',
    Authorization: authStore.token ? `Bearer ${authStore.token}` : '',
    Accept: 'application/json',
  };
}

// ── Carga inicial ─────────────────────────────────────────────────────────────
onMounted(async () => {
  await storeTutor.fetchInicioTutor();
  if (storeTutor.inicio?.tutor?.id) {
    await Promise.all([
      storeTutor.fetchAlumnosDeMiCursoSinTutorAsignado(storeTutor.inicio.tutor.id),
      fetchGradosDisponibles(),
    ]);
  }
});

// ── Ciclos que el tutor NO tiene asignados ────────────────────────────────────
// Endpoint: GET /api/tutorEgibide/{tutorId}/ciclos/disponibles
async function fetchGradosDisponibles() {
  const tutorId = storeTutor.inicio?.tutor?.id;
  if (!tutorId) return;

  loadingDisponibles.value = true;
  try {
    const response = await fetch(
      `${baseURL}/api/tutorEgibide/${tutorId}/ciclos/disponibles`,
      { headers: authHeaders() }
    );
    const data = await response.json();
    if (!response.ok) {
      setMessage(data.message || 'Error al cargar ciclos disponibles', 'error');
      return;
    }
    gradosDisponibles.value = data;
  } catch {
    setMessage('Error de conexión al obtener ciclos disponibles', 'error');
  } finally {
    loadingDisponibles.value = false;
  }
}

// ── Asignar ciclo ─────────────────────────────────────────────────────────────
// Endpoint: POST /api/tutorEgibide/{tutorId}/ciclos  { ciclo_id }
async function asignarGrado(ciclo) {
  const tutorId = storeTutor.inicio?.tutor?.id;
  if (!tutorId) return;

  loadingAccion.value = true;
  try {
    const response = await fetch(`${baseURL}/api/tutorEgibide/${tutorId}/ciclos`, {
      method: 'POST',
      headers: authHeaders(),
      body: JSON.stringify({ ciclo_id: ciclo.id }),
    });
    const data = await response.json();
    if (!response.ok) {
      setMessage(data.message || 'Error al asignar ciclo', 'error');
      return;
    }
    setMessage(`"${ciclo.nombre}" asignado correctamente`, 'success');
    await recargarListas();
  } catch {
    setMessage('Error de conexión al asignar ciclo', 'error');
  } finally {
    loadingAccion.value = false;
  }
}

// ── Desasignar ciclo ──────────────────────────────────────────────────────────
// Endpoint: DELETE /api/tutorEgibide/{tutorId}/ciclos/{cicloId}
function pedirConfirmacion(ciclo) {
  gradoADesasignar.value = ciclo;
}

function cancelarDesasignar() {
  gradoADesasignar.value = null;
}

async function confirmarDesasignar() {
  const tutorId = storeTutor.inicio?.tutor?.id;
  const ciclo   = gradoADesasignar.value;
  if (!tutorId || !ciclo) return;

  loadingAccion.value = true;
  try {
    const response = await fetch(
      `${baseURL}/api/tutorEgibide/${tutorId}/ciclos/${ciclo.id}`,
      { method: 'DELETE', headers: authHeaders() }
    );
    const data = await response.json();
    if (!response.ok) {
      setMessage(data.message || 'Error al desasignar ciclo', 'error');
      return;
    }
    setMessage(`"${ciclo.nombre}" desasignado correctamente`, 'success');
    gradoADesasignar.value = null;
    await recargarListas();
  } catch {
    setMessage('Error de conexión al desasignar ciclo', 'error');
  } finally {
    loadingAccion.value = false;
  }
}

// ── Recarga ambas listas tras cualquier cambio ────────────────────────────────
async function recargarListas() {
  const tutorId = storeTutor.inicio?.tutor?.id;
  await Promise.all([
    storeTutor.fetchAlumnosDeMiCursoSinTutorAsignado(tutorId),
    fetchGradosDisponibles(),
  ]);
}
</script>

<template>
  <div class="container my-5">

    <Toast v-if="message" :message="message" :type="messageType" />

    <!-- ── Modal confirmación desasignar ─────────────────────────────────── -->
    <Transition name="fade">
      <div v-if="gradoADesasignar" class="modal-overlay" @click.self="cancelarDesasignar">
        <div class="modal-box">
          <div class="modal-icon">
            <i class="bi bi-exclamation-triangle-fill text-warning fs-1"></i>
          </div>
          <h5 class="modal-title">¿Estás seguro?</h5>
          <p class="modal-text">
            Vas a desasignarte del ciclo
            <strong>{{ gradoADesasignar.nombre }}</strong>.
          </p>
          <div class="modal-actions">
            <button
              class="btn btn-outline-secondary"
              @click="cancelarDesasignar"
              :disabled="loadingAccion"
            >
              No, cancelar
            </button>
            <button
              class="btn btn-danger"
              @click="confirmarDesasignar"
              :disabled="loadingAccion"
            >
              <span v-if="loadingAccion" class="spinner-border spinner-border-sm me-1" role="status"></span>
              Sí, desasignar
            </button>
          </div>
        </div>
      </div>
    </Transition>

    <h1 class="page-title mb-5">Gestión de Ciclos</h1>

    <div class="row g-4">

      <div class="col-12 col-lg-6">
        <div class="section-header mb-3">
          <h2 class="section-title">Mis ciclos</h2>
        </div>

        <!-- Cargando -->
        <div v-if="storeTutor.loading" class="state-box">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2 text-muted small">Cargando...</p>
        </div>

        <!-- Sin ciclos -->
        <div v-else-if="!storeTutor.misCursos?.length" class="state-box">
          <i class="bi bi-journal-x fs-2 text-muted mb-2"></i>
          <p class="text-muted fst-italic small mb-0">No tienes ningún ciclo asignado.</p>
        </div>

        <!-- Lista -->
        <TransitionGroup v-else name="list" tag="div" class="d-flex flex-column gap-2">
          <div
            v-for="ciclo in storeTutor.misCursos"
            :key="ciclo.id"
            class="grado-card grado-card--assigned"
          >
            <div class="grado-info">
              <span class="grado-nombre">{{ ciclo.nombre }}</span>
              <span v-if="ciclo.grupo" class="grado-grupo">{{ ciclo.grupo }}</span>
            </div>
            <button
              class="btn btn-sm btn-outline-danger"
              @click="pedirConfirmacion(ciclo)"
              :disabled="loadingAccion"
              title="Desasignarme de este ciclo"
            >
              <i class="bi bi-x-circle me-1"></i>Desasignar
            </button>
          </div>
        </TransitionGroup>
      </div>

      <div class="col-12 col-lg-6">
        <div class="section-header mb-3">
          <h2 class="section-title">Ciclos sin asignar</h2>
        </div>

        <div v-if="loadingDisponibles" class="state-box">
          <div class="spinner-border text-secondary" role="status"></div>
          <p class="mt-2 text-muted small">Cargando...</p>
        </div>

        <div v-else-if="!gradosDisponibles.length" class="state-box">
          <i class="bi bi-check-circle fs-2 text-success mb-2"></i>
          <p class="text-muted fst-italic small mb-0">No hay más ciclos disponibles.</p>
        </div>

        <TransitionGroup v-else name="list" tag="div" class="d-flex flex-column gap-2">
          <div
            v-for="ciclo in gradosDisponibles"
            :key="ciclo.id"
            class="grado-card grado-card--available"
          >
            <div class="grado-info">
              <span class="grado-nombre">{{ ciclo.nombre }}</span>
              <span v-if="ciclo.grupo" class="grado-grupo">{{ ciclo.grupo }}</span>
            </div>
            <button
              class="btn btn-sm btn-outline-success"
              @click="asignarGrado(ciclo)"
              :disabled="loadingAccion"
              title="Asignarme a este ciclo"
            >
              <i class="bi bi-plus-circle me-1"></i>Asignar
            </button>
          </div>
        </TransitionGroup>
      </div>

    </div>
  </div>
</template>