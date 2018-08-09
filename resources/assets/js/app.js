
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// import css, necessary lib
import 'bootstrap/dist/css/bootstrap.min.css';
import './assets/chat.css';
import 'v-toaster/dist/v-toaster.css';

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import App from './components/ChatComponent.vue';
import Toaster from 'v-toaster'

Vue.use(Toaster, {timeout: 3000});

new Vue({
    el: '#app',
    render: h => h(App)
});
