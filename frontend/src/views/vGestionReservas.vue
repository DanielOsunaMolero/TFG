<template>
  <div class="gestion-reservas">
    <h1>Reservas recibidas</h1>

    <div v-if="reservas.length" class="reserva-lista">
      <div class="reserva-card" v-for="r in reservas" :key="r.id_reserva">
        <img :src="'http://localhost:8080/fotos_perfil/' + r.foto_perfil" alt="Foto" class="foto-reserva" />


        <div class="info">
          <p><strong>{{ r.nombre_usuario }}</strong></p>
          <p>🏠 {{ r.titulo_casa }}</p>
          <p>📅 {{ r.fecha_inicio }} - {{ calcularDias(r.fecha_inicio, r.fecha_fin) }} días</p>
          <p>📌 Estado: <span :class="'estado ' + r.estado">{{ r.estado }}</span></p>
        </div>

        <div class="acciones" v-if="r.estado === 'pendiente'">
          <button @click="cambiarEstado(r.id_reserva, 'confirmada')">✅</button>
          <button @click="cambiarEstado(r.id_reserva, 'cancelada')">❌</button>
        </div>
      </div>
    </div>

    <p v-else>No hay reservas.</p>
  </div>
</template>


<script>
export default {
  data() {
    return {
      reservas: []
    };
  },
  mounted() {
    const id_propietario = localStorage.getItem('id_usuario');
    fetch(`http://localhost/dashboard/TFG/backend/api/reserva.php?id_propietario=${id_propietario}`)
      .then(res => res.json())
      .then(data => this.reservas = data);
  },
  methods: {
  calcularDias(inicio, fin) {
    const d1 = new Date(inicio);
    const d2 = new Date(fin);
    return Math.round((d2 - d1) / (1000 * 60 * 60 * 24)) + 1;
  },

  cambiarEstado(id, nuevoEstado) {
    if (nuevoEstado === 'cancelada') {
      // Lógica nueva: eliminar la reserva
      fetch('http://localhost/dashboard/TFG/backend/api/cancelarReserva.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_reserva: id })
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          // Eliminar del array de reservas
          this.reservas = this.reservas.filter(r => r.id_reserva !== id);
          alert("Reserva cancelada correctamente.");
        } else {
          alert(data.message || "Error al cancelar la reserva.");
        }
      })
      .catch(err => {
        console.error("Error al cancelar:", err);
        alert("Error de conexión.");
      });

    } else {
      // Confirmar reserva
      fetch('http://localhost/dashboard/TFG/backend/api/reserva.php', {
        method: 'PUT',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_reserva=${id}&estado=${nuevoEstado}`
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === "ok") {
          this.reservas = this.reservas.map(r =>
            r.id_reserva === id ? { ...r, estado: nuevoEstado } : r
          );
        }
      });
    }
  },
  getFotoPerfilUrl(nombre) {
    return nombre
      ? `http://localhost:8080/fotos_perfil/${nombre}`
      : 'https://via.placeholder.com/60x60?text=👤';
  }
}

}
</script>

<style scoped>
.gestion-reservas {
  padding: 20px;
  
}

.reserva-lista {
  display: flex;
  flex-direction: column;
  gap: 16px;
  justify-content: center;
  align-items: center;
}

.reserva-card {
  display: flex;
  align-items: center;
  justify-content: center;
  background: #d7eeb2;
  border-radius: 12px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 16px;
  gap: 20px;
  transition: transform 0.2s ease;
  width: 60%;
  border: solid black 1px;
}

.reserva-card:hover {
  transform: scale(1.01);
}

.foto-usuario {
  width: 60px;
  height: 60px;
  border-radius: 100px;
  object-fit: cover;
  border: 2px solid #ccc;
}

.info {
  flex: 1;
}

.estado {
  font-weight: bold;
  text-transform: capitalize;
}

.estado.pendiente {
  color: orange;
}
.estado.confirmada {
  color: green;
}
.estado.cancelada {
  color: red;
}

.acciones button {
  background: none;
  font-size: 24px;
  border: none;
  cursor: pointer;
  margin: 0 5px;
  transition: transform 0.1s ease;
}
.acciones button:hover {
  transform: scale(1.2);
}

.tarjeta-reserva {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #f9f9f9;
  border: 1px solid #ddd;
  padding: 16px;
  margin-bottom: 12px;
  border-radius: 12px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.foto-reserva {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  margin-right: 16px;
  flex-shrink: 0;
}

.info-reserva {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.estado {
  font-weight: bold;
  text-transform: capitalize;
}

.botones-acciones {
  display: flex;
  gap: 10px;
}

.boton-icono {
  font-size: 20px;
  background: none;
  border: none;
  cursor: pointer;
  transition: transform 0.2s;
}

.boton-icono:hover {
  transform: scale(1.2);
}

.boton-icono.confirmar {
  color: green;
}

.boton-icono.cancelar {
  color: red;
}
h1{
  text-align: center;
  margin-bottom: 20px;
  font-style: italic;
}

</style>

