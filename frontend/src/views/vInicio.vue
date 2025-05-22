<template>
  <div class="inicio-container">

    <!-- Barra de búsqueda y filtros -->
    <section class="buscador">
      <div class="filtros">

      </div>

      <input type="text" placeholder="Buscar casas..." class="input-busqueda" />
      <button class="boton-buscar">🔍</button>
    </section>

    <!-- Cuadrícula de tarjetas -->
    <section class="galeria">
      <div v-for="n in 10" :key="n" class="tarjeta">
        <div class="imagen-circular"></div>
        <p>CASA X</p>
      </div>
    </section>

  </div>
</template>

<script>
export default {
  name: 'vInicio'
}
</script>

<style scoped>
.inicio-container {
  background-color: #dcdcdc;
  padding: 20px 20px;
  padding-top: 0px;                     /*NO QUIERO QUE HAYA AMRGEN ENTRE EL BUSCADOR Y EL HEADER*/
  position: relative;
}

/* Barra buscador */
.buscador {
  display: flex;
  align-items: center;
  background-color: #747973;
  padding: 12px;
  border-radius: 10px;
  margin: 0 auto 40px auto; /* ← centrado horizontal + separación abajo */
  width: 60%;
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
  grid-template-columns: repeat(3, 1fr);                  /*QUIERO QUE EN EL FONDO GRIS VAYA APARECIENDO LA IMAGEN CON EL NOMBRE FLOTANTE EN MEDIO
                                                            CON UN HOVER MAS OSCURO CUANDO PASES EL RATON */
  gap: 30px;
  position: relative;
  z-index: 1;

}

.tarjeta {
  background-color: #a0a0a0;
  border-radius: 8px;
  padding: 40px;
  text-align: center;
}

.imagen-circular {
  width: 180px;
  height: 180px;
  border-radius: 50%;
  background-color: #ccc;
  margin: 0 auto 12px;
}
</style>
