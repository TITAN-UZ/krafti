<template>
  <b-form :class="css" @submit.prevent="onLogin">
    <b-form-group label="Логин">
      <b-form-input v-model="login.email" trim required autofocus />
    </b-form-group>

    <b-form-group label="Пароль">
      <b-form-input v-model="login.password" trim required type="password" />
    </b-form-group>
    <div class="d-flex justify-content-center justify-content-md-end">
      <button type="submit" class="btn btn-primary" :disabled="loading">Вход</button>
    </div>
  </b-form>
</template>

<script>
export default {
  name: 'AuthForm',
  props: {
    css: {
      type: String,
      required: false,
      default: '',
    },
  },
  data() {
    return {
      login: {
        email: '',
        password: '',
      },
      loading: false,
    }
  },
  methods: {
    onLogin() {
      this.$auth
        .loginWith('local', {data: this.login})
        .then(() => {
          this.loading = false
          this.$notify.success({message: 'Добро пожаловать!'})
        })
        .catch(() => {})
        .finally(() => {
          this.loading = false
        })
    },
  },
}
</script>
