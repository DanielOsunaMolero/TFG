<template>
    <div class="perfil-usuario">
        <h1>Mi Perfil</h1>

        <div class="perfil-info" v-if="usuario">
            <img :src="fotoUrl" alt="Foto de perfil" class="foto-perfil" />

            <div class="datos">
                <p><strong>Nombre:</strong> {{ usuario.nombre }}</p>
                <p><strong>Email:</strong> {{ usuario.email }}</p>
                <p><strong>Tipo:</strong> {{ usuario.tipo }}</p>
                <p><strong>Fecha de registro:</strong> {{ usuario.fecha_registro }}</p>
            </div>
        </div>

        <div class="subir-foto">
            <h3>Actualizar Foto de Perfil</h3>
            <input type="file" @change="handleArchivo" accept="image/*" />
            <button @click="subirFoto">Subir Foto</button>
        </div>


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
    </div>
</template>

<script>
import { mapMutations, mapState } from 'vuex';

export default {
    data() {
        return {
            reservas: [],
            archivoFoto: null
        };
    },
    computed: {
        ...mapState(['usuario']),
        fotoUrl() {
            return this.usuario && this.usuario.foto_perfil
                ? `http://localhost:8080/fotos_perfil/${this.usuario.foto_perfil}`
                : 'https://via.placeholder.com/150x150?text=👤';
        }
    }

    ,
    methods: {
        ...mapMutations(['guardarUsuario']),

        handleArchivo(e) {
            this.archivoFoto = e.target.files[0];
        },
        async subirFoto() {
            if (!this.archivoFoto) {
                alert("Selecciona una imagen primero.");
                return;
            }

            const id_usuario = localStorage.getItem('id_usuario');
            const formData = new FormData();
            formData.append("foto", this.archivoFoto);
            formData.append("id_usuario", id_usuario);

            try {
                const res = await fetch("http://localhost/dashboard/TFG/backend/api/subirFotoPerfil.php", {
                    method: "POST",
                    body: formData
                });

                const data = await res.json();

                if (data.success) {
                    alert("Foto actualizada correctamente.");
                    this.usuario.foto_perfil = data.nombre_foto;
                    const usuarioActual = JSON.parse(localStorage.getItem("usuario"));
                    usuarioActual.foto_perfil = data.nombre_foto;
                    this.guardarUsuario(usuarioActual);
                    localStorage.setItem("usuario", JSON.stringify(usuarioActual));
                } else {
                    alert("Error al subir la foto.");
                }
            } catch (err) {
                console.error("Error en subida:", err);
                alert("Error al conectar con el servidor.");
            }
        }
    },


    mounted() {
        const id = this.usuario?.id_usuario || localStorage.getItem("id_usuario");

        if (!id) {
            console.error("ID de usuario no disponible");
            return;
        }

        fetch(`http://localhost/dashboard/TFG/backend/api/getReservasUsuario.php?id_usuario=${id}`)
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
    }


};
</script>



<style scoped>
.perfil-usuario {
    max-width: 900px;
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

.perfil-info {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
}

.foto-perfil {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 50%;
    border: 4px solid #60e29b;
    margin-right: 30px;
}

.datos p {
    margin: 8px 0;
    font-size: 1rem;
    color: #444;
}

.datos strong {
    color: #000;
}

.subir-foto {
    margin-top: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.subir-foto input[type="file"] {
    padding: 6px;
    border-radius: 8px;
    background-color: #fff;
    border: 1px solid #ccc;
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

h2 {
    margin-top: 40px;
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
</style>
