<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import AppHeader from './AppHeader.vue';
import AppFooter from './AppFooter.vue';

const router = useRouter();
const queja = ref('');
const mensaje = ref('');
const cargando = ref(false);

const enviarQueja = async () => {
  if (!queja.value.trim()) {
    mensaje.value = '❌ El texto de la queja es requerido';
    return;
  }

  cargando.value = true;
  mensaje.value = '';

  try {
    const response = await fetch('../../php/quejas.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        action: 'enviar_queja',
        queja: queja.value
      }),
      credentials: 'include'
    });

    const resultado = await response.json();
    
    if (resultado.error) {
      mensaje.value = `❌ ${resultado.error}`;
    } else {
      mensaje.value = '✅ Queja enviada correctamente';
      queja.value = '';
      setTimeout(() => mensaje.value = '', 3000);
    }
  } catch (error) {
    mensaje.value = '❌ Error al conectar con el servidor';
    console.error('Error:', error);
  } finally {
    cargando.value = false;
  }
};

const cerrarSesion = async () => {
  try {
    await fetch('../../php/logout.php', {
      method: 'POST',
      credentials: 'include'
    });
    router.push('/login');
  } catch (error) {
    console.error('Error al cerrar sesión:', error);
  }
};
</script>

<template>
  <div class="enviar-queja">
    <AppHeader title="Sistema Búho de Gestión de Quejas" />
    
    <div class="container">
      <h2>Enviar una Queja</h2>
      
      <form @submit.prevent="enviarQueja">
        <div class="form-group">
          <textarea
            v-model="queja"
            placeholder="Describe tu queja aquí..."
            required
            rows="5"
          ></textarea>
        </div>
        
        <button type="submit" :disabled="cargando">
          {{ cargando ? 'Enviando...' : 'Enviar Queja' }}
        </button>
      </form>
      
      <p v-if="mensaje" class="mensaje">{{ mensaje }}</p>
      
      <div class="action-buttons">
        <router-link to="/visualizar-quejas" class="btn">Ver mis quejas</router-link>
        <button @click="cerrarSesion" class="btn btn-logout">Cerrar Sesión</button>
      </div>
    </div>
    
    <AppFooter />
  </div>
</template>

<style scoped>

.container {
  max-width: 800px;
  margin: 2rem auto;
  padding: 2rem;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  flex-grow: 1;
  
}

h2 {
  color: #2c3e50;
  text-align: center;
  margin-bottom: 2rem;
  font-size: 1.8rem;
  border-bottom: 2px solid #3498db;
  padding-bottom: 0.5rem;
}

form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}


.form-group textarea{
display: flex;
  width: 100%;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-family: inherit;
  font-size: 1rem;
  line-height: 1.5;
  resize: vertical;
}

textarea {
  width: 100%;
  padding: 1rem;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-family: inherit;
  font-size: 1rem;
  line-height: 1.5;
  resize: vertical;
  min-height: 150px;
}

textarea:focus {
  outline: none;
  border-color: #3498db;
  box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

button[type="submit"] {
  background-color: #2ecc71;
  color: white;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 6px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  align-self: center;
  min-width: 200px;
}

button[type="submit"]:disabled {
  background-color: #95a5a6;
  cursor: not-allowed;
}

.mensaje {
  margin-top: 1.5rem;
  padding: 0.75rem;
  border-radius: 4px;
  text-align: center;
  font-weight: 500;
}

.action-buttons {
  display: flex;
  justify-content: center;
  margin-top: 2rem;
  gap: 1rem;
}

.btn {
  padding: 0.75rem 1.5rem;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 500;
  text-align: center;
  flex: 1;
  max-width: 200px;
}

.btn {
  background-color: #3498db;
  color: white;
  border: none;
  width: fit-content;
}
.btn-logout {
  background-color: #7f8c8d; /* Color gris como en VisualizarQuejas */
  color: white;
  border-color: #707b7c; /* Borde gris más oscuro */
}

.btn-logout:hover {
  background-color: #707b7c;
}

/* Diseños responsivos */
@media (max-width: 768px) {
  .container {
    margin: 1rem;
    padding: 1.5rem;
  }
  
  .action-buttons {
    flex-direction: column;
    align-items: center;
  }
  
  .btn {
    width: 100%;
    max-width: none;
  }
  
  h2 {
    font-size: 1.5rem;
  }
}
</style>