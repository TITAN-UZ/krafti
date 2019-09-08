<template>
  <div class="container__940">
    <b-alert variant="info" show v-if="!courses.length">
      Вы еще ничего не купили
    </b-alert>
    <div class="CoursesList courses-list" v-else>
      <courses-list :courses="courses"/>
    </div>
    <!--
      <div class="row viewCourseVideo">
        <div class="swiper-container d-flex align-content-center justify-content-center flex-column">
          <div class="course__slider swiper-wrapper">
            <div class="slider__item swiper-slide"><a class="continue" href="">Продолжить просмотр</a>
              <div class="slider__item&#45;&#45;video">
                <a class="video" href="" aria-label="video" data-toggle="modal" data-target="#videoModal">
                  <img class="video__thumb img-responsive" src="~assets/images/content/video.jpg" alt="">
                </a>
              </div>
              <div class="slider__item&#45;&#45;info">
                <div class="title">Интересное название курса</div>
                <div class="description">On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized</div>
              </div>
            </div>
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
      <div class="row CoursesList">
        <div class="col-6">
          <div class="item">
            <div class="item__content">
              <img class="desktop img-responsive" src="~assets/images/content/courses/courses-item-6.jpg" alt="">
              <img class="mobile img-responsive" src="~assets/images/content/courses/courses-item-mob.png" alt="">
            </div>
            <div class="item__body">
              <div class="tagline">Tagline</div>
              <div class="title">Интересное название курса</div>
            </div>
            <div class="item__bottom d-flex justify-content-between align-items-center">
              <div class="price">1990 р.</div>
              <div class="ic__heart"></div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="item big">
            <div class="item__content">
              <img class="desktop img-responsive" src="~assets/images/content/courses/courses-item-7.jpg" alt="">
              <img class="mobile img-responsive" src="~assets/images/content/courses/courses-item-7-mob.png" alt="">
            </div>
            <div class="item__body">
              <div class="tagline">Подборка</div>
              <div class="title">Самое удивительное предложение</div>
            </div>
            <div class="item__bottom d-flex justify-content-between align-items-center">
              <div class="price">1990 р.</div>
              <div class="ic__heart"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 d-flex justify-content-center">
          <button class="button btn-more">Показать еще +7</button>
        </div>
      </div>
    -->
  </div>
</template>

<script>
    import CoursesList from '../../components/courses-list'

    export default {
        components: {CoursesList},
        async asyncData({app}) {
            const courses = await app.$axios.get('user/courses', {params: {limit: 0}});

            return {
                courses: courses.data.rows,
            }
        },

        head() {
            return {
                title: 'Крафти / Личный кабинет / Мои курсы',
            }
        },
    }
</script>


<style lang="scss">
  .courses-list {
    .course {
      &.small {
        width: 50%;
      }
    }
  }
</style>
