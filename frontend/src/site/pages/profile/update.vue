<template>
  <div class="wrapper">
    <div class="wrapper__content">
      <section class="edit-profile__content">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <h2 class="section__title text-center">Изменить профиль</h2>
            </div>
          </div>

          <div class="row edit-profile__form profile-form">
            <div class="col-12">
              <client-only>
                <div class="form-body">
                  <div class="form-group mb-3">
                    <div class="avatar__box">
                      <upload-photo :size="150" class="mb-3" />
                    </div>
                    <label class="text-center">Сменить фото профиля</label>
                  </div>

                  <b-form-group class="mb-3" label="Авторизация через соц.сети">
                    <div class="social-providers flex-wrap profile">
                      <button
                        v-if="$auth.user && $auth.user.oauth2.vkontakte === undefined"
                        class="vkontakte inactive col-12 col-md-6"
                        @click.prevent="onLink('vkontakte')"
                      >
                        <fa :icon="['fab', 'vk']" class="mr-2" />
                        <span>Подключить</span>
                      </button>
                      <button v-else class="vkontakte col-12 col-md-6" @click.prevent="onUnlink('vkontakte')">
                        <fa :icon="['fab', 'vk']" class="mr-2" />
                        <span>{{ $auth.user.oauth2.vkontakte }}</span>
                        <fa :icon="['fal', 'times']" class="ml-2" />
                      </button>

                      <button
                        v-if="$auth.user.oauth2.instagram === undefined"
                        class="instagram inactive  col-12 col-md-6"
                        @click.prevent="onLink('instagram')"
                      >
                        <fa :icon="['fab', 'instagram']" class="mr-2" />
                        <span>Подключить</span>
                      </button>
                      <button v-else class="instagram  col-12 col-md-6" @click.prevent="onUnlink('instagram')">
                        <fa :icon="['fab', 'instagram']" class="mr-2" />
                        <span>{{ $auth.user.oauth2.instagram }}</span>
                        <fa :icon="['fal', 'times']" class="ml-2" />
                      </button>
                    </div>
                  </b-form-group>
                </div>

                <form class="edit-profile" @submit.prevent="onSubmit">
                  <div class="form-body">
                    <form-profile v-model="childrenForm" :record="record" />
                    <div class="d-flex align-items-center justify-content-center">
                      <button class="button" type="submit" aria-label="submit" :disabled="childrenForm">
                        Сохранить
                      </button>
                    </div>
                  </div>
                </form>
              </client-only>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import Vue from 'vue'
import VueClipboard from 'vue-clipboard2'
import {faVk, faInstagram} from '@fortawesome/free-brands-svg-icons'
import {faCopy, faPlusCircle, faCheckCircle, faTimesCircle, faMale, faFemale} from '@fortawesome/pro-duotone-svg-icons'
import {faTimes} from '@fortawesome/pro-light-svg-icons'
import FormProfile from '../../components/forms/profile'

VueClipboard.config.autoSetContainer = true // add this line
Vue.use(VueClipboard)

export default {
  components: {FormProfile},
  auth: true,
  async asyncData({app}) {
    const {data: record} = await app.$axios.get('user/profile')
    return {record: record.user}
  },
  data() {
    return {
      record: {},
      childrenForm: false,
    }
  },
  created() {
    this.$app.header_image.set(false)
    this.$fa.add(faCopy, faPlusCircle, faCheckCircle, faTimesCircle, faTimes, faVk, faInstagram, faMale, faFemale)
  },
  methods: {
    async onSubmit() {
      const {data: res} = await this.$axios.patch('user/profile', this.record)
      this.record = res.user
      this.$auth.fetchUser()
      this.$notify.success({message: 'Изменения успешно сохранены'})
    },
    onLink(provider) {
      const x = screen.width / 2 - 700 / 2
      const y = screen.height / 2 - 450 / 2

      const oauth2 = this.$axios.defaults.baseURL + 'security/oauth2/' + provider
      const win = window.open(
        oauth2,
        'AuthPopup',
        'width=700,height=450,modal=yes,alwaysRaised=yes,left=' + x + ',top=' + y,
      )

      const timer = this.setInterval(() => {
        try {
          const data = JSON.parse(win.document.body.textContent)
          win.close()
          if (data.success) {
            this.$notify.success({
              message: 'Вы успешно привязали ' + (provider === 'instagram' ? 'Instagram' : 'Вконтакте'),
            })
            this.$auth.fetchUser()
          } else if (data.error) {
            this.$notify.error({message: data.error})
          }
        } catch (e) {
          // console.error(e)
        }
        if (win && win.closed) {
          this.clearInterval(timer)
        }
      }, 500)
    },
    onUnlink(provider) {
      this.$axios.delete('security/oauth2', {params: {provider}}).then(() => {
        this.$notify.info({message: 'Вы успешно отвязали ' + (provider === 'instagram' ? 'Instagram' : 'Вконтакте')})
        this.$auth.fetchUser()
      })
    },
  },
  head() {
    return {
      title: 'Крафти / Личный кабинет / Профиль',
    }
  },
}
</script>

<style lang="scss">
.children-form {
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 0.5s;
  }

  .fade-enter,
  .fade-leave-to {
    opacity: 0;
  }

  .child-badge {
    font-size: 16px;
    cursor: pointer;
    padding: 10px;
    font-weight: 400;

    .fa-times {
      font-size: 14px;
    }

    &:hover {
      opacity: 0.8;
    }
  }

  .col-form-label {
    height: auto !important;
    padding-top: calc(0.4rem + 1px);
    padding-bottom: calc(0.4rem + 1px);
  }

  .form-control {
    padding: 0.5rem 0.6rem !important;
    height: auto !important;
  }

  .custom-control-label {
    color: #000;
  }

  button {
    color: #6c757d;
    padding-left: 0;
    padding-right: 0;

    .svg-inline--fa {
      font-size: 30px;
      --fa-primary-color: #fff;
      --fa-secondary-color: #6c757d;
      border: 1px solid #fff;
      border-radius: 30px;
      box-shadow: 0 0.125rem 0.25rem rgba(#000, 0.075);
    }

    &:hover {
      .svg-inline--fa {
        box-shadow: 0 0.25rem 0.5rem rgba(#000, 0.15);
      }
    }
  }
}
</style>
