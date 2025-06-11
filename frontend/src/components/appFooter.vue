<template>
  <footer class="nuevo-footer">
    <div class="footer-wrapper">

      <div class="footer-left">
        <div class="footer-social">
          <a href="#" class="social-icon">
            <font-awesome-icon :icon="['fab', 'instagram']" /> Instagram
          </a>
          <a href="#" class="social-icon">
            <font-awesome-icon :icon="['fab', 'facebook']" /> Facebook
          </a>
        </div>
      </div>

      <div class="footer-contact">
        <p>
          <font-awesome-icon :icon="['fas', 'phone']" style="margin-right: 8px;" />
          +34 123 456 789
        </p>
        <p>
          <font-awesome-icon :icon="['fas', 'envelope']" style="margin-right: 8px;" />
          contacto@weekendhouse.com
        </p>
      </div>


      <div class="footer-logo-area">
        <img src="@/assets/logo.png" alt="Logo" />
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
.nuevo-footer {
  background: linear-gradient(90deg, #60e29b 0%, rgba(255, 255, 255, 0.95) 100%);
  color: #222;
  padding: 30px 30px;
  font-family: 'Poppins', sans-serif;
  box-shadow: 0 -2px 12px rgba(0, 0, 0, 0.05);

}

.footer-wrapper {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  max-width: 1200px;
  margin: 0 auto;
  flex-wrap: wrap;
  gap: 20px;
  padding: 0 10px;
}

.footer-left {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  flex: 1;
  min-width: 200px;
}

.footer-left .marca {
  font-size: 18px;
  font-weight: 700;
  margin-bottom: 14px;
}

.footer-social {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.footer-social .social-icon {
  text-decoration: none;
  color: #222;
  margin: 6px 0;
  font-size: 14px;
  transition: color 0.3s ease;
  display: flex;
  align-items: center;
  gap: 8px;
}

.footer-social .social-icon:hover {
  color: #60e29b;
}


.footer-contact {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  flex: 1;
  min-width: 220px;
  max-width: 300px;
}

.footer-contact h3 {
  margin-bottom: 10px;
  font-size: 16px;
  font-weight: 600;
  color: #222;
}

.footer-contact p {
  margin: 6px 0;
  font-size: 14px;
  color: #444;
  display: flex;
  align-items: center;
}


.footer-logo-area {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  flex: 1;
  min-width: 150px;
}

.footer-logo-area img {
  width: 80px;
  height: auto;
  opacity: 0.9;
  transition: transform 0.3s ease;
}

.footer-logo-area img:hover {
  transform: scale(1.05);
}


@media (max-width: 768px) {
  .footer-wrapper {
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: 15px;
  }

  .footer-left {
    align-items: center;
  }

  .footer-social {
    align-items: center;
  }

  .footer-contact {
    align-items: center;
  }

  .footer-logo-area {
    justify-content: center;
  }

  .footer-logo-area img {
    width: 60px;
  }
}
</style>
