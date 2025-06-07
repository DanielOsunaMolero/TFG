<template>
  <div id="app">
    <div class="app-container">
      <Header 
        @mostrar-login="mostrarLogin = true" 
        @mostrar-registro="mostrarRegistro = true" 
      />

      <!-- Transición global con zoom-fade -->
      <transition name="zoom-fade" mode="out-in">
        <router-view class="contenido-principal" />
      </transition>

      <Footer />
    </div>

    <!-- Modales -->
    <LoginModal 
      v-if="mostrarLogin" 
      @cerrar="mostrarLogin = false" 
      @login-exitoso="handleLoginExitoso"
    />
    <RegistroModal 
      v-if="mostrarRegistro" 
      @cerrar="mostrarRegistro = false" 
    />
  </div>
</template>

<script>
import Header from './components/appHeader.vue'
import Footer from './components/appFooter.vue'
import LoginModal from './components/loginModal.vue'
import RegistroModal from './components/registroModal.vue'

export default {
  name: 'App',
  components: {
    Header,
    Footer,
    LoginModal,
    RegistroModal
  },
  data() {
    return {
      mostrarLogin: false,
      mostrarRegistro: false
    }
  },
  methods: {
    handleLoginExitoso() {
      this.mostrarLogin = false;
      // opcional: redireccionar o mostrar un mensaje global
    }
  }
}
</script>

<style>
html, body {
  margin: 0;
  padding: 0;
  height: 100%;
}

#app {
  height: 100%;
}

.app-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.contenido-principal {
  flex: 1;
}

/* Animación global - zoom-fade */
.zoom-fade-enter-active,
.zoom-fade-leave-active {
  transition: opacity 0.5s ease, transform 0.5s ease;
}

.zoom-fade-enter-from,
.zoom-fade-leave-to {
  opacity: 0;
  transform: scale(0.95);
}

.zoom-fade-enter-to,
.zoom-fade-leave-from {
  opacity: 1;
  transform: scale(1);
}
</style>
