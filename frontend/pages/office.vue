<template>
  <div class="wrapper">
    <client-only>
      <office-header/>
          <!--TODO Личный кабинет юзера-->
      <div class="wrapper__content">
        <section class="container__940 tabs">
          <div class="container">
            <div class="row">
              <div class="col-12">
                <div class="row mob_container">
                  <div class="col-12 tab__wrap--scroll">
                    <ul class="nav nav-tabs" id="diplomsList" role="tablist">
                      <b-nav-item v-for="(title, to) in menu" :to="to" :key="to">
                        {{title}}
                        <span class="label ml-2"
                              v-if="to == '/office/messages' && user.unread">{{user.unread}}</span>
                      </b-nav-item>
                    </ul>
                    <div class="tab-content">
                      <nuxt-child/>
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
    import office_header from '../components/office-header'

    export default {
        //middleware: 'token',
        auth: true,
        components: {
            'office-header': office_header,
        },
        data() {
            return {
                menu: {
                    '/office/store': 'Магазин',
                    '/office/diplomas': 'Дипломы',
                    '/office/courses': 'Мои курсы',
                    '/office/messages': 'Сообщения',
                    '/office/works': 'Мои работы',
                }
            }
        },
        asyncData({app, params}) {
            return app.$axios.get('user/profile')
                .then((res) => {
                    app.$auth.setUser(res.data.user);
                }).catch(() => {
                })
        },
        computed: {
            user() {
                return this.$auth.user;
            },
        },
        /*created() {
            if (this.$route.name == 'office') {
                this.$router.replace('office/store')
            }
        },*/
        mounted() {
            document.getElementsByTagName('header')[0].classList.add('header_img');
        }
    }
</script>
