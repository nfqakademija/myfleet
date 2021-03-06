import L from "leaflet";
import getNewCoordinates from "./getNewCoordinates";

export default function () {
    let coordinates = window.coordinates;

    const Map = L.map('map').setView(coordinates[coordinates.length - 1], 13);
    L.tileLayer('https://api.maptiler.com/maps/streets/256/{z}/{x}/{y}.png?key=0FpdZwCBDclducjXH2WE', {
        attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>',
    }).addTo(Map);

    L.polyline(coordinates).addTo(Map);
    const Marker = L.marker(coordinates[coordinates.length - 1]).addTo(Map);

    window.coordinates = [];

    setInterval(() => {
        getNewCoordinates();
    }, 10000);

    setInterval(() => {
        if (window.coordinates.length === 0) {
            return;
        }

        coordinates.push(window.coordinates.shift());

        L.polyline(coordinates).addTo(Map);
        Map.panTo(coordinates[coordinates.length - 1]);
        Marker.setLatLng(coordinates[coordinates.length - 1]);
    }, 600);
};