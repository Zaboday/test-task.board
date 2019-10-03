<template>
    <div class="container">
        <div class="row mb-5">
            <div class="col-sm-12 blog-main">

                <div class="jumbotron" v-if="success">
                    <h1 class="display-3">Вы зарегистрированы</h1>
                    <p class="lead">Добро пожаловать в огромное сообщество великой стены</p>
                    <hr class="my-4">
                    <p>Для продолжения работы вам нужно авторизоваться</p>
                    <p class="lead">
                        <router-link :to="{ name: 'login' }" class="btn btn-primary btn-lg">Войти</router-link>
                    </p>
                </div>

                <form v-if="!success">
                    <div class="alert alert-danger alert-dismissable" v-if="error">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true" v-on:click="error = null">&times;</button>
                        <strong>Ошибка!</strong> {{ error }}
                    </div>

                    <h2 class="form-signin-heading">Регистрация</h2>

                    <div class="form-group">
                        <label for="email" class="sr-only">Email</label>
                        <input id="email" type="email" class="form-control" placeholder="Email" v-model="email" required autofocus v-bind:class="{ 'is-invalid': emailError !== null }">
                        <p v-if="emailError" class="invalid-feedback">{{emailError}}</p>
                    </div>

                    <div class="form-group">
                        <label for="user_login" class="sr-only">Имя пользователя</label>
                        <input id="user_login" type="email" class="form-control" placeholder="Имя пользователя" v-model="name" required autofocus v-bind:class="{ 'is-invalid': nameError !== null }">
                        <p v-if="nameError" class="invalid-feedback">{{nameError}}</p>
                    </div>

                    <div class="form-group">
                        <label for="user_password" class="sr-only">Пароль</label>
                        <input id="user_password" type="password" class="form-control" v-model="password" placeholder="Пароль" required v-bind:class="{ 'is-invalid': passwordError !== null }">
                        <p v-if="passwordError" class="invalid-feedback">{{passwordError}}</p>
                    </div>

                    <div class="form-group">
                        <label for="user_password_confirmation" class="sr-only">Повторите пароль</label>
                        <input id="user_password_confirmation" type="password" class="form-control" v-model="password_confirmation" placeholder="Повторите Пароль" required>
                    </div>
                    <button class="btn btn-lg btn-primary btn-block" type="submit" @click="handleSubmit">
                        Зарегистрироваться
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                page_title: 'Регистрация',
                page_description: 'Присоединяйтесь к большому сообществу',
                email: null,
                name: null,
                password: null,
                password_confirmation: null,
                loading: false,
                error: null,
                success: false,
                emailError: null,
                nameError: null,
                passwordError: null,
            };
        },
        computed: {
            authorizedUser() {
                return this.$store.getters.user;
            },
            apiToken() {
                return this.$store.getters.apiToken;
            },
        },
        methods: {
            resetErrors() {
                this.emailError = this.nameError = this.passwordError = null;
            },
            handleSubmit(e) {
                e.preventDefault();
                this.resetErrors();
                this.success = false;
                this.error = null;
                this.loading = true;
                axios
                    .post('/api/register', {
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.password_confirmation,
                        name: this.name,
                    })
                    .then(response => {
                        this.loading = false;
                        //this.updateUser(response.data.user, response.data.api_token);
                        this.success = true;

                    })
                    .catch(error => {
                        this.loading = false;
                        this.riseErrors(error.response.data.errors);
                        if (this.nameError === null && this.emailError === null && this.passwordError === null) {
                            this.error = error.response.data.message || error.message;
                        }
                    });
            },
            riseErrors(errors) {
                if (!errors) {
                    return;
                }
                if (errors.hasOwnProperty('email')) {
                    this.emailError = errors['email'].join(' ');
                }
                if (errors.hasOwnProperty('name')) {
                    this.nameError = errors['name'].join(' ');
                }
                if (errors.hasOwnProperty('password')) {
                    this.passwordError = errors['password'].join(' ');
                }
            }
        }
    }
</script>
