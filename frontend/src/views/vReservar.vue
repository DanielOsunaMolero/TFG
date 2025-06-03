<template>
  <div class="reserva-container">
    <main class="reserva-main">
      <!-- Columna izquierda: Formulario -->
      <div class="columna-izquierda">
        <form class="formulario" @submit.prevent="procesarPago">
          <h2>Información personal</h2>
          <input type="text" v-model="nombre" placeholder="Nombre" required />
          <input type="text" v-model="apellidos" placeholder="Apellidos" required />
          <input type="text" v-model="direccion" placeholder="Dirección" required />
          <input type="tel" v-model="telefono" placeholder="Teléfono" required />

          <h2>Pago</h2>
          <input type="text" v-model="tarjeta" placeholder="Nº Tarjeta" required />
          <div class="fila">
            <input type="text" v-model="vencimiento" placeholder="Fecha de Vencimiento" required />
            <input type="text" v-model="cvv" placeholder="CVV" required />
          </div>
          <input type="text" v-model="titular" placeholder="Nombre del titular" required />

          <button type="submit">Reservar</button>
        </form>
      </div>

      <!-- Columna derecha: Fotos + info casa + precio + calendario -->
      <div class="columna-derecha" v-if="casa">
        <div class="galeria-fotos">
          <img :src="imagenes[index1]" class="foto" alt="foto 1" />
          <img :src="imagenes[index2]" class="foto" alt="foto 2" />
        </div>

        <!-- Información casa y precio -->
        <section class="info-casa">
          <h2>{{ casa.titulo }}</h2>
          <label>Días:
            <input type="number" v-model.number="dias" min="1" @input="actualizarPrecio" />
          </label>
          <p class="precio">Total: {{ total }}€</p>

          <!-- Calendario funcional -->
          <div class="calendario">
  <DatePicker is-inline v-model="fechaSeleccionada" />
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
      tarjeta: '',
      vencimiento: '',
      cvv: '',
      titular: '',
      dias: 1,
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
      alert("Reserva realizada con éxito");
    },
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

.formulario input {
  display: block;
  width: 100%;
  padding: 10px;
  margin-bottom: 12px;
  border-radius: 4px;
  border: 1px solid #ccc;
}

.formulario .fila {
  display: flex;
  gap: 10px;
}

button {
  background-color: #4CAF50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

.galeria-fotos {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

.foto {
  width: 50%;
  height: 200px;
  object-fit: cover;
  border-radius: 8px;
}

.info-casa {
  background-color: #ffffff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.info-casa label,
.info-casa .precio {
  display: block;
  margin-top: 10px;
  font-size: 16px;
}

.calendario {
  margin-top: 30px;
  background-color: #ffffff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
}
.calendario .vc-container {
  width: 100%;
  max-width: 350px;
}

.calendario .vc-pane {
  min-height: 320px;
}

</style>
