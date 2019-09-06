<template>
  <div>
    <!-- Mobile menu -->
    <aside class="menu">
      <div class="menu-content">
        <div class="menu-header">
          <div class="login" v-if="$auth.loggedIn">
            <b-link :to="$settings.links.profile" @click="hideMenu()">
              <img v-if="user && user.photo" :src="user.photo" class="avatar"/>
              <fa v-else :icon="['fad', 'user-circle']" class="avatar"/>
            </b-link>
            <div>
              <div class="title">{{user.fullname}}</div>
              <a class="logout-link" @click.prevent="onLogout()">Выход</a>
            </div>
          </div>
          <div class="login" v-else-if="$route.name != 'login'">
            <a class="login-link title" aria-label="Login" @click.prevent="showModal()">
              <fa :icon="['fad', 'user-circle']" class="avatar"/>
              Вход
            </a>
          </div>
          <div class="menu-close" @click="hideMenu()">
            <fa :icon="['fal', 'times']" size="3x"/>
          </div>
        </div>

        <div class="menu-navs">
          <ul class="menu-list">
            <li class="menu-item" v-for="i in $settings.menu.header" :key="i.to"
                v-if="!i.scope || $auth.hasScope(i.scope)">
              <b-link class="menu-link" :to="i.to" @click="hideMenu()">{{i.title}}</b-link>
            </li>
          </ul>
        </div>
        <div class="menu-footer">
          <a class="ic__instagram" :href="$settings.links.instagram" target="_blank"></a>
          <div class="copyright">{{$settings.copyright}}</div>
        </div>
      </div>
    </aside>

    <!-- Main menu -->
    <header :class="{header: true, header_img: $app.header_image.get() === true}">
      <div class="container">
        <div class="header__wrap d-flex justify-content-between align-items-center">
          <div class="header__logo">
            <nuxt-link to="/" class="logo__desktop">
              <img src="~assets/images/general/logo.svg" alt="">
            </nuxt-link>
            <nuxt-link to="/" class="logo__mobile">
              <img src="~assets/images/general/logo_mob.svg" alt="">
            </nuxt-link>
          </div>
          <div class="header__menu">
            <nav class="menu-wrap">
              <ul class="menu-list d-flex align-items-center">
                <b-nav-item v-for="i in $settings.menu.header" v-if="!i.scope || $auth.hasScope(i.scope)"
                            :to="i.to" :key="i.to">{{i.title}}
                </b-nav-item>
              </ul>
            </nav>
          </div>

          <div class="login" v-if="$auth.loggedIn">
            <div class="d-flex flex-column">
              <div class="title text-right">{{user.fullname}}</div>
              <a @click.prevent="onLogout" class="logout-link text-right">Выход</a>
            </div>
            <b-link :to="{'name': user && user.unread > 0 ? 'office-messages' : 'office'}">
              <img v-if="user && user.photo" :src="user.photo" class="avatar"/>
              <fa v-else :icon="['fad', 'user-circle']" class="avatar"/>
              <span class="label" v-if="user.unread > 0">{{user.unread}}</span>
            </b-link>
          </div>
          <div class="login" v-else>
            <div v-if="$route.name != 'login'">
              <a class="login-link" aria-label="Login" @click.prevent="showModal()">
                <span class="title">Вход</span>
                <fa :icon="['fad', 'user-circle']" class="avatar"/>
              </a>
            </div>
          </div>

          <div class="menu-open d-md-none" @click="showMenu()">
            <fa :icon="['fal', 'stream']" size="3x"/>
            <span class="label" v-if="user && user.unread > 0">{{user.unread}}</span>
          </div>
        </div>
      </div>
    </header>

    <!-- Modal -->
    <b-modal id="modalLogin" ref="modalWindow" hide-footer :lazy="false" @hidden="/*clearForms*/">
      <auth-form :auth_mode.sync="auth_mode"/>
      <template slot="modal-header">
        <button class="close" type="button" aria-label="Close" @click="hideModal()">
          <fa :icon="['fal', 'times']" size="3x"/>
        </button>
      </template>
    </b-modal>
  </div>
</template>

<script>
    import auth from './auth-form';
    import {faUserCircle} from '@fortawesome/pro-duotone-svg-icons'
    import {faTimes, faStream} from '@fortawesome/pro-light-svg-icons'

    export default {
        data() {
            return {
                sidebar: false,
                auth_mode: 'login',
            }
        },
        components: {
            'auth-form': auth,
        },
        computed: {
            user() {
                return this.$auth.user
            },
            loggedIn() {
                return this.$auth.loggedIn
            }
        },
        watch: {
            loggedIn(newValue) {
                if (newValue === true) {
                    this.hideModal();
                    this.hideMenu();
                }
            }
        },
        methods: {
            showMenu() {
                document.getElementsByTagName('aside')[0].classList.add('active');
                document.getElementsByTagName('html')[0].classList.add('off');
            },
            hideMenu() {
                document.getElementsByTagName('aside')[0].classList.remove('active');
                document.getElementsByTagName('html')[0].classList.remove('off');
            },
            hideModal() {
                if (this.$refs['modalWindow']) {
                    this.$refs['modalWindow'].hide();
                }
                //this.clearForms();
            },
            showModal() {
                if (this.$refs['modalWindow']) {
                    this.$refs['modalWindow'].show();
                }
                this.hideMenu();
            },
            onLogout() {
                this.$auth.logout('local').then(() => {
                    this.$notify.info({message: 'Вы вышли из системы'});
                    this.hideMenu();
                }).catch(() => {
                });
            }
        },
        created() {
            this.$fa.add(faUserCircle, faTimes, faStream);
            /*
            this.$root.$on('app::auth-form::reset', () => {
                this.hideModal();
                this.hideMenu();
            });*/
        },
        /*beforeDestroy() {
            this.$root.$off('app::auth-form::reset');
        }*/
    }
</script>
