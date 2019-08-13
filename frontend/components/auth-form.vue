<template>
    <div class="auth-form">
        <div v-if="mode == 'login'">
            <h5 class="title text-center mb-5">Вход</h5>
            <form action="" method="post" @submit.prevent="onLogin">

                <b-form-group class="mb-3">
                    <b-input v-model="login.email" type="email" placeholder="Адрес эл. почты" trim required autofocus/>
                </b-form-group>

                <b-form-group class="mb-3">
                    <b-input v-model="login.password" type="password" placeholder="Пароль" trim required/>
                </b-form-group>

                <div class="alert alert-danger" v-if="error.login != ''">
                    {{error.login}}
                </div>

                <div class="mt-5 mb-5 d-flex justify-content-center">
                    <button class="button mr-2" type="submit" :disabled="checkLogin()">Отправить</button>
                </div>

                <div class="form-footer d-flex align-items-center justify-content-center flex-column">
                    <div class="login__note">Если у вас нет аккаунта, то</div>
                    <a class="link__registration" @click.prevent="mode = 'register'" aria-label="Registration">
                        зарегистрируйтесь
                    </a>
                    <div class="login__note mt-2">А если не помните свой пароль,</div>
                    <a class="link__registration" @click.prevent="mode = 'reset'" aria-label="Reset">
                        его можно сбросить
                    </a>
                </div>
            </form>
        </div>
        <div v-else-if="mode == 'register'">
            <h5 class="title text-center mb-5">Регистрация</h5>
            <form action="" method="post" @submit.prevent="onRegister" :disabled="loading">

                <b-form-group class="mb-2">
                    <b-input v-model="register.fullname" placeholder="Имя Фамилия" trim required autofocus/>
                </b-form-group>

                <b-form-group class="mb-2">
                    <b-input v-model="register.email" placeholder="Адрес эл. почты" trim required/>
                </b-form-group>

                <b-form-group class="mb-2">
                    <b-input v-model="register.password" type="password" placeholder="Пароль" trim required/>
                </b-form-group>

                <b-form-group class="mb-2">
                    <b-input v-model="register.instagram" placeholder="@instagram" trim/>
                </b-form-group>

                <div class="alert alert-danger" v-if="error.register != ''">
                    {{error.register}}
                </div>

                <div class="mt-5 mb-5 d-flex justify-content-center">
                    <button class="button mr-2" type="submit" aria-label="submit" :disabled="checkRegister()">
                        Отправить
                    </button>
                </div>
                <div class="form-footer d-flex align-items-center justify-content-center flex-column">
                    <div class="login__note">У вас уже есть логин? Тогда можно</div>
                    <a class="link__registration" @click.prevent="mode = 'login'" aria-label="Login">
                        войти на сайт
                    </a>
                </div>
            </form>
        </div>
        <div v-else>
            <h5 class="title text-center mb-5">Сброс пароля</h5>
            <form action="" method="post" @submit.prevent="onReset" :disabled="loading">

                <b-form-group class="mb-3">
                    <b-input v-model="reset.email" type="email" placeholder="Адрес эл. почты" trim required autofocus/>
                </b-form-group>

                <div class="alert alert-danger" v-if="error.reset != ''">
                    {{error.reset}}
                </div>

                <div class="mt-5 mb-5 d-flex justify-content-center ">
                    <button class="button mr-2" type="submit" aria-label="submit" :disabled="checkReset()">
                        Отправить
                    </button>
                </div>
                <div class="form-footer d-flex align-items-center justify-content-center flex-column">
                    <div class="login__note">Вспомнили пароль? Тогда</div>
                    <a class="link__registration" @click.prevent="mode = 'login'" aria-label="Login">
                        авторизуйтесь
                    </a>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'auth-form',
        data() {
            return {
                login: {
                    email: '',
                    password: '',
                },
                register: {
                    email: '',
                    fullname: '',
                    instagram: '',
                    password: '',
                },
                reset: {
                    email: '',
                },
                error: {
                    login: '',
                    register: '',
                    reset: '',
                },
                loading: false,
            }
        },
        props: {
            auth_mode: {
                type: String,
                required: false,
                default: 'login',
            },
        },
        computed: {
            mode: {
                get() {
                    return this.auth_mode
                },
                set(newValue) {
                    this.$emit('update:auth_mode', newValue);
                }
            }
        },
        methods: {
            updateValue: function (value) {
                this.$emit('update', value);
            },
            checkLogin() {
                return this.login.email == '' || this.login.password == '' || this.loading
            },
            checkRegister() {
                return this.register.fullname == '' || this.register.email == '' || this.loading
            },
            checkReset() {
                return this.reset.email == '' || this.loading
            },
            /*clearForms() {
                for (let i in ['login', 'register', 'reset']) {
                    if (!this[i].hasOwnProperty(i)) {
                        continue;
                    }
                    for (let i2 in this[i]) {
                        if (this[i].hasOwnProperty(i2)) {
                            this[i][i2] = '';
                        }
                    }
                    if (this.error[i] !== undefined) {
                        this.error[i] = '';
                    }
                }
            },*/
            onLogin() {
                this.error.login = '';
                this.loading = true;
                this.$auth.loginWith('local', {data: this.login}).then(() => {
                    this.loading = false;
                    this.$root.$emit('app::auth-form::login');
                    this.$notify.success({message: 'Добро пожаловать!'});
                    //this.clearForms();
                }).catch((res) => {
                    this.error.login = res.data;
                    this.loading = false;
                })
            },
            onRegister() {
                this.error.register = '';
                this.loading = true;
                this.$axios.post('security/register', this.register)
                    .then(() => {
                        this.loading = false;
                        this.login.email = this.register.email;
                        this.login.password = this.register.password;
                        this.onLogin();
                    })
                    .catch((res) => {
                        this.loading = false;
                        this.error.register = res.data;
                    })
            },
            onReset() {
                this.error.reset = '';
                this.loading = true;
                this.$axios.post('security/reset', this.reset)
                    .then(() => {
                        this.mode = 'login';
                        this.loading = false;
                        this.$notify.success({message: 'Для подтверждения сброса пароля вам нужно пройти по ссылке из нашего письма'});
                        this.$root.$emit('app::auth-form::reset');
                        //this.clearForms();
                    })
                    .catch((res) => {
                        this.loading = false;
                        this.error.reset = res.data;
                    })
            },
        },
        created() {
            //this.$root.$on('app::auth-form::clear', this.clearForms);
        }
    }
</script>