<template>
  <div class="gestion-propietarios">
    <h1>Gestión de Propietario</h1>

    <div class="botones-superiores">
      <button class="crear-btn" @click="crearCasa">
        <font-awesome-icon :icon="['fas', 'plus']" style="margin-right: 6px;" />
        Crear Nueva Casa
      </button>
      <button class="reservas-btn" @click="verReservas">
        <font-awesome-icon :icon="['fas', 'clipboard-list']" style="margin-right: 6px;" />
        Gestión de Reservas
      </button>
    </div>

    <div class="grid-casas">
      <div v-for="casa in casas" :key="casa.id" class="card-casa">
        <img v-if="casa.imagen" :src="casa.imagen" alt="Imagen casa" class="img-casa">

        <h3>{{ casa.titulo }}</h3>
        <div class="acciones">
          <button @click="editarCasa(casa.id)">
            <font-awesome-icon :icon="['fas', 'pen']" style="margin-right: 6px;" />
            Editar
          </button>
          <button @click="eliminarCasa(casa.id)">
            <font-awesome-icon :icon="['fas', 'trash']" style="margin-right: 6px;" />
            Eliminar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import { API_BASE, IMG_BASE } from '@/config.js';
import { useToast } from 'vue-toastification';

export default {
  data() {
    return {
      casas: []
    };
  },
  methods: {
    async cargarCasas() {
      try {
        const id_usuario = localStorage.getItem("id_usuario");
        const res = await fetch(`${API_BASE}getCasasPropietario.php?id_propietario=${id_usuario}`);
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
    },
    verReservas() {
      this.$router.push("/gestion-reservas");
    },
    eliminarCasa(id) {
      const toast = useToast();

      if (confirm("¿Estás seguro de que quieres eliminar esta casa?")) {
        const formData = new FormData();
        formData.append("id", id);

        fetch(`${API_BASE}eliminarCasa.php`, {
          method: "POST",
          body: formData
        })
          .then(res => res.json())
          .then(data => {
            console.log("Respuesta eliminarCasa:", data);
            if (data.success) {
              this.cargarCasas();
              toast.success("✅ Casa eliminada correctamente.");
            } else {
              toast.error(data.message || "❌ No se pudo eliminar la casa.");
              console.error("Error del backend:", data);
            }
          })
          .catch(err => {
            console.error("Error al eliminar:", err);
            toast.error("❌ Error al eliminar la casa.");
          });
      }
    },
    getFotoCasaUrl(casa) {
      if (casa.imagenes && casa.imagenes.length > 0) {
        return `${IMG_BASE}${casa.imagenes[0]}`; // Primera imagen
      } else {
        return `${IMG_BASE}default_casa.jpg`; // Foto por defecto
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
  padding: 24px;
  max-width: 1300px;
  margin: auto;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

h1 {
  font-size: 2rem;
  margin-bottom: 24px;
  font-weight: 700;
  text-align: center;
  color: #2c3e50;
}

.botones-superiores {
  display: flex;
  justify-content: center;
  gap: 16px;
  margin-bottom: 32px;
}

.crear-btn,
.reservas-btn {
  padding: 12px 24px;
  font-size: 16px;
  font-weight: bold;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: all 0.25s ease;
}

.crear-btn {
  background-color: #34c38f;
  color: white;
}

.crear-btn:hover {
  background-color: #2eb27d;
}

.reservas-btn {
  background-color: #4a90e2;
  color: white;
}

.reservas-btn:hover {
  background-color: #3f7fcc;
}

.grid-casas {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
}




.card-casa {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
  transition: transform 0.2s ease;
}

.card-casa:hover {
  transform: translateY(-4px);
}

.img-casa {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.card-casa h3 {
  font-size: 1.2rem;
  font-weight: 600;
  margin: 16px 0 0;
  text-align: center;
  color: #333;
}

.acciones {
  display: flex;
  justify-content: center;
  gap: 12px;
  padding: 16px;
}

.acciones button {
  font-size: 14px;
  font-weight: 600;
  border: none;
  padding: 8px 14px;
  border-radius: 8px;
  cursor: pointer;
  transition: background 0.2s ease;
  display: flex;
  align-items: center;
  gap: 6px;
}

.acciones button:first-child {
  background-color: #f9c74f;
  color: #333;
}

.acciones button:first-child:hover {
  background-color: #f1bb2c;
}

.acciones button:last-child {
  background-color: #f44336;
  color: white;
}

.acciones button:last-child:hover {
  background-color: #d93025;
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

@media (max-width: 480px) {
  .gestion-propietarios {
    width: 90%;
  }
}

</style>
