<template>


    <div class="container">
        <div class="row mb-5">
            <div class="col-sm-12 blog-main">

                <form>

                    <div class="alert alert-danger alert-dismissable" v-if="error">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true" v-on:click="error = null">&times;</button>
                        <strong>Ошибка!</strong> {{ error }}
                    </div>

                    <div class="form-group">
                        <label for="email" class="sr-only">E-Mail Address</label>
                        <input id="email" type="email" class="form-control" placeholder="Email" v-model="email" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="password" class="sr-only">Пароль</label>
                        <input id="password" type="password" class="form-control" v-model="password" placeholder="Пароль" required>
                    </div>

                    <button class="btn btn-lg btn-primary btn-block" type="submit" @click="handleSubmit">
                        Войти
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
                page_title: 'Авторизация',
                page_description: 'С возвращением в большое сообщество имени великой стены',
                email: null,
                password: null,
                loading: false,
                messages: null,
                error: null
            };
        },
        methods: {
            handleSubmit(e) {
                e.preventDefault();
                this.error = null;
                this.loading = true;
                axios
                    .post('/api/login', {
                        email: this.email,
                        password: this.password
                    })
                    .then(response => {
                        this.loading = false;
                        this.updateUser(response.data.data.user, response.data.data.api_token);
                    })
                    .catch(error => {
                        this.loading = false;
                        this.error = error.response.data.message || error.message;
                    });
            },
            updateUser(user, api_token) {
                if (!user || !api_token) {
                    return;
                }
                // Store User
                this.$store.commit('user', user);
                this.$store.commit('apiToken', api_token);
                localStorage.setItem('user', JSON.stringify(user));
                localStorage.setItem('api_token', api_token);

                this.$router.push({name: 'home'});
            }
        }
    }
</script>
