<template>
  <div class="gestion-reservas">
    <h1>Reservas recibidas</h1>

    <div v-if="reservas.length" class="reserva-lista">
      <div class="reserva-card" v-for="r in reservas" :key="r.id_reserva">
        <img :src="getFotoPerfilUrl(r.foto_perfil)" alt="Foto" class="foto-reserva" />

        <div class="info">
          <p><strong>{{ r.nombre_usuario }}</strong></p>
          <p>
            <font-awesome-icon :icon="['fas', 'home']" style="margin-right: 6px;" />
            {{ r.titulo_casa }}
          </p>
          <p>
            <font-awesome-icon :icon="['fas', 'calendar-alt']" style="margin-right: 6px;" />
            {{ r.fecha_inicio }} → {{ r.fecha_fin }} ({{ calcularDias(r.fecha_inicio, r.fecha_fin) }} días)
          </p>
          <p>
            <font-awesome-icon :icon="['fas', 'thumbtack']" style="margin-right: 6px;" />
            Estado:
            <span :class="'estado ' + r.estado">{{ r.estado }}</span>
          </p>
        </div>

        <div class="acciones" v-if="r.estado === 'pendiente'">
          <button @click="cambiarEstado(r.id_reserva, 'confirmada')">
            <font-awesome-icon :icon="['fas', 'check']" />
          </button>
          <button @click="cambiarEstado(r.id_reserva, 'cancelada')">
            <font-awesome-icon :icon="['fas', 'times']" />
          </button>
        </div>
      </div>
    </div>

    <p v-else>No hay reservas.</p>
  </div>
</template>

<script>
import { API_BASE, IMG_PERFIL_BASE } from '@/config.js';
import { useToast } from 'vue-toastification';

export default {
  data() {
    return {
      reservas: []
    };
  },
  mounted() {
    const id_propietario = localStorage.getItem('id_usuario');
    fetch(`${API_BASE}reserva.php?id_propietario=${id_propietario}`)
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
      const toast = useToast();

      fetch(`${API_BASE}actualizarEstadoReserva.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          id_reserva: id,
          estado: nuevoEstado
        })
      })
        .then(res => res.json())
        .then(data => {
          console.log("Respuesta actualizarEstadoReserva:", data);
          if (data.success) {
            if (nuevoEstado === 'cancelada') {
              this.reservas = this.reservas.filter(r => r.id_reserva !== id);
              toast.success("✅ Reserva cancelada correctamente.");
            } else {
              this.reservas = this.reservas.map(r =>
                r.id_reserva === id ? { ...r, estado: nuevoEstado } : r
              );
              toast.success("✅ Reserva confirmada correctamente.");
            }
          } else {
            toast.error(data.message || "❌ Error al actualizar la reserva.");
          }
        })
        .catch(err => {
          console.error("Error al actualizar reserva:", err);
          toast.error("❌ Error de conexión al actualizar reserva.");
        });
    },

    getFotoPerfilUrl(nombre) {
      return nombre
        ? `${IMG_PERFIL_BASE}${nombre}`
        : `${IMG_PERFIL_BASE}default.jpg`;
    }
  }
};
</script>





<style scoped>
.gestion-reservas {
  padding: 30px 20px;
  background-color: #f5f5f5;
  min-height: 100vh;
}

.gestion-reservas h1 {
  text-align: center;
  margin-bottom: 30px;
  color: #333;
  font-family: 'Poppins', sans-serif;
  font-weight: 600;
}

.reserva-lista {
  display: flex;
  flex-direction: column;
  gap: 20px;
  justify-content: center;
  align-items: center;
}

.reserva-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  padding: 20px;
  gap: 20px;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  width: 90%;
  max-width: 700px;
  border: none;
}

.reserva-card:hover {
  transform: scale(1.01);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
}

.foto-reserva {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
  flex-shrink: 0;
  border: 2px solid #60e29b;
}

.info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 6px;
  color: #444;
  font-size: 15px;
}

.info p {
  margin: 0;
}

.estado {
  display: inline-block;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 13px;
  font-weight: bold;
  text-transform: capitalize;
  color: white;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.estado.pendiente {
  background-color: #f39c12;
}

.estado.confirmada {
  background-color: #27ae60;
}

.estado.cancelada {
  background-color: #e74c3c;
}

.acciones {
  display: flex;
  gap: 10px;
}

.acciones button {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: none;
  font-size: 18px;
  cursor: pointer;
  transition: transform 0.2s ease, background-color 0.2s ease;
}

.acciones button:first-child {
  background-color: #60e29b;
  color: white;
}

.acciones button:last-child {
  background-color: #e74c3c;
  color: white;
}

.acciones button:hover {
  transform: scale(1.2);
  opacity: 0.9;
}

p {
  text-align: center;
  color: #666;
  margin-top: 20px;
}

@media (max-width: 768px) {
  .reserva-card {
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 16px;
  }

  .info {
    align-items: center;
  }

  .acciones {
    justify-content: center;
    width: 100%;
  }
}

@media (max-width: 480px) {
  .gestion-reservas {
    width: 90%;
  }
}
</style>
