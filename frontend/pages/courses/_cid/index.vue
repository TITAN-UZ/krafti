<template>
  <div class="wrapper">
    <div class="wrapper__bg mt-6" :style="style_bg"></div>
    <div class="wrapper__content">
      <section class="course__content">

        <div class="container">
          <div class="row course__content--header">
            <div class="col-lg-7 col-12">
              <div class="course-video">
                  <a class="video-link"
                     :style="{'background-image': (record.cover ? 'url(' + record.cover + ')' : false)}"></a>
              </div>
            </div>
            <div class="col-lg-5 col-12">
              <div class="course__info d-flex flex-column">
                <div
                  class="course__info--top d-flex justify-content-lg-end justify-content-between align-items-center mb-2">
                  <!--
                  <a class="ic__heart&#45;&#45;gray mr-4" href="" aria-label="Favorites"></a>
                  <a class="ic__share" href="" aria-label="Share"></a>
                  -->

                  <b-spinner type="grow" class="text-primary" v-if="loading == ('favorite:' + record.id)"/>
                  <a @click.prevent="addFavorite(record.id)" v-else-if="$auth.user && !$auth.user.favorites.includes(record.id)">
                    <fa :icon="['fal', 'heart']" size="2x"/>
                  </a>
                  <a @click.prevent="deleteFavorite(record.id)" v-else>
                    <fa :icon="['fas', 'heart']" size="2x"/>
                  </a>
                  <a class="ml-md-4">
                    <fa :icon="['fas', 'share']" size="2x"/>
                  </a>
                </div>
                <div class="course__info--pretop d-flex justify-content-center align-items-center">
                  <span class="count__lessons">{{record.lessons_count}} видео {{record.lessons_count | noun('урок|урока|уроков')}}</span>
                </div>
                <div class="course__info--body d-flex align-items-center justify-content-center flex-column">
                  <div class="course__title">{{record.title}}</div>
                  <div class="course__tagline">{{record.tagline}}</div>
                </div>
                <div class="course__info--footer">
                  <div class="row course__dopinfo mb-2 d-flex justify-content-around">
                    <div class="col nowrap">
                      <span class="ic__eyes mr-2"></span>
                      <span class="text">{{record.views_count}}</span>
                    </div>
                    <div class="col nowrap">
                      <span class="ic__user mr-2"></span>
                      <span class="text">{{record.age}} лет</span>
                    </div>
                    <div class="col nowrap">
                      <span class="ic__like mr-2"></span>
                      <span class="text">{{record.likes_count}}</span>
                    </div>
                  </div>
                  <div class="row buy__wrap">
                    <form class="buy" action="">
                      <button class="btn__buy">Купить за<span class="price">{{record.price}} р</span></button>
                      <!-- button.btn__play Воспроизвести все-->
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row course__content--tabs">
            <div class="col-12">
              <div class="row mob_container">
                <div class="col-12 tab__wrap--scroll">
                  <b-tabs v-model="tab">
                    <b-tab title="Описание" active>
                      <div class="text">{{record.description}}</div>
                    </b-tab>
                    <b-tab title="Отзывы">
                      <div class="row reviews__wrap">
                        <div class="col-lg-7 col-12 review__item--list">
                          <div class="media review__item">
                            <div class="wrap mr-3"><img class="review__item--photo rounded-circle"
                                                        src="~assets/images/content/teacher.png" alt="..."></div>
                            <div class="media-body"><a href="">
                              <h4 class="review__item--title mt-0">Анна Сотнич</h4></a>
                              <h5 class="review__item--position">Head bartender в BB Group</h5>
                              <div class="review__item--info">On the other hand, we denounce with righteous indignation
                                and dislike men who are so beguiled and demoralized by the charms of pleasure of the
                                moment, so ...
                              </div>
                              <div class="review__item--more"><a class="more_link" href="#">Показать целиком</a></div>
                            </div>
                          </div>
                          <div class="media review__item">
                            <div class="wrap mr-3"><img class="review__item--photo rounded-circle"
                                                        src="~assets/images/content/teacher.png" alt="..."></div>
                            <div class="media-body"><a href="">
                              <h4 class="review__item--title mt-0">Валентина Емельяненоко</h4></a>
                              <h5 class="review__item--position">Housewife</h5>
                              <div class="review__item--info">On the other hand, we denounce with righteous indignation
                                and dislike men who are so beguiled and demoralized by the charms of pleasure of the
                                moment, so ...
                              </div>
                              <div class="review__item--more"><a class="more_link" href="#">Показать целиком</a></div>
                            </div>
                          </div>
                          <button class="button btn-more ml-5 mt-4">Показать все отзывы</button>
                        </div>
                        <div class="col-5 d-none d-md-none d-lg-block"><img class="img-responsive contact__img"
                                                                            src="~assets/images/general/tab-reviews.png"
                                                                            alt=""></div>
                      </div>
                    </b-tab>
                    <b-tab title="Преподаватели">
                      <div class="row mob_container item__wrap d-flex tab__wrap--scroll">
                        <div class="col-12 col-md-6 col-lg-4 m-width-80">
                          <div class="teacher__item d-flex flex-column justify-content-center align-items-center">
                            <div class="teacher__item--photo"><img class="rounded-circle"
                                                                   src="~assets/images/content/review/man.png" alt="">
                            </div>
                            <h2 class="teacher__item--name">Виктор Сухоруков</h2>
                            <div class="teacher__item--position">SaveSpace Inc.</div>
                            <div class="teacher__item--text">These cases are perfectly simple and easy to distinguish.
                              In a free hour, when our power of choice is untrammelled and when nothing prevents our
                              being able to do what we like best, every pleasure is to be welcomed and every pain
                              avoided.
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 m-width-80">
                          <div class="teacher__item d-flex flex-column justify-content-center align-items-center">
                            <div class="teacher__item--photo"><img class="rounded-circle"
                                                                   src="~assets/images/content/review/woman2.png"
                                                                   alt=""></div>
                            <h2 class="teacher__item--name">Виктор Сухоруков</h2>
                            <div class="teacher__item--position">SaveSpace Inc.</div>
                            <div class="teacher__item--text">These cases are perfectly simple and easy to distinguish.
                              In a free hour, when our power of choice is untrammelled and when nothing prevents our
                              being able to do what we like best, every pleasure is to be welcomed and every pain
                              avoided.
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4 m-width-80">
                          <div class="teacher__item d-flex flex-column justify-content-center align-items-center">
                            <div class="teacher__item--photo"><img class="rounded-circle"
                                                                   src="~assets/images/content/review/man2.png" alt="">
                            </div>
                            <h2 class="teacher__item--name">Виктор Сухоруков</h2>
                            <div class="teacher__item--position">SaveSpace Inc.</div>
                            <div class="teacher__item--text">These cases are perfectly simple and easy to distinguish.
                              In a free hour, when our power of choice is untrammelled and when nothing prevents our
                              being able to do what we like best, every pleasure is to be welcomed and every pain
                              avoided.
                            </div>
                          </div>
                        </div>
                      </div>
                    </b-tab>
                    <b-tab title="Уроки">
                      <div class="row palitra">
                        <div
                          class="col-lg-8 col-12 palitra__info d-flex justify-content-center align-items-center flex-column">
                          <div class="palitra__info--title">Палитра прогресса</div>
                          <div class="palitra__info--count">+450 крафтиков</div>
                          <div class="palitra__info--text">Это ваша персональная палитра прогресса. Смотрите уроки,
                            отправляйте домашние задания и ваша палитра будет заполняться цветами. За полную палитру вы
                            получаете крафтики.
                          </div>
                        </div>
                        <div
                          class="col-lg-4 col-12 col-md-6 palitra__img d-flex align-content-center justify-content-center">
                          <img class="img-responsive" src="~assets/images/general/bg_palitra.png" alt="">
                        </div>
                      </div>
                      <div class="steps__wrap">
                        <div class="row">
                          <div class="col-12 tab__wrap--scroll">
                            <b-tabs>
                              <b-tab title="1 этап" active>
                                <div class="step__wrap">
                                  <div class="row">
                                    <div class="col-12">
                                      <div class="step__description">On the other hand, we denounce with righteous
                                        indignation and dislike men who are so beguiled and demoralized by the charms of
                                        pleasure of the moment, so blinded by desire, that they cannot foresee the pain
                                        and trouble that are bound to ensue; and equal blame belongs to those who fail
                                        in their.
                                      </div>
                                    </div>
                                  </div>
                                  <div class="row lessons__list">
                                    <div
                                      class="col-lg-4 col-12 col-md-6 lesson__item viewed d-flex justify-content-lg-center justify-content-between align-content-center flex-lg-column">
                                      <div class="lesson__item--video">
                                        <nuxt-link class="video" to="/courses/1/video/1" aria-label="video"
                                                   data-toggle="modal" data-target="#videoModal">
                                          <img class="img-responsive lesson__video--thumb"
                                               src="~assets/images/content/courses/lesson-1.png" alt="">
                                        </nuxt-link>
                                      </div>
                                      <div class="lesson__item--info d-flex align-items-center justify-content-center">
                                        On the other hand, we denounce with righteous indignation and dislike men who
                                        are so beguiled and demoralized
                                      </div>
                                    </div>
                                    <div
                                      class="col-lg-4 col-12 col-md-6 lesson__item d-flex justify-content-lg-center align-content-center flex-lg-column">
                                      <div class="lesson__item--video">
                                        <nuxt-link class="video" to="/courses/1/video/2" aria-label="video"
                                                   data-toggle="modal" data-target="#videoModal">
                                          <img class="img-responsive lesson__video--thumb"
                                               src="~assets/images/content/courses/lesson-2.png" alt="">
                                        </nuxt-link>
                                      </div>
                                      <div class="lesson__item--info d-flex align-items-center justify-content-center">
                                        On the other hand, we denounce with righteous indignation and dislike men who
                                        are so beguiled and demoralized
                                      </div>
                                    </div>
                                    <div
                                      class="col-lg-4 col-12 col-md-6 lesson__item d-flex justify-content-lg-center align-content-center flex-lg-column">
                                      <div class="lesson__item--video">
                                        <nuxt-link class="video" to="/courses/1/video/3" aria-label="video"
                                                   data-toggle="modal" data-target="#videoModal">
                                          <img class="img-responsive lesson__video--thumb"
                                               src="~assets/images/content/courses/lesson-3.png" alt="">
                                        </nuxt-link>
                                      </div>
                                      <div class="lesson__item--info d-flex align-items-center justify-content-center">
                                        On the other hand, we denounce with righteous indignation and dislike men who
                                        are so beguiled and demoralized
                                      </div>
                                    </div>
                                    <div
                                      class="col-lg-4 col-12 col-md-6 lesson__item d-flex justify-content-lg-center align-content-center flex-lg-column">
                                      <div class="lesson__item--video">
                                        <nuxt-link class="video" to="/courses/1/video/4" aria-label="video"
                                                   data-toggle="modal" data-target="#videoModal">
                                          <img class="img-responsive lesson__video--thumb"
                                               src="~assets/images/content/courses/lesson-4.png" alt="">
                                        </nuxt-link>
                                      </div>
                                      <div class="lesson__item--info d-flex align-items-center justify-content-center">
                                        On the other hand, we denounce with righteous indignation and dislike men who
                                        are so beguiled and demoralized
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="home__work d-flex justify-content-center align-items-center flex-column">
                                  <div class="home__work--img"><img class="img-responsive"
                                                                    src="~assets/images/general/work_thumb.png" alt="">
                                  </div>
                                  <div class="home__work--title">Домашнее задание</div>
                                  <div class="home__work--count">+150 крафтиков</div>
                                  <div class="home__work--text">On the other hand, we denounce with righteous
                                    indignation and dislike men who are so beguiled and demoralized by the charms of
                                    pleasure of the moment, so blinded by desire.
                                  </div>
                                  <form class="homeWork" action="">
                                    <label for="photo">
                                      <input type="file" name="photo">
                                    </label>
                                    <input id="photo" type="submit" value="" style="display: none;">
                                  </form>
                                </div>
                              </b-tab>
                              <b-tab title="2 этап" disabled>...</b-tab>
                              <b-tab title="3 этап" disabled>...</b-tab>
                            </b-tabs>
                          </div>
                        </div>
                      </div>
                      <div class="bonus__lesson">
                        <div class="bonus__lesson--wrap d-flex justify-content-center align-items-center flex-column">
                          <div class="bonus__lesson--thumb"><span class="ic__star-gold"></span></div>
                          <div class="bonus__lesson--title"><span class="ic__locked--gray mr-2"> </span>Бонусный урок
                          </div>
                          <div class="bonus__lesson--text text-center">On the other hand, we denounce with righteous
                            indignation and dislike men who are so beguiled and demoralized by the charms of pleasure of
                            the moment, so blinded by desire.
                          </div>
                          <div class="bonus__lesson--video"><a class="video" href="" aria-label="video"
                                                               data-toggle="modal" data-target="#videoModal"><img
                            class="img-responsive bonus__lesson--thumb"
                            src="~assets/images/content/courses/lesson-2.png" alt=""></a></div>
                          <div class="bonus__btn">
                            <button class="btn__buy justify-content-center align-items-center"><span
                              class="ic__star mr-2"></span>Купить за 450 крафтиков
                            </button>
                          </div>
                        </div>
                      </div>
                    </b-tab>
                  </b-tabs>
                </div>
                <div class="col-12 tab__wrap--scroll mt-5" v-if="tab != 3">
                  <!--<div class="course__content&#45;&#45;options tab__wrap&#45;&#45;scroll">
                    <div class="row no-gutters item__wrap d-flex justify-content-between">
                      <div class="col item__option d-flex align-items-center justify-content-center">
                        <div class="item__option&#45;&#45;icon"><img class="img-responsive"
                                                             src="~assets/images/general/options/option-1.png" alt="">
                        </div>
                        <div class="item__option&#45;&#45;description">
                          <div class="icon"><img class="img-responsive"
                                                 src="~assets/images/general/options/option-1.png" alt=""></div>
                          <div class="text">Встроенные Субтитры</div>
                        </div>
                      </div>
                      <div class="col item__option d-flex align-items-center justify-content-center">
                        <div class="item__option&#45;&#45;icon"><img class="img-responsive"
                                                             src="~assets/images/general/options/option-2.png" alt="">
                        </div>
                        <div class="item__option&#45;&#45;description">
                          <div class="icon"><img class="img-responsive"
                                                 src="~assets/images/general/options/option-2.png" alt=""></div>
                          <div class="text">Встроенные Субтитры</div>
                        </div>
                      </div>
                      <div class="col item__option d-flex align-items-center justify-content-center">
                        <div class="item__option&#45;&#45;icon"><img class="img-responsive"
                                                             src="~assets/images/general/options/option-3.png" alt="">
                        </div>
                        <div class="item__option&#45;&#45;description">
                          <div class="icon"><img class="img-responsive"
                                                 src="~assets/images/general/options/option-3.png" alt=""></div>
                          <div class="text">Встроенные Субтитры</div>
                        </div>
                      </div>
                      <div class="col item__option d-flex align-items-center justify-content-center">
                        <div class="item__option&#45;&#45;icon"><img class="img-responsive"
                                                             src="~assets/images/general/options/option-4.png" alt="">
                        </div>
                        <div class="item__option&#45;&#45;description">
                          <div class="icon"><img class="img-responsive"
                                                 src="~assets/images/general/options/option-4.png" alt=""></div>
                          <div class="text">Встроенные Субтитры</div>
                        </div>
                      </div>
                      <div class="col item__option d-flex align-items-center justify-content-center">
                        <div class="item__option&#45;&#45;icon"><img class="img-responsive"
                                                             src="~assets/images/general/options/option-5.png" alt="">
                        </div>
                        <div class="item__option&#45;&#45;description">
                          <div class="icon"><img class="img-responsive"
                                                 src="~assets/images/general/options/option-5.png" alt=""></div>
                          <div class="text">Встроенные Субтитры</div>
                        </div>
                      </div>
                      <div class="col item__option d-flex align-items-center justify-content-center">
                        <div class="item__option&#45;&#45;icon"><img class="img-responsive"
                                                             src="~assets/images/general/options/option-6.png" alt="">
                        </div>
                        <div class="item__option&#45;&#45;description">
                          <div class="icon"><img class="img-responsive"
                                                 src="~assets/images/general/options/option-6.png" alt=""></div>
                          <div class="text">Встроенные Субтитры</div>
                        </div>
                      </div>
                      <div class="col item__option d-flex align-items-center justify-content-center">
                        <div class="item__option&#45;&#45;icon"><img class="img-responsive"
                                                             src="~assets/images/general/options/option-7.png" alt="">
                        </div>
                        <div class="item__option&#45;&#45;description">
                          <div class="icon"><img class="img-responsive"
                                                 src="~assets/images/general/options/option-7.png" alt=""></div>
                          <div class="text">Встроенные Субтитры</div>
                        </div>
                      </div>
                      <div class="col item__option d-flex align-items-center justify-content-center">
                        <div class="item__option&#45;&#45;icon"><img class="img-responsive"
                                                             src="~assets/images/general/options/option-8.png" alt="">
                        </div>
                        <div class="item__option&#45;&#45;description">
                          <div class="icon"><img class="img-responsive"
                                                 src="~assets/images/general/options/option-8.png" alt=""></div>
                          <div class="text">Встроенные Субтитры</div>
                        </div>
                      </div>
                    </div>
                  </div>-->
                  <div class="course__content--process">
                    <div class="row">
                      <div class="col-12">
                        <h2 class="section__title">Процесс</h2>
                      </div>
                    </div>
                    <div class="tab__wrap--scroll">
                      <div class="row item__wrap flex-lg-wrap">
                        <div class="col-lg-4 col-12 col-md-6 m-width-80">
                          <div class="process__item d-flex justify-content-end align-items-end">
                            <div class="process__item--number">1</div>
                            <div class="process__item--body">
                              <div class="title">Смотрите видеоуроки в модуле курса</div>
                              <div class="text">Вы можете смотреть уроки с любого устройства, когда вам удобно</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4 col-12 col-md-6 m-width-80">
                          <div class="process__item color-1 d-flex justify-content-end align-items-end">
                            <div class="process__item--number">2</div>
                            <div class="process__item--body">
                              <div class="title">Делаете домашнее задание</div>
                              <div class="text">Выполняете домашнее задание и отправляете на проверку</div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4 col-12 col-md-6 m-width-80">
                          <div class="process__item color-2 d-flex justify-content-end align-items-end">
                            <div class="process__item--number">3</div>
                            <div class="process__item--body">
                              <div class="title">Получаете обратную связь</div>
                              <div class="text">Преподаватель разбирает ошибки, если они есть и двигаетесь дальше</div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <section class="courses-list tab__wrap--scroll" v-if="similar.length">
                    <h2 class="section__title">Похожие курсы</h2>
                    <courses-list :courses="similar"/>
                  </section>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <nuxt-child></nuxt-child>

  </div>
</template>

<script>
    import CoursesList from '../../../components/courses-list'
    import {faHeart as faHeartSolid, faShare} from '@fortawesome/pro-solid-svg-icons'
    import {faHeart as faHeartLight} from '@fortawesome/pro-light-svg-icons'
    import bg from '../../../assets/images/general/headline_course.png';

    export default {
        data() {
            return {
                loading: false,
                tab: 0,
                style_bg: {'background-image': 'url(' + bg + ')'},
            }
        },
        components: {
            'courses-list': CoursesList,
        },
        scrollToTop: false,
        asyncData({app, params}) {
            let data = {
                record: {},
                similar: [],
            };
            return app.$axios.get('web/courses', {params: {id: params.cid}})
                .then(res => {
                    data.record = res.data;

                    return app.$axios.get('web/courses', {params: {exclude: params.cid, category: res.data.category}})
                        .then(res => {
                            data.similar = res.data.rows;

                            return data;
                        });
                })
        },
        methods: {
            addFavorite(id) {
                this.loading = 'favorite:' + id;
                this.$axios.put('user/favorite', {course_id: id})
                    .then(res => {
                        this.loading = false;
                        this.$auth.setUser(res.data.user);
                    })
                    .catch(() => {
                        this.loading = false;
                    })
            },
            deleteFavorite(id) {
                this.loading = 'favorite:' + id;
                this.$axios.delete('user/favorite', {params: {course_id: id}})
                    .then(res => {
                        this.loading = false;
                        this.$auth.setUser(res.data.user);
                    })
                    .catch(() => {
                        this.loading = false;
                    })
            },
        },
        head() {
            return {
                title: 'Крафти / Курсы / ' + this.record.title,
            }
        },
        mounted() {
            document.getElementsByTagName('header')[0].classList.add('header_img')
        },
        created() {
            this.$fa.add(faHeartSolid, faHeartLight, faShare)
        }
    }
</script>
