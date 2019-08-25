<template>
  <div>
    <div class="wrapper">
      <div class="wrapper__bg bg_600" :style="style_bg">
        <a class="ic__play" @click.prevent="$refs.mainVideo.show()"></a>
      </div>
      <vimeo :video="355023151" ref="mainVideo"/>

      <div class="wrapper__content">
        <section class="about__info">
          <div class="container">
            <div class="row">
              <div class="col-md-8 offset-md-2">
                <div class="row">
                  <div class="col-12 d-flex justify-content-between align-items-center">
                    <h2 class="section__title">О нас</h2>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-lg-5">
                    <div class="about__text">
                      <p>Творчество всей семьёй? Конечно! Мы в KRAFTI, онлайн-мастерской для детей и взрослых, подготовили поэтапное руководство.</p>
                      <p>Наша команда разработала авторские курсы с обучением в режиме реального времени.</p>
                      <p>Их не придётся ставить на паузу — мы позаботились, чтобы всё было пошагово и легко.
                        Наши уроки помогут создавать произведения искусства независимо от возраста и умений.</p>
                      <p>Практические навыки и вдохновение — всё здесь!</p>
                    </div>
                  </div>
                  <div class="col-12 col-lg-7 swiper-container">
                    <div class="gallery__slider swiper-wrapper">
                      <div class="slider__item swiper-slide">
                        <div class="slider__wrap--item d-flex">
                          <div class="left__block mr-1"><img class="img-responsive"
                                                             src="~assets/images/content/teacher/img.jpg" alt=""></div>
                          <div class="right__block"><img class="mb-15 img-responsive"
                                                         src="~assets/images/content/teacher/img-2.jpg" alt=""><img
                            class="img-responsive" src="~assets/images/content/teacher/img-3.jpg" alt=""></div>
                        </div>
                      </div>
                      <div class="slider__item swiper-slide">
                        <div class="slider__wrap--item d-flex">
                          <div class="left__block mr-1"><img class="img-responsive"
                                                             src="~assets/images/content/teacher/img.jpg" alt=""></div>
                          <div class="right__block"><img class="mb-15 img-responsive"
                                                         src="~assets/images/content/teacher/img-2.jpg" alt=""><img
                            class="img-responsive" src="~assets/images/content/teacher/img-3.jpg" alt=""></div>
                        </div>
                      </div>
                    </div>
                    <div class="swiper-pagination"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <section class="courses-list mt-5">
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
        <section class="reviews_list tab__wrap--scroll mt-5">
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
                обновлений, а также получите <b>+150 крафтиков</b> на свой бонусный счет
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-12 d-flex align-items-end">
            <form class="subscription__form" action="" @submit.prevent="onSubscribe">
              <label for="email">
                <input id="email" type="email" placeholder="Введите ваш e-mail"
                       v-model="subscriber" :disabled="loading"/>
              </label>
              <input type="submit" value="" style="display: none;">
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
    import Swiper from 'swiper'
    import bg from '../assets/images/general/headline_main.png'

    export default {
        auth: false,
        data() {
            return {
                loading: false,
                subscriber: '',
                style_bg: {'background-image': 'url(' + bg + ')'},
            }
        },
        components: {CoursesList, ReviewsList},
        async asyncData({app}) {
            const [courses, reviews] = await Promise.all([
                app.$axios.get('web/courses', {params: {limit: 2}}),
                app.$axios.get('web/reviews', {params: {limit: 3}})
            ]);

            return {
                courses: courses.data.rows,
                courses_total: courses.data.total,
                reviews: reviews.data.rows,
                reviews_total: reviews.data.total,
            };
        },
        mounted() {
            document.getElementsByTagName('header')[0].classList.add('header_img');

            new Swiper('.swiper-container', {
                pagination: {
                    el: '.swiper-pagination',
                    type: 'bullets',
                }
            });
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
        head() {
            return {
                title: 'Крафти',
            }
        },
    }
</script>
