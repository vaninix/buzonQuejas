<script setup>
import { RouterLink, RouterView } from 'vue-router';
</script>

<template>
  <div class="app-container">
    <nav class="navbar">
      <router-link to="/" class="nav-link">Inicio</router-link>
      <router-link to="/registro" class="nav-link">Registro</router-link>
      <router-link to="/login" class="nav-link">Iniciar sesión</router-link>

    </nav>

    <main class="content">
      <router-view></router-view>
    </main>
  </div>
</template>

<style scoped>
/* Estilo general de la app */
.app-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  min-height: 100vh;
  background: #f4f4f9;
  font-family: 'Arial', sans-serif;
}

/* Estilos para la barra de navegación */
.navbar {
  display: flex;
  justify-content: center;
  gap: 20px;
  width: 100%;
  background: #053364;
  padding: 15px 0;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

/* Estilos para los enlaces de navegación */
.nav-link {
  color: white;
  text-decoration: none;
  font-size: 18px;
  font-weight: bold;
  padding: 10px 15px;
  transition: background 0.3s, transform 0.2s;
  border-radius: 8px;
}

.nav-link:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: scale(1.05);
}

/* Contenido principal */
.content {
  width: 90%;
  max-width: 1200px;
  margin-top: 20px;
  padding: 20px;
  background: white;
  border-radius: 10px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}
</style>