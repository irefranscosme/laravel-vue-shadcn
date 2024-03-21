import { createApp } from 'vue';
import './style.css';
import App from './App.vue';
import router from './router/routes';
import './lib/axios';
import { createPinia } from 'pinia';

const app = createApp(App);
const pinia = createPinia();

app.use(router);
app.use(pinia);
app.mount('#app');
