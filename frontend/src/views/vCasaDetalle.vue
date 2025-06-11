<template>
  <div class="detalle-casa">
    <!-- Galería de imágenes -->
    <section class="galeria">
      <div class="imagen-principal">
        <img :src="fotoSeleccionada" alt="Foto principal" class="img-principal" />
      </div>

      <div class="miniaturas">
        <div
          class="miniatura"
          v-for="(foto, i) in fotosCasa"
          :key="i"
          :style="{ backgroundImage: `url('${foto}')` }"
          @click="fotoSeleccionada = foto"
        ></div>
      </div>
    </section>

    <!-- Título -->
    <h1 class="titulo">{{ casa?.titulo || 'Sin título' }}</h1>

    <!-- Servicios -->
    <section class="servicios">
      <div>
        <h3>Servicios</h3>
        <ul>
          <li v-for="servicio in serviciosIzquierda" :key="servicio">● {{ servicio }}</li>
        </ul>
      </div>
      <div>
        <ul>
          <li v-for="servicio in serviciosDerecha" :key="servicio">● {{ servicio }}</li>
        </ul>
      </div>
    </section>

    <!-- Descripción y valoraciones -->
    <section class="info-extra">
  <div class="descripcion">
    <h3>Descripción</h3>
    <p>{{ casa?.descripcion }}</p>

    <!-- Formulario de valoración -->
    <div v-if="puedeValorar" class="form-valoracion">
      <h4>Deja tu valoración</h4>
      <textarea
        v-model="formularioValoracion.texto"
        placeholder="Escribe tu valoración"
      ></textarea>
      <div class="form-controls">
        <div class="estrellas-clicables">
          <span
            v-for="n in 5"
            :key="n"
            class="estrella"
            :class="{ activa: n <= formularioValoracion.puntuacion }"
            @click="formularioValoracion.puntuacion = n"
          >
            ★
          </span>
        </div>
        <label>
          Días de estancia:
          <input type="number" v-model="formularioValoracion.dias" min="1" />
        </label>
        <button @click="enviarValoracion">Enviar valoración</button>
      </div>
    </div>
  </div>

  <div class="valoraciones">
    <h3>Valoraciones</h3>

    <div v-if="valoraciones.length === 0">Esta casa aún no tiene valoraciones.</div>

    <div class="grid-valoraciones">
      <valoracionCard
        v-for="valoracion in valoraciones"
        :key="valoracion.id_valoracion"
        :fotoPerfil="rutaFotoPerfil(valoracion.foto_perfil)"
        :nombreUsuario="valoracion.nombre_usuario"
        :nombreCasa="valoracion.nombre_casa"
        :textoValoracion="valoracion.texto_valoracion"
        :puntuacion="Number(valoracion.puntuacion)"
        :diasEstancia="valoracion.dias_estancia"
      />
    </div>
  </div>
</section>


<div class="boton-reserva" v-if="id_usuario && tipoUsuario === 'visitante'">

  <button @click="$router.push(`/reservar/${casa.id_casa}`)">Reservar</button>

</div>

  </div>
</template>


<script>
import axios from 'axios';
import valoracionCard from '@/components/valoracionCard.vue';
import { API_BASE, IMG_BASE, IMG_PERFIL_BASE } from '@/config.js';
import { useToast } from 'vue-toastification'; 

export default {
  name: 'vCasaDetalle',
  components: { valoracionCard },
  data() {
    return {
      casa: null,
      fotoSeleccionada: null,
      valoraciones: [],
      puedeValorar: false,
      formularioValoracion: {
        texto: '',
        puntuacion: 5,
        dias: 1
      },
      id_usuario: null,
      tipoUsuario: '' // ✅ añadido
    };
  },
  computed: {
    serviciosIzquierda() {
      if (!this.casa?.servicios) return [];
      const servicios = this.casa.servicios.split(',');
      const mitad = Math.ceil(servicios.length / 2);
      return servicios.slice(0, mitad);
    },
    serviciosDerecha() {
      if (!this.casa?.servicios) return [];
      const servicios = this.casa.servicios.split(',');
      const mitad = Math.ceil(servicios.length / 2);
      return servicios.slice(mitad);
    },
    fotosCasa() {
      if (!this.casa) return [];

      const normalizar = (str) =>
        (str || '')
          .normalize('NFD')
          .replace(/[\u0300-\u036f]/g, '')
          .toLowerCase()
          .replace(/[^a-z0-9]+/g, '_')
          .replace(/^_+|_+$/g, '');

      const titulo = normalizar(this.casa.titulo);

      const fotos = [];
      for (let i = 1; i <= 6; i++) {
        const ruta = `${IMG_BASE}Foto_${titulo}(${i}).jpg`;
        fotos.push(ruta);
      }

      return fotos;
    }
  },
  mounted() {
    const id = this.$route.params.id;
    this.id_usuario = localStorage.getItem('id_usuario');
   this.tipoUsuario = (localStorage.getItem('tipo_usuario') || '').toLowerCase();


    this.cargarCasa(id);
    this.cargarValoraciones(id);
    this.verificarReserva(id);
  },
  methods: {
    async cargarCasa(id) {
      try {
        const res = await axios.get(`${API_BASE}casa.php?id=${id}`);
        this.casa = res.data;

        this.$nextTick(() => {
          const fotos = this.fotosCasa;
          if (fotos.length > 0) {
            this.fotoSeleccionada = fotos[0];
            const img = new Image();
            img.src = this.fotoSeleccionada;
            img.onload = () => console.log('Imagen cargada correctamente.');
            img.onerror = () => console.warn('Imagen NO cargada.');
          }
        });
      } catch (err) {
        console.error('Error al cargar casa:', err);
      }
    },
    async cargarValoraciones(id_casa) {
      try {
        const res = await axios.get(`${API_BASE}getValoraciones.php?id_casa=${id_casa}`);
        this.valoraciones = res.data;
      } catch (err) {
        console.error('Error al cargar valoraciones:', err);
      }
    },
    async verificarReserva(id_casa) {
      if (!this.id_usuario) return;

      try {
        const res = await axios.get(
          `${API_BASE}haReservado.php?id_usuario=${this.id_usuario}&id_casa=${id_casa}`
        );
        this.puedeValorar = res.data.haReservado;
      } catch (err) {
        console.error('Error al verificar reserva:', err);
      }
    },
    async enviarValoracion() {
      const toast = useToast(); // ✅ Inicializamos toast

      try {
        const payload = {
          id_usuario: this.id_usuario,
          id_casa: this.casa.id_casa,
          texto_valoracion: this.formularioValoracion.texto,
          puntuacion: this.formularioValoracion.puntuacion,
          dias_estancia: this.formularioValoracion.dias
        };

        const res = await axios.post(`${API_BASE}crearValoracion.php`, payload);

        console.log("Respuesta crearValoracion:", res.data); // ✅ opcional depurar

        if (res.data.success) {
          toast.success("✅ Valoración enviada correctamente.");
          this.formularioValoracion = { texto: '', puntuacion: 5, dias: 1 };
          this.cargarValoraciones(this.casa.id_casa);
        } else {
          toast.error("❌ Error al enviar valoración.");
        }
      } catch (err) {
        console.error('Error al enviar valoración:', err);
        toast.error("❌ Error de conexión al enviar valoración.");
      }
    },
    rutaFotoPerfil(fotoPerfil) {
      return fotoPerfil
        ? `${IMG_PERFIL_BASE}${fotoPerfil}`
        : `${IMG_PERFIL_BASE}default.png`;
    }
  }
};
</script>









<style scoped>
.detalle-casa {
  width: 100%;
  padding: 40px 20px;
  background-color: #e0e0e0;

  margin: 0 auto;
  box-sizing: border-box;
}

/* Galería */
.galeria {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-bottom: 20px;
  margin-right: 5%;
  margin-left: 5%;
}

.imagen-principal {
  flex: 0.5 1 300px;
  aspect-ratio: 1 / 1;
  background-color: black;
  background-size: cover !important;
  background-position: center !important;
  background-repeat: no-repeat !important;
  border-radius: 8px;
  display: block;
  /* CAMBIADO de flex a block */
  transition: background-image 0.3s ease-in-out;
}



.miniaturas {
  flex: 1 1;
  display: grid;
  grid-template-columns: repeat(3, 3fr);
  gap: 10px;
}

.miniatura {
  background-size: cover;
  background-position: center;
  border-radius: 6px;
  aspect-ratio: 1 / 0;
}


/* Título */
.titulo {
  font-size: 32px;
  margin: 20px 0;
  border-bottom: 1px solid #333;
  padding-bottom: 10px;
}

/* Servicios */
.servicios {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin: 30px 0;
}

.servicios>div {
  flex: 1 1 300px;
}

.servicios h3 {
  margin-bottom: 10px;
}

.servicios ul {
  list-style: none;
  padding: 0;
}

.servicios li {
  margin-bottom: 6px;
}

/* Info adicional */
.info-extra {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.descripcion,
.valoraciones {
  flex: 1 1 300px;
}

/* Botón */
.boton-reserva {
  margin-top: 40px;
  text-align: center;
}

.boton-reserva button {
  background-color: #444;
  color: white;
  padding: 14px 40px;
  border: none;
  border-radius: 10px;
  font-size: 16px;
  cursor: pointer;
}

/* Responsive para pantallas pequeñas */
@media (max-width: 768px) {
  .galeria {
    flex-direction: column;
  }

  .imagen-principal,
  .miniaturas {
    width: 100%;
  }

  .servicios,
  .info-extra {
    flex-direction: column;
  }

  .servicios {
    margin: 0px 0; /* <<< esto quita el hueco */
    gap: 0px;
  }

  .titulo {
    font-size: 26px;
  }

  .miniaturas {
    grid-template-columns: repeat(6, 0.fr);
    aspect-ratio: 1 / 0.5;
  }

  .servicios > div,
  .descripcion,
  .valoraciones {
    flex: none;
    width: 100%;
  }
}



.imagen-principal {
  border: solid black 1px;
}

.miniatura {
  border: solid black 1px;

}

.img-principal {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 8px;
}

.miniatura:hover {
  transform: scale(1.05);
  border-color: #0e0e0e;
}

.form-valoracion {
  margin-top: 20px;
  padding: 16px;
  background-color: #ffffff;
  border: 1px solid #ccc;
  border-radius: 10px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.form-valoracion h4 {
  margin-bottom: 12px;
}

.form-valoracion textarea {
  width: 100%;
  height: 80px;
  padding: 8px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 6px;
  resize: vertical;
  margin-bottom: 10px;
}

.form-valoracion label {
  display: block;
  margin-bottom: 8px;
  font-size: 14px;
  color: #333;
}

.form-valoracion select,
.form-valoracion input[type='number'] {
  padding: 6px;
  font-size: 14px;
  border: 1px solid #ccc;
  border-radius: 6px;
  margin-right: 10px;
  width: 80px;
}

.form-valoracion button {
  background-color: #444;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.form-valoracion button:hover {
  background-color: #222;
}

.estrellas-clicables {
  display: inline-flex;
  font-size: 24px;
  cursor: pointer;
  color: #ccc;
  flex-direction: row; /* Asegura dirección normal */
}

.estrellas-clicables .estrella {
  display: inline-block;
  transition: color 0.2s ease;
}

.estrellas-clicables .estrella:hover,
.estrellas-clicables .estrella:hover ~ .estrella {
  color: #ccc; /* desmarcar las de la derecha */
}

.estrellas-clicables .estrella:hover,
.estrellas-clicables .estrella:nth-child(-n + var(--hovered)) {
  color: #ffcc00;
}

.estrellas-clicables .estrella.activa {
  color: #ffcc00;
}

.valoraciones {
  flex: 1 1 300px;
  
}

.grid-valoraciones {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 12px;
  margin-top: 7%;
}
</style>
