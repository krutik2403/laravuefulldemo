<template>
    <div class="row">
        <div class="col-md-12">
            <!-- <h4 class="mb-5 mt-5">Wolcome to Dashboard {{ currentUser.name }} !!</h4> -->
            <div class="alert alert-danger" id="notification-error" style="display:none;"></div>
            
            <!-- Modal -->
            <div class="modal fade" id="addPostModal" tabindex="-1" role="dialog" aria-labelledby="addPostModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Post</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form @submit.prevent="saveTodo" action="">
                            <div class="modal-body">    
                                <div class="form-group">
                                    <input type="text" class="form-control" v-model="todo.title" required>
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" cols="30" rows="2" v-model="todo.description" required></textarea>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3"><input type="file" accept=".png,.jpg,.jpeg" v-on:change="onImageChange" class="form-control-file"></div>
                                    <div class="col-md-9"><img :src="todo.image" alt="" class="img-responsive" height="70" width="90"></div>
                                </div>                                                            
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <nav aria-label="Page navigation example">
                <ul class="pagination"> 
                    <li class="page-item" v-bind:class="[{disabled: !pagination.prev_page_url}]"><button type="button" class="page-link" @click="fetchTodos(pagination.prev_page_url)">Previous</button></li>
                    <li class="page-item disabled"><a class="page-link test-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
                    <li class="page-item" v-bind:class="[{disabled: !pagination.next_page_url}]"><button type="button" class="page-link" @click="fetchTodos(pagination.next_page_url)">Next</button></li>                
                    <li class="ml-5"><button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addPostModal">Add Post</button></li>               
                </ul>
            </nav>

            <div class="row mb-3">
                <div class="col-md-4" v-for="todo in todos" v-bind:key="todo.id">
                    <div class="card">
                        <img :src="todo.image_url" class="img-responsive" style="width: 358px;height: 200px;">
                        <div class="card-body">
                            <h5 class="card-title">{{ todo.title }}</h5>
                            <p class="card-text">{{ todo.description.substring(0,100) + "..." }}</p>                            
                            <a @click="editTodo(todo)"><i class="fa fa-edit"></i></a>
                            <a @click="removeTodo(todo.id)"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                </div>                
            </div>
            
            <!-- <div v-for="todo in todos" v-bind:key="todo.id" class="alert alert-warning">
                {{ todo.title }}
                
                <div class="text-right">
                    <a @click="editTodo(todo)"><i class="fa fa-edit"></i></a>
                    <a @click="removeTodo(todo.id)"><i class="fa fa-trash"></i></a>
                </div>
            </div> -->

            <nav aria-label="Page navigation example">
                <ul class="pagination">                
                    <li class="page-item" v-bind:class="[{disabled: !pagination.prev_page_url}]"><button type="button" class="page-link" @click="fetchTodos(pagination.prev_page_url)">Previous</button></li>            
                    <li class="page-item disabled"><a class="page-link test-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
                    <li class="page-item" v-bind:class="[{disabled: !pagination.next_page_url}]"><button type="button" class="page-link" @click="fetchTodos(pagination.next_page_url)">Next</button></li>                
                </ul>
            </nav>
        </div>
    </div>
</template>

<style>
    .image-div {
        width: 358px;
        height: 200px;        
        padding: 2px;   
    }
</style>


<script>
export default {
    data() {
        return {
            todos: [],
            todo: {
                id: '',
                title: '',
                image: '',
                description: ''                
            },
            editTodoItem: false,            
            pagination: {},
            current_page_url: '/api/todo'
        }
    },

    methods: {
        onImageChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
        },

        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.todo.image = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        fetchTodos(page_url_param) {            
            let pageurl = page_url_param ? page_url_param : this.current_page_url ? this.current_page_url : '/api/todo';
            this.current_page_url = pageurl;
            fetch(pageurl)
            .then(res => res.json())
            .then(res => {
                this.todos = res.data.todos.data;                    
                this.makePagination(res.data.todos)
            }).catch(error => console(error));
        },

        makePagination(data) {
            
            let pagination = {
                current_page: data.current_page,
                last_page: data.last_page,
                next_page_url: data.next_page_url,
                prev_page_url: data.prev_page_url
            };

            this.pagination = pagination;
            console.log(this.pagination.prev_page_url);
            console.log(this.pagination.next_page_url);
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
                    if( this.editTodoItem != true ) {
                        this.current_page_url = ""    
                    }
                    this.flushTodo();
                    this.fetchTodos();
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
                    this.current_page_url = ""
                    this.fetchTodos();                                        
                })
                .catch(error => console.log(error));
            }
        },

        editTodo(todo) {
            this.editTodoItem = true;
            this.todo.id = todo.id;
            this.todo.title = todo.title;
            this.todo.image = todo.image_url;
            this.todo.description = todo.description;
            $('#addPostModal').modal('toggle');
        },

        flushTodo() {
            $('#addPostModal').modal('hide');
            $('.modal-backdrop').hide();
            this.todo.id = ""
            this.todo.title = ""
            this.todo.image = ""
            this.todo.description = ""
            this.editTodoItem = false            
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

