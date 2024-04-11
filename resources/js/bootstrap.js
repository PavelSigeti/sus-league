import _ from 'lodash';
window._ = _;

import store from './store/index.js';
import router from './router/index.js';
import {ProgressFinisher, useProgress} from '@marcoschulte/vue3-progress';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;
window.axios.interceptors.response.use(null, async (error) => {
    if ( (error.response.status === 401 || error.response.status === 419) && router.currentRoute._value.name !== 'Home') {
        await axios.get('/sanctum/csrf-cookie');
        store.dispatch('auth/logout');
        router.push({name: 'Login'});
    }
    if(error.response.status === 500) {
        store.dispatch('notification/displayMessage', {
            value: 'Ошибка (500) в получении/обработке данных',
            type: 'error',
        });
    }

    return Promise.reject(error)
});

const progresses = [];

window.axios.interceptors.request.use(config => {
    progresses.push(useProgress().start());
    return config;
});

window.axios.interceptors.response.use(resp => {
    progresses.pop()?.finish();
    return resp;
}, (error) => {
    progresses.pop()?.finish();
    return Promise.reject(error);
});


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
