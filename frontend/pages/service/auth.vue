<template>
  <div class="wrapper">
    <div class="wrapper__content">
      <div class="container">
        <div class="row">
          <div class="col-md-6 offset-md-3">
            <auth-form :auth_mode.sync="auth_mode"/>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    import auth from '../../components/auth-form';

    export default {
        auth: true,
        data() {
            return {
                auth_mode: 'login'
            }
        },
        components: {
            'auth-form': auth,
        },
        mounted() {
            //this.$parent.$parent.$refs['header'].showModal();
        }
    }
</script>
