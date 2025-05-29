import { createStore } from 'vuex'

export default createStore({
  state: {
    usuario: JSON.parse(localStorage.getItem('usuario')) || null
  },
  mutations: {
    guardarUsuario(state, usuario) {
      state.usuario = usuario
      localStorage.setItem('usuario', JSON.stringify(usuario))
    },
    cerrarSesion(state) {
      state.usuario = null
      localStorage.removeItem('usuario')
    }
  },
  getters: {
    estaAutenticado: (state) => !!state.usuario,
    esPropietario: (state) => state.usuario?.tipo === 'propietario'
  }
})
