<template>
    <div>
        <div class="blog-masthead">
            <div class="container">
                <nav class="nav">
                    <router-link class="nav-link" :to="{ name: 'home' }">Стена</router-link>
                    <router-link :to="{ name: 'register' }" class="nav-link" v-if="!user">Зарегистрироваться</router-link>
                    <router-link :to="{ name: 'login' }" class="nav-link" v-if="!user">Войти</router-link>
                    <span class="nav-link ml-auto" v-if="!user">%username%</span>
                    <span class="nav-link ml-auto" v-if="user">{{user.name}}</span>
                    <a class="nav-link" v-on:click="logout" href="#" v-if="user">Выйти</a>
                </nav>
            </div>
        </div>

        <div class="blog-header">
            <div class="container">
                <h1 class="blog-title">{{page_title}}</h1>
                <p class="lead blog-description">{{page_description}}</p>
            </div>
        </div>

        <router-view></router-view>

    </div>
</template>
<script>
    export default {
        data() {
            return {
                page_title: 'Стена',
                page_description: 'Место, где каждый может высказаться',
                loading: false,
                messages: null,
                error: null,
            };
        },
        computed: {
            user() {
                return this.$store.getters.user;
            },
            apiToken() {
                return this.$store.getters.apiToken;
            },
        },
        methods: {
            logout() {
                this.$store.commit('user', null);
                this.$store.commit('apiToken', null);
                localStorage.removeItem('api_token');
                localStorage.removeItem('user');
            }
        }
    }
</script>
