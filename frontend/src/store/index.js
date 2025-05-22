import { createStore } from 'vuex'

export default createStore({
  state: {
    usuario: null // null si no está logueado
  },
  mutations: {
    setUsuario(state, usuario) {
      state.usuario = usuario
    },
    cerrarSesion(state) {
      state.usuario = null
    }
  },
  getters: {
    esVisitante: state => state.usuario?.tipo === 'visitante',
    esPropietario: state => state.usuario?.tipo === 'propietario',
    estaAutenticado: state => !!state.usuario
  }
})
