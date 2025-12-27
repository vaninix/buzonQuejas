<template>
  <footer class="app-footer">
    <div class="footer-content">
      <div class="footer-section">
        <h3 class="footer-title">Instituto Tecnológico de Orizaba</h3>
        <p class="footer-text">Oriente 9, Emiliano Zapata Sur</p>
        <p class="footer-text">C.P. 94320 Orizaba, Ver.</p>
      </div>
      
      <div class="footer-section">
        <p class="footer-text">Teléfono: +52 (272) 110 5360</p>
        <p class="footer-text">Email: contacto@itorizaba.edu.mx</p>
      </div>
      
      <div class="footer-divider"></div>
      
      <div class="footer-bottom">
        <p class="copyright">Tecnológico Nacional de México - Derechos reservados © 2023</p>
      </div>
    </div>
  </footer>
</template>

<script>
export default {}
</script>

<style scoped>
.app-footer {
  background: linear-gradient(135deg, #053364 0%, #021d3a 100%);  color: #ecf0f1;
  padding: 2rem 1rem;
  margin-top: 3rem;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.footer-content {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.footer-section {
  text-align: center;
  margin-bottom: 1.5rem;
}

.footer-title {
  font-size: 1.3rem;
  font-weight: 600;
  color: white;
  margin-bottom: 0.8rem;
  letter-spacing: 0.5px;
}

.footer-text {
  font-size: 0.9rem;
  line-height: 1.6;
  margin: 0.3rem 0;
  color: #bdc3c7;
}

.footer-divider {
  width: 80%;
  max-width: 400px;
  height: 1px;
  background-color: rgba(255, 255, 255, 0.1);
  margin: 1rem auto;
}

.footer-bottom {
  width: 100%;
  text-align: center;
}

.copyright {
  font-size: 0.8rem;
  color: #95a5a6;
  margin-top: 1rem;
}

/* Efectos hover para interactividad */
.footer-text:hover {
  color: #3498db;
  transition: color 0.3s ease;
}

/* Diseños responsivos */
@media (max-width: 768px) {
  .app-footer {
    padding: 1.5rem 1rem;
  }
  
  .footer-title {
    font-size: 1.1rem;
  }
  
  .footer-text {
    font-size: 0.85rem;
  }
}

@media (max-width: 480px) {
  .footer-content {
    padding: 0 0.5rem;
  }
  
  .footer-title {
    font-size: 1rem;
  }
  
  .copyright {
    font-size: 0.75rem;
  }
}
</style>