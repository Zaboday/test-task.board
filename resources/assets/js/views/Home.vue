<template>

    <div class="container">

        <div class="row mb-5">

            <div class="col-sm-8 blog-main">

                <div v-if="canCreateMessage()" class="mb-4">
                    <a v-if="!message_text_is_open" href="#" class="btn btn-primary" @click="message_text_is_open = true">Написать на стене</a>
                </div>

                <form class="mb-4 border-bottom" v-if="canCreateMessage() && message_text_is_open">
                    <h3>Написать на стене</h3>

                    <div class="form-group">

                        <div class="form-group">
                            <label for="message_title">Заголовок сообщение</label>
                            <input type="text" class="form-control" name="message_title" id="message_title" placeholder="Заголовок"
                                   v-model="message_title"
                                   v-bind:class="{ 'is-invalid': messageTitleError !== null }">
                            <p v-if="messageTitleError" class="invalid-feedback">{{messageTitleError}}</p>
                        </div>

                        <div class="form-group">
                            <label for="message_text">Текст сообщения:</label>
                            <textarea id="message_text" name="message_text" class="form-control"
                                      placeholder="Ваше сообщение" rows="4" v-model="message_text"
                                      required="required" v-bind:class="{ 'is-invalid': messageTextError !== null }"></textarea>
                            <p v-if="messageTextError" class="invalid-feedback">{{messageTextError}}</p>
                        </div>
                    </div>

                    <input type="submit" class="btn btn-success btn-send" value="Отправить сообщение" @click="handleSubmitMessage"/> &nbsp;&nbsp;&nbsp;
                    <input type="submit" class="btn btn btn-light" value="Омена" @click="cancelMessage()"/>

                    <hr/>
                </form>

                <div class="alert alert-danger alert-dismissable" v-if="error">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true" v-on:click="error = null">&times;</button>
                    <strong>Ошибка!</strong> {{ error }}
                </div>

                <div v-if="messages.length > 0">
                    <div class="blog-post" v-for="{ id, text, title, created_at, user } in messages">

                        <h2 class="blog-post-title">{{id}} {{title}}</h2>
                        <p class="blog-post-meta">Опубликован {{created_at}}. Автор: {{user.name}}</p>

                        <p>{{text}}</p>
                        <p>
                            <a v-on:click="deleteMessage(id)" href="#" v-if="canDeleteMessage(user.id)" class="btn-success btn-sm">Удалить</a>
                        </p>

                    </div>

                </div>

                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item"><a class="page-link" v-on:click="prevPage()" :disabled="!hasPrevPage()" href="#">&larr;</a></li>
                        <li class="page-item" v-for="i in (page, last_page)" v-bind:class="{ active: (i === page) }">
                            <a class="page-link" v-on:click="setPage(i)" href="#">{{ i }}</a>
                        </li>
                        <li class="page-item"><a class="page-link" v-on:click="nextPage()" :disabled="!hasNextPage()" href="#">&rarr;</a></li>
                    </ul>
                </nav>

            </div>

            <div class="col-sm-3 offset-sm-1 blog-sidebar" v-if="messages.length > 0">
                <div class="sidebar-module sidebar-module-inset">
                    <h5>Сортировка</h5>
                    <p v-if="sortByIdAsc === 0"><a href="#" @click="changeSort(1)">По возрастанию</a></p>
                    <p v-if="sortByIdAsc === 1"><a href="#" @click="changeSort(0)">По убыванию</a></p>
                    <h5>Статистика страницы</h5>
                    <p>Всего постов на странице: {{messages.length}}</p>
                    <p>Дата первого:<br/>{{messages[0].created_at}}</p>
                    <p>Дата последнего:<br/>{{messages[messages.length - 1].created_at}}</p>
                    <p>Автор первого:<br/>{{messages[0].user.name}}</p>
                    <p>Автор последнего:<br/>{{messages[messages.length - 1].user.name}}</p>
                </div>
                <!-- /.blog-sidebar -->

            </div>
        </div>
    </div>
</template>
<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                page_title: 'Стена',
                page_description: 'Место, где каждый может высказаться',
                loading: false,
                messages: [],
                message_text: null,
                message_title: null,
                message_text_error: null,
                message_title_error: null,
                message_text_is_open: false,
                messageTextError: null,
                messageTitleError: null,
                error: null,
                page: 1,
                last_page: 1,
                sortByIdAsc: 0,
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
        created() {
            this.fetchData();
        },
        methods: {
            changeSort(direction) {
                this.sortByIdAsc = direction;
                this.fetchData();
            },
            canDeleteMessage(id) {
                if (!this.authorizedUser) {
                    return false;
                }
                return this.authorizedUser.is_admin || this.authorizedUser.id === id;
            },
            canCreateMessage() {
                if (this.authorizedUser) {
                    return true;
                }
                return false;
            },
            fetchData() {
                this.messages = [];
                this.error = null;
                this.loading = true;
                axios
                    .get('/api/messages?page=' + this.page + '&sort=' + this.sortByIdAsc,
                        {headers: {Authorization: this.getAuthString()}})
                    .then(response => {

                        this.loading = false;
                        this.messages = response.data.data;

                        if (response.headers['x-count'] === undefined) {
                            this.last_page = 1;
                        } else {
                            this.last_page = parseInt(parseInt(response.headers['x-count']) / 3) + 1;
                            if (this.last_page <= 0) {
                                this.last_page = 1;
                            }
                        }

                        if (this.page === 1 && this.messages.length === 0) {
                            this.message_text_is_open = true;
                        }
                    })
                    .catch(error => {
                        this.loading = false;
                        this.error = error.response.data.message || error.message;
                    });
            },
            handleSubmitMessage(e) {
                e.preventDefault();
                this.message_text_error = this.messageTextError = this.message_title_error = this.messageTitleError = null;
                axios
                    .post('/api/messages', {text: this.message_text, title: this.message_title}, {headers: {Authorization: this.getAuthString()}})
                    .then(response => {
                        this.message_text = null;
                        this.message_title = null;
                        this.setPage(1);
                    })
                    .catch(error => {
                        this.message_text_error = error.response.data.message || error.message;
                        //this.message_title_error = error.response.data.title;
                        this.riseMessageErrors(error.response.data.errors);
                    });
            },
            riseMessageErrors(errors) {
                if (!errors) {
                    return;
                }
                if (errors.hasOwnProperty('text')) {
                    this.messageTextError = errors['text'].join(' ');
                }
                if (errors.hasOwnProperty('title')) {
                    this.messageTitleError = errors['title'].join(' ');
                }
            },
            cancelMessage() {
                this.message_text_is_open = false;
                this.message_text = this.messageTextError = null;
                this.message_title = this.messageTitleError = null;
            },
            setPage(goToPage) {
                this.page = goToPage;
                this.fetchData();
            },
            nextPage() {
                if (!this.hasNextPage()) {
                    return;
                }
                this.page++;
                this.fetchData();
            },
            prevPage() {
                if (!this.hasPrevPage()) {
                    return;
                }
                this.page--;
                this.fetchData();
            },
            hasNextPage() {
                return this.page + 1 <= this.last_page;
            },
            hasPrevPage() {
                return this.page - 1 >= 1;
            },
            getAuthString() {
                if (!this.apiToken) {
                    return;
                }
                return 'Bearer ' + this.apiToken;
            },
            deleteMessage(id) {
                this.error = null;
                this.loading = true;
                axios
                    .delete('/api/messages/' + id, {headers: {Authorization: this.getAuthString()}})
                    .then(response => {
                        this.loading = false;
                        this.fetchData();
                    })
                    .catch(error => {
                        this.loading = false;
                        this.error = error.response.data.message || error.message;
                    });
            }
        }
    }
</script>
