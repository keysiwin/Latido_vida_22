<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Personas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
</head>
<body>
    <div class="container">
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
            <tbody id="personTableBody">
                <!-- Aquí se insertarán las filas mediante JS -->
            </tbody>
        </table>
    </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const personTableBody = document.getElementById('personTableBody');
            const mapModal = new bootstrap.Modal(document.getElementById('mapModal'));
            let map, markers = [];

            function fetchPersons() {
                axios.get('/api/persons')
                    .then(response => {
                        const persons = response.data;
                        personTableBody.innerHTML = '';
                        markers.forEach(marker => marker.remove());
                        markers = [];

                        persons.forEach(person => {
                            const row = document.createElement('tr');

                            row.innerHTML = `
                                <td>${person.dni}</td>
                                <td>${person.nombres}</td>
                                <td>${person.apellidos}</td>
                                <td>${person.celular}</td>
                                <td>${person.latest_location ? person.latest_location.latitud : 'N/A'}</td>
                                <td>${person.latest_location ? person.latest_location.longitud : 'N/A'}</td>
                                <td>${person.estado}</td>
                                <td>
                                    <button class="btn btn-primary" onclick="openModal(${person.latest_location ? person.latest_location.latitud : 0}, ${person.latest_location ? person.latest_location.longitud : 0})">
                                        Ver mapa
                                    </button>
                                </td>
                            `;

                            personTableBody.appendChild(row);

                            if (person.latest_location) {
                                const marker = L.marker([person.latest_location.latitud, person.latest_location.longitud]).bindPopup(`${person.nombres} ${person.apellidos}`);
                                markers.push(marker);
                            }
                        });
                    })
                    .catch(error => console.log(error));
            }

            function openModal(lat, lng) {
                if (!map) {
                    map = L.map('map').setView([lat, lng], 12);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19
                    }).addTo(map);
                } else {
                    map.setView([lat, lng], 12);
                }

                setTimeout(() => {
                    map.invalidateSize();
                }, 500);

                mapModal.show();
            }

            fetchPersons();
            setInterval(fetchPersons, 5000);

            window.openModal = openModal;
        });
    </script>
</body>
</html>
