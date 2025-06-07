<template>
  <div class="gestion-propietarios">
    <h1>Gestión de Propietario</h1>

    <div class="botones-superiores">
      <button class="crear-btn" @click="crearCasa">➕ Crear Nueva Casa</button>
      <button class="reservas-btn" @click="verReservas">📋 Gestión de Reservas</button>
    </div>


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
    const id_usuario = localStorage.getItem("id_usuario");
    const res = await fetch(`http://localhost/dashboard/TFG/backend/api/getCasasPropietario.php?id_propietario=${id_usuario}`);
    this.casas = await res.json();
  } catch (error) {
    console.error("Error cargando casas:", error);
  }
}
,
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
    this.cargarCasas();
  } else {
    alert(data.message || "No se pudo eliminar la casa.");
    console.error("Error del backend:", data);
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


</style>
