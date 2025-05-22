<template>
  <div class="detalle-casa">

    <!-- Galería de imágenes -->
    <section class="galeria">
      <div class="imagen-principal">Perfil casa rural</div>
      <div class="miniaturas">
        <div class="miniatura" v-for="n in 4" :key="n"></div>
      </div>
    </section>

    <!-- Título -->
    <h1 class="titulo">LOREM IPSUM</h1>

    <!-- Servicios -->
    <section class="servicios">
      <div>
        <h3>LOREM IPSUM</h3>
        <ul>
          <li v-for="n in 8" :key="'izq-' + n">● LOREM IPSUM</li>
        </ul>
      </div>
      <div>
        <h3>LOREM IPSUM</h3>
        <ul>
          <li v-for="n in 8" :key="'der-' + n">● LOREM IPSUM</li>
        </ul>
      </div>
    </section>

    <!-- Descripción y valoraciones -->
    <section class="info-extra">
      <div class="descripcion">
        <h3>LOREM IPSUM</h3>
        <p>
          Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum
          lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum
        </p>
      </div>
      <div class="valoraciones">
        <h3>LOREM IPSUM</h3>
        <div class="bloque-valoracion"></div>
      </div>
    </section>

    <!-- Botón de acción -->
    <div class="boton-reserva">
      <button>Reservar</button>
    </div>

  </div>
</template>

<script>
export default {
  name: 'vCasaDetalle'
}
</script>

<style scoped>
.detalle-casa {
  padding: 40px 20px;
  background-color: #e0e0e0;
}

/* Galería */
.galeria {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
}

.imagen-principal {
  flex: 2;
  background-color: #aaa;
  aspect-ratio: 1 / 1;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 24px;
  border-radius: 8px;
}

.miniaturas {
  flex: 1;
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px;
}

.miniatura {
  background-color: #999;
  aspect-ratio: 1 / 1;
  border-radius: 6px;
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
  justify-content: space-between;
  margin: 30px 0;
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
  gap: 20px;
}

.descripcion,
.valoraciones {
  flex: 1;
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
</style>
