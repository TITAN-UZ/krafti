<template>
  <b-modal hide-footer visible @hidden="onHidden" ref="modalWindow">

    <form @submit.prevent="onSubmit" :disabled="loading" class="mt-4 text-center">
      <h4>Выберите вариант покупки:</h4>
      <b-button
        :size="payment.period == 3 ? 'lg' : ''"
        :variant="payment.period == 3 ? 'success' : 'outline-secondary'"
        :disabled="loading"
        @click="payment.period = 3"> 3 месяца<br>за 2 990 р
      </b-button>
      <b-button
        :size="payment.period == 6 ? 'lg' : ''"
        :variant="payment.period == 6 ? 'success' : 'outline-secondary'"
        :disabled="loading"
        @click="payment.period = 6"> 6 месяцев<br>за 3 990 р
      </b-button>
      <b-button
        :size="payment.period == 12 ? 'lg' : ''"
        :variant="payment.period == 12 ? 'success' : 'outline-secondary'"
        :disabled="loading"
        @click="payment.period = 12"> 1 год<br>за 5 990 р
      </b-button>

      <div v-if="$auth.loggedIn" class="mt-5">
        <button class="button" type="submit" aria-label="submit">
          Оплатить
        </button>
      </div>
    </form>

    <div v-if="!$auth.loggedIn" class="mt-4 text-center">
      <auth-form :auth_mode.sync="auth_mode" :forms="['login', 'register']">
        <template slot="login-title">
          <h4>Войдите на сайт</h4>
        </template>
        <template slot="login-button">
          Войти и Оплатить
        </template>
        <template slot="register-title">
          <h4>Укажите свои данные</h4>
        </template>
        <template slot="register-button">
          Зарегистрироваться и Оплатить
        </template>
      </auth-form>
    </div>

    <template slot="modal-header">
      <button class="close" type="button" aria-label="Close" @click="hideModal">
        <fa :icon="['fal', 'times']" size="3x"/>
      </button>
    </template>
  </b-modal>
</template>

<script>
    import {faTimes} from '@fortawesome/pro-light-svg-icons'
    import AuthForm from '../../../../components/auth-form'

    export default {
        auth: true,
        data() {
            return {
                loading: false,
                auth_mode: 'register',
                payment: {
                    period: 6,
                    service: 'robokassa',
                    course_id: this.$route.params.cid,
                },
            }
        },
        components: {
            'auth-form': AuthForm,
        },
        methods: {
            hideModal() {
                if (this.$refs['modalWindow']) {
                    this.$refs['modalWindow'].hide();
                }
            },
            onHidden() {
                this.$router.push({name: 'courses-cid', params: this.$route.params})
            },
            onSubmit() {
                this.$axios.post('user/payment', this.payment)
                    .then(res => {
                        if (res.data && res.data.redirect) {
                            document.location.replace(res.data.redirect)
                        }
                    })
                    .catch(() => {
                    })
                    .finally(() => {
                        this.loading = false;
                        this.hideModal();
                    })
            }
        },
        created() {
            this.$fa.add(faTimes);
            this.$root.$on('app::auth-form::login', this.onSubmit);
        },
        beforeDestroy() {
            this.$root.$off('app::auth-form::login');
        }
    }
</script>
