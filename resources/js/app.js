import './bootstrap';
import { createApp } from "vue";

import App from "./components/App.vue";

const app = createApp({});

app.component('tables-animals', App);
app.mount('#app');
