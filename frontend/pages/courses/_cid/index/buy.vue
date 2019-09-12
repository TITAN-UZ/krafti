<template>
  <b-modal hide-footer visible @hidden="onHidden" ref="modalWindow">

    <form @submit.prevent="onSubmit" :disabled="loading" class="mt-4 text-center payment-form">
      <div class="period">
        <h4>Выберите вариант покупки:</h4>
        <b-button
          :size="payment.period == 3 ? 'lg' : ''"
          :variant="payment.period == 3 ? 'primary' : 'outline-secondary'"
          :disabled="loading"
          @click="payment.period = 3">Доступ на<br>3 месяца<br>за {{record.price['3'] - record.discount | number}} р
        </b-button>
        <b-button
          :size="payment.period == 6 ? 'lg' : ''"
          :variant="payment.period == 6 ? 'primary' : 'outline-secondary'"
          :disabled="loading"
          @click="payment.period = 6">Доступ на<br>6 месяцев<br>за {{record.price['6'] - record.discount | number}} р
        </b-button>
        <b-button
          :size="payment.period == 12 ? 'lg' : ''"
          :variant="payment.period == 12 ? 'primary' : 'outline-secondary'"
          :disabled="loading"
          @click="payment.period = 12">Доступ на<br>1 год<br>за {{record.price['12'] - record.discount | number}} р
        </b-button>
      </div>
      <div class="mt-5 payment">
        <h4>Выберите платёжную систему:</h4>
        <b-button
          variant="default"
          :class="{active: payment.service == 'robokassa'}"
          :disabled="loading"
          @click="payment.service = 'robokassa'">
          <img :src="rbLogo" class="robokassa"/>
        </b-button>
        <b-button
          variant="default"
          :class="{active: payment.service == 'paypal'}"
          :disabled="loading"
          @click="payment.service = 'paypal'">
          <img :src="ppLogo" class="paypal"/>
        </b-button>
      </div>

      <div class="alert alert-info mt-5" v-if="record.discount">
        Благодаря тому, что вы зарегистрировались по реферальной ссылке,
        у вас есть скидка <strong>{{record.discount | number}} р.</strong> на первую покупку.
      </div>

      <div class="mt-4 text-center">
        <div class="auth-form">
          <b-form-group description="Если у вас есть промокод - укажите его здесь">
            <b-form-input v-model.trim="payment.code" placeholder="Промокод" :state="discount_code"/>
          </b-form-group>
        </div>
      </div>

      <div v-if="$auth.loggedIn" class="mt-5 d-flex justify-content-center">
        <button class="button d-flex align-items-center" type="submit" aria-label="submit" :disabled="loading">
          <b-spinner class="mr-2" small v-if="loading"/>
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
        <fa :icon="['fal', 'times']" size="2x"/>
      </button>
    </template>
  </b-modal>
</template>

<script>
    import {faTimes} from '@fortawesome/pro-light-svg-icons'
    import AuthForm from '../../../../components/auth-form'
    import ppLogo from '../../../../assets/images/general/payment-paypal.svg'
    import rbLogo from '../../../../assets/images/general/payment-robokassa.svg'

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
                    code: null,
                },
                discount_code: null,
                ppLogo,
                rbLogo,
            }
        },
        components: {
            'auth-form': AuthForm,
        },
        computed: {
            loggedIn() {
                return this.$auth.loggedIn;
            },
            /*checkCode() {
                if (this.payment.code === '') {
                    return null
                }

                this.$axios.get('user/payment', {params: {code: this.payment.code}})
                    .then(() => {
                        this.discount_code = true;
                    })
            }*/
        },
        watch: {
            loggedIn(newValue) {
                if (newValue === true) {
                    this.onSubmit()
                }
            },
            'payment.code': function(val) {
                if (val === '') {
                    this.discount_code = null;
                } else {
                    this.$axios.get('user/payment', {params: {code: val}})
                        .then(res => {
                            if (!res.data.success && res.data.message) {
                                this.$notify.info({message: res.data.message});
                                this.payment.code = '';
                                this.discount_code = null;
                            } else {
                                this.discount_code = res.data.success;
                            }
                        })
                }
            }
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
                this.loading = true;
                this.$axios.post('user/payment', this.payment)
                    .then(res => {
                        if (res.data.redirect) {
                            document.location.replace(res.data.redirect)
                        } else {
                            this.$notify.info({message: res.data})
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
        asyncData({app, params}) {
            return app.$axios.get('web/courses', {params: {id: params.cid}})
                .then(res => {
                    return {record: res.data}
                })
        },
        created() {
            this.$fa.add(faTimes);
            if (this.record.bought) {
                this.$router.push({name: 'courses-cid', params: this.$route.params})
            }
        },
    }
</script>

<style lang="scss">
  .payment-form {
    .payment {
      button {
        padding: 15px 20px;

        &.active {
          border-color: #ff7474;

          &:focus {
            box-shadow: 0 0 0 0.2rem rgba(200, 119, 119, 0.5);
          }
        }
      }
    }

    img {
      max-width: 100px;
      //filter: drop-shadow(0 0 5px rgba(255, 255, 255, .7));
      &.robokassa {
        padding: 10px 0;
      }
    }
  }
</style>
