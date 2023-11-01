<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-6">
                <div id="todo-container" class="p-3">
                    <section id="todo-header" class="mt-3">
                        <h3 class="text-left">Laravel Vue TodoList App123</h3>
                    </section>
                    <section id="todo-errors">
                        <div v-if="createTodoForm.errors.length > 0" class="alert alert-warning alert-dismissible fade show" role="alert">
                            <span v-for="(error, index) in createTodoForm.errors" :key="index">{{ error }}</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div v-if="createTodoForm.isCreated" class="alert alert-success alert-dismissible fade show" role="alert">
                            <span>Todo item created successfully</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div v-if="editTodoForm.isUpdated" class="alert alert-success alert-dismissible fade show" role="alert">
                            <span>Todo item updated successfully</span>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </section>
                    <section id="add-todo-form" class="my-3">
                        <form>
                            <div class="d-flex justify-content-between align-items-center">
                                <input
                                    v-model="createTodoForm.name"
                                    v-on:keyup.enter="addTodo"
                                    minlength="5" maxlength="50"
                                    placeholder="Enter todo name and press enter"
                                    type="text" class="form-control mr-3">
                                <button v-if="createTodoForm.isSubmitting" class="btn btn-primary" type="button" disabled>
                                    <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                    <span class="sr-only">Loading...</span>
                                </button>
                                <button v-else @click.prevent="addTodo" class="btn">Add</button>
                            </div>
                        </form>
                    </section>
                    <section id="todo-actions"></section>
                    <section id="todo-list">
                        <ul class="list-group">
                            <div v-if="todos.isLoading" class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                            <li
                                v-if="!todos.isLoading && todos.data.length > 0"
                                v-for="todo in todos.data" :key="todo.uuid"
                                class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    {{ todo.name }}
                                    <span class="d-flex justify-content-between align-items-center">
                                        <a class="text-success mr-2" href="#" @click.prevent="showEditTodoForm(todo)">
                                            <i class="fa fa-edit"> </i>
                                        </a>
                                        <a class="text-danger" href="#" @click.prevent="destroy(todo)">
                                            <i class="fa fa-trash-o"></i>
                                        </a>
                                    </span>
                                </div>
                                <div class="d-flex w-100 justify-content-between">
                                    <small class="text-muted">Created</small>
                                    <small class="text-muted">{{ todo.created_at }}</small>
                                </div>
                            </li>
                            <li v-if="!todos.isLoading && todos.data.length === 0" class="list-group-item list-group-item-action list-group-item-warning">
                                No todo items found.
                            </li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="editTodoItemModal" tabindex="-1" role="dialog" aria-labelledby="editTodoItemModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTodoItemModalLabel">Edit TodoItem</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <section id="edit-todo-errors" class="mb-3">
                            <div v-if="editTodoForm.errors.length > 0" class="alert alert-warning alert-dismissible fade show" role="alert">
                                <span v-for="(error, index) in editTodoForm.errors" :key="index">{{ error }}</span>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        </section>
                        <section id="edit-todo">
                            <form>
                                <div class="d-flex justify-content-between align-items-center">
                                    <input
                                        v-model="editTodoForm.name"
                                        v-on:keyup.enter="updateTodo"
                                        minlength="5" maxlength="50"
                                        placeholder="Enter todo name and press enter"
                                        type="text" class="form-control mr-3">
                                    <button v-if="editTodoForm.isSubmitting" class="btn btn-primary" type="button" disabled>
                                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                        <span class="sr-only">Loading...</span>
                                    </button>
                                    <button v-else @click.prevent="updateTodo" class="btn btn-success">Save</button>
                                </div>
                            </form>
                        </section>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: 'TodoList',
    data() {
        return {
            todos: {
                isLoading: false,
                data: []
            },
            createTodoForm: {
                isSubmitting: false,
                isCreated: false,
                name: undefined,
                errors: []
            },
            editTodoForm: {
                isSubmitting: false,
                isUpdated: false,
                uuid: undefined,
                name: undefined,
                errors: []
            },
            error: undefined
        }
    },
    mounted() {
        this.loadTodos();
    },
    methods: {
        showEditTodoForm(todo) {
            this.editTodoForm.name = todo.name;
            this.editTodoForm.uuid = todo.uuid;
            $('#editTodoItemModal').modal('show');
        },
        loadTodos() {
            this.todos.isLoading = true;

            axios.get('/todos')
                .then((response) => {
                    this.todos.data = response.data;
                })
                .catch((error) => {
                    this.error = 'Unable to load todos. View log for more information';
                })
                .finally(() => {
                    this.todos.isLoading = false;
                })
        },
        addTodo() {
            this.createTodoForm.isSubmitting = true;
            axios.post('/todos', this.createTodoForm)
                .then((response) => {
                    this.createTodoForm.errors = [];
                    this.createTodoForm.name = undefined;
                    this.createTodoForm.isCreated = true;
                    this.loadTodos();
                })
                .catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.createTodoForm.errors = _.flatten(_.toArray(error.response.data.errors));
                    } else {
                        this.error = 'Unable to add todo. View log for more information';
                    }
                })
                .finally(() => {
                    this.createTodoForm.isSubmitting = false;
                })
        },
        updateTodo() {
            this.editTodoForm.isSubmitting = true;
            axios.put('/todos/${this.editTodoForm.uuid}', this.editTodoForm)
                .then((response) => {
                    $('#editTodoItemModal').modal('hide');
                    this.editTodoForm.name = undefined;
                    this.editTodoForm.uuid = undefined;
                    this.editTodoForm.isUpdated = true;
                    this.loadTodos();
                })
                .catch((error) => {
                    if (error.response && error.response.status === 422) {
                        this.editTodoForm.errors = _.flatten(_.toArray(error.response.data.errors));
                    } else {
                        this.error = 'Unable to update todo. View log for more information';
                    }
                })
                .finally(() => {
                    this.editTodoForm.isSubmitting = false;
                })
        },
        destroy(todoItem) {
            axios.delete('/todos/${todoItem.uuid}')
                .then((response) => {
                    this.loadTodos();
                })
                .catch((error) => {
                    this.error = 'Unable to delete todo. View log for more information';
                })
        }
    }
}
</script>
