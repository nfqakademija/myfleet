import axios from 'axios';
import L from 'leaflet';

const loadMap = () => {
    axios.get('/demo/api/vehicle_data/YV2AS02A76B424444').then(response => {
        return response.data.map(coordinatesDataEntry => {
            return [
                coordinatesDataEntry.latitude,
                coordinatesDataEntry.longitude,
            ]
        });
    }).then((path) => {
        const center = path[path.length - 1];
        const Map = L.map('map').setView(center, 13);

        L.marker(center).addTo(Map);
        L.polyline(path).addTo(Map);

        L.tileLayer('https://api.maptiler.com/maps/streets/256/{z}/{x}/{y}.png?key=0FpdZwCBDclducjXH2WE', {
            attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>',
        }).addTo(Map);
    });
};

window.onload = () => {
    loadMap();
};