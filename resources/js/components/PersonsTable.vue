<template>
  <div>
    <h1>Lista de Personas</h1>
    <table class="table table-striped">
      <thead>
        <tr>
          <th scope="col">DNI</th>
          <th scope="col">Nombres</th>
          <th scope="col">Apellidos</th>
          <th scope="col">Celular</th>
          <th scope="col">Latitud</th>
          <th scope="col">Longitud</th>
          <th scope="col">Estado</th>
          <th scope="col">Mapa</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="person in persons" :key="person.id">
          <td>{{ person.dni }}</td>
          <td>{{ person.nombres }}</td>
          <td>{{ person.apellidos }}</td>
          <td>{{ person.celular }}</td>
          <td>{{ person.latest_location ? person.latest_location.latitud : 'N/A' }}</td>
          <td>{{ person.latest_location ? person.latest_location.longitud : 'N/A' }}</td>
          <td>{{ person.estado }}</td>
          <td>
            <button 
              class="btn btn-primary" 
              @click="openModal(person.latest_location ? person.latest_location.latitud : 0, person.latest_location ? person.latest_location.longitud : 0)">
              Ver mapa
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Modal -->
    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="mapModalLabel">Mapa</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="map" style="height: 500px;"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import L from 'leaflet';
import * as bootstrap from 'bootstrap'; // Import Bootstrap JS

export default {
  data() {
    return {
      persons: [],
      map: null,
      markers: [],
      currentLat: 0,
      currentLng: 0,
    };
  },
  methods: {
    fetchPersons() {
      axios.get('/api/persons')
        .then(response => {
          this.persons = response.data;
          this.updateMarkers();
        })
        .catch(error => {
          console.log(error);
        });
    },
    updateMarkers() {
      if (this.map) {
        this.markers.forEach(marker => this.map.removeLayer(marker));
        this.markers = this.persons.map(person => {
          if (person.latest_location) {
            const marker = L.marker([person.latest_location.latitud, person.latest_location.longitud], {
              icon: L.icon({
                iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png'
              })
            }).addTo(this.map);
            marker.bindPopup(`${person.nombres} ${person.apellidos}`);
            return marker;
          }
        }).filter(marker => marker);
      }
    },
    openModal(lat, lng) {
      this.currentLat = lat;
      this.currentLng = lng;
      const modal = new bootstrap.Modal(document.getElementById('mapModal'));
      modal.show();
      this.$nextTick(() => {
        this.showOnMap(lat, lng);
      });
    },
    showOnMap(lat, lng) {
      // If map is not initialized, initialize it
      if (!this.map) {
        this.map = L.map('map', {
          center: [lat, lng],
          zoom: 10, // Initial zoom level for animation
          layers: [
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              maxZoom: 19
            })
          ]
        });
      } else {
        this.map.setView([lat, lng], 10, { animate: true, duration: 1.0 });
      }
      // Perform animated zoom
      setTimeout(() => {
        this.map.setView([lat, lng], 12, { animate: true, duration: 1.0 }); // Final zoom level
      }, 300); // Small delay to ensure the map is rendered before starting the zoom
      // Invalidate map size after a short delay to ensure it's fully visible
      setTimeout(() => {
        this.map.invalidateSize();
      }, 500);
    }
  },
  mounted() {
    this.fetchPersons();
    setInterval(this.fetchPersons, 5000); // Actualiza cada 5 segundos
  }
};
</script>

<style scoped>
/* Aquí puedes agregar estilos específicos para el componente */
#map {
  height: 500px;
}
</style>
