/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import { store } from './store.js'
import { router } from './router.js'

import Header from './components/layouts/Header.vue'
import Footer from './components/layouts/Footer.vue'
Vue.component('component-header', Header)
Vue.component('component-footer', Footer)
Vue.component('app-component', require('./components/layouts/App.vue').default);

const app = new Vue({
    router,
    store,
    el: '#app',
});
