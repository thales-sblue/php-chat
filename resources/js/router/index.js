import { createRouter, createWebHistory } from 'vue-router';
import Login from '@/views/Login.vue';
import Home from '@/views/Home.vue';
import CreateClient from '@/views/CreateClient.vue';
import ChatPage from '@/views/ChatPage.vue';

const routes = [
    { path: '/login', name: 'login', component: Login },
    { path: '/home', name: 'home', component: Home },
    { path: '/create-client', name: 'create-client', component: CreateClient },
    { path: '/conversations/:id/messages', name: 'chat', component: ChatPage },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
