/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
// start the Stimulus application
import './bootstrap';
import { createApp } from 'vue';
import Navbar from './js/components/Navbar.vue';

import('./third-party/bootstrap-5.1.3-dist/js/bootstrap.js');
createApp({
    data() {
        return {
            // count: 0
        };
    },
    components: {
        Navbar,
    },
}).mount('#navbar');

if (document.querySelectorAll('.form__delete').length > 0) {
    import('./js/services/SweetAlertOnDeleteAction.js');
}
