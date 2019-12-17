import axios from 'axios';

export default function () {
    const vehicleVin = window.vehicleVin;
    let timestamp = window.timestamp;

    axios.get('/api/vehicle_data/' + vehicleVin, {
        params: {
            timestamp: timestamp,
        }
    }).then(response => {
        if (response.data.coordinates) {
            response.data.coordinates.forEach(coordinate => {
                window.coordinates.push(coordinate);
            });
        }

        if (response.data.timestamp) {
            window.timestamp = response.data.timestamp;
        }
    });
};