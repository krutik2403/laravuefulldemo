<template>
    <div class="row">
        <div class="col-md-12">
            <h4>Wolcome to Dashboard {{ currentUser.name }} !!</h4>
            <div class="alert alert-danger" id="notification-error" style="display:none;"></div>
            <form @submit.prevent="saveTodo" action="">
                <div class="form-group">
                    <input type="text" class="form-control" v-model="todo.title" required>
                </div>
                <div class="form-group">
                    <textarea class="form-control" cols="30" rows="2" v-model="todo.description" required></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-success btn-block">Save</button>
                </div>
            </form>

            <div v-for="todo in todos" v-bind:key="todo.id" class="alert alert-warning">
                {{ todo.title }}
                
                <div class="text-right">
                    <a @click="editTodo(todo)"><i class="fa fa-edit"></i></a>
                    <a @click="removeTodo(todo.id)"><i class="fa fa-trash"></i></a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            todos: [],
            todo: {
                id: '',
                title: '',
                description: ''                
            },
            editTodoItem: false,
            api_url: '',
            method: ''
        }
    },

    methods: {
        fetchTodos() {
            fetch('/api/todo')
            .then(res => res.json())
            .then(res => {
                this.todos = res.data.todos;                    
            }).catch(error => console(error));
        },

        saveTodo() {
            
            if( this.editTodoItem == true ) {

                this.api_url = 'api/todo/' + this.todo.id;
                this.method = "PUT";
            } else {

                this.api_url = 'api/todo';
                this.method = "POST";                
            }   
            
            fetch(this.api_url, {
                method: this.method,
                body: JSON.stringify(this.todo),
                headers: {
                    'content-type' : 'application/json'
                }
            })
            .then(res => res.json())
            .then(res => {
                if(res.status == 1) {
                    $('#notification-error').html('');
                    $('#notification-error').hide();
                    this.fetchTodos();
                    this.todo.id = ""
                    this.todo.title = ""
                    this.todo.description = ""
                    this.editTodoItem = false
                    this.api_url = ''
                    this.method = ''
                    
                } else {
                    $('#notification-error').html(res.message);
                    $('#notification-error').show();
                }
            })
            .catch(error => console.log(error))
        },

        removeTodo(id) {
            if(confirm('Are you sure want to delete?')) {
                fetch('api/todo/' + id, {
                    method: 'delete'
                })
                .then(res => res.json())
                .then(res => {
                    this.fetchTodos();
                })
                .catch(error => console.log(error));
            }
        },

        editTodo(todo) {
            this.editTodoItem = true;
            this.todo.id = todo.id;
            this.todo.title = todo.title;
            this.todo.description = todo.description;
        }
    },  
    
    computed: {
        currentUser() {
            return this.$store.state.currentUser;
        }
    },

    mounted() {

    },

    created() {
        this.fetchTodos();
    },

    updated() {
        console.log('updated')
    },

    destroyed() {
        console.log('destroyed')        
    },
}
</script>

