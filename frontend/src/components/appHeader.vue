<template>
  <header class="navbar">
    <nav class="nav-container">
      <router-link to="/" class="logo-link">
        <img src="@/assets/logo.png" alt="WeekendHouse" class="logo" />
      </router-link>

      <div class="nav-links">
        <router-link to="/" class="nav-link">Inicio</router-link>
        <router-link to="/blog" class="nav-link">Blog</router-link>
        <router-link v-if="esPropietario" to="/gestion" class="nav-link">Gestión</router-link>

        <template v-if="!estaAutenticado">
          <button class="auth-button login" @click="$emit('mostrar-login')">Iniciar sesión</button>
          <button class="auth-button register" @click="$emit('mostrar-registro')">Registro</button>
        </template>

        <template v-else>
          <router-link to="/perfil">
            <img
              class="foto-perfil"
              :src="rutaFotoPerfil"
              alt="Perfil"
              title="Ir al perfil"
            />
          </router-link>
          <a href="#" @click.prevent="logout" class="nav-link">Cerrar sesión</a>
        </template>
      </div>
    </nav>
  </header>
</template>


<script>
import { mapState, mapGetters, mapMutations } from 'vuex'
import { IMG_PERFIL_BASE } from '@/config.js'; 

export default {
  computed: {
    ...mapState(['usuario']),
    ...mapGetters(['esPropietario', 'estaAutenticado']),
    rutaFotoPerfil() {
  return this.usuario.foto_perfil
    ? `${IMG_PERFIL_BASE}${this.usuario.foto_perfil}`
    : `${IMG_PERFIL_BASE}default.jpg`;
}
  },
  methods: {
    ...mapMutations(['cerrarSesion']),
    logout() {
      this.cerrarSesion();
      this.$router.push('/');
    }
  }
}
</script>



<style scoped>
.navbar {
  height: auto;
  padding: 0 30px;
  display: flex;
  align-items: center;
  background: linear-gradient(90deg, #60e29b 0%, rgba(255, 255, 255, 0.8) 100%);
  backdrop-filter: blur(12px);
  font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  box-shadow: 0 1px 8px rgba(0, 0, 0, 0.05);
  z-index: 100;
}

.nav-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;
  max-width: 1200px;
  margin: 0 auto;
  width: 100%;
}

.logo-link {
  display: flex;
  align-items: center;
}

.logo {
  height: 55px;
  max-height: 60px;
  transition: transform 0.2s ease;
}

.logo-link:hover .logo {
  transform: scale(1.08);
}

.nav-links {
  display: flex;
  align-items: center;
  gap: 20px;
}

.nav-link {
  color: #333;
  text-decoration: none;
  font-weight: 500;
  padding: 8px 12px;
  border-radius: 8px;
  transition: background-color 0.3s ease, color 0.3s ease;
  font-size: 15px;
}

.nav-link:hover {
  background-color: rgba(96, 226, 155, 0.2);
  color: #222;
}

.auth-button {
  font-weight: 500;
  padding: 8px 16px;
  border-radius: 10px;
  border: 1px solid #60e29b;
  background-color: transparent;
  color: #60e29b;
  margin-left: 12px;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 14px;
}

.auth-button:hover {
  background-color: #60e29b;
  color: #fff;
  border-color: #60e29b;
}

.foto-perfil {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 12px;
  border: 2px solid #60e29b;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.foto-perfil:hover {
  transform: scale(1.1);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

@media (max-width: 768px) {
  .nav-container {
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 10px 20px;
    gap: 10px;
  }

  .nav-links {
    flex-direction: column;
    gap: 10px;
    width: 100%;
    align-items: center;
  }

  .auth-button, .nav-link {
    width: auto;
    text-align: center;
  }

  .foto-perfil {
    margin-right: 0;
  }

  .logo {
    height: 50px;
    max-height: 55px;
  }
}

</style>
