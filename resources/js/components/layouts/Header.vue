<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <h3>LaraVue</h3>        
        <router-link to="/"><a class="nav-link">Home</a></router-link>
        <router-link to="/login"><a class="nav-link">Login</a></router-link>
        <router-link to="/register"><a class="nav-link">Register</a></router-link>
        <button class="btn btn-info text-right" @click="logout">Logout</button>
    </nav>    
</template>

<script>
export default {
    data() {
        return {
            
        }
    },

    methods: {
        logout() {
            this.$router.push('/')
        }
    }
}
</script>

