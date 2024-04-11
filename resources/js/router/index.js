import { createRouter, createWebHistory } from 'vue-router';
import store from '@/store/index.js';
import routes from './routes.js';


const router = createRouter({
    history: createWebHistory(),
    routes,
    linkActiveClass: 'active',
    linkExactActiveClass: 'active',
});

router.beforeEach((to, from, next) => {
    const requireAuth = to.meta.auth;

    const token = store.getters['auth/isAuthenticated'];

    const redirectRoutes = ['Reset-password', 'Login', 'Reset', 'Register', ];

    if(redirectRoutes.includes(to.name) && token) {
        store.dispatch('auth/logout');
    }
    else if(token && to.name === 'Home') {
        next({name: 'Dashboard'});
    }
    else if(requireAuth && token) {
        next();
    } else if (requireAuth && !token) {
        next({name: 'Home',});
    } else {
        next();
}
});


export default router;
