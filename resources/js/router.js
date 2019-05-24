import Vue from 'vue';
import VueRouter from 'vue-router'
import { store } from './store.js'

Vue.use(VueRouter)

const routes = [
    { 
        path: '/', 
        name: 'home',
        component: require('./components/Home.vue').default
    },
    { 
        path: '/login', 
        name: 'login',
        component: require('./components/auth/Login.vue').default 
    },
    { 
        path: '/register', 
        name: 'register',
        component: require('./components/auth/Register.vue').default 
    },
    { 
        path: '/dashboard', 
        name: 'dashboard',
        component: require('./components/user-components/Dashboard.vue').default ,
        meta: { 
            requiresAuth: true
        }
    },
]
  
export const router = new VueRouter({
    routes 
})

router.beforeEach((to, from, next) => {
    
    if( to.meta.requiresAuth ) {        
        if ( store.state.isLoggedIn ) {
            next()
            return
        }
        next('/login')
    } else if ( to.path == '/login' || to.path == '/register' ) {
        if ( store.state.isLoggedIn ) {
            next('dashboard')
            return
        }

        next()
    } else {
        next() 
    }
})