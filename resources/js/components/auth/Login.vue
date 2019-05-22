<template>
    
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="alert alert-success" id="notification-success" style="display:none;"></div>
            <div class="alert alert-danger" id="notification-error" style="display:none;"></div>
            <div class="card">
                <div class="card-header">Login Component</div>
                <div class="card-body">
                    <form @submit.prevent="submitLogin" action="">
                        <div class="form-group">
                            <input type="text" name="email" id="email" placeholder="Email" class="form-control" required v-model="user.email">                                
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" id="password" placeholder="Password" class="form-control" required v-model="user.password"> 
                        </div>

                        <div class="text-left">
                            <button class="btn btn-secondary btn-block">Login</button>
                            <a href="" class="btn btn-danger btn-block">Don't have an account?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
    
</template>

<script>
export default {
    data() {
        return {
            user: {
                email: '',
                password: ''
            }            
        }
    },

    methods: {
        submitLogin() {
            fetch('api/user/login', {
                method: 'POST',
                body: JSON.stringify(this.user),
                headers: {
                    'content-type' : 'application/json'
                }
            })
            .then(res => res.json())
            .then(res => {
                if(res.status == 1) {
                    this.$router.push('/dashboard')
                } else {
                    $('#notification-error').html(res.message);
                    $('#notification-error').show();
                }
            })
            .catch(error => console.log(error))
        }
    }
}
</script>
