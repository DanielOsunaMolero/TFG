
<template>
  <div class="inicio-container">
  
    <!-- HERO IMAGE -->
    <section class="hero">
      <div class="buscador">
        <div class="filtros"></div>
        <input
          type="text"
          v-model="busqueda"
          @keyup.enter="filtrarCasas"
          placeholder="Buscar casas..."
          class="input-busqueda"
        />
        <button class="boton-buscar" @click="filtrarCasas">
          <font-awesome-icon :icon="['fas', 'search']" />
        </button>
      </div>
    </section>

    <!-- Cuadrícula -->
    <section class="galeria">
      <div v-if="casasFiltradas.length === 0" class="mensaje-no-resultados">
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
import axios from 'axios';
import { API_BASE, IMG_BASE } from '@/config.js';

export default {
  name: 'vInicio',
  data() {
    return {
      casas: [],
      casasFiltradas: [],
      busqueda: '',
      imagenIndex: 0
    };
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
      return `${IMG_BASE}Foto_${base}(${index}).jpg`;
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
      .get(`${API_BASE}casas.php`)
      .then(res => {
        this.casas = res.data;
        this.casasFiltradas = res.data;
      })
      .catch(err => console.error('Error al cargar casas:', err));

    setInterval(() => {
      this.imagenIndex++;
    }, 10000);
  }
};
</script>


<style scoped>
.inicio-container {
  background-color: #fbfcf3;
  padding: 0;
  margin: 0;
}

.buscador {
  display: flex;
  align-items: center;
  background-color: rgba(255, 255, 255, 0.85);
  padding: 10px 14px;
  border-radius: 12px;
  width: 90%;
  max-width: 600px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  backdrop-filter: blur(6px);
}

.filtros {
  color: #333;
  font-size: 14px;
}

.input-busqueda {
  flex: 1;
  margin: 0 12px;
  padding: 10px 16px;
  border-radius: 30px;
  border: 1px solid #ccc;
  outline: none;
  font-size: 15px;
  box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
  transition: border 0.3s ease, box-shadow 0.3s ease;
}

.input-busqueda:focus {
  border-color: #60e29b;
  box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.08);
}

.boton-buscar {
  background: linear-gradient(90deg, #60e29b 0%, #3ecf8e 100%);
  color: white;
  border: none;
  padding: 10px 18px;
  border-radius: 30px;
  cursor: pointer;
  font-size: 14px;
  transition: transform 0.2s ease, opacity 0.2s ease;
}

.boton-buscar:hover {
  transform: scale(1.05);
  opacity: 0.9;
}

.galeria {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 50px;
  position: relative;
  z-index: 1;
  margin: 4%;
  
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

.hero {
  background-image: url('/src/assets/hero_image.jpg');
  background-size: cover;
  background-position: center 40%;
  background-repeat: no-repeat;
  background-attachment: scroll;
  width: 100%;
  height: 150px;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  margin: 0;
  padding: 0;
}

.hero::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.3);
  z-index: 1;
}

.hero .buscador {
  position: relative;
  z-index: 2;
  background-color: rgba(255, 255, 255, 0.9);
  border-radius: 12px;
  padding: 12px;
  display: flex;
  align-items: center;
  width: 90%;
  max-width: 600px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}



@keyframes fadeIn {
  from {
    opacity: 0.5;
  }

  to {
    opacity: 1;
  }
}


@media (max-width: 1024px) {
  .galeria {
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    margin: 20px;
  }
}


@media (max-width: 768px) {
  .galeria {
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin: 15px;
  }

  .buscador {

    padding: 10px;
  }

  .input-busqueda {
    margin: 0 10px;
    padding: 6px 12px;
    font-size: 14px;
  }

  .boton-buscar {
    padding: 6px 12px;
    font-size: 14px;
  }
  .hero {
    height: 150px;
    background-position: center 45%;
  }
}



@media (max-width: 480px) {
  .galeria {
    grid-template-columns: 1fr;
    gap: 15px;
    margin: 10px;
  }

  .titulo-flotante {
    font-size: 14px;
    padding: 5px 8px;
  }
}
</style>
