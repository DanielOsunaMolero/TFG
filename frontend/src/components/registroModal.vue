<template>
  <div class="modal-overlay" @click.self="$emit('cerrar')">
    <div class="modal-box registro">
      <h2>Registro</h2>
      <input v-model="nombre" type="text" placeholder="Nombre completo" />
      <input v-model="email" type="email" placeholder="Correo electrónico" />
      <input v-model="password" type="password" placeholder="Contraseña" />
      <input v-model="repetir" type="password" placeholder="Repetir contraseña" />

      <div class="acciones">
        <a href="#">Términos</a>
        <a href="#">Política</a>
      </div>

      <!-- Sustituimos los puntos grises por los radios -->
      <div class="tipo-usuario">
        <label>
          <input type="radio" value="visitante" v-model="tipo" />
          Visitante
        </label>
        <label>
          <input type="radio" value="propietario" v-model="tipo" />
          Propietario
        </label>
      </div>

      <button @click="registrarse">Registrarse</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RegistroModal',
  data() {
    return {
      nombre: '',
      email: '',
      password: '',
      repetir: '',
      tipo: 'visitante'
    }
  },
  methods: {
    async registrarse() {
  if (this.password !== this.repetir) {
    alert('Las contraseñas no coinciden')
    return
  }

  const payload = {
    nombre: this.nombre,
    email: this.email,
    password: this.password,
    tipo: this.tipo
  }

  try {
    const res = await fetch('http://localhost/dashboard/TFG/backend/api/registro.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(payload)
    })

    const data = await res.json()
    if (data.success || data.mensaje) {
      alert('Registro exitoso.')
      this.$emit('cerrar')
    } else {
      alert(data.error || 'Error al registrar usuario')
    }
  } catch (e) {
    console.error('Error al registrar:', e)
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
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-box.registro {
  background: white;
  padding: 24px;
  border-radius: 16px;
  width: 320px;
  display: flex;
  flex-direction: column;
  align-items: stretch;
  text-align: center;
}

.modal-box h2 {
  margin-bottom: 16px;
  font-style: italic;
}

.modal-box input {
  background-color: #cfc2c2;
  border: none;
  padding: 10px;
  border-radius: 12px;
  margin-bottom: 12px;
  font-size: 14px;
}

.acciones {
  display: flex;
  justify-content: space-between;
  font-size: 13px;
  margin: 12px 0;
}

.acciones a {
  color: #333;
  text-decoration: none;
}

.tipo-usuario {
  display: flex;
  justify-content: space-around;
  margin-bottom: 16px;
  font-size: 14px;
}

.tipo-usuario input[type="radio"] {
  margin-right: 6px;
}

button {
  padding: 10px 20px;
  border: none;
  border-radius: 12px;
  background-color: #a39494;
  color: white;
  font-weight: bold;
  cursor: pointer;
}
</style>
