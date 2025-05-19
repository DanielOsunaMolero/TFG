import { createStore } from 'vuex'

export default createStore({
  state: {
    usuario: null
  },
  mutations: {
    setUsuario(state, user) {
      state.usuario = user
    }
  },
  actions: {
    login({ commit }, user) {
      commit('setUsuario', user)
    }
  },
  getters: {
    usuarioAutenticado: (state) => !!state.usuario
  }
})
