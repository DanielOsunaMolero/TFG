<template>
  <div class="reserva-container">
    <main class="reserva-main">
      <!-- Columna izquierda: Formulario -->
      <div class="columna-izquierda">
        <form class="formulario" @submit.prevent="procesarPago">
          <fieldset>
            <legend>Información personal</legend>

            <label>
              <input type="text" v-model="nombre" minlength="2" required placeholder="Nombre" />
            </label>

            <label>
              <input type="text" v-model="apellidos" minlength="2" required placeholder="Apellidos" />
            </label>

            <label>
              <input type="text" v-model="direccion" required placeholder="Dirección" />
            </label>

            <label>
              <input type="tel" v-model="telefono" pattern="^[0-9]{9}$" required placeholder="Teléfono (9 dígitos)" />
            </label>

            <label>
              <input type="email" v-model="email" required placeholder="Email" />
            </label>
          </fieldset>

          <fieldset>
            <legend>Datos de pago</legend>

            <label>
              <input type="text" v-model="tarjeta" pattern="^[0-9]{16}$" required
                placeholder="Nº Tarjeta (16 dígitos)" />
            </label>

            <div class="fila">
              <label>
                <input type="text" v-model="vencimiento" pattern="^(0[1-9]|1[0-2])\/\d{2}$" required
                  placeholder="Vencimiento (MM/AA)" />
              </label>

              <label>
                <input type="text" v-model="cvv" pattern="^[0-9]{3}$" required placeholder="CVV (3 dígitos)" />
              </label>
            </div>

            <label>
              <input type="text" v-model="titular" minlength="5" required placeholder="Nombre del titular" />
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
          </div>

          <div class="calendarios-flex">
            <div class="calendario-wrapper">
              <h3>Selecciona el día de inicio</h3>
              <DatePicker is-inline v-model="fecha_inicio" @update:model-value="calcularDias" :min-date="hoy" />

            </div>

            <div class="calendario-wrapper">
              <h3>Selecciona el día de fin</h3>
              <DatePicker is-inline v-model="fecha_fin" @update:model-value="calcularDias" :min-date="fecha_inicio" />
            </div>
          </div>


          <p class="precio-total">Total: <strong>{{ total }}€</strong></p>


        </section>
      </div>


    </main>
  </div>
</template>


<script>
import { DatePicker } from 'v-calendar';
import { API_BASE, IMG_BASE } from '@/config.js';
import { useToast } from 'vue-toastification';

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
      email: '',
      tarjeta: '',
      vencimiento: '',
      fecha_inicio: null,
      fecha_fin: null,
      cvv: '',
      titular: '',
      dias: 0, // ahora son noches!
      total: 0,
      casa: null,
      imagenes: [],
      index1: 0,
      index2: 1,
      intervalo: null,
      fechaSeleccionada: null,
      hoy: new Date().toISOString().split('T')[0],
      usuarioLogueado: false
    };
  },
  mounted() {
    const id = this.$route.params.id;

    this.usuarioLogueado = !!localStorage.getItem('id_usuario');

    fetch(`${API_BASE}casa.php?id=${id}`)
      .then(res => res.json())
      .then(data => {
        this.casa = data;
        const base = this.normalizar(this.casa.titulo);
        this.imagenes = Array.from({ length: 6 }, (_, i) => `${IMG_BASE}Foto_${base}(${i + 1}).jpg`);
        this.actualizarPrecio();
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
      const toast = useToast();
      const id_usuario = localStorage.getItem('id_usuario');

      // ✅ Comprobación de login
      if (!id_usuario) {
        toast.warning("⚠️ Debes iniciar sesión para poder reservar.");
        return;
      }

      // ✅ Comprobación de fechas seleccionadas
      if (!this.fecha_inicio || !this.fecha_fin) {
        toast.error("❌ Debes seleccionar la fecha de inicio y la fecha de fin.");
        return;
      }

      // ✅ Comprobación de que no sean iguales
      const inicio = new Date(this.fecha_inicio);
      const fin = new Date(this.fecha_fin);
      if (inicio.getTime() === fin.getTime()) {
        toast.error("❌ La fecha de inicio y la de fin no pueden ser iguales.");
        return;
      }

      // ✅ Comprobación de que haya al menos 1 noche
      if (this.dias <= 0) {
        toast.error("❌ Debes seleccionar un rango de fechas válido (al menos 1 noche).");
        return;
      }

      // ✅ Formatear fechas correctamente (evita problemas con timezone)
      const fecha_inicio = this.formatDate(this.fecha_inicio);
      const fecha_fin = this.formatDate(this.fecha_fin);

      const reserva = {
        id_usuario: parseInt(id_usuario),
        id_casa: this.casa.id_casa,
        fecha_inicio,
        fecha_fin,
        importe_total: this.total
      };

      fetch(`${API_BASE}reserva.php`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(reserva)
      })
        .then(res => res.json())
        .then(data => {
          if (data.status === "ok") {
            toast.success("✅ Reserva enviada. El propietario la revisará.");
            this.$router.push('/');
          } else {
            toast.error(`❌ Error al reservar: ${data.error}`);
          }
        })
        .catch(err => {
          console.error("Error al reservar:", err);
          toast.error("❌ Error de conexión al reservar.");
        });
    },

    avisoNoLogin() {
      const toast = useToast();
      toast.warning("⚠️ Debes iniciar sesión para poder reservar.");
    },

    actualizarPrecio() {
      this.total = (this.casa?.precio_noche || 0) * this.dias;
    },

    normalizar(str) {
      return (str || '')
        .normalize("NFD")
        .replace(/\p{Diacritic}/gu, '')
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '_');
    },

    calcularDias() {
      if (this.fecha_inicio && this.fecha_fin) {
        const inicio = new Date(this.fecha_inicio);
        const fin = new Date(this.fecha_fin);

        // ✅ cálculo correcto de noches
        const diferencia = Math.ceil((fin - inicio) / (1000 * 60 * 60 * 24)); //redondea hacia arriba para incluir el último día y que cuente como noche

        this.dias = diferencia > 0 ? diferencia : 0;
      } else {
        this.dias = 0;
      }

      this.actualizarPrecio();
    },

    formatDate(date) { // Formatea la fecha a YYYY-MM-DD
      const d = new Date(date);
      const year = d.getFullYear();
      const month = ('0' + (d.getMonth() + 1)).slice(-2);
      const day = ('0' + d.getDate()).slice(-2);
      return `${year}-${month}-${day}`;
    }
  },

  watch: {
    fecha_inicio(newInicio) { // Al cambiar la fecha de inicio, actualizamos la fecha de fin si es necesario
      if (!newInicio) return; // Si no hay fecha de inicio, no hacemos nada
      if (this.fecha_fin && new Date(this.fecha_fin) < new Date(newInicio)) {
        this.fecha_fin = newInicio;
      }
      this.calcularDias();
    },
    fecha_fin() {
      this.calcularDias();
    }
  }
};
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
  border: 1px solid #ffffff;
  border-radius: 6px;
}

.precio-total {
  font-size: 18px;
  font-weight: bold;
  color: #2c3e50;
}

.calendario-wrapper {
  background-color: #ffffff;
  padding: 16px;
  border-radius: 10px;
}

.calendario-wrapper h3 {
  margin-bottom: 10px;
  font-size: 16px;
  color: #333;
}

.calendarios-flex {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;

}

.calendario-wrapper {
  flex: 1;
}


@media (min-width: 1025px) and (max-width: 1400px) {
  .galeria {
    flex-direction: column;
    flex-wrap: wrap;
    gap: 12px;
  }

  .foto-principal,
  .foto-secundaria {
    width: 100%;
    height: auto;
    object-fit: cover;
  }

  .calendarios-flex {
    flex-direction: column;
    gap: 12px;
  }

  .calendario-wrapper {
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
  }
}

@media (max-width: 1024px) {
  .galeria {
    flex-direction: column;
    flex-wrap: wrap;
    gap: 10px;
  }

  .foto-principal,
  .foto-secundaria {
    width: 100%;
    height: auto;
    object-fit: cover;
  }

  .tarjeta-casa {
    width: 100%;
    padding: 16px;
  }

  .calendarios-flex {
    flex-direction: column;
    gap: 12px;
  }

  .calendario-wrapper {
    width: 100%;
    max-width: 380px;
    margin: 0 auto;
  }

  .precio-total {
    text-align: center;
  }
}

@media (max-width: 768px) {
  .reserva-main {
    flex-direction: column;
    gap: 20px;
  }

  .tarjeta-casa {

    padding: 16px;
  }

  .calendarios-flex {
    flex-direction: column;
    gap: 12px;
    align-items: center;
  }

  .calendario-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
    max-width: 360px;
    margin: 0 auto;
  }

  .precio-total {
    text-align: center;
  }
}

@media (max-width: 480px) {

  .reserva-container {
    padding: 10px;
  }

  .reserva-main {
    flex-direction: column;
    gap: 15px;
    padding: 0;
    justify-content: flex-start;
    /* corregido */
  }

  .columna-izquierda,
  .columna-derecha {
    flex: 1 1 100%;
    min-width: 100%;
    width: 100%;
    /* importante */
    padding: 0;
  }

  .formulario {
    padding: 10px 8px;
    /* más estrecho */
    border-radius: 10px;
    width: 90%;
  }

  .formulario input {
    padding: 10px;
    font-size: 15px;
    width: 90%;
  }

  button {
    width: 100%;
    padding: 14px;
    font-size: 17px;
  }

  .tarjeta-casa {
    width: 87%;
    padding: 12px;
  }

  .galeria {
    flex-direction: column;
    gap: 4px;
    padding: 0;
    margin: 0;
  }


  .foto-principal,
  .foto-secundaria {
    width: 100%;
    height: auto;
  }

  .calendarios-flex {
    flex-direction: column;
    gap: 8px;
    align-items: center;
  }

  .calendario-wrapper {
    width: 100%;
    max-width: 100%;
    padding: 10px;
  }

  .calendario-wrapper h3 {
    font-size: 15px;
    text-align: center;
  }

  .info-reserva h2 {
    font-size: 18px;
    text-align: center;
    word-wrap: break-word;
  }

  .precio-total {
    font-size: 16px;
    text-align: center;
    margin-top: 10px;
  }
}
</style>
