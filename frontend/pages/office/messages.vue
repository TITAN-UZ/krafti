<template>
  <div class="container__940">
    <div class="row messages__wrap">
      <div class="col-lg-7 col-12">

        <div class="media message__item" v-for="message in messages" :key="message.id">
          <div class="wrap mr-2">
            <img class="message__item--photo rounded-circle" v-if="!message.sender" :src="logo" alt="">
            <img class="message__item--photo rounded-circle" v-else :src="message.sender.photo" alt="">
          </div>
          <div class="media-body">
            <div class="media-body-top d-flex align-items-center justify-content-between">
              <h4 class="message__item--title mt-0" v-if="!message.sender">Администрация Krafti <span class="label__shape"></span></h4>
              <h4 class="message__item--title mt-0" v-else>{{message.sender.fullname}}</h4>
              <span class="days_ago">{{message.created_at | dateago}}</span>
            </div>
            <div class="media-body-bottom d-flex align-items-center justify-content-between">
              <div class="message__item--info">
                {{message.message}}
              </div>
              <!--<div class="message__item&#45;&#45;video">
                <a class="video" href="">
                  <img class="video__thumb img-responsive" src="~assets/images/content/video.jpg" alt="">
                </a>
              </div>-->
            </div>
          </div>
        </div>

        <b-pagination v-if="total > limit"
                      v-model="page"
                      :total-rows="total"
                      :per-page="limit"
        />
      </div>
      <div class="col-5 d-none d-md-none d-lg-block">
        <img class="img-responsive contact__img" src="~assets/images/general/bg_message.png" alt="">
      </div>
    </div>
  </div>
</template>

<script>
    import Logo from '../../static/favicons/android-chrome-512x512.png'

    export default {
        data() {
            return {
                messages: [],
                page: 1,
                total: 0,
                limit: 20,
                logo: Logo,
            }
        },
        watch: {
            page() {
                this.getMessages()
            }
        },
        methods: {
            getMessages() {
                this.$axios.get('user/messages', {params: {limit: this.limit, page: this.page}})
                    .then(res => {
                        this.messages = res.data.rows;
                        this.total = res.data.total;

                        this.$auth.fetchUser()
                    })
            }
        },
        created() {
            this.getMessages()
        },
        head() {
            return {
                title: 'Крафти / Личный кабинет / Сообщения',
            }
        },
    }
</script>
