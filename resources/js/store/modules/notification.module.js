export default {
    namespaced: true,
    state() {
        return {
            message: null,
            type: null,
        }
    },
    mutations: {
        setMessage(state, data) {
            state.message = data.value;
            state.type = data.type;
        },
        clearMessage(state) {
            state.message = null;
            state.type = null;
        }
    },
    actions: {
        displayMessage({ commit }, payload) {
            commit('clearMessage');
            commit('setMessage', payload);
            setTimeout(() => {
                commit('clearMessage');
            }, 5000);
        },
        clearMessage({ commit }) {
            commit('clearMessage');
        }

    },
    getters: {
        message(state) {
            return state.message;
        },
        type(state) {
            return state.type;
        }
    },
}
