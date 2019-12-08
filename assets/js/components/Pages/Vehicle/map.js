import axios from 'axios';
import L from 'leaflet';

const loadMap = () => {
    axios.get('/fake/api/vehicle_data/YS2R4X20002022235').then(response => {
        return response.data.map(coordinatesDataEntry => {
            return [
                coordinatesDataEntry.latitude,
                coordinatesDataEntry.longitude,
            ]
        });
    }).then((path) => {
        let currentPath = [];

        currentPath.push(path[0]);
        path.shift();

        setInterval(() => {
            currentPath.push(path.shift());

            L.polyline(currentPath).addTo(Map);
            Map.panTo(currentPath[currentPath.length - 1]);

            Marker.setLatLng(currentPath[currentPath.length - 1]);
        }, 200);

        const Map = L.map('map').setView(currentPath[currentPath.length - 1], 13);
        L.tileLayer('https://api.maptiler.com/maps/streets/256/{z}/{x}/{y}.png?key=0FpdZwCBDclducjXH2WE', {
            attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>',
        }).addTo(Map);

        const Marker = L.marker(currentPath[currentPath.length - 1]).addTo(Map);
    });
};

window.onload = () => {
    loadMap();
};