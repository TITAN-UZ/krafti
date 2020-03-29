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
      <div class="embed-responsive embed-responsive-16by9">
        <iframe
          v-if="record.video && record.video.vimeo"
          id="lesson-vimeo-iframe"
          class="embed-responsive-item"
          :src="'https://player.vimeo.com/video/' + record.video.vimeo"
          allowfullscreen
          allow="autoplay; fullscreen"
        ></iframe>
      </div>
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
                  <a class="mr-3" @click.prevent="onLike('like')">
                    <b-spinner v-if="loading == 'like:' + record.id" small type="grow" class="text-primary" />
                    <fa v-else :icon="['fad', 'thumbs-up']" :class="{'text-primary': record.like == 1}" />
                    {{ record.likes_count }}
                  </a>
                  <a @click.prevent="onLike('dislike')">
                    <b-spinner v-if="loading == 'dislike:' + record.id" small type="grow" class="text-primary" />
                    <fa v-else :icon="['fad', 'thumbs-down']" :class="{'text-primary': record.like == -1}" />
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
                  :course-id="course.id"
                  :section="0"
                  :image-id="record.homework.file_id"
                  :size="150"
                />
                <!--<div class="lesson__info&#45;&#45;share">
                  <button class="button">Поделиться работой</button>
                </div>-->
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
                  <!--<button class="needed__item--cart"><img src="~assets/images/general/ic_cart.svg" alt=""></button>-->
                </div>
              </div>
              <div v-if="record.file" class="row">
                <div class="col-12">
                  <div class="download__materials">
                    <a class="button" :href="record.file" target="_self">Скачать материалы</a>
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
                    <div class="embed-responsive embed-responsive-16by9">
                      <iframe
                        v-if="record.bonus && record.bonus.vimeo"
                        id="bonus-vimeo-iframe"
                        class="embed-responsive-item"
                        :src="'https://player.vimeo.com/video/' + record.bonus.vimeo"
                        allowfullscreen
                        allow="autoplay; fullscreen"
                      ></iframe>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
        <section class="lesson__content--bottom container__940 mt-5">
          <div class="container">
            <div class="row">
              <div :class="{'col-12': true, 'col-lg-7': record.next.length > 0}">
                <client-only>
                  <comments :course-id="course.id" :lesson-id="record.id" />
                </client-only>
              </div>
              <div v-if="record.next.length" class="col-lg-5 col-12">
                <div class="s-title">Следующие уроки</div>
                <div class="nextlessons__content">
                  <div v-for="item in record.next" :key="item.id" class="media mb-2">
                    <div class="media--video mr-2">
                      <nuxt-link
                        v-if="isLessonOpen(item)"
                        :to="{name: 'courses-cid-index-lesson-lid', params: {cid: course.id, lid: item.id}}"
                        class="video"
                        @click.native="scrollToTop"
                      >
                        <img
                          class="media--thumb img-responsive"
                          :src="item.preview[Object.keys(item.preview).shift()]"
                          alt=""
                        />
                      </nuxt-link>
                      <div v-else class="disabled">
                        <img
                          class="media--thumb img-responsive"
                          :src="item.preview[Object.keys(item.preview).shift()]"
                          alt=""
                        />
                      </div>
                    </div>
                    <div class="media-body">
                      <div>
                        <strong>{{ item.title }}</strong>
                      </div>
                      <div>{{ item.description }}</div>
                    </div>
                  </div>
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
import Comments from '../../../../../components/comments'
import AuthorsList from '../../../../../components/authors-list'

export default {
  auth: true,
  validate({params}) {
    return /^\d+$/.test(params.cid) && /^\d+$/.test(params.lid)
  },
  components: {AuthorsList, Comments},
  async asyncData({app, params, error}) {
    try {
      const [record, course] = await Promise.all([
        app.$axios.get('web/course/lessons', {params: {course_id: params.cid, id: params.lid}}),
        app.$axios.get('web/courses', {params: {id: params.cid}}),
      ])
      return {
        record: record.data,
        course: course.data,
      }
    } catch (e) {
      return error({statusCode: e.status, message: e.data})
    }
  },
  data() {
    return {
      loading: false,
    }
  },
  scrollToTop: false,
  created() {
    this.$fa.add(faTimes, faThumbsUp, faThumbsDown)
    this.$root.$emit('app::course' + this.$route.params.cid + '::progress', this.record.progress)

    this.$root.$on('app::lesson' + this.record.id + '::homework', (res) => {
      this.record.homework = res
    })
  },
  beforeDestroy() {
    this.$root.$off('app::lesson' + this.record.id + '::homework')
  },
  methods: {
    hideModal() {
      this.$refs.modalVideo.hide()
    },
    onHidden() {
      this.$router.push({name: 'courses-cid', params: this.$route.params})
    },
    onShown() {
      const elem = document.getElementById('lesson-vimeo-iframe')
      if (elem) {
        try {
          const player = new Player(elem)
          player.on('play', () => {
            this.setTimeout(() => {
              this.$axios.post('user/progress', {lesson_id: this.record.id}).then((res) => {
                this.course.progress = res.data
                this.$root.$emit('app::course' + this.course.id + '::progress', res.data)
              })
            }, 3000)
          })
          // player.play()
        } catch (e) {
          console.error(e)
        }
      }
      if (this.$route.hash) {
        this.setTimeout(() => {
          const elem = document.getElementById(this.$route.hash.replace(/^#/, ''))
          if (elem) {
            elem.scrollIntoView()
          }
        }, 200)
      }
    },
    onLike(action = 'like') {
      this.loading = action + ':' + this.record.id
      this.record.like = action === 'like' ? 1 : -1
      this.$axios
        .post('user/like', {lesson_id: this.record.id, action})
        .then((res) => {
          this.record.likes_count = res.data.likes_count
          this.record.dislikes_count = res.data.dislikes_count
          this.$root.$emit('app::course' + this.$route.params.cid + '::likes', res.data.likes_sum)
        })
        .catch(() => {})
        .finally(() => {
          this.loading = false
        })
    },
    isLessonOpen(item) {
      return this.$parent.isLessonOpen(item)
    },
    scrollToTop() {
      /* const el = document.getElementsByClassName('modal')[0];
                const scrollToTop = () => {
                    const c = el.scrollTop;
                    if (c > 0) {
                        window.requestAnimationFrame(scrollToTop);
                        el.scrollTo(0, c - c / 2);
                    }
                };
                scrollToTop(); */
    },
  },
  head() {
    return {
      title: 'Крафти / Курсы / ' + this.course.title + ' / ' + this.record.title,
    }
  },
}
</script>

<style lang="scss">
#lesson-vimeo-iframe {
  border-top-left-radius: 28.3099px;
  border-top-right-radius: 28.3099px;
}

#bonus-vimeo-iframe {
  border-radius: 15px;
}
</style>
