<template>
  <div class="wrapper">
    <div class="wrapper__content">
      <div class="container">
        <div class="row">
          <div class="col-md-6 offset-md-3">
            <auth-form :auth-mode.sync="authMode" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import auth from '../../components/auth-form'

export default {
  auth: true,
  components: {
    'auth-form': auth,
  },
  data() {
    return {
      authMode: 'login',
    }
  },
  mounted() {
    // this.$parent.$parent.$refs['header'].showModal();
  },
}
</script>
