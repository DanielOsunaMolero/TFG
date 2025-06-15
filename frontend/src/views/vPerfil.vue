<template>
    <div class="perfil-usuario">
        <h1>Mi Perfil</h1>

        <div class="perfil-layout">
            <!-- COLUMNA IZQUIERDA -->
            <div class="perfil-columna izquierda">
                <img :src="fotoUrl" alt="Foto de perfil" class="foto-perfil" />

                <div class="datos">
                    <p><strong>Nombre:</strong> {{ usuario?.nombre }}</p>
                    <p><strong>Email:</strong> {{ usuario?.email }}</p>
                    <p><strong>Tipo:</strong> {{ usuario?.tipo }}</p>
                    <p><strong>Fecha de registro:</strong> {{ usuario?.fecha_registro }}</p>
                </div>

                <div class="subir-foto">
                    <h3>Actualizar Foto de Perfil</h3>

                    <label class="input-file-label">
                        Seleccionar imagen
                        <input type="file" @change="handleArchivo" accept="image/*" />
                    </label>

                    <button @click="subirFoto">
                        <font-awesome-icon :icon="['fas', 'upload']" style="margin-right: 6px;" />
                        Actualizar
                    </button>
                </div>
            </div>

            <!-- COLUMNA DERECHA -->
            <div class="perfil-columna derecha">
                <!-- Reservas -->
                <section class="bloque">
                    <h2>Mis Reservas</h2>
                    <div v-if="reservas.length">
                        <table>
                            <thead>
                                <tr>
                                    <th>Casa</th>
                                    <th>Inicio</th>
                                    <th>Fin</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="reserva in reservas" :key="reserva.id_reserva">
                                    <td>{{ reserva.titulo_casa }}</td>
                                    <td>{{ reserva.fecha_inicio }}</td>
                                    <td>{{ reserva.fecha_fin }}</td>
                                    <td :class="'estado-' + reserva.estado">{{ reserva.estado }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p v-else>No tienes reservas aún.</p>
                </section>

                <!-- Valoraciones -->
                <section class="bloque">
                    <h2>Mis Valoraciones</h2>
                    <div v-if="valoracionesUsuario.length" class="grid-valoraciones">
                        <ValoracionCard v-for="valoracion in valoracionesUsuario" :key="valoracion.id_valoracion"
                            :fotoPerfil="rutaFotoPerfil(usuario ? usuario.foto_perfil : null)"
                            :nombreUsuario="usuario ? usuario.nombre : ''"
                            :nombreCasa="valoracion.nombre_casa"
                            :textoValoracion="valoracion.texto_valoracion"
                            :puntuacion="Number(valoracion.puntuacion)"
                            :diasEstancia="valoracion.dias_estancia" />
                    </div>
                    <p v-else>No has hecho valoraciones aún.</p>
                </section>
            </div>
        </div>
    </div>
</template>



<script>
import { mapMutations, mapState } from 'vuex';
import ValoracionCard from '@/components/valoracionCard.vue';
import { API_BASE, IMG_PERFIL_BASE } from '@/config.js';
import { useToast } from 'vue-toastification';

export default {
    components: {
        ValoracionCard
    },
    data() {
        return {
            reservas: [],
            archivoFoto: null,
            valoracionesUsuario: []
        };
    },
    computed: {
        ...mapState(['usuario']),
        fotoUrl() {
            if (!this.usuario || !this.usuario.foto_perfil) {
                return `${IMG_PERFIL_BASE}default.jpg`;
            }
            return `${IMG_PERFIL_BASE}${this.usuario.foto_perfil}`;
        }
    },
    methods: {
        ...mapMutations(['guardarUsuario']),

        handleArchivo(e) {
            this.archivoFoto = e.target.files[0];
        },

        async subirFoto() {
            const toast = useToast();

            if (!this.archivoFoto) {
                toast.warning("⚠️ Selecciona una imagen primero.");
                return;
            }

            const id_usuario = localStorage.getItem('id_usuario');
            const formData = new FormData();
            formData.append("foto", this.archivoFoto);
            formData.append("id_usuario", id_usuario);
            // Log de control
            try {
                const res = await fetch(`${API_BASE}subirFotoPerfil.php`, {
                    method: "POST",
                    body: formData
                });

                const data = await res.json();
                console.log("Respuesta subirFotoPerfil:", data);
                // Verificar si la respuesta es exitosa
                if (data.success) {
                    toast.success("✅ Foto actualizada correctamente.");
                    if (this.usuario) {
                        this.usuario.foto_perfil = data.nombre_foto;
                        const usuarioActual = JSON.parse(localStorage.getItem("usuario"));
                        usuarioActual.foto_perfil = data.nombre_foto;
                        this.guardarUsuario(usuarioActual);
                        localStorage.setItem("usuario", JSON.stringify(usuarioActual));
                    }
                } else {
                    toast.error("❌ Error al subir la foto.");
                }
            } catch (err) {
                console.error("Error en subida:", err);
                toast.error("❌ Error al conectar con el servidor.");
            }
        },

        rutaFotoPerfil(fotoPerfil) {
            if (!fotoPerfil) {
                return `${IMG_PERFIL_BASE}default.jpg`;
            }
            return `${IMG_PERFIL_BASE}${fotoPerfil}`;
        }
    },

    mounted() {
        const id = this.usuario?.id_usuario || localStorage.getItem("id_usuario");

        if (!id) {
            console.error("ID de usuario no disponible");
            return;
        }

        // Cargar reservas
        fetch(`${API_BASE}getReservasUsuario.php?id_usuario=${id}`)
            .then(res => {
                if (!res.ok) throw new Error("Error al consultar las reservas");
                return res.json();
            })
            .then(data => {
                this.reservas = data;
            })
            .catch(err => {
                console.error("Error al cargar reservas:", err);
            });

        // Cargar valoraciones del usuario
        fetch(`${API_BASE}getValoraciones.php?id_usuario=${id}`)
            .then(res => {
                if (!res.ok) throw new Error("Error al consultar las valoraciones");
                return res.json();
            })
            .then(data => {
                this.valoracionesUsuario = data;
            })
            .catch(err => {
                console.error("Error al cargar valoraciones:", err);
            });
    }
};
</script>

<style scoped>
.perfil-usuario {
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f9f9f9;
    border-radius: 20px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
}

h1 {
    font-size: 2rem;
    color: #333;
    margin-bottom: 25px;
    border-bottom: 2px solid #60e29b;
    display: inline-block;
}

.perfil-layout {
    display: flex;
    gap: 40px;
    flex-wrap: wrap;
}

.perfil-columna {
    flex: 1;
    min-width: 300px;
}

/* COLUMNA IZQUIERDA */
.perfil-columna.izquierda {
    max-width: 350px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
}

.foto-perfil {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid #60e29b;
}

.datos p {
    margin: 6px 0;
    font-size: 1rem;
    color: #444;
    text-align: left;
    width: 100%;
}

.subir-foto {
    margin-top: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.input-file-label {
    display: inline-block;
    padding: 8px 16px;
    background-color: #60e29b;
    color: white;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.2s ease;
    user-select: none;
    text-align: center;
    font-size: 0.95rem;
}

.input-file-label:hover {
    background-color: #4fc88b;
}

.input-file-label input[type="file"] {
    display: none;
}

.subir-foto button {
    background-color: #60e29b;
    color: white;
    border: none;
    padding: 8px 16px;
    font-weight: bold;
    border-radius: 10px;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.subir-foto button:hover {
    background-color: #4fc88b;
}

/* COLUMNA DERECHA */
.perfil-columna.derecha {
    flex: 2;
}

.bloque {
    margin-bottom: 30px;
}

h2 {
    margin-top: 0;
    font-size: 1.5rem;
    color: #333;
    border-bottom: 2px solid #60e29b;
    display: inline-block;
    margin-bottom: 15px;
}

table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.06);
}

th,
td {
    text-align: left;
    padding: 14px;
}

th {
    background-color: #60e29b;
    color: white;
}

tr:nth-child(even) {
    background-color: #f3f3f3;
}

.estado-pendiente {
    color: #e6a100;
    font-weight: bold;
}

.estado-confirmada {
    color: #2ba043;
    font-weight: bold;
}

.estado-cancelada {
    color: #d11a2a;
    font-weight: bold;
}

.grid-valoraciones {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

/* MEDIA QUERY para dispositivos pequeños */
@media (max-width: 768px) {
    .perfil-layout {
        flex-direction: column;
    }

    .perfil-columna.izquierda {
        align-items: center;
        max-width: 100%;
    }

    .perfil-columna.derecha {
        width: 100%;
    }
}

@media (max-width: 480px) {
  .perfil-usuario {
    width: 90%;
  }
}
</style>
