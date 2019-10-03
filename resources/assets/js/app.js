import Vue from 'vue'
import VueRouter from 'vue-router'
import Vuex from 'vuex';

Vue.use(VueRouter);
Vue.use(Vuex);

import App from './views/App'
import Login from './views/Login'
import Register from './views/Register'
import Home from './views/Home'

/**
 * Для сохранения состоняия между роутами
 * @type {Store<{user: null, token: null}>}
 */
export const store = new Vuex.Store({
    state: {
        user: null,
        api_token: null,
    },
    getters: {
        user: state => {
            return state.user;
        },
        apiToken: state => {
            return state.api_token;
        },
    },
    mutations: {
        user: (state, payload) => {
            state.user = payload;
        },

        apiToken: (state, payload) => {
            state.api_token = payload;
        },

        initialiseState(state) {
            // Check if the ID exists
            if(localStorage.getItem('user') && localStorage.getItem('api_token')) {
                state.user = JSON.parse(localStorage.getItem('user'));
                state.api_token = localStorage.getItem('api_token');
                /*// Replace the state object with the stored item
                this.replaceState(
                    Object.assign(state, JSON.parse(localStorage.getItem('store')))
                );*/
            }
        }

    },
    actions: {},
});

const router = new VueRouter({
    history: true,
    routes: [
        {
            path: '/',
            name: 'home',
            component: Home
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
        },
    ],
});

const app = new Vue({
    el: '#app',
    components: {App},
    router,
    store,
    beforeCreate() {
        this.$store.commit('initialiseState');
    }
});