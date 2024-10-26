import './bootstrap';
import { createApp } from "vue";

import App from "./views/App.vue";
import SignIn from "./views/SignIn.vue";
import PanelAdmin from './views/PanelAdmin.vue';

const app = createApp({});

app.component('tables-animals', App);
app.component('sign-in', SignIn);
app.component('panel-admin', PanelAdmin);

app.mount('#app');
