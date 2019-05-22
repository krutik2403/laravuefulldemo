<template>
    
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="alert alert-success" id="notification-success" style="display:none;"></div>
            <div class="alert alert-danger" id="notification-error" style="display:none;"></div>
            <div class="card">
                <div class="card-header">Register Component</div>
                <div class="card-body">
                    <form @submit.prevent="submitRegisterForm" action="">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" placeholder="eg. Krutik Patel" class="form-control" v-model="user.name">                                
                        </div>
                        <div class="form-group">
                            <label for="name">Email</label>
                            <input type="text" name="email" id="email" placeholder="eg. kkrutikk@gmail.com" class="form-control" v-model="user.email">                                
                        </div>
                        <div class="form-group">
                            <label for="name">Password</label>
                            <input type="password" name="password" id="password" placeholder="*********" class="form-control" v-model="user.password"> 
                        </div>
                        <div class="form-group">
                            <label for="name">Comfirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="*********" class="form-control" v-model="user.password_confirmation"> 
                        </div>
                        <div class="text-left">
                            <button class="btn btn-secondary btn-block">Register</button>
                            <a href="" class="btn btn-success btn-block">Already have an account?</a>
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
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
            }            
        }
    },

    methods: {
        submitRegisterForm() {
            fetch('api/user/register', {
                    method: 'POST',
                    body: JSON.stringify(this.user),
                    headers: {
                        'content-type' : 'application/json'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    $('.alert').hide();
                    $('.alert').html();
                    if(res.status == 1) {
                        $('#notification-success').html(res.message);
                        $('#notification-success').show();
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
