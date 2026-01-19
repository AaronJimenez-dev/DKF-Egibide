<script setup></script>

<template>
  <h2>Información</h2>
  <hr />

  <div class="info-links">
    <RouterLink
      to="/admin/ciclos"
      class="info-card"
    >
      <h4>Ciclos</h4>
      <p>Gestión de ciclos formativos</p>
    </RouterLink>

    <RouterLink
      to="/admin/competencias"
      class="info-card"
    >
      <h4>Competencias</h4>
      <p>Gestión de competencias</p>
    </RouterLink>

    <RouterLink
      to="/admin/empresas"
      class="info-card"
    >
      <h4>Empresas</h4>
      <p>Gestión de empresas colaboradoras</p>
    </RouterLink>

    <RouterLink
      to="/admin/alumnos"
      class="info-card"
    >
      <h4>Alumnos</h4>
      <p>Gestión de alumnos</p>
    </RouterLink>
  </div>
</template>


<style scoped>
  .info-links {
    display: grid;
    gap: 1.5rem; /* separación entre cards */
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  }

  /* Card */
  .info-card {
    padding: 1.5rem;
    border: 2px solid #81045f;
    border-radius: 10px;
    text-decoration: none;
    color: inherit;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    background-color: #e3e6ea;
  }

  .info-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
  }
</style>
