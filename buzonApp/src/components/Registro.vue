<template>
  <div class="register-page">
    <AppHeader title="Sistema Búho de Gestión de Quejas" />
    
    <div class="container">
      <h2>Registro de Usuario</h2>

      <form @submit.prevent="registrarUsuario">
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
          <label for="nombre">Nombre:</label>
          <input 
            type="text" 
            id="nombre" 
            v-model="nombre"
            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" 
            required
            title="Solo se permiten letras"
          >
        </div>

        <div class="form-group">
          <label for="primApe">Primer Apellido:</label>
          <input 
            type="text" 
            id="primApe" 
            v-model="primApe"
            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" 
            required
            title="Solo se permiten letras y espacios"
          >
        </div>

        <div class="form-group">
          <label for="segApe">Segundo Apellido:</label>
          <input 
            type="text" 
            id="segApe" 
            v-model="segApe"
            pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" 
            required
            title="Solo se permiten letras y espacios"
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

        <div class="form-group">
          <label for="carrera">Carrera:</label>
          <select 
            id="carrera" 
            v-model="carrera"
            required
          >
            <option value="" disabled selected>Seleccione una carrera</option>
            <option value="Ingeniería en Sistemas Computacionales">Ingeniería en Sistemas Computacionales</option>
            <option value="Ingeniería Mecánica">Ingeniería Mecánica</option>
            <option value="Ingeniería Informática">Ingeniería Informática</option>
            <option value="Ingeniería Química">Ingeniería Química</option>
            <option value="Ingeniería Semiconductores">Ingeniería Semiconductores</option>
            <option value="Ingeniería Eléctrica">Ingeniería Eléctrica</option>
            <option value="Ingeniería Electrónica">Ingeniería Electrónica</option>
            <option value="Ingeniería Industrial">Ingeniería Industrial</option>
            <option value="Ingeniería Industrial en Línea">Ingeniería Industrial en Línea</option>
            <option value="Ingeniería Gestión Empresarial">Ingeniería Gestión Empresarial</option>

          </select>
        </div>

        <div class="button-container">
          <button type="submit" class="btn">Registrar</button>
          <button type="button" @click="resetForm" class="btn btn-cancel">Cancelar</button>
        </div>
      </form>

      <p v-if="mensaje" class="mensaje">{{ mensaje }}</p>

      <div class="links-container">
        <router-link to="/login">¿Ya tienes una cuenta? Inicia sesión</router-link> | 
        <router-link to="/">Regresar</router-link>
      </div>
    </div>

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
const nombre = ref('');
const primApe = ref('');
const segApe = ref('');
const contra = ref('');
const carrera = ref('');
const mensaje = ref('');

const router = useRouter();

const resetForm = () => {
  nControl.value = '';
  nombre.value = '';
  primApe.value = '';
  segApe.value = '';
  contra.value = '';
  carrera.value = '';
};

const registrarUsuario = async () => {
  // Validación básica
  if (!nControl.value.match(/^\d{8}$/)) {
    mensaje.value = '❌ El número de control debe tener 8 dígitos';
    return;
  }

  if (!nControl.value || !nombre.value || !primApe.value || !contra.value || !carrera.value) {
    mensaje.value = '❌ Todos los campos son obligatorios';
    return;
  }

  const datos = {
    action: 'registro',
    nControl: nControl.value,
    nombre: nombre.value,
    primApe: primApe.value,
    segApe: segApe.value,
    contra: contra.value,
    carrera: carrera.value
  };

  try {
    const response = await fetch('/php/register.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(datos)
    });

    const resultado = await response.json();
    mensaje.value = resultado.mensaje || resultado.error;

    // Si el registro fue exitoso, redirigir a login
    if (resultado.success) {
      setTimeout(() => {
        router.push('/login');
      }, 2000);
    }
  } catch (error) {
    mensaje.value = '❌ Error de conexión con el servidor';
    console.error('Error:', error);
  }
};
</script>

<style scoped>
.register-page {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.container {
  max-width: 600px;
  margin: 20px auto;
  padding: 20px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h2 {
  text-align: center;
  color: #2c3e50;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
  color: #34495e;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 16px;
}

.form-group input:focus,
.form-group select:focus {
  border-color: #3498db;
  outline: none;
}

.button-container {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s;
}

.btn:hover {
  opacity: 0.9;
}

.btn[type="submit"] {
  background-color: #2ecc71;
  color: white;
}

.btn-cancel {
  background-color: #e74c3c;
  color: white;
}

.error-message {
  color: #e74c3c;
  margin: 15px 0;
  text-align: center;
  font-weight: bold;
}

.success-message {
  color: #2ecc71;
  margin: 15px 0;
  text-align: center;
  font-weight: bold;
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

