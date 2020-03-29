<template>
  <div>
    <div class="wrapper">
      <header-bg image="index" class="wrapper__bg bg_600 index-bg">
        <template slot="content">
          <span class="play-button main" @click.prevent="$refs.mainVideo.show()">
            <fa :icon="['fad', 'circle']" />
            <fa :icon="['fas', 'play']" />
          </span>
        </template>
      </header-bg>
      <vimeo ref="mainVideo" :video="$settings.video.index" />

      <div class="wrapper__content">
        <section class="about__info">
          <div class="container">
            <div class="row">
              <div class="col-md-8 offset-md-2">
                <div class="row">
                  <div class="col-12 d-flex justify-content-between align-items-center">
                    <h2 class="section__title">Сделано с любовью!</h2>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-xl-5">
                    <div class="about__text">
                      <p>
                        Привет! Мы — KRAFTi, новое слово в онлайн-обучении. Мы верим, что в каждом ребёнке живёт
                        настоящий творец, а в каждом взрослом — настоящий ребёнок. Именно поэтому творческое и
                        интеллектуальное развитие взрослых и детей стало нашей страстью!
                      </p>
                      <p>
                        Мы не просто обучаем, мы раскрываем потенциал. Мы создаём невероятно увлекательные и насыщенные
                        курсы по уникальной методике. Мы назвали её «Методика непрерывного обучения». Мы верим, что
                        интересное обучение не только обогащает знаниями, но и способно изменить мировоззрение. Создать
                        для детей преимущество в предстоящей большой жизни и вернуть взрослым ощущение творческого
                        полёта.
                      </p>
                      <p>Учитесь, творите, развивайтесь, вдохновляйте окружающих!</p>
                    </div>
                  </div>
                  <div class="col-12 col-xl-7 swiper-container">
                    <div class="gallery__slider swiper-wrapper">
                      <div class="slider__item swiper-slide">
                        <div class="slider__wrap--item d-flex align-items-center">
                          <div class="left__block mr-1">
                            <a :href="slide1">
                              <img class="img-responsive" src="~assets/images/slider/slider-1-thumb.jpg" alt="" />
                            </a>
                          </div>
                          <div class="right__block">
                            <a :href="slide2">
                              <img class="mb-15 img-responsive" src="~assets/images/slider/slider-2-thumb.jpg" alt="" />
                            </a>
                            <a :href="slide3">
                              <img class="img-responsive" src="~assets/images/slider/slider-3-thumb.jpg" alt="" />
                            </a>
                          </div>
                        </div>
                      </div>
                      <!--<div class="slider__item swiper-slide">
                        <div class="slider__wrap&#45;&#45;item d-flex">
                          <div class="left__block mr-1">
                            <img class="img-responsive" src="~assets/images/content/teacher/img.jpg" alt=""></div>
                          <div class="right__block">
                            <img class="mb-15 img-responsive" src="~assets/images/content/teacher/img-2.jpg" alt="">
                            <img class="img-responsive" src="~assets/images/content/teacher/img-3.jpg" alt=""></div>
                        </div>
                      </div>-->
                    </div>
                    <!--<div class="swiper-pagination"></div>-->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section v-if="free.total > 0" class="courses-list mt-5">
          <div class="container">
            <div class="row">
              <div class="col-md-8 offset-md-2">
                <div class="row">
                  <div class="col-12 d-flex justify-content-between align-items-center">
                    <h2 class="section__title">Попробуйте бесплатно!</h2>
                  </div>
                </div>
                <div class="d-flex lessons__list align-items-start">
                  <template v-for="item in free.rows">
                    <div :key="item.id" class="lesson__item">
                      <div class="lesson__item--video">
                        <nuxt-link :to="{name: 'index-free', params: {id: item.id}}" class="video">
                          <b-img :src="item.video.preview['1280x720']" fluid-grow />
                        </nuxt-link>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section v-if="courses.total > 0" class="courses-list mt-5">
          <div class="container">
            <div class="row">
              <div class="col-md-8 offset-md-2">
                <div class="row">
                  <div class="col-12 d-flex justify-content-between align-items-center">
                    <h2 class="section__title">Курсы</h2>
                    <b-link v-if="courses.total > 2" to="/courses" class="link__more">См. все</b-link>
                  </div>
                </div>
                <courses-list :courses="courses.rows" />
              </div>
            </div>
          </div>
        </section>
        <section v-if="reviews.total > 0" class="reviews_list tab__wrap--scroll mt-5">
          <div class="container">
            <div class="row">
              <div class="col-md-8 offset-md-2">
                <div class="row">
                  <div class="col-12 d-flex justify-content-between align-items-center">
                    <h2 class="section__title">Отзывы</h2>
                    <b-link v-if="reviews.total > 3" :to="{name: 'reviews'}" class="link__more">См. все</b-link>
                  </div>
                </div>
                <reviews-list :reviews="reviews.rows" row-class="row mob_container item__wrap d-flex" />
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
    <section class="subscription">
      <div class="container subscription__wrapper">
        <div class="row container__940">
          <div class="col-lg-6 col-12">
            <div class="subscription__info">
              <div class="subscription__info--headtitle">подписка</div>
              <h2 class="subscription__info--title">Новости</h2>
              <div class="subscription__info--text">
                Подпишитесь на наши новости и оставайтесь в курсе самых последних обновлений, а также получите
                <b>+{{ subscribe_bonus }} {{ subscribe_bonus | noun('крафтик|крафтика|крафтиков') }}</b>
                на свой бонусный счет
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-12 d-flex align-items-end">
            <form class="subscription-form" @submit.prevent="onSubscribe">
              <label for="email">
                <input
                  id="email"
                  v-model="subscriber"
                  type="email"
                  placeholder="Введите ваш e-mail"
                  :disabled="loading"
                />
                <button class="btn" type="submit" :disabled="loading">
                  <fa :icon="['fad', 'paper-plane']" />
                </button>
              </label>
            </form>
          </div>
        </div>
      </div>
    </section>

    <nuxt-child />
  </div>
</template>

<script>
import {faPaperPlane, faCircle} from '@fortawesome/pro-duotone-svg-icons'
import {faPlay} from '@fortawesome/pro-solid-svg-icons'
import CoursesList from '../components/courses-list'
import ReviewsList from '../components/reviews-list'
// import Swiper from 'swiper'
import HeaderBg from '../components/header-bg'

import slide1 from '../assets/images/slider/slider-1.jpg'
import slide2 from '../assets/images/slider/slider-2.jpg'
import slide3 from '../assets/images/slider/slider-3.jpg'

export default {
  auth: false,
  components: {CoursesList, ReviewsList, HeaderBg},
  async asyncData({app, env}) {
    const [{data: courses}, {data: reviews}, {data: free}] = await Promise.all([
      app.$axios.get('web/courses', {params: {limit: 2}}),
      app.$axios.get('web/reviews', {params: {limit: 3}}),
      app.$axios.get('web/free-lessons', {params: {limit: 2}}),
    ])

    return {
      courses,
      reviews,
      free,
      subscribe_bonus: env.COINS_SUBSCRIBE,
    }
  },
  data() {
    return {
      loading: false,
      subscriber: '',
      courses: {},
      reviews: {},
      free: {},
      slide1,
      slide2,
      slide3,
    }
  },
  created() {
    this.$fa.add(faCircle, faPaperPlane, faPlay)
    this.$app.header_image.set(true)
  },
  mounted() {
    require(['lightgallery.js'], () => {
      window.lightGallery(document.getElementsByClassName('gallery__slider')[0], {
        selector: 'a',
        download: false,
      })
    })
  },
  methods: {
    onSubscribe() {
      this.loading = true
      this.$axios
        .put('web/subscribe', {email: this.subscriber})
        .then(() => {
          this.$notify.success({message: 'Вы успешно подписались на нашу рассылку!'})
          this.subscriber = ''
        })
        .catch(() => {})
        .finally(() => {
          this.loading = false
        })
    },
  },
  head() {
    return {
      title: 'Крафти',
    }
  },
}
</script>

<style lang="scss">
.gallery__slider {
  .swiper-slide {
    img {
      cursor: pointer;
      border-radius: 5px;
    }
  }
}
@media (max-width: 576px) {
  .index-bg {
    max-height: 350px;
    &::before {
      height: 100px !important;
    }
  }
}
</style>
