<template>
  <div class="wrapper course">
    <header-bg image="course" />
    <div class="wrapper__content">
      <section class="course__content">
        <div class="container">
          <div class="row course__content--header">
            <div class="col-lg-7 col-12">
              <course-video :record="record" />
            </div>
            <div class="col-lg-5 col-12">
              <div class="d-flex flex-column h-100 justify-content-between">
                <div class="d-flex justify-content-between align-items-center justify-content-lg-end mb-2">
                  <template v-if="$auth.loggedIn">
                    <b-button variant="default" class="text-primary">
                      <b-spinner v-if="loading == `favorite:${record.id}`" type="grow" />
                      <template v-else-if="$auth.user.favorites.includes(record.id)">
                        <fa :icon="['fas', 'heart']" size="2x" @click="deleteFavorite(record.id)" />
                      </template>
                      <template v-else>
                        <fa :icon="['fal', 'heart']" size="2x" @click="addFavorite(record.id)" />
                      </template>
                    </b-button>
                  </template>

                  <course-share class="ml-md-2 ml-auto" />
                </div>

                <div class="course__info--pretop d-flex justify-content-center align-items-center">
                  <span class="count__lessons">{{ record.videos_count }} видео</span>
                </div>
                <div class="course__info--body d-flex align-items-center justify-content-center flex-column">
                  <div class="course__title">{{ record.title }}</div>
                  <div class="course__tagline">{{ record.tagline }}</div>
                </div>
                <div class="course__info--footer">
                  <div class="course__dopinfo d-flex align-items-center justify-content-between mb-2">
                    <b-col class="text-nowrap text-primary text-center">
                      <fa :icon="['fad', 'eye']" />
                      <span class="text">{{ record.views_count | number }}</span>
                    </b-col>
                    <b-col class="text-nowrap text-primary text-center">
                      <fa :icon="['fad', 'user']" />
                      <span class="text">{{ record.age }} лет</span>
                    </b-col>
                    <b-col class="text-nowrap text-primary text-center">
                      <fa :icon="['fad', 'thumbs-up']" />
                      <span class="text">{{ likes | number }}</span>
                    </b-col>
                  </div>

                  <course-button :record="record" :lessons="lessons" />
                </div>
              </div>
            </div>
          </div>
          <div class="row course__content--tabs">
            <div class="col-12">
              <div class="row mob_container">
                <div class="col-12 tab__wrap--scroll">
                  <b-tabs v-model="mainTab">
                    <b-tab title="Описание" lazy>
                      <div class="text">
                        <div class="mb-3 markdown" v-html="$md.render(record.description)"></div>
                        <gallery-lightbox :object-id="record.id" object-name="Course" />
                      </div>
                    </b-tab>

                    <b-tab v-if="reviews.length" title="Отзывы" lazy>
                      <course-reviews :reviews="reviews" row-class="row reviews__wrap" />
                    </b-tab>

                    <b-tab v-if="authors.length" title="Преподаватели" lazy>
                      <authors-list :authors="authors" />
                    </b-tab>

                    <b-tab v-if="lessons.length" title="Уроки" lazy active>
                      <course-palette v-if="record.template.course_palette" :record="record" />
                      <div class="steps__wrap">
                        <div class="tab__wrap--scroll">
                          <ul v-show="record.template.course_steps" class="nav nav-tabs">
                            <li v-for="sec in sections" :key="sec" class="nav-item">
                              <span :class="lessonTabClass(sec)" @click="lessonTab = sec">Этап {{ sec }}</span>
                            </li>
                          </ul>
                          <div class="tab-content">
                            <course-lessons
                              :record="record"
                              :lessons="currentLessons"
                              :min-thumb-width="295"
                              class="step__wrap"
                            >
                              <template v-if="record.template.course_homeworks" slot="homework">
                                <div class="home__work d-flex justify-content-center align-items-center flex-column">
                                  <div class="home__work--img">
                                    <b-img-lazy src="~assets/images/general/work_thumb.png" alt="" height="88" />
                                  </div>
                                  <div class="home__work--title">Домашнее задание этапа {{ lessonTab }}</div>
                                  <div class="home__work--text">
                                    Отправьте нам фотографию того, что у вас получилось
                                  </div>
                                  <client-only>
                                    <upload-homework
                                      :course-id="record.id"
                                      :section="lessonTab"
                                      :image="homeworkImage"
                                      :size="500"
                                    />
                                  </client-only>
                                </div>
                              </template>
                            </course-lessons>
                          </div>
                        </div>
                      </div>

                      <course-bonus v-if="record.template.course_bonus" :record="record" :lessons="lessons" />
                    </b-tab>
                  </b-tabs>
                </div>

                <div class="col-12 tab__wrap--scroll mt-5">
                  <template v-if="record.template.course_steps">
                    <div class="course__content--process">
                      <h2 class="section__title">Процесс</h2>
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
                                <div class="text">
                                  После отправки всех трёх домашних заданий вы получите бонусный урок
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </template>

                  <section v-if="similar.length" class="courses-list tab__wrap--scroll">
                    <h2 class="section__title">Похожие курсы</h2>
                    <courses-list :courses="similar" />
                  </section>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <nuxt-child />
  </div>
</template>

<script>
import {faHeart as faHeartSolid, faPlay} from '@fortawesome/pro-solid-svg-icons'
import {faHeart as faHeartLight} from '@fortawesome/pro-light-svg-icons'
import {faCircle, faEye, faShare, faThumbsUp, faUser} from '@fortawesome/pro-duotone-svg-icons'
import HeaderBg from '../../../components/header-bg'
import CoursesList from '../../../components/courses-list'
import CourseReviews from '../../../components/course/reviews'
import GalleryLightbox from '../../../components/gallery-lightbox'
import AuthorsList from '../../../components/authors-list'

import bg from '../../../assets/images/general/headline_course.png'
import CoursePalette from '../../../components/course/palette'
import CourseVideo from '../../../components/course/video'
import CourseLessons from '../../../components/course/lessons'
import CourseShare from '../../../components/course/share'
import CourseButton from '../../../components/course/button'
import CourseBonus from '../../../components/course/bonus'

export default {
  auth: false,
  components: {
    HeaderBg,
    CourseBonus,
    CourseButton,
    CourseShare,
    CourseLessons,
    CourseVideo,
    CoursePalette,
    AuthorsList,
    CoursesList,
    CourseReviews,
    GalleryLightbox,
  },
  async asyncData({app, params, error, store}) {
    try {
      const [{data: record}, {data: similar}] = await Promise.all([
        app.$axios.get('web/courses', {params: {id: params.cid}}),
        app.$axios.get('web/course/similar', {params: {course_id: params.cid, limit: 4}}),
      ])
      store.commit('courses/progress', {id: params.cid, data: record.progress})
      store.commit('courses/likes', {id: params.cid, data: record.likes_sum})

      if (app.$auth.loggedIn) {
        const {data: homeworks} = await app.$axios.get('user/homeworks', {params: {course_id: params.cid}})
        store.commit('courses/homeworks', {id: params.cid, data: homeworks.rows})
      }

      return {
        record,
        similar: similar.rows,
        lessonTab: record.progress.section || 1,
      }
    } catch (e) {
      return error({statusCode: e.status, message: e.data})
    }
  },
  data() {
    return {
      loading: false,
      mainTab: 0,
      lessonTab: 1,
      style_bg: {'background-image': 'url(' + bg + ')'},
      bonus_cost: process.env.COINS_BUY_BONUS,
      record: {},
      similar: [],
      authors: [],
      reviews: [],
      lessons: [],
      sections: [],
      loadedTabs: [],
    }
  },
  computed: {
    progress() {
      return this.$store.getters['courses/progress'](this.record.id)
    },
    homeworks() {
      return this.$store.getters['courses/homeworks'](this.record.id)
    },
    likes() {
      return this.$store.getters['courses/likes'](this.record.id)
    },
    homeworkImage() {
      const homeworks = this.$store.getters['courses/homeworks'](this.record.id)
      const filtered = homeworks.filter((item) => item.section === this.lessonTab)
      return filtered.length ? filtered[0].file : {}
    },
    currentLessons() {
      return this.lessons.filter((v) => v.section === this.lessonTab)
    },
  },
  watch: {
    async '$auth.loggedIn'(newValue) {
      await this.loadCourse()
      if (newValue) {
        await this.loadLessons()
      } else {
        this.mainTab = 0
        this.lessons = []
        this.sections = []
      }
    },
  },
  scrollToTop: false,
  mounted() {
    // Scroll to top fix
    window.scrollTo(0, 0)
  },
  created() {
    this.$app.header_image.set(true)

    this.$fa.add(faHeartSolid, faHeartLight, faShare, faCircle, faPlay)
    this.$fa.add(faUser, faThumbsUp, faEye)

    if (this.record.bought) {
      this.loadLessons()
    }
    this.loadReviews()
    this.loadAuthors()
  },
  methods: {
    lessonTabClass(section) {
      const cls = {'nav-link': true}
      if (this.lessonTab === section) {
        cls.active = true
      } else if (this.progress.section && this.progress.section < section) {
        cls.disabled = true
      }
      return cls
    },
    async addFavorite(id) {
      this.loading = 'favorite:' + id
      try {
        const {data: res} = await this.$axios.put('user/favorites', {course_id: id})
        this.$auth.setUser(res.user)
      } catch (e) {
      } finally {
        this.loading = false
      }
    },
    async deleteFavorite(id) {
      this.loading = 'favorite:' + id
      try {
        const {data: res} = await this.$axios.delete('user/favorites', {params: {course_id: id}})
        this.$auth.setUser(res.user)
      } catch (e) {
      } finally {
        this.loading = false
      }
    },
    async loadCourse() {
      const {data: record} = await this.$axios.get('web/courses', {params: {id: this.record.id}})
      this.record = record
      this.$store.commit('courses/progress', {id: record.id, data: record.progress})
      this.$store.commit('courses/likes', {id: record.id, data: record.likes_sum})
    },
    async loadLessons() {
      const {data: lessons} = await this.$axios.get('web/course/lessons', {params: {course_id: this.record.id}})
      this.lessons = lessons.rows
      this.sections = Array.from(
        new Set(
          lessons.rows.map((item) => {
            return item.section
          }),
        ),
      ).filter((item) => item > 0)
    },
    async loadAuthors() {
      const params = {course_id: this.record.id, limit: 10}
      const {data: res} = await this.$axios.get('web/course/authors', {params})
      this.authors = res.rows
    },
    async loadReviews() {
      const params = {course_id: this.record.id, limit: 100}
      const {data: res} = await this.$axios.get('web/course/reviews', {params})
      this.reviews = res.rows
    },
  },
  head() {
    return {
      title: 'Крафти / Курсы / ' + this.record.title,
      meta: [
        {hid: 'og:title', property: 'og:title', content: this.record.title},
        {hid: 'og:description', property: 'og:description', content: this.record.description},
        {hid: 'og:image', property: 'og:image', content: this.record.cover},
      ],
    }
  },
}
</script>
