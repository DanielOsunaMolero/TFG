<template>
  <footer class="footer">
    <div class="footer-content">

      <!-- Columna izquierda -->
      <div class="footer-col">
        <h3>Contacto</h3>
        <p>Teléfono: +34 123 456 789</p>
        <p>Email: </p>
      </div>

      <!-- Columna central -->
      <div class="footer-center">
        <div class="footer-logo">
          <img src="@/assets/logo.png" alt="Logo" style="width: 100%; height: auto;" />
        </div>
        <p></p>
      </div>

      <!-- Columna derecha -->
      <div class="footer-col right">
        <div class="footer-item">
          
          <p>Sobre nosotros</p>
          <div class="circle"></div>
        </div>
        <div class="footer-item">
          
          <p>Instagram</p>
          <div class="circle"></div>
        </div>
        <div class="footer-item">
          
          <p>Facebook</p>
          <div class="circle"></div>
        </div>
      </div>

    </div>
  </footer>
</template>

<script>
export default {
  name: 'AppFooter'
}
</script>

<style scoped>
.footer {
background: linear-gradient(90deg, #60e29b 0%, rgba(255, 255, 255, 0.8) 100%);

  backdrop-filter: blur(12px);
  color: #333;
  padding: 30px 20px;
  font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  font-weight: 500;
  box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.05);
}

.footer-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  text-align: left;
  max-width: 1200px;
  margin: 0 auto;
  flex-wrap: wrap;
  gap: 20px;
}

.footer-col {
  flex: 1;
  min-width: 200px;
}

.footer-col.right {
  text-align: left;
}

.footer-col p,
.footer-col h3 {
  margin: 6px 0;
  color: #444;
  font-weight: 500;
}

.footer-col h3 {
  font-size: 16px;
  margin-bottom: 10px;
  color: #222;
}

.footer-center {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.footer-logo {
  width: 80px;
  height: 80px;
  background-color: #ffffff;
  border-radius: 50%;
  margin-bottom: 12px;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease;
}

.footer-logo img {
  width: 100%;
  height: auto;
}

.footer-logo:hover {
  transform: scale(1.05);
}

.footer-item {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 8px;
  margin-bottom: 10px;
  cursor: pointer;
  transition: color 0.3s ease;
}

.footer-item p {
  transition: color 0.3s ease;
}

.footer-item:hover p {
  color: #60e29b;
}

.circle {
  width: 6px;
  height: 6px;
  background-color: #60e29b;
  border-radius: 50%;
  transition: transform 0.2s ease;
}

.footer-item:hover .circle {
  transform: scale(1.2);
}


</style>
