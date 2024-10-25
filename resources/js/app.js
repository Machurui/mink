import './bootstrap';
import { createApp } from "vue";

import App from "./components/App.vue";
import SignIn from "./components/SignIn.vue";
import PanelAdmin from './components/PanelAdmin.vue';

const app = createApp({});

app.component('tables-animals', App);
app.component('sign-in', SignIn);
app.component('panel-admin', PanelAdmin);

app.mount('#app');
