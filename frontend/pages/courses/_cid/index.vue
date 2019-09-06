<template>
  <div class="wrapper course">
    <header-bg image="course"/>
    <div class="wrapper__content">
      <section class="course__content">
        <vimeo :video="355023151" ref="mainVideo"/>
        <div class="container">
          <div class="row course__content--header">
            <div class="col-lg-7 col-12">
              <div class="course-video">
                <a class="video-link" @click.prevent="$refs.mainVideo.show()"
                   :style="{'background-image': (record.cover ? 'url(' + record.cover + ')' : false)}"></a>
              </div>
            </div>
            <div class="col-lg-5 col-12">
              <div class="course__info d-flex flex-column">
                <div
                  class="course__info--top d-flex justify-content-lg-end justify-content-between align-items-center mb-2">
                  <button class="btn btn-default">
                    <b-spinner type="grow" class="text-primary" v-if="loading == ('favorite:' + record.id)"/>
                    <a @click.prevent="addFavorite(record.id)"
                       v-else-if="$auth.user && !$auth.user.favorites.includes(record.id)">
                      <fa :icon="['fal', 'heart']" size="2x"/>
                    </a>
                    <a @click.prevent="deleteFavorite(record.id)" v-else-if="$auth.user">
                      <fa :icon="['fas', 'heart']" size="2x"/>
                    </a>
                  </button>

                  <client-only>
                    <b-dropdown variant="default" no-caret right class="ml-md-2">
                      <template slot="button-content">
                        <a>
                          <fa :icon="['fad', 'share']" size="2x"/>
                        </a>
                      </template>
                      <social-sharing :url="'https://krafti.ru/courses/' + $route.params.cid" inline-template>
                        <div>
                          <b-dropdown-item>
                            <network network="facebook">
                              <fa :icon="['fab', 'facebook']" class="mr-2" style="color:#3b5998"/>
                              Facebook
                            </network>
                          </b-dropdown-item>
                          <b-dropdown-item>
                            <network network="vk">
                              <fa :icon="['fab', 'vk']" class="mr-2" style="color:#4c75a3"/>
                              Вконтакте
                            </network>
                          </b-dropdown-item>
                          <b-dropdown-item>
                            <network network="pinterest">
                              <fa :icon="['fab', 'pinterest']" class="mr-2" style="color:#bd081c"/>
                              Pinterest
                            </network>
                          </b-dropdown-item>
                          <b-dropdown-item>
                            <network network="twitter">
                              <fa :icon="['fab', 'twitter']" class="mr-2" style="color:#55acee"/>
                              Twitter
                            </network>
                          </b-dropdown-item>
                        </div>
                      </social-sharing>
                    </b-dropdown>
                  </client-only>
                </div>
                <div class="course__info--pretop d-flex justify-content-center align-items-center">
                  <span class="count__lessons">{{record.lessons_count}} видео {{record.lessons_count | noun('урок|урока|уроков')}}</span>
                </div>
                <div class="course__info--body d-flex align-items-center justify-content-center flex-column">
                  <div class="course__title">{{record.title}}</div>
                  <div class="course__tagline">{{record.tagline}}</div>
                </div>
                <div class="course__info--footer">
                  <div class="row course__dopinfo mb-2 d-flex align-items-center justify-content-around">
                    <div class="col nowrap">
                      <fa :icon="['fad', 'eye']"/>
                      <span class="text">{{record.views_count}}</span>
                    </div>
                    <div class="col nowrap">
                      <fa :icon="['fad', 'user']"/>
                      <span class="text">{{record.age}} лет</span>
                    </div>
                    <div class="col nowrap">
                      <fa :icon="['fad', 'thumbs-up']"/>
                      <span class="text">{{record.likes_sum}}</span>
                    </div>
                  </div>
                  <div class="row buy__wrap">
                    <button
                      class="btn btn-default btn__play"
                      v-if="!record.lessons_count">
                      Готовится к публикации
                    </button>
                    <nuxt-link
                      class="btn btn-default btn__play"
                      v-else-if="record.bought === true && lessons[record.progress.section] && lessons[record.progress.section][record.progress.rank]"
                      :to="{name: 'courses-cid-index-lesson-lid', params: {cid: record.id, lid: lessons[record.progress.section][record.progress.rank].id}}">
                      <span v-if="record.progress.section > 1 || record.progress.rank > 0">Продолжить просмотр</span>
                      <span v-else>Начать просмотр</span>
                    </nuxt-link>
                    <nuxt-link
                      class="btn btn-default btn__play"
                      v-else-if="record.bought === true"
                      :to="{name: 'courses-cid-index-lesson-lid', params: {cid: record.id, lid: lessons[1][1].id}}">
                      <span>Начать просмотр</span>
                    </nuxt-link>
                    <nuxt-link
                      class="btn btn-default btn__buy"
                      v-else-if="record.bought === false"
                      :to="{name: 'courses-cid-index-buy', params: $route.params}">
                      Купить от <span class="price ml-2">{{record.price[3] - record.discount | number}} р</span>
                    </nuxt-link>
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
                    <b-tab title="Описание">
                      <div class="text">
                        <div class="mb-3">
                          {{record.description}}
                        </div>
                        <swiper-gallery :object-id="record.id" object-name="Course"/>
                      </div>
                    </b-tab>
                    <b-tab title="Отзывы" v-if="reviews.length">
                      <reviews-list :reviews="reviews" row-class="row reviews__wrap"/>
                      <!--<div class="row reviews__wrap">
                        <div class="col-lg-7 col-12 review__item&#45;&#45;list">
                          <div class="media review__item">
                            <div class="wrap mr-3">
                              <img class="review__item&#45;&#45;photo rounded-circle" src="~assets/images/content/teacher.png" alt="...">
                            </div>
                            <div class="media-body"><a href="">
                              <h4 class="review__item&#45;&#45;title mt-0">Анна Сотнич</h4></a>
                              <h5 class="review__item&#45;&#45;position">Head bartender в BB Group</h5>
                              <div class="review__item&#45;&#45;info">On the other hand, we denounce with righteous indignation
                                and dislike men who are so beguiled and demoralized by the charms of pleasure of the
                                moment, so ...
                              </div>
                              <div class="review__item&#45;&#45;more"><a class="more_link" href="#">Показать целиком</a></div>
                            </div>
                          </div>
                          <div class="media review__item">
                            <div class="wrap mr-3"><img class="review__item&#45;&#45;photo rounded-circle"
                                                        src="~assets/images/content/teacher.png" alt="..."></div>
                            <div class="media-body"><a href="">
                              <h4 class="review__item&#45;&#45;title mt-0">Валентина Емельяненоко</h4></a>
                              <h5 class="review__item&#45;&#45;position">Housewife</h5>
                              <div class="review__item&#45;&#45;info">On the other hand, we denounce with righteous indignation
                                and dislike men who are so beguiled and demoralized by the charms of pleasure of the
                                moment, so ...
                              </div>
                              <div class="review__item&#45;&#45;more"><a class="more_link" href="#">Показать целиком</a></div>
                            </div>
                          </div>
                          <button class="button btn-more ml-5 mt-4">Показать все отзывы</button>
                        </div>
                        <div class="col-5 d-none d-md-none d-lg-block">
                          <img class="img-responsive contact__img" src="~assets/images/general/tab-reviews.png" alt="">
                        </div>
                      </div>-->
                    </b-tab>
                    <b-tab title="Преподаватели" v-if="authors.length">
                      <div class="row mob_container item__wrap d-flex tab__wrap--scroll">

                        <div class="col-12 col-md-6 col-lg-4 m-width-80" v-for="item in authors">
                          <div class="teacher__item d-flex flex-column justify-content-center align-items-center">
                            <div class="teacher__item--photo">
                              <img class="rounded-circle" :src="item.photo" alt="" v-if="item.photo">
                            </div>
                            <h2 class="teacher__item--name">{{item.fullname}}</h2>
                            <div class="teacher__item--position">{{item.company}}</div>
                            <div class="teacher__item--text">{{item.description}}</div>
                          </div>
                        </div>
                      </div>
                    </b-tab>
                    <b-tab title="Уроки" v-if="Object.keys(lessons).length">
                      <div class="row palitra">
                        <div
                          class="col-lg-8 col-12 palitra__info d-flex justify-content-center align-items-center flex-column">
                          <div class="palitra__info--title">Палитра прогресса</div>
                          <div class="palitra__info--count">+{{palette_bonus}} {{palette_bonus | noun('крафтик|крафтика|крафтиков')}}</div>
                          <div class="palitra__info--text">Это ваша персональная палитра прогресса. Смотрите уроки,
                            отправляйте домашние задания и ваша палитра будет заполняться цветами. За полную палитру вы
                            получаете крафтики.
                          </div>
                        </div>
                        <div class="col-lg-4 col-12 col-md-6 palitra__img d-flex align-content-center justify-content-center">
                          <img class="img-responsive" src="~assets/images/palette/palette-1.svg" v-if="record.progress.section == 1"/>
                          <img class="img-responsive" src="~assets/images/palette/palette-3.svg" v-if="record.progress.section == 2"/>
                          <img class="img-responsive" src="~assets/images/palette/palette-6.svg" v-if="record.progress.section == 3"/>
                          <img class="img-responsive" src="~assets/images/palette/palette-7.svg" v-if="record.progress.section == 0"/>
                        </div>
                      </div>

                      <div class="steps__wrap">
                        <div class="row">
                          <div class="col-12 tab__wrap--scroll">
                            <b-tabs ref="mainTabs" v-model="lesson_tab">
                              <b-tab v-for="(items, section) in lessons"
                                     v-if="section > 0"
                                     :key="section"
                                     :title="section + ' этап'"
                                     :disabled="(record.progress.section > 0 && section > record.progress.section)">
                                <div class="step__wrap">
                                  <div class="row lessons__list align-items-start">
                                    <div v-for="(item, rank) in items" class="col-lg-4 col-12 col-md-6 lesson__item d-flex justify-content-lg-center align-content-center flex-lg-column">
                                      <div class="lesson__item--video">
                                        <div class="disabled" v-if="record.progress.section > 0 && (record.progress.section < item.section || (record.progress.section == item.section && record.progress.rank < rank))">
                                          <img class="img-responsive bonus__lesson--thumb" :src="item.preview['295x166']"/>
                                        </div>
                                        <nuxt-link :to="{name: 'courses-cid-index-lesson-lid', params: {cid: record.id, lid: item.id}}" v-else class="video">
                                          <img class="img-responsive lesson__video--thumb" :src="item.preview['295x166']" alt="" v-if="item.preview['295x166']">
                                        </nuxt-link>
                                      </div>
                                      <div class="lesson__item--info d-flex align-items-center justify-content-center">
                                        {{item.description}}
                                      </div>
                                    </div>
                                  </div>

                                  <div class="home__work d-flex justify-content-center align-items-center flex-column">
                                    <div class="home__work--img">
                                      <img class="img-responsive" src="~assets/images/general/work_thumb.png" alt="">
                                    </div>
                                    <div class="home__work--title">Домашнее задание этапа {{section}}</div>
                                    <!--<div class="home__work&#45;&#45;count">+150 крафтиков</div>-->
                                    <div class="home__work--text">Отправьте нам фотографию того, что у вас получилось</div>
                                    <client-only>
                                      <upload-homework
                                        :course_id="record.id"
                                        :section="Number(section)"
                                        :image="homeworks[section] ? homeworks[section].file : ''"
                                        :size="500"/>
                                    </client-only>
                                  </div>
                                </div>
                              </b-tab>
                            </b-tabs>
                          </div>
                        </div>
                      </div>

                      <div class="bonus__lesson" v-if="lessons[0] && lessons[0][0]">
                        <div class="bonus__lesson--wrap d-flex justify-content-center align-items-center flex-column">
                          <div class="bonus__lesson--thumb">
                            <span class="ic__star-gold"></span>
                          </div>
                          <div class="bonus__lesson--title">
                            <span :class="{'mr-2': true, 'ic__locked--gray': record.progress.section != 0}"> </span>Бонусный урок
                          </div>
                          <div class="bonus__lesson--text text-center">
                            <p><strong>{{lessons[0][0].title}}</strong></p>
                            {{lessons[0][0].description}}
                          </div>
                          <div class="bonus__lesson--video">
                            <div class="disabled" v-if="record.progress.section != 0">
                              <img class="img-responsive bonus__lesson--thumb" :src="lessons[0][0].preview['295x166']"/>
                            </div>
                            <nuxt-link :to="{name: 'courses-cid-index-lesson-lid', params: {cid: record.id, lid: lessons[0][0].id}}"
                                       v-else class="video">
                              <img class="img-responsive bonus__lesson--thumb" :src="lessons[0][0].preview['295x166']"/>
                            </nuxt-link>
                          </div>
                          <div class="bonus__btn" v-if="record.progress.section != 0">
                            <nuxt-link class="btn btn-default btn__buy" :to="{name: 'courses-cid-index-buy-bonus', cid: record.id}">
                              <span class="ic__star mr-2"></span>Купить за {{bonus_cost | number}} {{bonus_cost | noun('крафтик|крафтика|крафтиков')}}
                            </nuxt-link>
                          </div>
                        </div>
                      </div>

                    </b-tab>
                  </b-tabs>
                </div>
                <div class="col-12 tab__wrap--scroll mt-5">

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
                              <div class="title">Открываете следующий этап</div>
                              <div class="text">После отправки всех трёх домашних заданий, вы получите бонусный урок</div>
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
    import ReviewsList from '../../../components/reviews-list'
    import HeaderBg from '../../../components/header-bg'
    import SwiperGallery from '../../../components/swiper-gallery'
    import {faHeart as faHeartSolid} from '@fortawesome/pro-solid-svg-icons'
    import {faHeart as faHeartLight} from '@fortawesome/pro-light-svg-icons'
    import {faFacebook, faPinterest, faVk, faTwitter} from '@fortawesome/free-brands-svg-icons'
    import {faUser, faThumbsUp, faEye, faShare} from '@fortawesome/pro-duotone-svg-icons'

    import SocialSharing from 'vue-social-sharing'
    import bg from '../../../assets/images/general/headline_course.png';

    export default {
        auth: false,
        data() {
            return {
                loading: false,
                tab: 0,
                lesson_tab: 0,
                style_bg: {'background-image': 'url(' + bg + ')'},
                homeworks: {},
            }
        },
        computed: {
            loggedIn() {
                return this.$auth.loggedIn;
            }
        },
        watch: {
            loggedIn(newValue) {
                if (newValue === true) {
                    this.loadLessons()
                } else {
                    this.record.bought = false;
                    this.lessons = {};
                    this.homeworks = {};
                    this.tab = 0;
                }
            }
        },
        components: {CoursesList, ReviewsList, HeaderBg, SwiperGallery, 'social-sharing': SocialSharing},
        scrollToTop: false,
        async asyncData({app, params, error, env}) {
            let data = {
                palette_bonus: env.COINS_PALETTE,
                bonus_cost: env.COINS_BUY_BONUS,
            };
            try {
                const res = await app.$axios.get('web/courses', {params: {id: params.cid}});
                data.record = res.data
            } catch (e) {
                return error({statusCode: 404, message: 'Страница не найдена'})
            }

            const [similar, authors, reviews, lessons, homeworks/*, reviews*/] = await Promise.all([
                app.$axios.get('web/course/similar', {params: {course_id: params.cid, limit: 4}}),
                app.$axios.get('web/course/authors', {params: {course_id: params.cid, limit: 10}}),
                app.$axios.get('web/course/reviews', {params: {course_id: params.cid, limit: 10}}),
                data.record.bought
                    ? app.$axios.get('web/course/lessons', {params: {course_id: params.cid}})
                    : null,
                data.record.bought
                    ? app.$axios.get('user/homeworks', {params: {course_id: params.cid}})
                    : null,
            ]);
            data.similar = similar.data.rows;
            data.authors = authors.data.rows;
            data.reviews = reviews.data.rows;
            data.lessons = {};
            if (lessons) {
                lessons.data.rows.forEach(v => {
                    if (data.lessons[v.section] === undefined) {
                        data.lessons[v.section] = [];
                    }
                    data.lessons[v.section].push(v);
                });
            }
            data.homeworks = {};
            if (homeworks) {
                homeworks.data.rows.forEach(v => {
                    data.homeworks[v.section] = v;
                });
            }

            return data;
        },
        methods: {
            addFavorite(id) {
                this.loading = 'favorite:' + id;
                this.$axios.put('user/favorites', {course_id: id})
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
                this.$axios.delete('user/favorites', {params: {course_id: id}})
                    .then(res => {
                        this.loading = false;
                        this.$auth.setUser(res.data.user);
                    })
                    .catch(() => {
                        this.loading = false;
                    })
            },
            async loadLessons() {
                const res = await this.$axios.get('web/courses', {params: {id: this.record.id}});

                if (res.data.bought) {
                    const [lessons, homeworks] = await Promise.all([
                        this.$axios.get('web/course/lessons', {params: {course_id: res.data.id}}),
                        this.$axios.get('user/homeworks', {params: {course_id: res.data.id}}),
                    ]);
                    if (lessons) {
                        this.lessons = {};
                        lessons.data.rows.forEach(v => {
                            if (this.lessons[v.section] === undefined) {
                                this.lessons[v.section] = [];
                            }
                            this.lessons[v.section].push(v);
                        });
                    }
                    if (homeworks) {
                        homeworks.data.rows.forEach(v => {
                            this.homeworks[v.section] = v;
                        });
                    }
                }

                this.record = res.data
            },
        },
        head() {
            return {
                title: 'Крафти / Курсы / ' + this.record.title,
                meta: [
                    {property: 'og:description', content: this.record.description},
                    {property: 'og:image', content: this.record.cover},
                ],
            }
        },
        mounted() {
            // Scroll to top fix
            window.scrollTo(0,0);
        },
        created() {
            this.$app.header_image.set(true);
            this.$fa.add(faHeartSolid, faHeartLight, faShare);
            this.$fa.add(faFacebook, faPinterest, faVk, faTwitter);
            this.$fa.add(faUser, faThumbsUp, faEye);

            this.lesson_tab = this.record.progress.section > 0
                ? this.record.progress.section - 1
                : 0;

            this.$root.$on('app::course' + this.record.id + '::likes', res => {
                this.record.likes_sum = res
            });
            this.$root.$on('app::course' + this.record.id + '::progress', res => {
                this.record.progress = res
            });
            this.$root.$on('app::course' + this.record.id + '::homeworks', res => {
                this.homeworks = res
            });
        },
        beforeDestroy: function () {
            this.$root.$off('app::course' + this.record.id + '::likes');
            this.$root.$off('app::course' + this.record.id + '::progress');
            this.$root.$off('app::course' + this.record.id + '::homeworks');
        }
    }
</script>
