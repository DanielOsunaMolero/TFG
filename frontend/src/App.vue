<template>
  <div id="app">
    <Header @mostrar-login="mostrarLogin = true" @mostrar-registro="mostrarRegistro = true" />
    <router-view />
    <Footer />

    <!-- Modales flotantes -->
    <LoginModal v-if="mostrarLogin" @cerrar="mostrarLogin = false" />
    <RegistroModal v-if="mostrarRegistro" @cerrar="mostrarRegistro = false" />
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
  }
}
</script>

<style>
body {
  margin: 0;
  font-family: Arial, sans-serif;
}
</style>
