<template>
  <div class="auth-form">
    <div v-if="mode == 'login' && forms.includes('login')">
      <slot name="login-title">
        <h5 class="title text-center mb-5">Вход через</h5>
      </slot>

      <div class="social-providers">
        <button class="vkontakte" @click="onPopup('vkontakte')">
          <fa :icon="['fab', 'vk']" />
        </button>
        <button class="instagram" @click="onPopup('instagram')">
          <fa :icon="['fab', 'instagram']" />
        </button>
      </div>

      <h4 class="text-center">или</h4>

      <b-form action="" method="post" @submit.prevent="onLogin">
        <b-form-group class="mb-3">
          <b-input v-model="login.email" type="email" placeholder="Адрес эл. почты" trim required autofocus />
        </b-form-group>

        <b-form-group class="mb-3">
          <b-input v-model="login.password" type="password" placeholder="Пароль" trim required />
        </b-form-group>

        <div v-if="error.login != ''" class="alert alert-danger">
          {{ error.login }}
        </div>

        <div class="mt-5 mb-5 d-flex justify-content-center">
          <button class="button mr-2" type="submit" :disabled="checkLogin()">
            <slot name="login-button">Отправить</slot>
          </button>
        </div>

        <div class="form-footer d-flex align-items-center justify-content-center flex-column">
          <div class="login__note">Если у вас нет аккаунта, то</div>
          <a class="link__registration" aria-label="Registration" @click.prevent="mode = 'register'">
            зарегистрируйтесь
          </a>
          <div v-if="forms.includes('reset')" class="text-center">
            <div class="login__note mt-2">А если не помните свой пароль,</div>
            <a class="link__registration" aria-label="Reset" @click.prevent="mode = 'reset'">
              его можно сбросить
            </a>
          </div>
        </div>
      </b-form>
    </div>
    <div v-else-if="mode == 'register' && forms.includes('register')">
      <slot name="register-title">
        <h5 class="title text-center mb-5">Регистрация</h5>
      </slot>
      <form action="" method="post" @submit.prevent="onRegister">
        <b-form-group class="mb-2">
          <b-input v-model="register.fullname" placeholder="Имя Фамилия" trim required autofocus />
        </b-form-group>

        <b-form-group class="mb-2">
          <b-input v-model="register.email" placeholder="Адрес эл. почты" trim required />
        </b-form-group>

        <b-form-group class="mb-2">
          <b-input v-model="register.password" type="password" placeholder="Пароль" trim required />
        </b-form-group>

        <b-form-group
          v-show="false"
          class="mb-2"
          description="Вы получите скидку на первую покупку, а ваш друг - крафтики"
        >
          <b-input v-model="register.promo" placeholder="Реферальный код вашего друга" trim />
        </b-form-group>

        <b-form-group class="mb-2">
          <b-input v-model="register.instagram" placeholder="@instagram" trim />
        </b-form-group>

        <b-form-group>
          <b-form-checkbox v-model="register.agree" class="mt-3">
            Я соглашаюсь на обработку <a href="/info/privacy" target="_blank" rel="noreferrer">персональных данных</a>
          </b-form-checkbox>
        </b-form-group>

        <div v-if="error.register != ''" class="alert alert-danger">
          {{ error.register }}
        </div>

        <div class="mt-5 mb-5 d-flex justify-content-center">
          <button class="button mr-2" type="submit" aria-label="submit" :disabled="checkRegister()">
            <slot name="register-button">Отправить</slot>
          </button>
        </div>
        <div class="form-footer d-flex align-items-center justify-content-center flex-column">
          <div class="login__note">У вас уже есть логин? Тогда можно</div>
          <a class="link__registration" aria-label="Login" @click.prevent="mode = 'login'">
            войти на сайт
          </a>
        </div>
      </form>
    </div>
    <div v-else-if="forms.includes('reset')">
      <slot name="reset-title">
        <h5 class="title text-center mb-5">Сброс пароля</h5>
      </slot>
      <form action="" method="post" :disabled="loading" @submit.prevent="onReset">
        <b-form-group class="mb-3">
          <b-input v-model="reset.email" type="email" placeholder="Адрес эл. почты" trim required autofocus />
        </b-form-group>

        <div v-if="error.reset != ''" class="alert alert-danger">
          {{ error.reset }}
        </div>

        <div class="mt-5 mb-5 d-flex justify-content-center ">
          <button class="button mr-2" type="submit" aria-label="submit" :disabled="checkReset()">
            <slot name="reset-button">Отправить</slot>
          </button>
        </div>
        <div class="form-footer d-flex align-items-center justify-content-center flex-column">
          <div class="login__note">Вспомнили пароль? Тогда</div>
          <a class="link__registration" aria-label="Login" @click.prevent="mode = 'login'">
            авторизуйтесь
          </a>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import {faVk, faInstagram} from '@fortawesome/free-brands-svg-icons'

export default {
  name: 'AuthForm',
  props: {
    authMode: {
      type: String,
      required: false,
      default: 'login',
    },
    forms: {
      type: Array,
      required: false,
      default: () => {
        return ['login', 'register', 'reset']
      },
    },
  },
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
        agree: false,
        promo: process.client && localStorage.promo !== undefined ? localStorage.promo : '',
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
  computed: {
    mode: {
      get() {
        return this.authMode
      },
      set(newValue) {
        this.$emit('update:authMode', newValue)
      },
    },
  },
  created() {
    this.$fa.add(faVk, faInstagram)

    if (this.register.promo !== '' && this.mode === 'login') {
      this.mode = 'register'
    }
  },
  methods: {
    updateValue(value) {
      this.$emit('update', value)
    },
    checkLogin() {
      return this.login.email === '' || this.login.password === '' || this.loading
    },
    checkRegister() {
      return this.register.fullname === '' || this.register.email === '' || !this.register.agree || this.loading
    },
    checkReset() {
      return this.reset.email === '' || this.loading
    },
    /* clearForms() {
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
            }, */
    onLogin() {
      this.error.login = ''
      this.loading = true
      this.$auth
        .loginWith('local', {data: this.login})
        .then(() => {
          this.loading = false
          this.$notify.success({message: 'Добро пожаловать!'})
          // this.clearForms();
        })
        .catch((res) => {
          this.error.login = res.data
          this.loading = false
        })
    },
    onRegister() {
      this.error.register = ''
      this.loading = true
      this.$axios
        .post('security/register', this.register)
        .then(() => {
          this.loading = false
          this.login.email = this.register.email
          this.login.password = this.register.password
          localStorage.removeItem('promo')
          this.onLogin()
        })
        .catch((res) => {
          this.loading = false
          this.error.register = res.data
        })
    },
    onReset() {
      this.error.reset = ''
      this.loading = true
      this.$axios
        .post('security/reset', this.reset)
        .then(() => {
          this.mode = 'login'
          this.loading = false
          this.$notify.success({message: 'Для подтверждения сброса пароля вам нужно пройти по ссылке из нашего письма'})
          this.reset.email = ''
        })
        .catch((res) => {
          this.loading = false
          this.error.reset = res.data
        })
    },
    onPopup(provider) {
      this.error.login = ''
      const x = screen.width / 2 - 700 / 2
      const y = screen.height / 2 - 450 / 2

      let oauth2 = this.$axios.defaults.baseURL + 'security/oauth2/' + provider
      if (localStorage.promo !== undefined) {
        oauth2 += '?promo=' + localStorage.promo
      }
      const win = window.open(
        oauth2,
        'AuthPopup',
        'width=700,height=450,modal=yes,alwaysRaised=yes,left=' + x + ',top=' + y,
      )

      const timer = this.setInterval(() => {
        try {
          const data = JSON.parse(win.document.body.textContent)
          win.close()
          if (data.token) {
            this.$auth.setUserToken(data.token)
            this.$notify.success({message: 'Добро пожаловать!'})
            localStorage.removeItem('promo')
          } else if (data.error) {
            this.error.login = data.error
          }
        } catch (e) {
          // console.error(e)
        }
        if (win && win.closed) {
          this.clearInterval(timer)
        }
      }, 500)
    },
  },
}
</script>
