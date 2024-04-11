import axios from "axios";
import router from '@/router/index.js';

const TOKEN_KEY = 'x_xsrf_token';
const USER_DATA = 'user_data';

export default {
    namespaced: true,
    state() {
        return {
            token: localStorage.getItem(TOKEN_KEY),
            user: JSON.parse(localStorage.getItem(USER_DATA)),
        }
    },
    mutations: {
        setToken(state, token) {
            state.token = token;
            localStorage.setItem(TOKEN_KEY, token);
        },
        async logout(state) {
            state.token = null;
            localStorage.removeItem(TOKEN_KEY);
            localStorage.removeItem(USER_DATA);
            await axios.get('/sanctum/csrf-cookie');
        },
        setUser(state, user) {
            state.user = user;
            localStorage.setItem(USER_DATA, JSON.stringify(user));
        },
    },
    actions: {
        async login({ commit, dispatch }, payload) {

            try {
                await axios.get('/sanctum/csrf-cookie');
                const data = await axios.post('/api/login', {
                    email: payload.email,
                    password: payload.password,
                });
                localStorage.setItem('x_xsrf_token', data.config.headers['X-XSRF-TOKEN']);
                commit('setToken', data.config.headers['X-XSRF-TOKEN']);
                commit('setUser', data.data.user);
                router.push({name: 'Dashboard',});
            } catch(e) {
                dispatch('notification/displayMessage', {
                    value: 'E-mail или пароль не верны',
                    type: 'error',
                }, {root:true});
            }


        },
        async logout({commit}) {
            // try{
                await axios.post('/logout');
                localStorage.removeItem('x_xsrf_token');
                router.push('/');
                commit('logout');
            // } catch (e) {
            //     console.log(e.message);
            // }

        }
    },
    getters: {
        token(state) {
            return state.token;
        },
        user(state) {
          return state.user;
        },
        isAuthenticated(_, getters) {
            return !!getters.token && !!getters.user;
        }
    }
}
