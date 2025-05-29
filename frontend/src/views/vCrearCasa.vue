<template>
  <div class="crear-casa">
    <h1>Crear Nueva Casa Rural</h1>
    <form @submit.prevent="enviarFormulario" enctype="multipart/form-data">
      <label>Título:</label>
      <input type="text" v-model="form.titulo" required />

      <label>Descripción:</label>
      <textarea v-model="form.descripcion" required></textarea>

      <label>Ubicación:</label>
      <input type="text" v-model="form.ubicacion" required />

      <label>Precio por noche (€):</label>
      <input type="number" v-model="form.precio" min="0" required />

      <label>Servicios:</label>
      <input type="text" v-model="form.servicios" placeholder="Ej: Wifi, Piscina" />

      <label>Imágenes (puedes seleccionar varias):</label>
      <input type="file" multiple @change="handleArchivos" accept="image/*" required />

      <button type="submit">Guardar Casa</button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        titulo: "",
        descripcion: "",
        ubicacion: "",
        precio: 0,
        servicios: "",
      },
      archivos: []
    };
  },
  methods: {
    handleArchivos(event) {
      this.archivos = event.target.files;
    },
    async enviarFormulario() {
      const formData = new FormData();
      for (const key in this.form) {
        formData.append(key, this.form[key]);
      }
      for (let i = 0; i < this.archivos.length; i++) {
        formData.append("imagenes[]", this.archivos[i]);
      }

      try {
        const res = await fetch("http://localhost/dashboard/TFG/backend/api/crearCasa.php", {
          method: "POST",
          body: formData
        });
        const resultado = await res.json();

        if (resultado.success) {
          alert("Casa creada correctamente.");
          this.$router.push("/gestion");
        } else {
          alert("Error al crear la casa.");
        }
      } catch (err) {
        console.error("Error al enviar el formulario:", err);
        alert("Hubo un problema al enviar los datos.");
      }
    }
  }
};
</script>

<style scoped>
.crear-casa {
  max-width: 700px;
  margin: auto;
  padding: 20px;
}

form {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

input, textarea, button {
  padding: 8px;
  font-size: 1rem;
}

button {
  background-color: #4CAF50;
  color: white;
  border: none;
  cursor: pointer;
  border-radius: 6px;
}
</style>
