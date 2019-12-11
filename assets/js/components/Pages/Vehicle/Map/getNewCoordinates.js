import axios from 'axios';
import L from 'leaflet';

export default function (Map, Marker) {
    const vehicleVin = window.vehicleVin;

    axios.get('/fake/api/vehicle_data/' + vehicleVin).then(response => {
        return response.data.map(coordinatesDataEntry => {
            return [
                coordinatesDataEntry.latitude,
                coordinatesDataEntry.longitude,
            ];
        });
    }).then(path => {
        let newPath = [];

        setInterval(() => {
            if (path.length === 0) {
                return;
            }

            newPath.push(path.shift());

            L.polyline(newPath).addTo(Map);
            Map.panTo(newPath[newPath.length - 1]);
            Marker.setLatLng(newPath[newPath.length - 1]);
        }, 300);
    });
};