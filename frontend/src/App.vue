<template>
  <div id="app">
    <div class="app-container">
      <Header 
        @mostrar-login="mostrarLogin = true" 
        @mostrar-registro="mostrarRegistro = true" 
      />

      <router-view class="contenido-principal" />

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
</style>
