/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

// import { create } from 'core-js/core/object';

ClassicEditor
    .create(document.querySelector('#article_form_content'))
    .catch( error => {
        console.log(error);
    });