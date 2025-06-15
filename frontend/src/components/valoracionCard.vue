<template>
  <div class="valoracion-card" :class="bordePuntuacion">
    <div class="header">
      <img :src="fotoPerfil" alt="Foto perfil" class="foto-perfil" />
      <div class="info-user">
        <h4>{{ nombreUsuario }}</h4>
        <p>{{ nombreCasa }}</p>
      </div>
    </div>

    <div class="contenido">
      <p class="texto">{{ textoValoracion }}</p>
      <div class="estrellas">
        <span
          v-for="n in 5"
          :key="n"
          class="estrella"
          :class="{ activa: n <= puntuacion }"
        >★</span>
      </div>
      <p class="dias">{{ diasEstancia }} día(s) de estancia</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ValoracionCard',
  props: {
    fotoPerfil: String,
    nombreUsuario: String,
    nombreCasa: String,
    textoValoracion: String,
    puntuacion: Number,
    diasEstancia: Number
  },
  computed: {
    bordePuntuacion() {
      if (this.puntuacion >= 4) {
        return 'borde-verde';
      } else if (this.puntuacion >= 3) {
        return 'borde-amarillo';
      } else {
        return 'borde-rojo';
      }
    }
  }
}
</script>

<style scoped>
.valoracion-card {
  display: flex;
  flex-direction: column;
  align-items: stretch;
  justify-content: flex-start;
  padding: 16px 20px;
  background-color: #ffffff;
  border-left: 6px solid #60e29b; 
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
  text-align: left;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  height: auto;
  min-height: 220px;
  box-sizing: border-box;

  /* Animación */
  opacity: 0;
  transform: translateY(20px);
  animation: fadeInUp 0.6s ease forwards;
  animation-delay: 0.1s;
}

.valoracion-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
}

.header {
  display: flex;
  align-items: center;
  margin-bottom: 12px;
}

.foto-perfil {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #60e29b;
  margin-right: 12px;
}

.info-user h4 {
  margin: 0;
  font-size: 15px;
  color: #333;
  font-weight: 600;
}

.info-user p {
  margin: 2px 0 0;
  font-size: 13px;
  color: #666;
}

.contenido {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  width: 100%;
}

.contenido .texto {
  font-size: 14px;
  margin: 8px 0 12px;
  line-height: 1.5;
  color: #444;
}

.estrellas {
  display: flex;
  justify-content: flex-start;
  margin: 4px 0;
}

.estrella {
  font-size: 18px;
  margin-right: 2px;
  color: #ccc;
  transition: color 0.3s ease;
}

.estrella.activa {
  color: #ffce00;
}

.dias {
  font-size: 12px;
  color: #555;
  margin-top: auto;
  margin-top: 10px;
  font-style: italic;
}

/* Bordes dinámicos */
.borde-verde {
  border-left: 6px solid #2ecc71;
}

.borde-amarillo {
  border-left: 6px solid #f1c40f;
}

.borde-rojo {
  border-left: 6px solid #e74c3c;
}

/* Animación */
@keyframes fadeInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
@media (max-width: 1300px) {
  .valoracion-card {
    padding: 16px 7px;
    min-height: 180px;
  }

  .foto-perfil {
    width: 40px;
    height: 40px;
  }

  .info-user h4 {
    font-size: 14px;
  }

  .info-user p {
    font-size: 12px;
  }

  .contenido .texto {
    font-size: 13px;
  }

  .estrellas .estrella {
    font-size: 16px;
  }

  .dias {
    font-size: 11px;
  }
}
</style>
