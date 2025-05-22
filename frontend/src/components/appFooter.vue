<template>
  <footer class="footer">
    <div class="footer-content">

      <!-- Columna izquierda -->
      <div class="footer-col">
        <h3>LOREM IPSUM</h3>
        <p>LOREM IPSUM</p>
        <p>LOREM IPSUM</p>
        <p>LOREM IPSUM</p>
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
  background-color: #84cca5;
  color: white;
  padding: 20px 20px;
  font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
  font-weight: 800;
}

.footer-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  text-align: center;
  max-width: 1200px;
  margin: 0 auto;
}

.footer-col {
  flex: 1;
  text-align: left;
}

.footer-col.right {
  text-align: left;
}

.footer-col p,
.footer-col h3 {
  margin: 6px 0;
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
  background-color: #84cca5;
  border-radius: 50%;
  margin-bottom: 12px;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  
}
.footer-logo img {
  width: 100%;
  height: auto;
}

.footer-item {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 8px;
  margin-bottom: 10px;
}

.circle {
  width: 5px;
  height: 5px;
  background-color: #dcdcdc;
  border-radius: 50%;
}
</style>
