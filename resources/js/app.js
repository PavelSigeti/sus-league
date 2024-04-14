import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import {Vue3ProgressPlugin} from '@marcoschulte/vue3-progress';

import 'remixicon/fonts/remixicon.css';

const app = createApp(App)
    .use(router)
    .use(store)
    .use(Vue3ProgressPlugin)
    .mount('#app');
