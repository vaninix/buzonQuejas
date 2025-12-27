<template>
  <header class="app-header">
    <div class="header-content">
      <h1 class="header-title">{{ title }}</h1>
    </div>
  </header>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: true
    }
  }
}
</script>

<style scoped>
.app-header {
  background: linear-gradient(135deg, #053364 0%, #021d3a 100%);  color: white;
  padding: 1.2rem 1rem;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  position: relative;
  z-index: 10;
}

.header-content {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: center;
  align-items: center;
}

.header-title {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-size: 1.8rem;
  font-weight: 600;
  letter-spacing: 0.5px;
  margin: 0;
  text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
  position: relative;
  display: inline-block;
  padding-bottom: 0.5rem;
}


/* Diseños responsivos */
@media (max-width: 768px) {
  .header-title {
    font-size: 1.5rem;
  }
  
  .app-header {
    padding: 1rem;
  }
}

@media (max-width: 480px) {
  .header-title {
    font-size: 1.3rem;
  }
  
  .header-title::after {
    width: 60px;
    height: 2px;
  }
}
</style>