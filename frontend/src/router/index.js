import { createRouter, createWebHistory } from 'vue-router'

import vInicio from '../views/vInicio.vue'
import vCasaDetalle from '../views/vCasaDetalle.vue'
import vPago from '../views/vPago.vue'
import vLogin from '../views/vLogin.vue'
import vRegistro from '../views/vRegistro.vue'
import vGestionPropietarios from '../views/vGestionPropietarios.vue'
import vBlog from '../views/vBlog.vue'
import vSobre from '../views/vSobreNosotros(opcional).vue'
import vCrearCasa from '../views/vCrearCasa.vue'
import vEditarCasa from '../views/vEditarCasa.vue'
import vReservar from '../views/vReservar.vue'
import vGestionReservas from '../views/vGestionReservas.vue'
import vPerfil from '../views/vPerfil.vue'
const routes = [
  { path: '/', component: vInicio },
  { path: '/casa/:id', component: vCasaDetalle },
  { path: '/pago/:id', component: vPago },
  { path: '/login', component: vLogin },
  { path: '/registro', component: vRegistro },
  { path: '/gestion', component: vGestionPropietarios },
  { path: '/blog', component: vBlog },
  { path: '/sobre', component: vSobre },
  { path: "/crear-casa", component: vCrearCasa },
  { path: "/editar-casa/:id", component: vEditarCasa },
  { path: "/reservar/:id", component: vReservar },
  { path: "/gestion-reservas", component: vGestionReservas },
    { path: "/perfil", component: vPerfil }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router

