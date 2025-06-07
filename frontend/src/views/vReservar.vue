<template>
  <div class="reserva-container">
    <main class="reserva-main">
      <!-- Columna izquierda: Formulario -->
      <div class="columna-izquierda">
        <form class="formulario" @submit.prevent="procesarPago">
          <fieldset>
            <legend>Información personal</legend>

            <label>Nombre
              <input type="text" v-model="nombre" minlength="2" />
            </label>

            <label>Apellidos
              <input type="text" v-model="apellidos" minlength="2" />
            </label>

            <label>Dirección
              <input type="text" v-model="direccion" />
            </label>

            <label>Teléfono
              <input type="tel" v-model="telefono" pattern="^[0-9]{9}$" />
            </label>

            <label>Email
              <input type="email" v-model="email" />
            </label>
          </fieldset>

          <fieldset>
            <legend>Datos de pago</legend>

            <label>Nº Tarjeta
              <input type="text" v-model="tarjeta" />
              <!-- pattern="^[0-9]{16}$"  -->
            </label>

            <div class="fila">
              <label>Vencimiento
                <input type="text" v-model="vencimiento" placeholder="MM/AA" />
                <!-- pattern="^(0[1-9]|1[0-2])\/\d{2}$ -->
              </label>

              <label>CVV
                <input type="text" v-model="cvv" />
                <!-- pattern="^[0-9]{3}$ -->
              </label>
            </div>

            <label>Nombre del titular
              <input type="text" v-model="titular" minlength="5" />
            </label>
          </fieldset>

          <button type="submit">Reservar</button>
        </form>
      </div>

      <!-- Columna derecha: Fotos + info casa + precio + calendario -->
      <div class="columna-derecha" v-if="casa">
        <section class="tarjeta-casa">
          <div class="galeria">
            <img :src="imagenes[index1]" class="foto-principal" alt="foto 1" />
            <img :src="imagenes[index2]" class="foto-secundaria" alt="foto 2" />
          </div>

          <div class="info-reserva">
            <h2>{{ casa.titulo }}</h2>
            <label>Días de estancia:
              <input type="number" v-model.number="dias" min="1" @input="actualizarPrecio" />
            </label>
            <p class="precio-total">Total: <strong>{{ total }}€</strong></p>
          </div>

          <div class="calendario-wrapper">
  <h3>Selecciona el día de inicio</h3>
  <DatePicker is-inline v-model="fecha_inicio" />
</div>

        </section>
      </div>


    </main>
  </div>
</template>



<script>
import { DatePicker } from 'v-calendar' // ✅ Importar componente

export default {
  components: {
    DatePicker
  },
  data() {
    return {
      nombre: '',
      apellidos: '',
      direccion: '',
      telefono: '',
      email: '', // Validación de email
      tarjeta: '',
      vencimiento: '',
      fecha_inicio: null, // Fecha de inicio seleccionada
      cvv: '',
      titular: '',
      dias: 1,  // Días de estancia
      total: 0,
      casa: null,
      imagenes: [],
      index1: 0,
      index2: 1,
      intervalo: null,
      fechaSeleccionada: null
    };
  },
  mounted() {
    const id = this.$route.params.id;
    fetch(`http://localhost/dashboard/TFG/backend/api/casa.php?id=${id}`)
      .then(res => res.json())
      .then(data => {
        this.casa = data;
        const base = this.normalizar(this.casa.titulo);
        this.imagenes = Array.from({ length: 6 }, (_, i) => `${window.location.origin}/fotos/Foto_${base}(${i + 1}).jpg`);
      });

    this.intervalo = setInterval(() => {
      this.index1 = (this.index1 + 1) % this.imagenes.length;
      this.index2 = (this.index1 + 1) % this.imagenes.length;
    }, 10000);
  },
  beforeUnmount() {
    clearInterval(this.intervalo);
  },
  methods: {
    procesarPago() {
      const id_usuario = localStorage.getItem('id_usuario');
      const fecha_inicio = this.fecha_inicio;
      const fecha_fin = new Date(new Date(fecha_inicio).getTime() + (this.dias - 1) * 24 * 60 * 60 * 1000)
        .toISOString().split('T')[0];

      const reserva = {
        id_usuario: parseInt(id_usuario),
        id_casa: this.casa.id_casa,
        fecha_inicio,
        fecha_fin,
        importe_total: this.total
      };

      fetch('http://localhost/dashboard/TFG/backend/api/reserva.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(reserva)
      })
        .then(res => res.json())
        .then(data => {
          if (data.status === "ok") {
            alert("Reserva enviada. El propietario la revisará.");
            this.$router.push('/');
          } else {
            alert("Error al reservar: " + data.error);
          }
        });
    }
    ,
    actualizarPrecio() {
      this.total = (this.casa?.precio_noche || 0) * this.dias;
    },
    normalizar(str) {
      return (str || '').normalize("NFD").replace(/\p{Diacritic}/gu, '').toLowerCase().replace(/[^a-z0-9]+/g, '_');
    }
  }
}
</script>


<style scoped>
.reserva-container {
  padding: 20px;
  background-color: #f5f5f5;
  min-height: 100vh;
}

.reserva-main {
  display: flex;
  flex-wrap: wrap;
  gap: 40px;
  justify-content: space-between;
}

.columna-izquierda,
.columna-derecha {
  flex: 1;
  min-width: 300px;
}



.formulario .fila {
  display: flex;
  gap: 10px;
}



.foto {
  width: 50%;
  height: 200px;
  object-fit: cover;
  border-radius: 8px;
}


.formulario {
  background-color: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.formulario fieldset {
  border: none;
  margin-bottom: 20px;
}

.formulario legend {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 10px;
  color: #2c3e50;
}

.formulario label {
  display: block;
  margin-bottom: 12px;
  font-weight: 500;
  color: #333;
}

.formulario input {
  width: 100%;
  padding: 10px;
  margin-bottom: 12px;
  border-radius: 6px;
  border: 1px solid #ccc;
  transition: box-shadow 0.2s ease;
}


.formulario input:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(72, 187, 120, 0.3);
}

button {
  background-color: #27ae60;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

button:hover {
  background-color: #219150;
}


.tarjeta-casa {
  background-color: #ffffff;
  padding: 24px;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.galeria {
  display: flex;
  gap: 12px;
}

.foto-principal,
.foto-secundaria {
  flex: 1;
  height: 220px;
  object-fit: cover;
  border-radius: 8px;
}

.info-reserva {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.info-reserva input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 6px;
}

.precio-total {
  font-size: 18px;
  font-weight: bold;
  color: #2c3e50;
}

.calendario-wrapper {
  background-color: #f9f9f9;
  padding: 16px;
  border-radius: 10px;
}

.calendario-wrapper h3 {
  margin-bottom: 10px;
  font-size: 16px;
  color: #333;
}
</style>
