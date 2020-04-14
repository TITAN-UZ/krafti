<template>
  <div>
    <!-- Mobile menu -->
    <aside :class="{menu: true, active: $app.mobile_menu.get()}">
      <div class="menu-content">
        <div class="menu-header">
          <div v-if="$auth.loggedIn" class="login">
            <b-link :to="$settings.links.profile" @click="hideMenu()">
              <user-avatar :user="user" :size="50" class="mr-2" />
            </b-link>
            <div>
              <div class="title">{{ user.fullname }}</div>
              <a class="logout-link" @click.prevent="onLogout()">Выход</a>
            </div>
          </div>
          <div v-else-if="$route.name != 'login'" class="login">
            <a class="login-link title" aria-label="Login" @click.prevent="showModal()">
              <user-avatar :user="{}" :size="50" class="mr-2" />
              Вход
            </a>
          </div>
          <div class="menu-close" @click="hideMenu()">
            <fa :icon="['fal', 'times']" size="3x" />
          </div>
        </div>

        <div class="menu-navs">
          <ul class="menu-list">
            <li v-for="(i, idx) in computedMenuHeader" :key="idx" class="menu-item">
              <b-link class="menu-link" :to="i.to" @click="hideMenu()">{{ i.title }}</b-link>
            </li>
            <li v-if="$hasScope('admin')" class="menu-item">
              <b-link class="menu-link" href="/admin">Админка</b-link>
            </li>
          </ul>
        </div>
        <div class="menu-footer">
          <a class="ic__instagram" :href="$settings.links.instagram" target="_blank" rel="noreferrer"></a>
          <div class="copyright">{{ $settings.copyright }}</div>
        </div>
      </div>
    </aside>

    <!-- Main menu -->
    <header :class="{header: true, header_img: $app.header_image.get() === true}">
      <div class="container">
        <div class="header__wrap d-flex justify-content-between align-items-center">
          <div class="header__logo">
            <nuxt-link to="/" class="logo__desktop">
              <img src="~assets/images/general/logo.svg" alt="" />
            </nuxt-link>
            <nuxt-link to="/" class="logo__mobile">
              <img src="~assets/images/general/logo_mob.svg" alt="" />
            </nuxt-link>
          </div>
          <div class="header__menu">
            <nav class="menu-wrap">
              <ul class="menu-list d-flex align-items-center">
                <b-nav-item v-for="(i, idx) in computedMenuHeader" :key="idx" :to="i.to">
                  {{ i.title }}
                </b-nav-item>
                <b-nav-item v-if="$hasScope('admin')" href="/admin">Админка</b-nav-item>
              </ul>
            </nav>
          </div>

          <div v-if="$auth.loggedIn" class="login">
            <div class="d-flex flex-column">
              <div class="title text-right">{{ user.fullname }}</div>
              <a class="logout-link text-right" @click.prevent="onLogout">Выход</a>
            </div>
            <b-link :to="{name: user && user.unread > 0 ? 'office-messages' : 'profile-update'}">
              <user-avatar :user="user" :size="50" class="ml-2" />
              <span v-if="user.unread > 0" class="label">{{ user.unread }}</span>
            </b-link>
          </div>
          <div v-else class="login">
            <div v-if="$route.name != 'login'">
              <a class="login-link" aria-label="Login" @click.prevent="showModal()">
                <span class="title">Вход</span>
                <user-avatar :user="{}" :size="50" class="ml-2" />
              </a>
            </div>
          </div>

          <div class="menu-open" @click="showMenu()">
            <fa :icon="['fal', 'stream']" size="3x" />
            <span v-if="user && user.unread > 0" class="label">{{ user.unread }}</span>
          </div>
        </div>
      </div>
    </header>

    <!-- Modal -->
    <b-modal id="modalLogin" ref="modalWindow" hide-footer :lazy="false" @hidden="/*clearForms*/">
      <auth-form :auth-mode.sync="authMode" />
      <template slot="modal-header">
        <button class="close" type="button" aria-label="Close" @click="hideModal()">
          <fa :icon="['fal', 'times']" size="3x" />
        </button>
      </template>
    </b-modal>
  </div>
</template>

<script>
import {faUserCircle} from '@fortawesome/pro-duotone-svg-icons'
import {faTimes, faStream} from '@fortawesome/pro-light-svg-icons'
import AuthForm from './auth-form'

export default {
  components: {AuthForm},
  data() {
    return {
      sidebar: false,
      authMode: 'login',
    }
  },
  computed: {
    user() {
      return this.$auth.user
    },
    loggedIn() {
      return this.$auth.loggedIn
    },
    computedMenuHeader() {
      return this.$settings.menu.header.filter((item) => !item.scope || this.$auth.hasScope(item.scope))
    },
  },
  watch: {
    loggedIn(newValue) {
      if (newValue === true) {
        this.hideModal()
        this.hideMenu()
      }
    },
  },
  created() {
    this.$fa.add(faUserCircle, faTimes, faStream)
  },
  methods: {
    showMenu() {
      this.$app.mobile_menu.set(true)
    },
    hideMenu() {
      this.$app.mobile_menu.set(false)
    },
    hideModal() {
      if (this.$refs.modalWindow) {
        this.$refs.modalWindow.hide()
      }
      // this.clearForms();
    },
    showModal() {
      if (this.$refs.modalWindow) {
        this.$refs.modalWindow.show()
      }
      this.hideMenu()
    },
    onLogout() {
      this.$auth
        .logout('local')
        .then(() => {
          this.$notify.info({message: 'Вы вышли из системы'})
          this.hideMenu()
        })
        .catch(() => {})
    },
  },
}
</script>
