import axios from 'axios';

export const getData = (vinCode) => {
    axios.get('/demo/api/vehicle_data/', {
        params: vinCode,
    }).then(response => {
        console.log(response);
    })
};


const Map = L.map('map').setView([51.505, -0.09], 13);

L.tileLayer('https://api.maptiler.com/maps/streets/256/{z}/{x}/{y}.png?key=0FpdZwCBDclducjXH2WE', {
    attribution: '<a href="https://www.maptiler.com/copyright/" target="_blank">&copy; MapTiler</a> <a href="https://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap contributors</a>',
}).addTo(Map);

L.marker([51.505, -0.09]).addTo(Map);