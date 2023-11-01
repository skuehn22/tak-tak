require('./bootstrap');
import '../css/app.css';

window.Vue = require('vue');

Vue.component('todo-list-component', require('./components/TodoList.vue').default);

const app = new Vue({
    el: '#app',
});
