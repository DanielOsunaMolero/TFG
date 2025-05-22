<template>
  <div class="modal-overlay" @click.self="$emit('cerrar')">
    <div class="modal-box login">
      <h2>Inicio de sesión</h2>
      <input placeholder="Email" />
      <input placeholder="Contraseña" type="password" />
      <button>Entrar</button>
    </div>
  </div>
</template>

<script>
export default { name: 'LoginModal' }
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

.modal-box {
  background: white;
  padding: 24px;
  border-radius: 16px;
  width: 300px;
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
