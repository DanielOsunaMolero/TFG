<template>
  <div class="crear-casa">
    <h1>Crear Nueva Casa Rural</h1>
    <form @submit.prevent="enviarFormulario" enctype="multipart/form-data" class="form-grid">
      <label class="full-width">
        <input type="text" v-model="form.titulo" placeholder="Título de la casa rural" required />
      </label>

      <label class="full-width">
        <textarea v-model="form.descripcion" placeholder="Descripción detallada" required></textarea>
      </label>

      <label>
        <input type="text" v-model="form.ubicacion" placeholder="Ubicación" required />
      </label>

      <label>
        <input type="number" v-model="form.precio" min="0" placeholder="Precio por noche (€)" required />
      </label>

      <label>
        <input type="text" v-model="form.servicios" placeholder="Ejemplo: Wifi, Piscina, Parking" />
      </label>

      <label class="file-label">
        <font-awesome-icon :icon="['fas', 'camera']" style="margin-right: 6px;" />
        Seleccionar imágenes...
        <input type="file" multiple @change="handleArchivos" accept="image/*" required />
      </label>

      <div class="full-width center-btn">
        <button type="submit">Guardar Casa</button>
      </div>
    </form>
  </div>
</template>

<script>
import { API_BASE } from '@/config.js';
import { useToast } from 'vue-toastification';

export default {
  data() {
    return {
      form: {
        titulo: "",
        descripcion: "",
        ubicacion: "",
        precio: "",
        servicios: "",
      },
      archivos: []
    };
  },
  methods: {
    handleArchivos(event) {
      this.archivos = event.target.files;

      const toast = useToast();
      if (this.archivos.length !== 6) {
        toast.warning('⚠️ Debes seleccionar exactamente 6 imágenes.');
      }
    },

    async enviarFormulario() {
      const toast = useToast();

      if (this.archivos.length !== 6) {
        toast.error('❌ Debes seleccionar exactamente 6 imágenes.');
        return;
      }

      const formData = new FormData();

     
      formData.append("titulo", this.form.titulo);
      formData.append("descripcion", this.form.descripcion);
      formData.append("ubicacion", this.form.ubicacion);
      formData.append("precio_noche", Number(this.form.precio));
      formData.append("servicios", this.form.servicios);

      // Obtener el ID del propietario desde localStorage
      const idPropietario = parseInt(localStorage.getItem("id_usuario")) || 0;
      formData.append("id_propietario", idPropietario);

      for (let i = 0; i < this.archivos.length; i++) {
        formData.append("imagenes[]", this.archivos[i]);
      }

      // Log de control
      console.log("Enviando crearCasa:", {
        titulo: this.form.titulo,
        descripcion: this.form.descripcion,
        ubicacion: this.form.ubicacion,
        precio_noche: Number(this.form.precio),
        servicios: this.form.servicios,
        id_propietario: idPropietario,
        imagenes: this.archivos.length
      });
      console.log("typeof id_usuario:", typeof localStorage.getItem("id_usuario"));

      try {
        const res = await fetch(`${API_BASE}crearCasa.php`, {
          method: "POST",
          body: formData
        });
        const resultado = await res.json();

        console.log("Respuesta crearCasa:", resultado);

        if (resultado.success) {
          toast.success("✅ Casa creada correctamente.");
          this.$router.push("/gestion");
        } else {
          toast.error(`❌ Error al crear la casa: ${resultado.error || resultado.message || ''}`);
        }
      } catch (err) {
        console.error("Error al enviar el formulario:", err);
        toast.error("❌ Hubo un problema al enviar los datos.");
      }
    }
  }
};
</script>


<style scoped>
.crear-casa {
  max-width: 900px;
  margin: auto;
  padding: 30px;
  background-color: #f3ffee;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.crear-casa h1 {
  text-align: center;
  margin-bottom: 30px;
  color: #333;
  font-family: 'Poppins', sans-serif;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px 20px;
}

.full-width {
  grid-column: span 2;
}

input[type="text"],
input[type="number"],
textarea,
input[type="file"] {
  width: 100%;
  padding: 12px 16px;
  font-size: 1rem;
  border: 1px solid #ccc;
  border-radius: 8px;
  transition: box-shadow 0.3s ease, border-color 0.3s ease;
  box-sizing: border-box;
}

input[type="text"]:focus,
input[type="number"]:focus,
textarea:focus {
  outline: none;
  border-color: #60e29b;
  box-shadow: 0 0 0 3px rgba(96, 226, 155, 0.3);
}

textarea {
  resize: vertical;
  min-height: 100px;
}

button {
  background-color: #60e29b;
  color: white;
  border: none;
  padding: 12px 20px;
  font-size: 1rem;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.2s ease;
}

button:hover {
  background-color: #4cc08e;
  transform: translateY(-1px);
}

.center-btn {
  display: flex;
  justify-content: center;
}

.file-label {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 12px 20px;
  background-color: #ffffff;
  color: #60e29b;
  font-size: 1rem;
  font-weight: 500;
  border: 2px dashed #60e29b;
  border-radius: 12px;
  cursor: pointer;
  text-align: center;
  transition: all 0.3s ease;
  user-select: none;
}

.file-label:hover {
  background-color: #f0fff7;
  border-color: #4cc08e;
  color: #4cc08e;
  transform: translateY(-1px);
}

.file-label input[type="file"] {
  display: none;
}



@media (max-width: 768px) {
  .form-grid {
    grid-template-columns: 1fr;
  }

  .full-width {
    grid-column: span 1;
  }

  .crear-casa {
    padding: 20px;
  }

  button {
    width: 100%;
  }
}

@media (max-width: 480px) {
  .crear-casa {
    width: 90%;
  }
}
</style>
