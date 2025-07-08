import { createRouter, createWebHistory } from 'vue-router';
import Login from '../components/Login.vue';
import Home from '../components/Home.vue';
import CreateClient from '../components/CreateClient.vue';

const routes = [
    { path: '/login', name: 'login', component: Login },
    { path: '/home', name: 'home', component: Home },
    { path: '/create-client', name: 'create-client', component: CreateClient },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
