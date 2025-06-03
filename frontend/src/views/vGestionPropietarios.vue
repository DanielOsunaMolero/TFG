<template>
  <div class="gestion-propietarios">
    <h1>Gestión de Casas Rurales</h1>

    <button class="crear-btn" @click="crearCasa">➕ Crear Nueva Casa</button>

    <div class="grid-casas">
      <div v-for="casa in casas" :key="casa.id" class="card-casa">
        <img :src="casa.imagen || placeholder" alt="Imagen casa" class="img-casa">
        <h3>{{ casa.titulo }}</h3>
        <div class="acciones">
          <button @click="editarCasa(casa.id)">✏️ Editar</button>
          <button @click="eliminarCasa(casa.id)">🗑 Eliminar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      casas: [],
      placeholder: "https://via.placeholder.com/300x200?text=Sin+imagen"
    };
  },
  methods: {
    async cargarCasas() {
      try {
        const res = await fetch("http://localhost/dashboard/TFG/backend/api/getCasasPropietario.php");
        this.casas = await res.json();
      } catch (error) {
        console.error("Error cargando casas:", error);
      }
    },
    crearCasa() {
      this.$router.push("/crear-casa");
    },
    editarCasa(id) {
      this.$router.push(`/editar-casa/${id}`);
    }
    ,
    eliminarCasa(id) {
      if (confirm("¿Estás seguro de que quieres eliminar esta casa?")) {
        const formData = new FormData();
        formData.append("id", id);

        fetch("http://localhost/dashboard/TFG/backend/api/eliminarCasa.php", {
          method: "POST",
          body: formData
        })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              this.cargarCasas(); // recarga la lista de casas
            } else {
              alert("No se pudo eliminar la casa.");
            }
          })
          .catch(err => {
            console.error("Error al eliminar:", err);
            alert("Error al eliminar la casa.");
          });
      }
    }

  },
  mounted() {
    this.cargarCasas();
  }
};
</script>

<style scoped>
.gestion-propietarios {
  padding: 20px;
  max-width: 1200px;
  margin: auto;
}

.crear-btn {
  background-color: #4CAF50;
  color: white;
  border: none;
  padding: 12px 20px;
  margin-bottom: 20px;
  cursor: pointer;
  font-size: 16px;
  border-radius: 8px;
}

.grid-casas {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}



.card-casa {
  background: #f7f7f7;
  border-radius: 10px;
  padding: 16px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.img-casa {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 8px;
}

.acciones {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 10px;
}

.acciones button {
  padding: 8px 12px;
  border: none;
  cursor: pointer;
  border-radius: 6px;
}

.acciones button:first-child {
  background-color: #f0c14b;
}

.acciones button:last-child {
  background-color: #e74c3c;
  color: white;
}


@media (max-width: 900px) {
  .grid-casas {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 600px) {
  .grid-casas {
    grid-template-columns: 1fr;
  }
}

</style>
