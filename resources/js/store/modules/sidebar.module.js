export default {
    namespaced: true,
    state() {
        return {
            status: false,
        }
    },
    mutations: {
        toggleStatus(state) {
            state.status = !state.status;
        },
    },
    actions: {
        toggleStatus({ commit }) {
            commit('toggleStatus');
        },
    },
    getters: {
        status(state) {
            return state.status;
        },
    },
}
