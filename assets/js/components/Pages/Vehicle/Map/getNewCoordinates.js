import axios from 'axios';

export default function () {
    const vehicleVin = window.vehicleVin;
    let timestamp = window.timestamp;

    axios.get('/api/vehicle_data/' + vehicleVin, {
        params: {
            timestamp: timestamp,
        }
    }).then(response => {
        window.coordinates = response.data.coordinates;
        window.timestamp = response.data.timestamp;
    });
};