<template>
  <div class="login-page">
    <AppHeader title="Sistema Búho de Gestión de Quejas" />
    
    <main>
      <div class="login-container">
        <h2>Bienvenido. Por favor, inicia sesión</h2>

        <form @submit.prevent="iniciarSesion" class="formulario">
          <div class="form-group">
            <label for="nControl">Número de Control:</label>
            <input 
              type="text" 
              id="nControl" 
              v-model="nControl"
              pattern="\d{8}" 
              maxlength="8" 
              required
              title="Debe contener exactamente 8 dígitos numéricos"
            >
          </div>

          <div class="form-group">
            <label for="contra">Contraseña:</label>
            <input 
              type="password" 
              id="contra" 
              v-model="contra"
              maxlength="20" 
              required
              title="Máximo 20 caracteres"
            >
          </div>
          
          <p v-if="mensaje" class="mensaje">{{ mensaje }}</p>
          
          <div class="button-container">
            <button type="submit" class="btn btnIngresar">Ingresar</button>
          </div>
        </form>
        
        <div class="links-container">
          <router-link to="/registro" class="btn-link">¿No tienes cuenta? Regístrate</router-link> | 
          <router-link to="/" class="btn-link">Regresar</router-link>
        </div>
      </div>
    </main>

    <AppFooter />
  </div>
</template>
<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import AppHeader from './AppHeader.vue';
import AppFooter from './AppFooter.vue';

// Datos del formulario
const nControl = ref('');
const contra = ref('');
const mensaje = ref('');

const router = useRouter();

const iniciarSesion = async () => {
  if (!nControl.value.match(/^\d{8}$/)) {
    mensaje.value = '❌ El número de control debe tener 8 dígitos';
    return;
  }

  if (!contra.value) {
    mensaje.value = '❌ La contraseña es obligatoria';
    return;
  }

  const datos = {
    action: 'login',
    nControl: nControl.value,
    contra: contra.value
  };

  try {
    const response = await fetch('/php/login.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(datos)
    });

    const resultado = await response.json();
    mensaje.value = resultado.mensaje || resultado.error;

    if (resultado.success) {
      const ruta = resultado.userType === 'estudiante' 
        ? '/enviar-queja' 
        : '/visualizar-quejas';
      router.push(ruta);
    }
  } catch (error) {
    mensaje.value = '❌ Error de conexión con el servidor';
  }
};
</script>

  <style scoped>
  .login-page {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }
  
  .login-container {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  }
  
  .form-group {
    margin-bottom: 15px;
  }
  
  .form-group label {
    display: block;
    margin-bottom: 5px;
  }
  
  .form-group input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
  }
  
  .error-message {
    color: #e74c3c;
    margin: 10px 0;
    text-align: center;
  }
  
  .button-container {
    margin: 20px 0;
    text-align: center;
  }
  
  .btnIngresar {
    background-color: #3498db;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  .links-container {
    margin-top: 20px;
    text-align: center;
  }
  
  .links-container a {
    color: #3498db;
    text-decoration: none;
    margin: 0 5px;
  }
  
  .links-container a:hover {
    text-decoration: underline;
  }
  </style>