
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import { Form, HasError, AlertError } from 'vform';

import swal from 'sweetalert2'

window.swal = swal;

const toast = swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});

window.toast = toast;
window.Form  = Form;

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

import VueRouter from 'vue-router'

Vue.use(VueRouter);

let routes = [];

const router = new VueRouter({
    mode : 'history',
    routes
  });

window.Fire =  new Vue();

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('files', require('./components/Files.vue'));


const app = new Vue({
    el: '#app',
    router,
});
