<template>
  <div class="inicio-container">
    <!-- Buscador -->
    <section class="buscador">
      <div class="filtros"></div>
      <input type="text" v-model="busqueda" 
      @keyup.enter="filtrarCasas"
       placeholder="Buscar casas..."
      class="input-busqueda" />
      <button class="boton-buscar" @click="filtrarCasas">🔍</button>
    </section>

    <!-- Cuadrícula -->
    <section class="galeria">
  <div
    v-if="casasFiltradas.length === 0"
    class="mensaje-no-resultados"
  >
    No se ha encontrado ninguna casa que coincida con la búsqueda.
  </div>

  <div
    v-for="casa in casasFiltradas"
    :key="casa.id_casa"
    class="tarjeta"
    @click="$router.push(`/casa/${casa.id_casa}`)"
  >
    <div class="imagen-con-transicion">
      <div
        class="imagen"
        :style="{ backgroundImage: `url('${getImagen(casa.titulo)}')` }"
      ></div>
      <div class="titulo-flotante">{{ casa.titulo }}</div>
    </div>
  </div>
</section>

  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'vInicio',
  data() {
    return {
      casas: [],
      casasFiltradas: [],
      busqueda: '',
      imagenIndex: 0
    }
  },
  methods: {
    getImagen(titulo) {
      const normalizar = (str) =>
        (str || '')
          .normalize("NFD")
          .replace(/[\u0300-\u036f]/g, "")
          .toLowerCase()
          .replace(/[^a-z0-9]+/g, "_")
          .replace(/^_+|_+$/g, "");

      const base = normalizar(titulo);
      const index = (this.imagenIndex % 6) + 1;
      return `${window.location.origin}/fotos/Foto_${base}(${index}).jpg`;
    },
    filtrarCasas() {
      const b = this.busqueda.toLowerCase();
      this.casasFiltradas = this.casas.filter(c =>
        c.titulo.toLowerCase().includes(b) ||
        c.ubicacion.toLowerCase().includes(b) ||
        c.servicios.toLowerCase().includes(b)
      );
    }
  },
  mounted() {
    axios
      .get('http://localhost/dashboard/TFG/backend/api/casas.php')
      .then(res => {
        this.casas = res.data;
        this.casasFiltradas = res.data; // mostrar todas al inicio
      })
      .catch(err => console.error('Error al cargar casas:', err));

    setInterval(() => {
      this.imagenIndex++;
    }, 10000);
  }
}
</script>

<style scoped>
.inicio-container {
  background-color: #dcdcdc;
  padding: 20px;
  padding-top: 0;
}

/* Buscador */
.buscador {
  display: flex;
  align-items: center;
  background-color: #747973;
  padding: 12px;
  border-radius: 10px;
  margin: 0 auto 40px auto;
  width: 90%;
  max-width: 600px;
}

.filtros {
  color: white;
  font-size: 14px;
}

.input-busqueda {
  flex: 1;
  margin: 0 20px;
  padding: 8px 16px;
  border-radius: 20px;
  border: none;
  outline: none;
}

.boton-buscar {
  background-color: black;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 6px;
  cursor: pointer;
}

/* Tarjetas */
.galeria {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  /* Fuerza 3 columnas */
  gap: 50px;
  position: relative;
  z-index: 1;
  margin: 30px;
}


.tarjeta {
  position: relative;
  background-color: #a0a0a0;
  border-radius: 8px;
  padding: 0;
  overflow: hidden;
  cursor: pointer;
  transition: background-color 0.3s;
  aspect-ratio: 1 / 1;
  display: flex;
  justify-content: center;
  align-items: center;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.tarjeta:hover {
  background-color: #8a8a8a;
}




.titulo-flotante {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background-color: rgba(0, 0, 0, 0.6);
  color: white;
  font-weight: bold;
  padding: 6px 10px;
  font-size: 16px;
  text-align: center;
  border-bottom-left-radius: 8px;
  border-bottom-right-radius: 8px;
}

.imagen-con-transicion {
  width: 100%;
  height: 100%;
  position: relative;
  overflow: hidden;
}

.imagen {
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  transition: opacity 1s ease-in-out;
  opacity: 1;
  animation: fadeIn 1s ease-in-out;
}

.mensaje-no-resultados {
  grid-column: 1 / -1;
  text-align: center;
  color: #333;
  background-color: #f5f5f5;
  padding: 40px 20px;
  font-size: 18px;
  border-radius: 10px;
}


@keyframes fadeIn {
  from {
    opacity: 0.5;
  }

  to {
    opacity: 1;
  }
}
</style>
