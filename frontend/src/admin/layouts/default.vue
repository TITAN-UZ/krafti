<template>
  <div id="app" class="d-flex flex-column" :class="{'justify-content-center': !$auth.loggedIn}">
    <app-header v-if="$auth.loggedIn" ref="Navigation" />

    <div id="content" class="mt-3 pt-3 container">
      <nuxt />
    </div>

    <app-footer v-if="$auth.loggedIn" ref="Footer" />
  </div>
</template>

<script>
import AppHeader from '../components/app-header'
import AppFooter from '../components/app-footer'

export default {
  components: {
    AppHeader,
    AppFooter,
  },
}
</script>
