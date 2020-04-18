<template>
  <div class="wrapper">
    <client-only>
      <office-header />
      <div class="wrapper__content">
        <section class="container__940 tabs">
          <div class="container">
            <div class="row">
              <div class="col-12">
                <div class="row mob_container">
                  <div class="col-12 tab__wrap--scroll">
                    <ul id="diplomsList" class="nav nav-tabs" role="tablist">
                      <b-nav-item v-for="(title, to) in menu" :key="to" :to="{name: to}">
                        {{ title }}
                        <span v-if="to == 'office-messages' && user.unread" class="label ml-2">{{ user.unread }}</span>
                      </b-nav-item>
                    </ul>
                    <div class="tab-content">
                      <nuxt-child />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </client-only>
  </div>
</template>

<script>
import officeHeader from '../components/office-header'

export default {
  auth: true,
  scrollToTop: false,
  components: {
    'office-header': officeHeader,
  },
  asyncData({app, params}) {
    return app.$axios
      .get('user/profile')
      .then((res) => {
        app.$auth.setUser(res.data.user)
      })
      .catch(() => {})
  },
  data() {
    return {
      menu: {
        'office-courses': 'Мои курсы',
        'office-works': 'Мои работы',
        'office-messages': 'Сообщения',
        'office-diplomas': 'Дипломы',
        // 'office/store': 'Магазин',
      },
    }
  },
  computed: {
    user() {
      return this.$auth.user
    },
  },
  created() {
    this.$app.header_image.set(true)
  },
  mounted() {
    window.scrollTo(0, 0)
  },
}
</script>
