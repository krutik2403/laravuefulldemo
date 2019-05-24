<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <img src="../../assets/vuejs.png" style="width:2%; margin-right:5px;">
        <img src="../../assets/laravel.jpg" style="width:2%;">        
        <router-link to="/"><a class="nav-link">Home</a></router-link>
        <router-link to="/dashboard"><a class="nav-link" v-if="isLoggedIn == true">Dashboard</a></router-link>
        <router-link to="/login"><a class="nav-link" v-if="isLoggedIn == false">Login</a></router-link>
        <router-link to="/register"><a class="nav-link" v-if="isLoggedIn == false">Register</a></router-link>
        <button class="btn btn-info text-right" v-if="isLoggedIn == true" @click="logout">Logout</button>
    </nav>    
</template>

<style>
    a, a:hover {
        color: white;
    }
</style>


<script>
export default {
    data() {
        return {
            token: ''    
        }
    },

    mounted() {
        
        if ( this.$store.state.token != '' ) {
            fetch('api/me', {
                method: 'GET',                
                headers: {
                    'content-type': 'application/json',
                    'Authorization': 'Bearer ' + this.$store.state.token
                }
            })
            .then(res => res.json())
            .then(res => {
                if(res.status == 0) {
                    this.$store.commit('logout')
                    this.$router.push('/login')
                }

                this.$store.state.isLoggedIn = true
                this.$store.state.currentUser = res.data.user
                this.$store.state.token = res.data.user                                
            })
            .catch(error => console.log(error))
        }
    },

    computed: {
        isLoggedIn() {
            return this.$store.state.isLoggedIn
        }
    },

    methods: {
        logout() {
            this.$store.commit('logout')
            this.$router.push('/')
        }
    }
}
</script>

