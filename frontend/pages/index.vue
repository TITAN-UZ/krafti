<template>
  <!--TODO-DONE Установка счётчиков Яндекс и Google-->
  <!--TODO-DONE Блокировка уроков-->
  <!--TODO-DONE Кнопка fullscreen в плеере Safari-->
  <!--TODO-DONE Авторизация через VK и Instagram-->
  <!--TODO-DONE Личный кабинет юзера (кроме уведомлений)-->
  <!--TODO-DONE Дети в профиле (4 шт максимум) с датами их рождения-->
  <!--TODO-DONE Подключить PayPal-->
  <!--TODO-DONE Ограничение просмотра до 5 устройств-->
  <!--TODO-DONE Вывод информации по рефералам-->
  <!--TODO-DONE Управление авторами (добавить 3 фото в аккаунт автора) и расширить описание-->
  <!--TODO-DONE Галерея выполненных работ в курсе-->
  <!--TODO-DONE Генерация дипломов об окончании курса-->
  <!--TODO-DONE Генерация и вывод уведомлений об окончании оплаты курса, днях рождения, начислении крафтиков и выпуске дипломов-->
  <div>
    <div class="wrapper">
      <header-bg image="index" class="wrapper__bg bg_600 index-bg">
        <template slot="content">
          <span class="play-button main" @click.prevent="$refs.mainVideo.show()">
            <fa :icon="['fad', 'circle']"/>
            <fa :icon="['fas', 'play']"/>
          </span>
        </template>
      </header-bg>
      <vimeo :video="$settings.video.index" ref="mainVideo"/>

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
                        Привет! Мы — KRAFTi, новое слово в онлайн-обучении. Мы верим, что в каждом ребёнке живёт настоящий творец, а в каждом взрослом — настоящий ребёнок.
                        Именно поэтому творческое и интеллектуальное развитие взрослых и детей стало нашей страстью!
                      </p>
                      <p>
                        Мы не просто обучаем, мы раскрываем потенциал. Мы создаём невероятно увлекательные и насыщенные курсы по уникальной методике. Мы назвали её «Методика непрерывного обучения».
                        Мы верим, что интересное обучение не только обогащает знаниями, но и способно изменить мировоззрение. Создать для детей преимущество в предстоящей большой жизни и вернуть
                        взрослым ощущение творческого полёта.</p>
                      <p>Учитесь, творите, развивайтесь, вдохновляйте окружающих!</p>
                    </div>
                  </div>
                  <div class="col-12 col-xl-7 swiper-container">
                    <div class="gallery__slider swiper-wrapper">
                      <div class="slider__item swiper-slide">
                        <div class="slider__wrap--item d-flex align-items-center">
                          <div class="left__block mr-1">
                            <a :href="slide1">
                              <img class="img-responsive" src="~assets/images/slider/slider-1-thumb.jpg" alt="">
                            </a>
                          </div>
                          <div class="right__block">
                            <a :href="slide2">
                              <img class="mb-15 img-responsive" src="~assets/images/slider/slider-2-thumb.jpg" alt="">
                            </a>
                            <a :href="slide3">
                              <img class="img-responsive" src="~assets/images/slider/slider-3-thumb.jpg" alt="">
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
        <section class="courses-list mt-5" v-if="courses_total > 0">
          <div class="container">
            <div class="row">
              <div class="col-md-8 offset-md-2">
                <div class="row">
                  <div class="col-12 d-flex justify-content-between align-items-center">
                    <h2 class="section__title">Курсы</h2>
                    <b-link to="/courses" class="link__more" v-if="courses_total > 2">См. все</b-link>
                  </div>
                </div>
                <courses-list :courses="courses"/>
              </div>
            </div>
          </div>
        </section>
        <section class="reviews_list tab__wrap--scroll mt-5" v-if="reviews_total > 0">
          <div class="container">
            <div class="row">
              <div class="col-md-8 offset-md-2">
                <div class="row">
                  <div class="col-12 d-flex justify-content-between align-items-center">
                    <h2 class="section__title">Отзывы</h2>
                    <b-link :to="{name: 'reviews'}" class="link__more" v-if="reviews_total > 3">См. все</b-link>
                  </div>
                </div>
                <reviews-list :reviews="reviews" row-class="row mob_container item__wrap d-flex"/>
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
                Подпишитесь на наши новости и оставайтесь в курсе самых последних
                обновлений, а также получите <b>+{{subscribe_bonus}} {{subscribe_bonus | noun('крафтик|крафтика|крафтиков')}}</b>
                на свой бонусный счет
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-12 d-flex align-items-end">
            <form class="subscription-form" @submit.prevent="onSubscribe">
              <label for="email">
                <input id="email" type="email" placeholder="Введите ваш e-mail" v-model="subscriber" :disabled="loading"/>
                <button class="btn" type="submit" :disabled="loading">
                  <fa :icon="['fad', 'paper-plane']"/>
                </button>
              </label>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
    import CoursesList from '../components/courses-list'
    import ReviewsList from '../components/reviews-list'
    //import Swiper from 'swiper'
    import HeaderBg from '../components/header-bg'
    import {faPaperPlane, faCircle} from '@fortawesome/pro-duotone-svg-icons'
    import {faPlay} from '@fortawesome/pro-solid-svg-icons'

    import slide1 from '../assets/images/slider/slider-1.jpg'
    import slide2 from '../assets/images/slider/slider-2.jpg'
    import slide3 from '../assets/images/slider/slider-3.jpg'

    export default {
        auth: false,
        data() {
            return {
                loading: false,
                subscriber: '',
                slide1, slide2, slide3
            }
        },
        components: {CoursesList, ReviewsList, HeaderBg},
        async asyncData({app, env}) {
            const [courses, reviews] = await Promise.all([
                app.$axios.get('web/courses', {params: {limit: 2}}),
                app.$axios.get('web/reviews', {params: {limit: 3}})
            ]);

            return {
                courses: courses.data.rows,
                courses_total: courses.data.total,
                reviews: reviews.data.rows,
                reviews_total: reviews.data.total,
                subscribe_bonus: env.COINS_SUBSCRIBE,
            };
        },
        methods: {
            onSubscribe() {
                this.loading = true;
                this.$axios.put('web/subscribe', {email: this.subscriber})
                    .then(() => {
                        this.$notify.success({message: 'Вы успешно подписались на нашу рассылку!'});
                        this.subscriber = '';
                    })
                    .catch(() => {
                    })
                    .finally(() => {
                        this.loading = false;
                    })
            }
        },
        created() {
            this.$fa.add(faCircle, faPaperPlane, faPlay);
            this.$app.header_image.set(true);
        },
        mounted() {
            //import 'lightgallery.js/src/css/lightgallery.css'

            /*new Swiper('.swiper-container', {
                pagination: {
                    el: '.swiper-pagination',
                    type: 'bullets',
                }
            });*/
            require(['lightgallery.js'], () => {
                lightGallery(document.getElementsByClassName('gallery__slider')[0], {
                    selector: 'a',
                    download: false,
                });
            });
        },
        head() {
            return {
                title: 'Крафти',
            }
        }
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
