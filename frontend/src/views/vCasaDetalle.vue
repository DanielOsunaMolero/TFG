<template>
  <div class="detalle-casa">
    <!-- Galería de imágenes -->
    <section class="galeria">
      <div class="imagen-principal">
        <img :src="fotoSeleccionada" alt="Foto principal" class="img-principal" />
      </div>


      <div class="miniaturas">
        <div class="miniatura" v-for="(foto, i) in fotosCasa" :key="i" :style="{ backgroundImage: `url('${foto}')` }" @click="fotoSeleccionada = foto"></div>
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
        <h3>&nbsp;</h3>
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
      </div>
      <div class="valoraciones">
        <h3>Valoraciones</h3>
        <div class="bloque-valoracion">[Próximamente]</div>
      </div>
    </section>

    <!-- Botón de acción -->
    <div class="boton-reserva">
      <button>Reservar</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'vCasaDetalle',
  data() {
    return {
      casa: null,
      fotoSeleccionada: null
    }
  },
  computed: {
    serviciosIzquierda() {
      if (!this.casa?.servicios) return []
      const servicios = this.casa.servicios.split(',')
      const mitad = Math.ceil(servicios.length / 2)
      return servicios.slice(0, mitad)
    },
    serviciosDerecha() {
      if (!this.casa?.servicios) return []
      const servicios = this.casa.servicios.split(',')
      const mitad = Math.ceil(servicios.length / 2)
      return servicios.slice(mitad)
    },
    fotosCasa() {
      if (!this.casa) return []

      const normalizar = (str) =>
        (str || '')
          .normalize("NFD")
          .replace(/[\u0300-\u036f]/g, "")
          .toLowerCase()
          .replace(/[^a-z0-9]+/g, "_")
          .replace(/^_+|_+$/g, "")

      const titulo = normalizar(this.casa.titulo)
      console.log("Título normalizado:", titulo)

      const fotos = []
      for (let i = 1; i <= 6; i++) {
        const ruta = `${window.location.origin}/fotos/Foto_${titulo}(${i}).jpg`
        console.log("Ruta generada:", ruta)
        fotos.push(ruta)
      }

      return fotos
    }
  },
  mounted() {
    const id = this.$route.params.id
    axios
      .get(`http://localhost/dashboard/TFG/backend/api/casa.php?id=${id}`)
      .then(res => {
        this.casa = res.data
         console.log("Casa cargada:", this.casa) // ⬅️ Asegúrate de que llega bien el título
        this.$nextTick(() => {
          const fotos = this.fotosCasa
          console.log("Rutas generadas:", fotos)

          if (fotos.length > 0) {
            this.fotoSeleccionada = fotos[0]
            const img = new Image()
            img.src = this.fotoSeleccionada
            img.onload = () => console.log("Imagen cargada correctamente.")
            img.onerror = () => console.warn("Imagen NO cargada.")
          }
        })
      })
      .catch(err => {
        console.error('Error al cargar casa:', err)
      })
  },
  methods: {
    verificarImagen(url) {
      return new Promise((resolve) => {
        const img = new Image()
        img.onload = () => resolve(true)
        img.onerror = () => resolve(false)
        img.src = url
      })
    }
  }
}
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

.bloque-valoracion {
  height: 150px;
  background-color: #aaa;
  border-radius: 8px;
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

  .titulo {
    font-size: 26px;
  }

  .miniaturas {
    grid-template-columns: repeat(6, 0.fr);
    aspect-ratio: 1 / 0.5;
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
</style>
