import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'

import { setupCalendar } from 'v-calendar'

import 'v-calendar/style.css'

const app = createApp(App)
setupCalendar(app)  // ⬅️ esto es lo correcto

app.use(router)
app.use(store)
app.mount('#app')
