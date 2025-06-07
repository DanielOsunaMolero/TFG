<template>
  <div class="vblog">
    <h1>Blog de Valoraciones</h1>

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

    <div v-if="valoraciones.length === 0" class="sin-valoraciones">
      No hay valoraciones aún.
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import valoracionCard from '@/components/valoracionCard.vue'

export default {
  name: 'vBlog',
  components: { valoracionCard },
  data() {
    return {
      valoraciones: []
    }
  },
  mounted() {
    this.cargarValoraciones()
  },
  methods: {
    async cargarValoraciones() {
      try {
        const response = await axios.get('http://localhost/dashboard/TFG/backend/api/getValoraciones.php')
        this.valoraciones = response.data
        console.log('Valoraciones cargadas:', this.valoraciones)
      } catch (error) {
        console.error('Error al cargar valoraciones:', error)
      }
    },
    rutaFotoPerfil(fotoPerfil) {
      if (!fotoPerfil) {
        return `${window.location.origin}/fotos_perfil/default.png` // Ajusta si tienes imagen por defecto
      }
      return `${window.location.origin}/fotos_perfil/${fotoPerfil}`
    }
  }
}
</script>

<style scoped>
.vblog {
  padding: 40px 20px;
  background-color: #fafafa;
  min-height: 100vh;
  box-sizing: border-box;
}

.vblog h1 {
  text-align: center;
  font-size: 36px;
  color: #333;
  font-weight: 700;
  margin-bottom: 30px;
  letter-spacing: 0.5px;
  font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', sans-serif;
}

.grid-valoraciones {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 24px;
  padding: 10px;
}

.sin-valoraciones {
  text-align: center;
  color: #666;
  font-size: 18px;
  font-style: italic;
  margin-top: 50px;
  opacity: 0.8;
}
</style>

