<template>
  <div class="wrapper">
    <div class="wrapper__content">
      <div class="container">
        <div class="row">
          <div class="col-md-6 offset-md-3">
            <auth-form />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AuthForm from '../../components/auth-form'

export default {
  auth: true,
  components: {AuthForm},
}
</script>
