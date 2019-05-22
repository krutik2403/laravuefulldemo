/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import VueRouter from 'vue-router'
  
Vue.use(VueRouter)

import Header from './components/layouts/Header.vue'
import Footer from './components/layouts/Footer.vue'
Vue.component('component-header', Header)
Vue.component('component-footer', Footer)
   
const routes = [
  { path: '/', component: require('./components/Home.vue').default },
  { path: '/login', component: require('./components/auth/Login.vue').default },
  { path: '/register', component: require('./components/auth/Register.vue').default },
  { path: '/dashboard', component: require('./components/user-components/Dashboard.vue').default },
]
  
const router = new VueRouter({
    routes 
})

Vue.component('app-component', require('./components/layouts/App.vue').default);


const app = new Vue({
    router,
    el: '#app',
});
