const state = {
    type: null,
    message: null,
    status: null
};

const actions = {
    success({ commit }, message) {
        commit('success', message);
    },
    error({ commit }, message) {
        commit('error', message);
    },
    clear({ commit }) {
        commit('clear');
    }
};

const mutations = {
    success(state, message) {
        state.type = 'alert-success';
        state.message = message;
        state.status = 'success';
    },
    error(state, message) {
        state.type = 'alert-danger';
        state.message = message;
        state.status = 'error';
    },
    clear(state) {
        state.type = null;
        state.message = null;
        state.status = null;
    }
};

export const alert = {
    namespaced: true,
    state,
    actions,
    mutations
};
