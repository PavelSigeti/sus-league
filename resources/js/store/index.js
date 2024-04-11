import {createStore} from 'vuex';
import auth from './modules/auth.module.js';
import notification from './modules/notification.module.js';
import sidebar from './modules/sidebar.module.js';

export default createStore({
    state() {
        return {

        }
    },
    mutations: {

    },
    actions: {

    },
    modules: {
        auth, notification, sidebar,
    }
});
