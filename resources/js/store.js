import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);
export const store = new Vuex.Store({
    state: {
        currentUser: null,
        isLoggedIn: localStorage.getItem('token') ? true : false,
        token: localStorage.getItem('token') || '',
        status: null
    },
    
    mutations: {
        setLogin ( state, data ) {
            state.currentUser = data.user
            state.isLoggedIn = true
            localStorage.setItem('token', data.access_token)
            state.token = data.access_token                        
        },

        logout ( state ) {
            state.currentUser = null
            state.isLoggedIn = false
            state.token = ''
            localStorage.removeItem('token')            
        }
    },
})