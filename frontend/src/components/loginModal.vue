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
        const res = await fetch("http://localhost/dashboard/TFG/backend/api/login.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({
            email: this.email,
            password: this.password
          })
        });

        const data = await res.json();
        console.log("Respuesta del servidor:", data)

        if (data.success && data.usuario) {
          this.guardarUsuario(data.usuario)
          localStorage.setItem("id_usuario", data.usuario.id_usuario)
          localStorage.setItem("usuario", JSON.stringify(data.usuario))
          alert("Sesión iniciada correctamente.")
          this.$emit("cerrar")  // ✅ Cierra el modal
        } else {
          alert(data.message || "Credenciales incorrectas")
        }

      } catch (error) {
        console.error("Error al iniciar sesión:", error)
        alert("Error al conectar con el servidor.")
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
  background: #fff;
  padding: 30px 24px;
  border-radius: 20px;
  width: 320px;
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  animation: fadeIn 0.3s ease-in-out;
}

.modal-box h2 {
  margin-bottom: 24px;
  font-style: italic;
  font-weight: 600;
  font-size: 22px;
  color: #222;
  text-align: center;
}

.modal-box input {
  width: 90%;
  margin-bottom: 16px;
  padding: 10px 14px;
  border-radius: 10px;
  border: 1px solid #ccc;
  background: #f7f7f7;
  transition: border 0.3s;
  font-size: 14px;
}

.modal-box input:focus {
  outline: none;
  border-color: #5bc597;
  background: #fff;
}

.modal-box button {
  background: #5bc597;
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 10px;
  width: 100%;
  font-weight: bold;
  font-size: 15px;
  cursor: pointer;
  transition: background 0.3s;
}

.modal-box button:hover {
  background: #4cb184;
}

/* Animación de entrada */
@keyframes fadeIn {
  from { transform: scale(0.95); opacity: 0; }
  to   { transform: scale(1); opacity: 1; }
}
</style>
