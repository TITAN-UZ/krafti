<template>
  <div>
    <app-header ref="header" />
    <nuxt />
    <app-footer ref="footer" />
  </div>
</template>

<script>
import AppHeader from '../components/app-header'
import AppFooter from '../components/app-footer'

export default {
  name: 'Default',
  components: {AppHeader, AppFooter},
}
</script>
