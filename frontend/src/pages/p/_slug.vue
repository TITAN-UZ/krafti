<template>
  <b-row style="padding: 100px 0;">
    <b-spinner class="m-auto p-5" />
  </b-row>
</template>

<script>
export default {
  auth: false,
  validate({params}) {
    return /^[\w+]{8,}/.test(params.slug)
  },
  created() {
    if (process.client) {
      localStorage.setItem('promo', this.$route.params.slug)
    }
    console.log(this.$route.params.slug)
  },
  mounted() {
    if (!this.$auth.loggedIn) {
      this.$auth.redirect('login')
    } else {
      this.$auth.redirect('home')
    }
  },
}
</script>
