<template>
  <div class="modal-overlay" @click.self="$emit('cerrar')">
    <div class="modal-box login">
      <h2>Inicio de sesión</h2>

      <input v-model="email" type="email" placeholder="Correo electrónico" />
      <input v-model="password" type="password" placeholder="Contraseña" />

      <button @click="iniciarSesion">Entrar</button>
    </div>
  </div>
</template>

<script>
import { mapMutations } from 'vuex'

export default {
  name: 'LoginModal',
  data() {
    return {
      email: '',
      password: ''
    }
  },
  methods: {
    ...mapMutations(['guardarUsuario']),

    async iniciarSesion() {
      if (!this.email || !this.password) {
        alert("Introduce tu correo y contraseña.")
        return
      }

      try {
        const res = await fetch('http://localhost/dashboard/TFG/backend/api/login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            email: this.email,
            password: this.password
          })
        })

        const data = await res.json()

        if (data.success && data.usuario) {
          this.guardarUsuario(data.usuario)
          alert("Sesión iniciada correctamente.")
          this.$emit('cerrar')
        } else {
          alert(data.error || "Login incorrecto.")
        }

      } catch (error) {
        console.error("Error al iniciar sesión:", error)
        alert("Error de conexión con el servidor.")
      }
    }
  }
}
</script>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-box.login {
  background: white;
  padding: 24px;
  border-radius: 16px;
  width: 300px;
}

.modal-box h2 {
  margin-bottom: 16px;
  font-style: italic;
}

.modal-box input {
  width: 100%;
  margin-bottom: 12px;
  padding: 10px;
  border-radius: 8px;
  border: none;
  background: #d3c7c7;
}

.modal-box button {
  background: #aaa;
  padding: 10px 20px;
  border: none;
  border-radius: 10px;
  width: 100%;
}
</style>
