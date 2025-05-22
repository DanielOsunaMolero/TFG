<template>
  <header class="navbar">
    <nav class="nav-container">
      <router-link to="/" class="logo-link">
        <img src="@/assets/logo.png" alt="WeekendHouse" class="logo" />
      </router-link>

      <div class="nav-links">
        <router-link to="/casa/:id" class="nav-link">PerfilCasa</router-link>
        <router-link to="/" class="nav-link">Inicio</router-link>
        <router-link to="/buscar" class="nav-link">Buscar</router-link>
        <router-link to="/blog" class="nav-link">Blog</router-link>
        <router-link v-if="esPropietario" to="/gestion" class="nav-link">Gestión</router-link>

        <template v-if="!estaAutenticado">
            <button @click="$emit('mostrar-login')">Iniciar sesión</button>
              <button @click="$emit('mostrar-registro')">Registro</button>
        </template>
  

        <template v-else>
          <span class="nav-user">Hola, {{ usuario.nombre }}</span>
          <a href="#" @click.prevent="logout" class="nav-link">Cerrar sesión</a>
        </template>
      </div>
    </nav>
  </header>
</template>

<script>
import { mapState, mapGetters, mapMutations } from 'vuex'

export default {
  computed: {
    ...mapState(['usuario']),
    ...mapGetters(['esPropietario', 'estaAutenticado'])
  },
  methods: {
    ...mapMutations(['cerrarSesion']),
    logout() {
      this.cerrarSesion()
      this.$router.push('/')
    }
  }
}
</script>

<style scoped>
.navbar {
  background-color: #84cca5;
  padding: 12px 24px;
  font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
}

.nav-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.logo-link {
  display: flex;
  align-items: center;
}

.logo {
  height: 60px;
  margin-right: 20px;
}

.nav-links {
  display: flex;
  align-items: center;
}

.nav-link {
  color: rgb(0, 0, 0);
  margin-right: 16px;
  text-decoration: none;
  font-weight: bold;
}

.nav-link:hover {
  text-decoration-color: #07ec07;
  background-color: #97f1bd;
  border-radius: 20px;
  padding: 5px 10px;
}

.nav-user {
  color: #f6e27f;
  margin-right: 16px;
}
</style>
