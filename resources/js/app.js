import { createApp } from 'vue';
import PersonsTable from './components/PersonsTable.vue';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'leaflet/dist/leaflet.css';
import 'bootstrap';
import 'leaflet';

const app = createApp({});
app.component('persons-table', PersonsTable);
app.mount('#app');