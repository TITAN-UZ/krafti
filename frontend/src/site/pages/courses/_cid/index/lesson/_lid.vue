<template>
  <b-modal
    ref="modalVideo"
    :key="record.id"
    hide-footer
    visible
    size="xl"
    dialog-class="modal-video"
    @hidden="onHidden"
    @shown="onShown"
  >
    <div class="wrapper">
      <b-embed
        v-if="record.video && record.video.remote_key"
        ref="video"
        :src="`https://player.vimeo.com/video/${record.video.remote_key}`"
        type="iframe"
        aspect="16by9"
        allowfullscreen
      />
      <div class="wrapper__content pt-3 pt-md-5">
        <div class="lesson__info container__940">
          <div class="container">
            <div class="row">
              <div :class="{'col-12': true, 'col-lg-7': record.extra !== true}">
                <div class="lesson__info--description">
                  <div class="s-title">{{ record.title }}</div>
                </div>
                <div v-html="$md.render(record.description)" />
                <div class="lesson__info--like">
                  <a @click.prevent="onLike('like')">
                    <b-spinner v-if="loading === `like:${record.id}`" small type="grow" class="text-primary" />
                    <fa v-else :icon="['fad', 'thumbs-up']" :class="{'text-primary': like === 1}" />
                    {{ record.likes_count }}
                  </a>
                  <a class="ml-3" @click.prevent="onLike('dislike')">
                    <b-spinner v-if="loading === `dislike:${record.id}`" small type="grow" class="text-primary" />
                    <fa v-else :icon="['fad', 'thumbs-down']" :class="{'text-primary': like === -1}" />
                    {{ record.dislikes_count }}
                  </a>
                </div>
              </div>
              <div v-if="record.extra !== true && !record.title.includes('Домашнее задание')" class="col-lg-5 col-12">
                <div class="lesson__info--note">
                  Вы можете поделиться с нами результатом каждого урока. Нам очень интересно, что у вас получилось!
                  Просто пришлите нам фотографию вашей картины. Лучшие работы мы опубликуем на главной странице сайта!
                </div>

                <upload-homework
                  :lesson-id="record.id"
                  :course-id="record.course.id"
                  :section="0"
                  :image-id="record.homework ? record.homework.file_id : null"
                  :size="300"
                />
              </div>
            </div>
          </div>
        </div>
        <div v-if="record.products.length || record.file" class="what__is__needed container__940 mt-5">
          <div class="container">
            <div class="s-title">Что понадобится</div>
            <div class="needed__list">
              <div v-if="record.products.length" class="needed__list--wrapper d-flex justify-content-between flex-wrap">
                <div
                  v-for="product in record.products"
                  :key="product.id"
                  class="needed__item d-flex justify-content-between align-items-center"
                >
                  <div class="needed__item--title">{{ product }}</div>
                </div>
              </div>
              <div v-if="record.file" class="row">
                <div class="col-12">
                  <div class="download__materials">
                    <a class="button" :href="$file(record.file)" target="_self">Скачать материалы</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <section v-if="record.author" class="teacher__content container__940 mt-5">
          <div class="container">
            <div class="row">
              <div class="col-12">
                <div class="s-title">Преподаватель</div>
              </div>
            </div>

            <authors-list
              :authors="[record.author]"
              :show-desc="false"
              row-class="d-flex justify-content-center"
              item-class="col-9"
            />

            <!--<div class="row">
              <div class="col-12">
                <div class="teacher__detail&#45;&#45;wrap">
                  <div class="teacher__info">
                    <div class="teacher__info&#45;&#45;photo" v-if="record.author.photo">
                      <img class="teacher-photo rounded-circle" :src="record.author.photo" alt="">
                      <span class="label__shape"></span>
                    </div>
                    <h2 class="teacher__info&#45;&#45;name">{{record.author.fullname}}</h2>
                    <div class="teacher__info&#45;&#45;position">{{record.author.company}}</div>
                  </div>
                  <div class="teacher__text">{{record.author.description}}
                  </div>
                </div>
              </div>
            </div>-->
          </div>
        </section>
        <section v-if="record.bonus" class="science__content container__940 mt-5">
          <div class="container">
            <div class="row">
              <div class="col-lg-8 offset-lg-2 col-12">
                <div class="wrapper__science">
                  <div class="science__content--img">
                    <img src="~assets/images/general/bg_science.png" alt="" />
                  </div>
                  <div class="science__content--pretitle">мини-лекция</div>
                  <h2 class="science__content--title">
                    {{ record.bonus.title }}
                  </h2>
                  <div class="science__content--text">
                    {{ record.bonus.description }}
                  </div>
                  <div class="science__content--video d-flex justify-content-center">
                    <b-embed
                      v-if="record.bonus && record.bonus.remote_key"
                      :src="`https://player.vimeo.com/video/${record.bonus.remote_key}`"
                      type="iframe"
                      aspect="16by9"
                      allowfullscreen
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <section class="lesson__content--bottom container__940 mt-5">
          <div class="container">
            <div class="row">
              <div :class="{'col-12': true, 'col-lg-7': nextLessons.length > 0}">
                <client-only>
                  <comments-list :course-id="record.course.id" :lesson-id="record.id" />
                </client-only>
              </div>

              <div v-if="nextLessons.length" class="col-lg-5 col-12">
                <div class="s-title">Следующие уроки</div>
                <div class="nextlessons__content">
                  <course-lessons :record="record.course" :lessons="nextLessons" wrapper-class="media mb-2">
                    <template v-slot="{item, open, link, thumb}">
                      <div class="media--video mr-2">
                        <nuxt-link v-if="open(item)" :to="link(item)" class="video">
                          <b-img-lazy class="media--thumb img-responsive" :src="thumb(item)" alt="" />
                        </nuxt-link>
                        <div v-else class="disabled">
                          <b-img-lazy class="media--thumb img-responsive" :src="thumb(item)" alt="" />
                        </div>
                      </div>
                      <div class="media-body">
                        <div>
                          <strong>{{ item.title }}</strong>
                        </div>
                        <div>{{ item.description | truncate(50) }}</div>
                      </div>
                    </template>
                  </course-lessons>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>

    <template slot="modal-header">
      <button class="close" type="button" aria-label="Close" @click="hideModal">
        <fa :icon="['fal', 'times']" size="2x" />
      </button>
    </template>
  </b-modal>
</template>

<script>
import {faTimes} from '@fortawesome/pro-light-svg-icons'
import {faThumbsUp, faThumbsDown} from '@fortawesome/pro-duotone-svg-icons'
import Player from '@vimeo/player'
import CommentsList from '../../../../../components/comments/list'
import AuthorsList from '../../../../../components/authors-list'
import CourseLessons from '../../../../../components/course/lessons'

export default {
  auth: true,
  validate({params}) {
    return /^\d+$/.test(params.cid) && /^\d+$/.test(params.lid)
  },
  components: {CourseLessons, AuthorsList, CommentsList},
  async asyncData({app, params, error}) {
    try {
      params = {course_id: params.cid, id: params.lid}
      const {data: record} = await app.$axios.get('web/course/lessons', {params})
      return {record}
    } catch (e) {
      return error(e)
    }
  },
  data() {
    return {
      loading: false,
      url: 'web/course/lessons',
      record: {},
      lessons: [],
    }
  },
  scrollToTop: false,
  computed: {
    like() {
      return this.record.like ? this.record.like.value : false
    },
    nextLessons() {
      return this.lessons.filter((item) => item.rank > this.record.rank)
    },
  },
  watch: {
    'record.id'() {
      this.loadLessons()
    },
  },
  created() {
    this.$fa.add(faTimes, faThumbsUp, faThumbsDown)
    this.loadLessons()
  },
  methods: {
    hideModal() {
      this.$refs.modalVideo.hide()
    },
    onHidden() {
      this.$router.push({name: 'courses-cid', params: this.$route.params})
    },
    onShown() {
      const elem = this.$refs.video.firstChild
      if (elem) {
        try {
          const player = new Player(elem)
          player.on('play', () => {
            this.setTimeout(() => {
              this.onProgress()
            }, 3000)
          })
          // player.play()
        } catch (e) {
          console.error(e)
        }
      }
      // Scroll to comment
      if (this.$route.hash) {
        this.setTimeout(() => {
          const elem = document.getElementById(this.$route.hash.replace(/^#/, ''))
          if (elem) {
            elem.scrollIntoView()
          }
        }, 200)
      }
    },
    async onLike(action) {
      this.loading = action + ':' + this.record.id
      try {
        this.record.like = action === 'like' ? 1 : -1
        const {data: res} = await this.$axios.post('user/like', {lesson_id: this.record.id, action})
        this.record.likes_count = res.likes_count
        this.record.dislikes_count = res.dislikes_count
        this.$store.commit('courses/likes', {id: this.record.course.id, data: res.likes_sum})
      } catch (e) {
      } finally {
        this.loading = false
      }
    },
    async onProgress() {
      try {
        const {data: res} = await this.$axios.post('user/progress', {lesson_id: this.record.id})
        this.record.course.progress = res
        this.$store.commit('courses/progress', {id: this.record.course.id, data: res})
        // await this.loadLessons()
      } catch (e) {
        console.error(e)
        await this.onProgress()
      }
    },
    async loadLessons() {
      const params = {course_id: this.record.course.id, section: this.record.section, sort: 'rank', dir: 'asc'}
      const {data: lessons} = await this.$axios.get(this.url, {params})
      this.lessons = lessons.rows
    },
  },
  head() {
    return {
      title: ['Крафти', 'Курсы', this.record.course.title, this.record.title].join(' / '),
    }
  },
}
</script>

<style scoped lang="scss">
div::v-deep {
  iframe {
    border-top-left-radius: 28.3099px;
    border-top-right-radius: 28.3099px;
  }

  .wrapper__science {
    iframe {
      border-radius: 15px;
    }
  }
}
</style>
