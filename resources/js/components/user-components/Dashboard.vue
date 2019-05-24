<template>
    <div class="row">
        <div class="col-md-12">
            <h2>Wolcome to Dashboard {{ currentUser.name }} !!</h2>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {

        }
    },

    methods: {

    },  
    
    computed: {
        currentUser() {
            return this.$store.state.currentUser;
        }
    },

    mounted() {
        
    },

    created() {
        console.log('created')        
    },

    updated() {
        console.log('updated')
    },

    destroyed() {
        console.log('destroyed')        
    },
}
</script>

