<template>
  <div>
    <b-modal hide-footer visible size="xl" @hidden="onHidden" ref="modalVideo" dialog-class="modal-video">
      <div class="wrapper">
        <!--<div class="wrapper__bg bg_600 js-bg-selection" :style="style_bg">
          <a class="ic__play&#45;&#45;white" href="" aria-label="video"></a>
        </div>-->
        <div class="embed-responsive embed-responsive-16by9">
          <iframe id="lesson-vimeo-iframe"
                  v-if="record.video && record.video.vimeo"
                  class="embed-responsive-item"
                  :src="'https://player.vimeo.com/video/' + record.video.vimeo"
                  allow="autoplay; fullscreen"></iframe>
        </div>
        <div class="wrapper__content pt-3 pt-md-5">
          <div class="lesson__info container__940">
            <div class="container">
              <div class="row">
                <div class="col-lg-7 col-12">
                  <div class="lesson__info--description">
                    <div class="s-title">{{record.title}}</div>
                    {{record.description}}
                  </div>
                  <div class="lesson__info--like">
                    <a class="mr-3" @click.prevent="onLike('like')">
                      <b-spinner small type="grow" class="text-primary" v-if="loading == ('like:' + record.id)"/>
                      <fa :icon="['fad', 'thumbs-up']" :class="{'text-primary': record.like == 1}" v-else/>
                      {{record.likes_count}}
                    </a>
                    <a @click.prevent="onLike('dislike')">
                      <b-spinner small type="grow" class="text-primary" v-if="loading == ('dislike:' + record.id)"/>
                      <fa :icon="['fad', 'thumbs-down']" :class="{'text-primary': record.like == -1}" v-else/>
                      {{record.dislikes_count}}
                    </a>
                  </div>
                </div>
                <div class="col-lg-5 col-12">
                  <div class="lesson__info--note">По окончании урока, вы можете поделиться с нами своим результатом.
                    Достаточно сделать фото и отправить его нам. Лучшие работы будут опубликованы на главной странице.
                  </div>

                  <upload-homework
                    :lesson_id="record.id"
                    :course_id="course.id"
                    :section="0"
                    :image="record.homework.file"
                    :size="150"/>
                  <!--<div class="lesson__info&#45;&#45;share">
                    <button class="button">Поделиться работой</button>
                  </div>-->
                </div>
              </div>
            </div>
          </div>
          <div class="what__is__needed container__940 mt-5" v-if="record.products.length || record.file">
            <div class="container">
              <div class="s-title">Что <br> понадобится</div>
              <div class="needed__list">
                <div class="needed__list--wrapper d-flex justify-content-between flex-wrap" v-if="record.products.length">
                  <div
                    class="needed__item d-flex justify-content-between align-items-center"
                    v-for="product in record.products">
                    <div class="needed__item--title">{{product}}</div>
                    <!--<button class="needed__item--cart"><img src="~assets/images/general/ic_cart.svg" alt=""></button>-->
                  </div>
                </div>
                <div class="row" v-if="record.file">
                  <div class="col-12">
                    <div class="download__materials">
                      <a class="button" :href="record.file" target="_self">Скачать материалы</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <section class="teacher__content container__940 mt-5" v-if="record.author">
            <div class="container">
              <div class="row">
                <div class="col-12">
                  <div class="s-title">Преподаватели</div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="teacher__detail--wrap">
                    <div class="teacher__info">
                      <div class="teacher__info--photo" v-if="record.author.photo">
                        <img class="teacher-photo rounded-circle" :src="record.author.photo" alt="">
                        <span class="label__shape"></span>
                      </div>
                      <h2 class="teacher__info--name">{{record.author.fullname}}</h2>
                      <div class="teacher__info--position">{{record.author.company}}</div>
                    </div>
                    <div class="teacher__text">{{record.author.description}}
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <section class="science__content container__940 mt-5" v-if="record.bonus">
            <div class="container">
              <div class="row">
                <div class="col-lg-8 offset-lg-2 col-12">
                  <div class="wrapper__science">
                    <div class="science__content--img">
                      <img class="img-responsive" src="~assets/images/general/bg_science.png" alt="">
                    </div>
                    <div class="science__content--pretitle">мини-лекция</div>
                    <h2 class="science__content--title">
                      {{record.bonus.title}}
                    </h2>
                    <div class="science__content--text">
                      {{record.bonus.description}}
                    </div>
                    <div class="science__content--video d-flex justify-content-center">
                      <div class="embed-responsive embed-responsive-16by9">
                        <iframe id="bonus-vimeo-iframe"
                                v-if="record.bonus && record.bonus.vimeo"
                                class="embed-responsive-item"
                                :src="'https://player.vimeo.com/video/' + record.bonus.vimeo"
                                allow="autoplay; fullscreen"></iframe>
                      </div>

                      <!--<a class="video" @click.prevent="$refs.mainVideo.show()">
                        <img class="img-responsive bonus__lesson&#45;&#45;thumb" :src="record.bonus.preview['640x360']" alt="">
                      </a>-->
                    </div>
                    <!--<vimeo :video="record.bonus.vimeo" ref="mainVideo"/>-->
                  </div>
                </div>
              </div>
            </div>
          </section>
          <!--TODO Сделать комментирирование урока-->
          <section class="lesson__content--bottom container__940 mt-5">
            <div class="container">
              <div class="row">
                <div class="col-lg-7 col-12">
                  <div class="s-title">Комментарии</div>
                  <div class="comments__content">
                    <div class="media message__item d-flex align-items-center justify-content-center">
                      <div class="wrap mr-2"><img class="message__item--photo rounded-circle"
                                                  src="~assets/images/content/teacher.png" alt="..."></div>
                      <div class="media-body d-flex align-items-center justify-content-between">
                        <div class="message__item--info">Оставьте комментарий</div>
                        <button class="btn__answer"><img src="~assets/images/general/ic_send.svg" alt=""></button>
                      </div>
                    </div>
                    <div class="media message__item">
                      <div class="wrap mr-2"><img class="message__item--photo rounded-circle"
                                                  src="~assets/images/content/teacher.png" alt="..."></div>
                      <div class="media-body">
                        <div class="media-body-top d-flex align-items-center justify-content-between">
                          <h4 class="message__item--title mt-0">Анна Сотнич</h4><span
                          class="days_ago">1 день назад</span>
                        </div>
                        <div class="media-body-bottom d-flex align-items-center justify-content-between">
                          <div class="message__item--info">On the other hand, we denounce with righteous indignation and
                            dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, and
                            dislike men who are so beguiled and demoralized by the charms of pleasure of the momentso
                            ...
                          </div>
                        </div>
                        <button class="btn__answer">Ответить</button>
                      </div>
                    </div>
                    <div class="media message__item">
                      <div class="wrap mr-2"><img class="message__item--photo rounded-circle"
                                                  src="~assets/images/content/teacher.png" alt="..."></div>
                      <div class="media-body">
                        <div class="media-body-top d-flex align-items-center justify-content-between">
                          <h4 class="message__item--title mt-0">Анна Сотнич </h4><span
                          class="days_ago">1 день назад</span>
                        </div>
                        <div class="media-body-bottom d-flex align-items-center justify-content-between">
                          <div class="message__item--info">On the other hand, we denounce with righteous indignation and
                            dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, and
                            dislike men who are so beguiled and demoralized by the charms of pleasure of the momentso
                            ...
                          </div>
                        </div>
                        <button class="btn__answer">Ответить</button>
                      </div>
                    </div>
                    <div class="media message__item">
                      <div class="wrap mr-2"><img class="message__item--photo rounded-circle"
                                                  src="~assets/images/content/teacher.png" alt="..."></div>
                      <div class="media-body">
                        <div class="media-body-top d-flex align-items-center justify-content-between">
                          <h4 class="message__item--title mt-0">Анна Сотнич</h4><span
                          class="days_ago">1 день назад</span>
                        </div>
                        <div class="media-body-bottom d-flex align-items-center justify-content-between">
                          <div class="message__item--info">On the other hand, we denounce with righteous indignation and
                            dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, and
                            dislike men who are so beguiled and demoralized by the charms of pleasure of the momentso
                            ...
                          </div>
                        </div>
                        <button class="btn__answer">Ответить</button>
                      </div>
                    </div>
                    <button class="button btn-more">Показать еще +7</button>
                  </div>
                </div>

                <div class="col-lg-5 col-12">
                  <div class="s-title" v-if="record.next.length">Следующие уроки</div>
                  <div class="nextlessons__content">
                    <div class="media mb-2" v-for="item in record.next">
                      <div class="media--video mr-2">
                        <a class="video" href="" aria-label="video">
                          <img class="media--thumb img-responsive" :src="item.preview['100x75']" alt="" v-if="item.preview['100x75']">
                        </a>
                      </div>
                      <div class="media-body">
                        <div><strong>{{item.title}}</strong></div>
                        <div>{{item.description}}</div>
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
          <fa :icon="['fal', 'times']" size="2x"/>
        </button>
      </template>
    </b-modal>
  </div>
</template>

<script>
    //import bg from '../../../../../assets/images/general/headline_video.png'
    import {faTimes} from '@fortawesome/pro-light-svg-icons'
    import {faThumbsUp, faThumbsDown} from '@fortawesome/pro-duotone-svg-icons'

    export default {
        auth: true,
        validate({params}) {
            return /^\d+$/.test(params.cid) && /^\d+$/.test(params.lid)
        },
        data() {
            return {
                loading: false,
                //style_bg: {'background-image': 'url(' + bg + ')'},
            }
        },
        scrollToTop: false,
        methods: {
            hideModal() {
                this.$refs.modalVideo.hide();
            },
            onHidden() {
                this.$router.push({name: 'courses-cid', params: this.$route.params})
            },
            onLike(action = 'like') {
                this.loading = action + ':' + this.record.id;
                this.record.like = (action == 'like') ? 1 : -1;
                this.$axios.post('user/like', {lesson_id: this.record.id, action: action})
                    .then(res => {
                        this.record.likes_count = res.data.likes_count;
                        this.record.dislikes_count = res.data.dislikes_count;
                        this.$root.$emit('app::course' + this.$route.params.cid + '::likes', res.data.likes_sum);
                    })
                    .catch(() => {
                    })
                    .finally(() => {
                        this.loading = false
                    })
            },
        },
        async asyncData({app, params, error}) {
            try {
                const [record, course] = await Promise.all([
                    app.$axios.get('web/course/lessons', {params: {course_id: params.cid, id: params.lid}}),
                    app.$axios.get('web/courses', {params: {id: params.cid}})
                ]);
                return {
                    record: record.data,
                    course: course.data,
                }
            } catch (e) {
                return error({statusCode: e.status, message: e.data})
            }
        },
        created() {
            this.$fa.add(faTimes, faThumbsUp, faThumbsDown);
            this.$root.$emit('app::course' + this.$route.params.cid + '::progress', this.record.progress);

            this.$root.$on('app::lesson' + this.record.id + '::homework', res => {
                this.record.homework = res
            })
        },
        beforeDestroy() {
            this.$root.$off('app::lesson' + this.record.id + '::homework')
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
