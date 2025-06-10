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
import { API_BASE } from '@/config.js';
import { mapMutations } from 'vuex';
import { useToast } from 'vue-toastification'; // ✅ Añadido

export default {
  name: 'RegistroModal',
  data() {
    return {
      nombre: '',
      email: '',
      password: '',
      repetir: '',
      tipo: 'visitante'
    };
  },
  methods: {
    ...mapMutations(['guardarUsuario']), // ✅ Importamos mutation

    async registrarse() {
      const toast = useToast(); // ✅ Inicializamos toast

      if (this.password !== this.repetir) {
        toast.error('❌ Las contraseñas no coinciden');
        return;
      }

      const payload = {
        nombre: this.nombre,
        email: this.email,
        password: this.password,
        tipo: this.tipo
      };

      try {
        const res = await fetch(`${API_BASE}registro.php`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(payload)
        });

        const data = await res.json();
        console.log("Respuesta registro:", data); // ✅ opcional

        if (data.success && data.usuario) {
          // ✅ Guardar sesión
          this.guardarUsuario(data.usuario);
          localStorage.setItem('id_usuario', data.usuario.id_usuario);
          localStorage.setItem('usuario', JSON.stringify(data.usuario));

          // ✅ Cerrar modal
          this.$emit('cerrar');

          // ✅ Toast
          toast.success('✅ Registro exitoso. Has iniciado sesión.');

        } else {
          toast.error(data.error || '❌ Error al registrar usuario');
        }
      } catch (e) {
        console.error('Error al registrar:', e);
        toast.error('❌ Error de conexión.');
      }
    }
  }
};
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

.modal-box.registro {
  background: #fff;
  padding: 30px 24px;
  border-radius: 20px;
  width: 340px;
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  animation: fadeIn 0.3s ease-in-out;
  display: flex;
  flex-direction: column;
}

.modal-box h2 {
  margin-bottom: 24px;
  font-style: italic;
  font-weight: 600;
  font-size: 22px;
  text-align: center;
  color: #222;
}

.modal-box input {
  background-color: #f7f7f7;
  border: 1px solid #ccc;
  padding: 10px 14px;
  border-radius: 10px;
  margin-bottom: 14px;
  font-size: 14px;
  transition: border 0.3s;
}

.modal-box input:focus {
  outline: none;
  border-color: #5bc597;
  background-color: #fff;
}

.tipo-usuario {
  display: flex;
  justify-content: center;
  gap: 40px;
  margin: 16px 0;
}

.tipo-usuario label {
  display: flex;
  flex-direction: column;
  align-items: center;
  font-size: 14px;
  color: #444;
  cursor: pointer;
}



.acciones {
  display: flex;
  justify-content: space-between;
  font-size: 13px;
  margin-bottom: 16px;
  color: #777;
}

.acciones a {
  color: #555;
  text-decoration: none;
  transition: color 0.3s;
}

.acciones a:hover {
  color: #333;
}

button {
  padding: 12px;
  border: none;
  border-radius: 10px;
  background-color: #5bc597;
  color: white;
  font-weight: bold;
  font-size: 15px;
  cursor: pointer;
  transition: background 0.3s ease;
}

button:hover {
  background-color: #48a97d;
}

/* Animación suave */
@keyframes fadeIn {
  from { transform: scale(0.95); opacity: 0; }
  to   { transform: scale(1); opacity: 1; }
}
</style>

