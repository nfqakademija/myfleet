import initializeMap from './initializeMap';
import closeModal from "../Modal/closeModal";

window.onload = () => {
    if (document.getElementById('map') !== null) {
        initializeMap();
    }
};