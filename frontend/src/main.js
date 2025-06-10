import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';

import Toast from 'vue-toastification';
import 'vue-toastification/dist/index.css';
import 'v-calendar/style.css';

// 👇 FontAwesome imports
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

import { faInstagram, faFacebook } from '@fortawesome/free-brands-svg-icons';
import { faGlobe, faPhone, faEnvelope, faSearch, faPlus, faClipboardList, faPen, faTrash, faHome, faCalendarAlt, faThumbtack, faCheck, faTimes, faUpload, faCamera } from '@fortawesome/free-solid-svg-icons';

library.add(
  faInstagram, faFacebook, faGlobe, faPhone, faEnvelope, faSearch,
  faPlus, faClipboardList, faPen, faTrash,
  faHome, faCalendarAlt, faThumbtack, faCheck, faTimes,
  faUpload, faCamera
);




// Crear app
const app = createApp(App);

app.use(store);
app.use(router);
app.use(Toast);

// Registrar el componente globalmente
app.component('font-awesome-icon', FontAwesomeIcon);

app.mount('#app');
