<template>
  <b-navbar toggleable="md" class="d-block border-top-0 border-left-0 border-right-0 border-bottom">
    <div class="container">
      <b-navbar-brand href="/">
        <img src="~assets/images/logo.svg" alt="Krafti.ru" />
      </b-navbar-brand>

      <b-button v-b-toggle.nav-collapse variant="default" class="menu-toggle d-md-none">
        <fa :icon="['fas', 'bars']" />
      </b-button>

      <b-collapse id="nav-collapse" is-nav style="height: 50px;">
        <b-navbar-nav class="flex-md-grow-1 flex-wrap w-100">
          <b-nav-item v-for="(item, idx) in mainMenu" :key="idx" :to="item.to" class="text-right text-md-left">
            {{ item.title }}
          </b-nav-item>
        </b-navbar-nav>
        <div class="d-flex ml-auto">
          <div class="d-flex flex-column ml-auto">
            <div class="text-right text-nowrap">{{ $auth.user.fullname }}</div>
            <a href="#" class="small text-right mt-1" @click.prevent="onLogout">Выход</a>
          </div>
          <user-avatar :user="$auth.user" :size="50" class="ml-2" />
        </div>
      </b-collapse>
    </div>
  </b-navbar>
</template>

<script>
export default {
  name: 'AppHeader',
  data() {
    return {}
  },
  computed: {
    mainMenu() {
      return this.$settings.menu.admin.filter((item) => !item.scope || this.$hasScope(item.scope))
    },
  },
  methods: {
    onLogout() {
      this.$auth
        .logout('local')
        .then(() => {
          this.$notify.info({message: 'Вы вышли из системы'})
        })
        .catch(() => {})
    },
  },
}
</script>

<style lang="scss">
#app-header {
  .menu-toggle,
  .dropdown-toggle {
    padding-top: 0;
    padding-bottom: 0;

    .svg-inline--fa {
      font-size: 20px;
    }
  }

  .dropdown {
    li {
      color: #000;
    }
  }
}
</style>
