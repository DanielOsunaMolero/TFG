<template>
  <div class="vblog">
    
    <div class="hero-blog">
     
<div class="buscador-blog">
  <input
    type="text"
    v-model="busqueda"
    @input="filtrarValoraciones"
    placeholder=" Buscar valoraciones por casa..."
    class="input-busqueda"
  />
  <button class="boton-buscar" @click="filtrarValoraciones">
    <font-awesome-icon :icon="['fas', 'search']" />
  </button>
</div>

    </div>

    <!-- Valoraciones -->
    <div class="grid-valoraciones">
      <valoracionCard
        v-for="valoracion in valoracionesFiltradas"
        :key="valoracion.id_valoracion"
        :fotoPerfil="rutaFotoPerfil(valoracion.foto_perfil)"
        :nombreUsuario="valoracion.nombre_usuario"
        :nombreCasa="valoracion.nombre_casa"
        :textoValoracion="valoracion.texto_valoracion"
        :puntuacion="Number(valoracion.puntuacion)"
        :diasEstancia="valoracion.dias_estancia"
      />
    </div>

    <div v-if="valoracionesFiltradas.length === 0" class="sin-valoraciones">
      No hay valoraciones que coincidan con la búsqueda.
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import valoracionCard from '@/components/valoracionCard.vue';
import { API_BASE, IMG_PERFIL_BASE } from '@/config.js';

export default {
  name: 'vBlog',
  components: { valoracionCard },
  data() {
    return {
      valoraciones: [],
      valoracionesFiltradas: [],
      busqueda: ''
    };
  },
  mounted() {
    this.cargarValoraciones();
  },
  methods: {
    async cargarValoraciones() {
      try {
        const response = await axios.get(`${API_BASE}getValoraciones.php`);
        this.valoraciones = response.data;
        this.valoracionesFiltradas = response.data;
        console.log('Valoraciones cargadas:', this.valoraciones);
      } catch (error) {
        console.error('Error al cargar valoraciones:', error);
      }
    },
    filtrarValoraciones() {
      const b = this.busqueda.toLowerCase();

      // Si está vacío → mostrar todo
      if (!b) {
        this.valoracionesFiltradas = this.valoraciones;
        return;
      }

      this.valoracionesFiltradas = this.valoraciones.filter(v =>
        v.nombre_casa.toLowerCase().includes(b)
      );
    },
    rutaFotoPerfil(fotoPerfil) {
      if (!fotoPerfil) {
        return `${IMG_PERFIL_BASE}default.png`;
      }
      return `${IMG_PERFIL_BASE}${fotoPerfil}`;
    }
  }
};
</script>

<style scoped>
.vblog {
  background-color: #fafafa;
  min-height: 100vh;
  box-sizing: border-box;
}

/* Hero (cabecera con imagen) */
.hero-blog {
  position: relative;
  background-image: url('@/assets/heri_image_blog.jpg');
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  height: 150px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

/* Buscador flotante encima de la imagen */
.buscador-blog {
  position: absolute;
  bottom: 20px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 10px;
  background-color: rgba(255, 255, 255, 0.9);
  padding: 10px 15px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.input-busqueda {
  width: 300px;
  padding: 10px 14px;
  border: 1px solid #ccc;
  border-radius: 20px;
  font-size: 14px;
  transition: border-color 0.3s;
}

.input-busqueda:focus {
  outline: none;
  border-color: #60e29b;
}

.boton-buscar {
  padding: 10px 16px;
  border: none;
  border-radius: 16px;
  background-color: #60e29b;
  color: white;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.boton-buscar:hover {
  background-color: #4cc190;
}

/* Valoraciones */
.grid-valoraciones {
  display: grid;
  grid-template-columns: repeat(4, minmax(260px, 1fr));
  gap: 24px;
  padding: 30px 20px 40px;
}

.sin-valoraciones {
  text-align: center;
  color: #666;
  font-size: 18px;
  font-style: italic;
  margin-top: 50px;
  opacity: 0.8;
}

@media (max-width: 1400px) {
  .grid-valoraciones {
    grid-template-columns: repeat(3, minmax(260px, 1fr));
  }
}

@media (max-width: 1024px) {
  .grid-valoraciones {
    grid-template-columns: repeat(2, minmax(260px, 1fr));
  }
}

@media (max-width: 768px) {
  .grid-valoraciones {
    grid-template-columns: 1fr;
    padding: 0 10px;
    gap: 16px;
    margin-bottom: 10px;
    margin-top: 10px;
  }

  .buscador-blog {
    flex-direction: column;
    width: 90%;
    bottom: 15px;
  }

  .input-busqueda {
    width: 92%;
  }
}

@media (max-width: 480px) {
  .hero-blog {
    height: 220px;
  }
}
</style>
