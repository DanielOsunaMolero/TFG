<template>
  <div class="contenedor-edicion">
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
        <div class="etiquetas-servicios">
          <div class="tag" v-for="(tag, index) in servicios" :key="index">
            {{ tag }}
            <span class="cerrar" @click="eliminarServicio(index)">×</span>
          </div>
          <input type="text" v-model="nuevoServicio" @keydown.enter.prevent="agregarServicio"
            placeholder="Añadir servicio..." />

        </div>

        <label>Imágenes nuevas:</label>
        <input type="file" multiple @change="handleImagenes" />

        <button type="submit">Guardar Cambios</button>
      </form>
    </div>

    <div class="galeria-imagenes">
      <div v-for="(img, index) in imagenesExistentes" :key="index" class="imagen-contenedor">
        <img :src="img" alt="Foto casa" />
        <span class="eliminar" @click="eliminarImagenLocal(index)">✖</span>
      </div>
    </div>

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
        precio_noche: 0
      },
      servicios: [],
      nuevoServicio: "",
      imagenes: [],
      imagenesExistentes: [],
      idCasa: null
    };
  },
  async mounted() {
    this.idCasa = this.$route.params.id;
    try {
      const res = await fetch(`http://localhost/dashboard/TFG/backend/api/casa.php?id=${this.idCasa}`);
      const datos = await res.json();

      this.form = {
        titulo: datos.titulo,
        descripcion: datos.descripcion,
        ubicacion: datos.ubicacion,
        precio_noche: datos.precio_noche
      };

      this.servicios = datos.servicios ? datos.servicios.split(',').map(s => s.trim()) : [];

      // Cargar imágenes que realmente existen (hasta 9)
      const base = "http://localhost:8080/fotos/";
      const nombreBase = datos.titulo.toLowerCase().replace(/\s+/g, "_");

      for (let i = 1; i <= 9; i++) {
        const nombre = `Foto_${nombreBase}(${i}).jpg`;
        const url = `${base}${nombre}`;

        try {
          const res = await fetch(url, { method: "HEAD" });
          if (res.ok) {
            this.imagenesExistentes.push(url);
          }
        } catch (e) {
          console.warn(`No existe la imagen: ${url}`);
        }
      }
    } catch (error) {
      console.error("Error al cargar la casa:", error);
    }
  },
  methods: {
    handleImagenes(event) {
      this.imagenes = event.target.files;
    },
    agregarServicio() {
      const nuevo = this.nuevoServicio.trim();
      if (nuevo && !this.servicios.includes(nuevo)) {
        this.servicios.push(nuevo);
      }
      this.nuevoServicio = '';
    },
    eliminarServicio(index) {
      this.servicios.splice(index, 1);
    },
    async enviarFormulario() {
      const formData = new FormData();
      for (const key in this.form) {
        formData.append(key, this.form[key]);
      }
      formData.append("id", this.idCasa);
      formData.append("servicios", this.servicios.join(','));

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
    },
    eliminarImagenLocal(index) {
      const ruta = this.imagenesExistentes[index];
      const nombre = ruta.split('/').pop();

      fetch("http://localhost/dashboard/TFG/backend/api/eliminarImagenCasa.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({ nombre })
      })
        .then(res => res.json())
        .then(data => {
          if (data.success) {
            this.imagenesExistentes.splice(index, 1);
          } else {
            alert("No se pudo eliminar del servidor.");
          }
        })
        .catch(err => {
          console.error("Error eliminando imagen:", err);
          alert("Error de conexión.");
        });
    }
  }
};
</script>


<style scoped>
.contenedor-edicion {
  display: flex;
  gap: 40px;
  align-items: flex-start;
  padding: 20px;
}

.editar-casa {
  flex: 1;
  max-width: 600px;
  padding: 30px;
  background-color: #fff;
  border-radius: 12px;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
  font-family: 'Segoe UI', sans-serif;
}

h1 {
  text-align: center;
  margin-bottom: 24px;
  font-size: 28px;
  color: #333;
}

form {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

label {
  font-weight: bold;
  color: #444;
  margin-bottom: 4px;
}

input,
textarea {
  padding: 10px 14px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  background-color: #f9f9f9;
  transition: border 0.2s ease;
}

input:focus,
textarea:focus {
  border-color: #4caf50;
  outline: none;
  background-color: #fff;
}

input[type="file"] {
  padding: 0;
  background: transparent;
  border: none;
}

button {
  padding: 12px;
  background-color: #f0c14b;
  border: none;
  border-radius: 8px;
  color: #333;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.2s ease;
}

button:hover {
  background-color: #e2b13e;
}

.etiquetas-servicios {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 8px;
  background-color: #f9f9f9;
}

.tag {
  background-color: #e0e0e0;
  padding: 6px 12px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  font-size: 0.9rem;
}

.cerrar {
  margin-left: 8px;
  cursor: pointer;
  font-weight: bold;
  color: #777;
}

.etiquetas-servicios input {
  border: none;
  outline: none;
  flex: 1;
  min-width: 120px;
  font-size: 0.9rem;
  padding: 6px;
  background: transparent;
}

.galeria-imagenes {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 14px;
  max-width: 360px;
}

.imagen-contenedor {
  position: relative;
  width: 100%;
  aspect-ratio: 1 / 1;
  overflow: hidden;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.imagen-contenedor img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  border-radius: 10px;
}

.imagen-contenedor .eliminar {
  position: absolute;
  top: 6px;
  right: 6px;
  background-color: rgba(0, 0, 0, 0.7);
  color: white;
  border-radius: 50%;
  padding: 6px 9px;
  font-weight: bold;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.imagen-contenedor .eliminar:hover {
  background-color: rgba(255, 0, 0, 0.8);
}
</style>
