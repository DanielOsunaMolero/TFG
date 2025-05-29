<template>
  <div class="editar-casa">
    <h1>Editar Casa Rural</h1>
    <form @submit.prevent="enviarFormulario">
      <label>Título:</label>
      <input type="text" v-model="form.titulo" required />

      <label>Descripción:</label>
      <textarea v-model="form.descripcion" required></textarea>

      <label>Ubicación:</label>
      <input type="text" v-model="form.ubicacion" required />

      <label>Precio por noche (€):</label>
      <input type="number" v-model="form.precio_noche" min="0" required />

      <label>Servicios:</label>
      <input type="text" v-model="form.servicios" />

      <label>Imágenes nuevas:</label>
      <input type="file" multiple @change="handleImagenes" />

      <button type="submit">Guardar Cambios</button>
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
        precio_noche: 0,
        servicios: ""
      },
      imagenes: [],
      idCasa: null
    };
  },
  async mounted() {
    this.idCasa = this.$route.params.id;
    try {
      const res = await fetch(`http://localhost/dashboard/TFG/backend/api/casa.php?id=${this.idCasa}`);
      const datos = await res.json();
      this.form = datos;
    } catch (error) {
      console.error("Error al cargar la casa:", error);
    }
  },
  methods: {
    handleImagenes(event) {
      this.imagenes = event.target.files;
    },
    async enviarFormulario() {
      const formData = new FormData();
      for (const key in this.form) {
        formData.append(key, this.form[key]);
      }
      formData.append("id", this.idCasa);
      for (let i = 0; i < this.imagenes.length; i++) {
        formData.append("imagenes[]", this.imagenes[i]);
      }

      try {
        const res = await fetch("http://localhost/dashboard/TFG/backend/api/editarCasa.php", {
          method: "POST",
          body: formData
        });
        const result = await res.json();
        if (result.success) {
          alert("Casa actualizada correctamente.");
          this.$router.push("/gestion");
        } else {
          alert("Error al actualizar.");
        }
      } catch (err) {
        console.error("Error al actualizar:", err);
      }
    }
  }
};
</script>

<style scoped>
.editar-casa {
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
  background-color: #f0c14b;
  color: black;
  border: none;
  cursor: pointer;
  border-radius: 6px;
}
</style>
