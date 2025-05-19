<template>
  <header class="navbar">
    <nav>
      <router-link to="/" class="nav-link">Inicio</router-link>
      <router-link to="/buscar" class="nav-link">Buscar</router-link>
      <router-link to="/blog" class="nav-link">Blog</router-link>
      <router-link to="/gestion" class="nav-link">Gestión</router-link>
      <router-link to="/login" class="nav-link">Iniciar sesión</router-link>
      <router-link to="/registro" class="nav-link">Registro</router-link>
    </nav>
  </header>
</template>

<script>
export default {
  name: 'AppHeader'
}
</script>

<style scoped>
.navbar {
  background-color: #264653;
  padding: 12px 24px;
}
.nav-link {
  color: white;
  margin-right: 16px;
  text-decoration: none;
  font-weight: bold;
}
.nav-link:hover {
  text-decoration: underline;
}
</style>
