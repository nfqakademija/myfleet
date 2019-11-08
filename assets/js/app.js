import Vue from 'vue';
import App from './App';
import Vehicle from "./components/Pages/Vehicle/Vehicle";
import TheTable from "./components/Pages/List/Table";
import TheHeader from "./components/TheHeader";

/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
const $ = require('jquery');
require('bootstrap');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});

new Vue({
    el: '#app',
    components: {
        Vehicle,
        TheHeader,
        TheTable,
    }
});