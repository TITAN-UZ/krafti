<template>
  <div>
    <template v-if="!$auth.loggedIn">
      <a href="/">
        <img src="~assets/images/logo.svg" class="logo mb-5" alt="Krafti.ru" title="Krafti.ru" />
      </a>
      <auth-form />
    </template>
    <div v-else class="text-center mt-5">
      <b-spinner style="width: 3rem; height: 3rem;" />
    </div>
  </div>
</template>

<script>
import AuthForm from '../components/auth-form'
export default {
  components: {AuthForm},
  watch: {
    '$auth.loggedIn'() {
      this.redirect()
    },
  },
  created() {
    if (this.$auth.loggedIn) {
      this.redirect()
    }
  },
  methods: {
    redirect() {
      for (const i in this.$settings.menu.admin) {
        if (Object.prototype.hasOwnProperty.call(this.$settings.menu.admin, i)) {
          const route = this.$settings.menu.admin[i]
          if (!route.scope || this.$hasScope(route.scope)) {
            this.$router.replace(route.to)
            break
          }
        }
      }
    },
  },
}
</script>
