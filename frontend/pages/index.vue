<template>
  <div>
    <div class="wrapper">
      <div class="wrapper__bg bg_600" :style="style_bg">
        <!--<a class="ic__play" href=""></a>-->
      </div>
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
                    <div class="about__text">These cases are perfectly simple and easy to distinguish. In a free hour,
                      when our power of choice is untrammelled and when nothing prevents our being able to do what we
                      like best, every pleasure is to be welcomed and every pain avoided. These cases are perfectly
                      simple and easy to distinguish. In a free hour, when our power of choice is untrammelled and when
                      nothing prevents our being able to do what we like best, every pleasure is to be welcomed and
                      every pain avoided.
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
                    <!--<b-link to="/reviews" class="link__more">См. все</b-link>-->
                  </div>
                </div>
                <div class="row mob_container item__wrap d-flex">
                  <div class="col-12 col-lg-4 m-width-80">
                    <div class="review__item d-flex flex-column justify-content-center align-items-center">
                      <div class="review__item--photo"><img class="rounded-circle"
                                                            src="~assets/images/content/review/man.png" alt=""></div>
                      <h2 class="review__item--name">Виктор Сухоруков</h2>
                      <div class="review__item--position">SaveSpace Inc.</div>
                      <div class="review__item--text">“Chamer is the most valuable business resource we have ever
                        purchased. I would be lost without chamer.”
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-lg-4 m-width-80">
                    <div class="review__item d-flex flex-column justify-content-center align-items-center">
                      <div class="review__item--photo"><img class="rounded-circle"
                                                            src="~assets/images/content/review/woman2.png" alt=""></div>
                      <h2 class="review__item--name">Виктор Сухоруков</h2>
                      <div class="review__item--position">SaveSpace Inc.</div>
                      <div class="review__item--text">“Chamer is the most valuable business resource we have ever
                        purchased. I would be lost without chamer.”
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-lg-4 m-width-80">
                    <div class="review__item d-flex flex-column justify-content-center align-items-center">
                      <div class="review__item--photo"><img class="rounded-circle"
                                                            src="~assets/images/content/review/man2.png" alt=""></div>
                      <h2 class="review__item--name">Виктор Сухоруков</h2>
                      <div class="review__item--position">SaveSpace Inc.</div>
                      <div class="review__item--text">“Chamer is the most valuable business resource we have ever
                        purchased. I would be lost without chamer.”
                      </div>
                    </div>
                  </div>
                </div>
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
    import Swiper from 'swiper'
    import bg from '../assets/images/general/headline_main.png'

    export default {
        data() {
            return {
                loading: false,
                subscriber: '',
                style_bg: {'background-image': 'url(' + bg + ')'},
            }
        },
        components: {
            'courses-list': CoursesList,
        },
        asyncData({app}) {
            let data = {
                courses: [],
                courses_total: 0,
                authors: [],
                authors_total: 0,
            };
            return app.$axios.get('web/courses', {params: {limit: 2}})
                .then(res => {
                    data.courses = res.data.rows;
                    data.courses_total = res.data.total;

                    /*app.$axios.get('authors', {params: {role_id: 2, limit: 3}})
                        .then(res => {
                            data.authors = res.data.rows;
                            data.authors_total = res.data.total;
                        });*/

                    return data;
                })
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
