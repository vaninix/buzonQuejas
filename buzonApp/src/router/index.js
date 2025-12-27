import { createRouter, createWebHistory } from 'vue-router';

const routes = [
  { path: '/', component: () => import('../components/HelloWorld.vue') },
  {
    path: '/registro',
    component: () => import('../components/Registro.vue')
  },
  {
    path: '/login',
    component: () => import('../components/Login.vue')
  },
  {
    path: '/enviar-queja',
    component: () => import('../components/EnviarQueja.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/visualizar-quejas',
    component: () => import('../components/VisualizarQuejas.vue'),
    meta: { requiresAuth: true, requiresAdmin: true }
  }



];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;