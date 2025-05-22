<template>
  <div class="modal-overlay" @click.self="$emit('cerrar')">
    <div class="modal-box registro">
      <h2>Registro</h2>
      <input type="text" placeholder="Nombre completo" />
      <input type="email" placeholder="Correo electrónico" />
      <input type="password" placeholder="Contraseña" />
      <input type="password" placeholder="Repetir contraseña" />

      <div class="acciones">
        <a href="#">Términos</a>
        <a href="#">Política</a>
      </div>

      <div class="validaciones">
        <span class="punto"></span>
        <span class="punto"></span>
      </div>

      <button>Registrarse</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RegistroModal'
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

.validaciones {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-bottom: 16px;
}

.punto {
  width: 12px;
  height: 12px;
  background: #cfcfcf;
  border-radius: 50%;
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
