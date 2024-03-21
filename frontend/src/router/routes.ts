import { createWebHistory, createRouter } from 'vue-router';

import Home from '../components/Home.vue';
import Register from '../components/Register.vue';
import Login from '../components/Login.vue';

const routes = [
    {
        path: '/home',
        component: Home,
        meta: { requiresAuth: true },
        name: 'home',
    },
    {
        path: '/register',
        component: Register,
        meta: { requiresAuth: false },
        name: 'register',
    },
    {
        path: '/login',
        component: Login,
        meta: { requiresAuth: false },
        name: 'login',
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, _, next) => {
    const isAuthenticated = localStorage.getItem('logged-in');

    // Prevent unauthenticated user from viewing protected paths.
    if ((to.meta.requiresAuth && !isAuthenticated) || to.path == '/') {
        return next({ name: 'login' });
    }

    // Prevent authenticated user from viewing login page.
    if ((isAuthenticated && !to.meta.requiresAuth) || to.path == '/') {
        return next({ name: 'home' });
    }

    next();
});

export default router;
