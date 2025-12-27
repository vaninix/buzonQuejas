<script setup>
import { ref } from 'vue'
import AppFooter from './AppFooter.vue';

defineProps({
  msg: String,
})

const count = ref(0)
</script>

<template>
  <div class="welcome-container">
    
    <header class="header">
      <img src="../assets/logo-tec-orizaba.png" alt="Logo IT Orizaba" class="logo">
      <h1 class="title">Sistema de Gestión de Quejas</h1>
      <p class="subtitle">Instituto Tecnológico de Orizaba</p>
    </header>

    <div class="main-content">
      <div class="welcome-card">
        <h2>¡Bienvenido al Sistema Búho!</h2>
        <p class="welcome-text">
          Plataforma oficial para la gestión de quejas y sugerencias de la comunidad estudiantil del Instituto Tecnológico de Orizaba.
        </p>
        
        <div class="features">
          <div class="feature-item">
            <i class="feature-icon">📝</i>
            <h3>Envía quejas</h3>
            <p>Reporta problemas académicos o de infraestructura</p>
          </div>
          
          <div class="feature-item">
            <i class="feature-icon">👁️</i>
            <h3>Consulta el estatus</h3>
            <p>Sigue el progreso de tus reportes</p>
          </div>
          
          <div class="feature-item">
            <i class="feature-icon">🔒</i>
            <h3>Sistema seguro</h3>
            <p>Tus reportes son confidenciales</p>
          </div>
        </div>
      </div>

      <div class="action-buttons">
        <router-link to="/login" class="btn btn-primary">Iniciar Sesión</router-link>
        <router-link to="/registro" class="btn btn-secondary">Registrarse</router-link>
      </div>
    </div>


  </div>
   <AppFooter />
</template>

<style scoped>
.welcome-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
  background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.header {
  text-align: center;
  padding: 2rem 0;
  width: 100%;
  background: linear-gradient(135deg, #053364 0%, #021d3a 100%);  color: white;

}

.logo {
  height: 80px;
  margin-bottom: 1rem;
  
}

.title {
  font-size: 2.2rem;
  margin-bottom: 0.5rem;
  color: #fff;
}

.subtitle {
  font-size: 1.2rem;
  color: #bdc3c7;
}

.main-content {
  flex: 1;
  width: 100%;
  max-width: 1200px;
  padding: 2rem;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.welcome-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  padding: 2.5rem;
  width: 100%;
  max-width: 900px;
  margin: 2rem 0;
  text-align: center;
}

.welcome-card h2 {
  color: #2c3e50;
  font-size: 1.8rem;
  margin-bottom: 1.5rem;
}

.welcome-text {
  font-size: 1.1rem;
  line-height: 1.6;
  color: #34495e;
  margin-bottom: 2rem;
}

.features {
  display: flex;
  justify-content: space-around;
  flex-wrap: wrap;
  gap: 2rem;
  margin: 3rem 0;
}

.feature-item {
  flex: 1;
  min-width: 250px;
  padding: 1.5rem;
  border-radius: 8px;
  background: #f8f9fa;
  transition: transform 0.3s ease;
}

.feature-item:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.feature-icon {
  font-size: 2.5rem;
  margin-bottom: 1rem;
  display: block;
}

.feature-item h3 {
  color: #3498db;
  margin-bottom: 0.8rem;
}

.feature-item p {
  color: #7f8c8d;
  font-size: 0.95rem;
}

.action-buttons {
  display: flex;
  gap: 1.5rem;
  margin: 2rem 0;
}

.btn {
  padding: 0.8rem 2rem;
  border-radius: 6px;
  font-size: 1rem;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
  border: none;
  cursor: pointer;
}

.btn-primary {
  background-color: #3498db;
  color: white;
}

.btn-primary:hover {
  background-color: #2980b9;
}

.btn-secondary {
  background-color: #2ecc71;
  color: white;
}

.btn-secondary:hover {
  background-color: #27ae60;
}

.footer {
  width: 100%;
  text-align: center;
  padding: 1.5rem;
  background-color: #2c3e50;
  color: #ecf0f1;
  font-size: 0.9rem;
}

.contact {
  margin-top: 0.5rem;
  color: #bdc3c7;
}

/* Diseños responsivos */
@media (max-width: 768px) {
  .features {
    flex-direction: column;
    align-items: center;
  }
  
  .action-buttons {
    flex-direction: column;
    width: 100%;
  }
  
  .btn {
    width: 100%;
    text-align: center;
  }
  
  .welcome-card {
    padding: 1.5rem;
  }
  
  .title {
    font-size: 1.8rem;
  }
}
</style>