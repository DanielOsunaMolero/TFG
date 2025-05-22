<template>
  <div class="blog-container">

    <!-- Entradas del blog -->
    <div class="blog-grid">
      <div class="blog-card" v-for="n in 4" :key="n">
        <div class="blog-image"></div>
        <div class="blog-content">
          <h2>LOREM IPSUM</h2>
          <p>
            Lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum
            lorem ipsum lorem ipsum lorem ipsum lorem ipsum lorem ipsum.
          </p>
        </div>
      </div>
    </div>

    <!-- Título decorativo inferior -->
    <h1 class="blog-decorativo">Blog</h1>

  </div>
</template>

<script>
export default {
  name: 'vBlog'
}
</script>

<style scoped>
.blog-container {
  background-color: #dcdcdc;
  padding: 60px 20px;
  position: relative;
}

/* Cuadrícula */
.blog-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 40px;
}

/* Tarjeta individual */
.blog-card {
  display: flex;
  background-color: #6b6767;
  color: white;
  padding: 20px;
  border-radius: 8px;
}

.blog-image {
  width: 80px;
  height: 80px;
  background-color: white;
  margin-right: 16px;
  border-radius: 4px;
  flex-shrink: 0;
}

.blog-content h2 {
  margin: 0 0 8px;
  font-size: 20px;
}

.blog-content p {
  font-size: 14px;
  line-height: 1.4;
}

/* Título decorativo */
.blog-decorativo {
  position: absolute;
  bottom: 20px;
  right: 30px;
  font-size: 48px;
  color: #888;
  font-weight: bold;
  font-style: italic;
  opacity: 0.3;
}
</style>
