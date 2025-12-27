<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import AppHeader from './AppHeader.vue';
import AppFooter from './AppFooter.vue';


const router = useRouter();
const quejas = ref([]);
const cargando = ref(true);
const mensaje = ref('');
const usuario = ref({ id: null, tipo: '', autenticado: false });
const filtroCarrera = ref('');
const filtroEstado = ref('');
const mostrarModal = ref(false);
const quejaEdit = ref({ id: null, mensaje: '' });

// Cargar quejas al iniciar
onMounted(async () =>{
  
  try {
    // Verificar sesión primero
    const resSesion = await fetch('/php/verificaSesion.php', {
      credentials: 'include'
    });
    const dataSesion = await resSesion.json();
    
    if (!dataSesion.autenticado) {
      router.push('/login');
      return;
    }
    
    // Asignar datos de usuario
    usuario.value = {
      id: dataSesion.usuario,
      tipo: dataSesion.tipo,
      autenticado: true
    };
    
    // Cargar quejas
    await cargarQuejas();
    
  } catch (error) {
    mensaje.value = '❌ Error de conexión';
    console.error(error);
  }
});

const cargarQuejas = async () => {
  cargando.value = true;
  try {
    const params = new URLSearchParams();
    if (usuario.value.tipo === 'admin') {
      if (filtroCarrera.value) params.append('carrera', filtroCarrera.value);
      if (filtroEstado.value) params.append('estado', filtroEstado.value);
    }
    
    const res = await fetch(`/php/verQuejas.php?${params}`, { credentials: 'include' });
    quejas.value = await res.json();
    
    if (usuario.value.tipo === 'admin') {
      quejas.value.forEach(q => q.nuevoEstado = q.estado);
    }
    
  } catch (error) {
    mensaje.value = 'Error al cargar quejas';
  } finally {
    cargando.value = false;
  }
};


const cambiarEstado = async (queja) => {
    try {
        const response = await fetch('/php/verQuejas.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: 'cambiar_estado',
                id: queja.id,
                estado: queja.nuevoEstado
            }),
            credentials: 'include'
        });

        const data = await response.json();

        if (data.success) {
            mensaje.value = '✅ Estado actualizado';
            setTimeout(() => mensaje.value = '', 2000);
            cargarQuejas(); // Recargar las quejas con el estado actualizado
        } else {
            mensaje.value = data.error || '❌ Error al actualizar';
        }

    } catch (error) {
        mensaje.value = '❌ Error al actualizar';
        console.error(error);
    }
};

const editarQueja = (queja) => {
  quejaEdit.value = { id: queja.id, mensaje: queja.mensaje };
  mostrarModal.value = true;
};

const guardarEdicion = async () => {
  try {
    const response = await fetch('/php/verQuejas.php', {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json'  // Se cambia el tipo de contenido a JSON
      },
      body: JSON.stringify({
        action: 'editar',
        id: quejaEdit.value.id,
        mensaje: quejaEdit.value.mensaje
      }), // Se envían los datos en formato JSON
      credentials: 'include'
    });

    const data = await response.json();  // Recibimos la respuesta en formato JSON
    
    if (data.success) {
      mensaje.value = '✅ Queja actualizada';
      mostrarModal.value = false;
      cargarQuejas();  // Función para cargar las quejas
      setTimeout(() => mensaje.value = '', 2000);
    } else {
      mensaje.value = `❌ ${data.error}`;
    }
  } catch (error) {
    mensaje.value = '❌ Error al editar';
    console.error(error);
  }
};

const eliminarQueja = async (id) => {
  if (!confirm('¿Seguro que quieres eliminar esta queja?')) return;
  
  try {
    const response = await fetch('/php/verQuejas.php', {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json'  // Cambiar tipo de contenido a JSON
      },
      body: JSON.stringify({
        action: 'eliminar',
        id: id
      }),  // Enviar los datos como JSON
      credentials: 'include'
    });

    const data = await response.json();  // Recibir la respuesta como JSON
    
    if (data.success) {
      mensaje.value = '✅ Queja eliminada';
      cargarQuejas();  // Función para cargar las quejas
      setTimeout(() => mensaje.value = '', 2000);
    } else {
      mensaje.value = `❌ ${data.error}`;
    }
  } catch (error) {
    mensaje.value = '❌ Error al eliminar';
    console.error(error);
  }
};


const cerrarSesion = async () => {
  try {
    await fetch('/php/logout.php', {
      method: 'POST',
      credentials: 'include'
    });
    router.push('/login');
  } catch (error) {
    console.error(error);
  }
};
</script>

<template>
  <div class="visualizar-quejas">
    <AppHeader title="Sistema Búho de Gestión de Quejas" />

    <h2>Lista de Quejas</h2>
    
    <!-- Solo mostrar contenido si está autenticado -->
    <template v-if="usuario.autenticado">
      <!-- Filtros para admin -->
      <div v-if="usuario.tipo === 'admin'" class="filtros">
        <select v-model="filtroCarrera" @change="cargarQuejas">
          <option value="">Todas las carreras</option>
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
        
        <select v-model="filtroEstado" @change="cargarQuejas">
          <option value="">Todos los estados</option>
          <option value="pendiente">Pendiente</option>
          <option value="en_proceso">En proceso</option>
          <option value="resuelta">Resuelta</option>
          <option value="rechazada">Rechazada</option>
        </select>
      </div>

      <!-- Mensajes y estado -->
      <p v-if="mensaje" class="mensaje">{{ mensaje }}</p>
      <p v-if="cargando">Cargando quejas...</p>

      <!-- Lista de quejas -->
      <div v-else class="quejas-list">
        <div v-for="queja in quejas" :key="queja.id" class="queja-item">
          <div class="queja-content">
            <p><strong>Carrera:</strong> {{ queja.carrera }}</p>
            <p><strong>Queja:</strong> {{ queja.mensaje }}</p>
            <p><strong>Estado:</strong> 
              <span :class="'estado-' + queja.estado">{{ queja.estado.replace('_', ' ').toUpperCase() }}</span>
            </p>
          </div>

          <!-- Acciones según tipo de usuario -->
          <div v-if="usuario.tipo === 'admin'" class="acciones-admin">
            <select v-model="queja.nuevoEstado">
              <option value="pendiente">Pendiente</option>
              <option value="en_proceso">En proceso</option>
              <option value="resuelta">Resuelta</option>
              <option value="rechazada">Rechazada</option>
            </select>
            <button @click="cambiarEstado(queja)">Actualizar</button>
          </div>

          <div v-else class="acciones-estudiante">
            <button @click="editarQueja(queja)">Editar</button>
            <button @click="eliminarQueja(queja.id)">Eliminar</button>
          </div>
        </div>
      </div>

      <!-- Botón para nueva queja -->
      <div class="nueva-queja-container">
  <button 
    v-if="usuario.tipo === 'estudiante'" 
    @click="router.push('/enviar-queja')"
    class="btn-nueva-queja"
  >
    Nueva Queja
  </button>
</div>
    </template>

    <div class="action-buttons">
        <button @click="cerrarSesion" class="btn btn-logout">Cerrar Sesión</button>
      </div>

      
    <AppFooter />
  </div>
</template>

<style scoped>
.visualizar-quejas {
  max-width: 900px;
  margin: 0 auto;
  padding: 2rem;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

h2 {
  color: #2c3e50;
  text-align: center;
  margin-bottom: 1.5rem;
  font-size: 1.8rem;
  border-bottom: 2px solid #3498db;
  padding-bottom: 0.5rem;
}

.filtros {
  display: flex;
  gap: 1rem;
  margin-bottom: 1.5rem;
  flex-wrap: wrap;
}

.filtros select {
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  background-color: white;
  min-width: 250px;
  font-size: 1rem;
}

.mensaje {
  padding: 0.75rem;
  border-radius: 4px;
  margin-bottom: 1rem;
  text-align: center;
  font-weight: bold;
}

.quejas-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.queja-item {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  padding: 1.5rem;
  /* Se eliminó la transición hover */
}

.queja-content {
  margin-bottom: 1rem;
}

.queja-content p {
  margin: 0.5rem 0;
  line-height: 1.6;
}

.queja-content strong {
  color: #2c3e50;
}

.acciones-admin, .acciones-estudiante {
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
  align-items: center;
}

.acciones-admin select {
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}

button {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
}

.acciones-admin button {
  background-color: #3498db;
  color: white;
}

.acciones-estudiante button:first-child {
  background-color: #3498db;
  color: white;
}

.acciones-estudiante button:last-child {
  background-color: #3498db;
  color: white;
}
/* Contenedor del botón nueva queja */
.nueva-queja-container {
  display: flex;
  justify-content: center;
  margin: 2rem 0;
}

/* Estilo específico para el botón */
.btn-nueva-queja {
  background-color: #2ecc71;
  color: white;
  padding: 0.75rem 0.8rem;
  border: none;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  min-width: 200px;
  text-align: center;
  transition: background-color 0.2s;
}

.btn-nueva-queja:hover {
  background-color: #27ae60;
}

/* Estilos para estados */
[class^="estado-"] {
  padding: 0.25rem 0.5rem;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 500;
  text-transform: uppercase;
}

.estado-pendiente {
  background-color: #fff3cd;
  color: #856404;
}

.estado-en_proceso {
  background-color: #cce5ff;
  color: #004085;
}

.estado-resuelta {
  background-color: #d4edda;
  color: #155724;
}

.estado-rechazada {
  background-color: #f8d7da;
  color: #721c24;
}

/* botones de acción */
.action-buttons {
  margin-top: 2rem;
  display: flex;
  justify-content: center;
}

.btn-logout {
  background-color: #7f8c8d;
  color: white;
  padding: 0.5rem 1.5rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
}

/* Diseños responsivos */
@media (max-width: 768px) {
  .visualizar-quejas {
    padding: 1rem;
  }
  
  .filtros {
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .filtros select {
    width: 100%;
  }
  
  .acciones-admin, .acciones-estudiante {
    flex-direction: column;
    align-items: flex-start;
  }
  
  .acciones-admin select {
    width: 100%;
  }
}
</style>
