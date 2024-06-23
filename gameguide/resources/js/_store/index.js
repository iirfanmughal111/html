import Vue from 'vue';
import Vuex from 'vuex';
import createPersistedState from "vuex-persistedstate";
import SecureLS from "secure-ls";
const ls = new SecureLS({ isCompression: false });

import { alert } from './alert.module';
//import { account } from './account.module';
import { inbox } from './chat/inbox.module';

Vue.use(Vuex);

const initialState = {
    //account: account.state,
   // users: users.state,
    alert: alert.state,
    inbox: inbox.state,
}

export const store = new Vuex.Store({
    modules: {
        alert,
      //  account,
     //   users,
        inbox
    },
    plugins: [
	    createPersistedState({
			storage: {
				getItem: key => ls.get(key),
				setItem: (key, value) => ls.set(key, value),
				removeItem: key => ls.remove(key)
			}
	    })
	],
    mutations: {
        reset (state) {
            Object.keys(state).forEach(key => {
                Object.assign(state[key], initialState[key])
            })
        },
    },
    actions:{
        resetState({commit}){
            commit('reset');
        }
    }
});
