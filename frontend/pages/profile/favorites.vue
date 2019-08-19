<template>
  <div class="wrapper">
    <div class="wrapper__bg js-bg-selection" :style="style_bg"></div>
    <div class="wrapper__content">
      <section class="favorite">
        <div class="container">
          <div class="row">
            <div class="col-md-8 offset-md-2">
              <h2 class="section__title">Избранное</h2>
              <div class="courses-list">
                <courses-list :courses="courses"/>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
    import CoursesList from '../../components/courses-list'
    import bg from '../../assets/images/general/headline_favorites.jpg'

    export default {
        auth: true,
        data() {
            return {
                style_bg: {'background-image': 'url(' + bg + ')'},
            }
        },
        components: {
            'courses-list': CoursesList,
        },
        asyncData({app}) {
            return app.$axios.get('user/favorite', {params: {limit: 0}})
                .then(res => {
                    return {courses: res.data.rows}
                })
        },
        head() {
            return {
                title: 'Крафти / Личный кабинет / Избранное',
            }
        },
        mounted() {
            document.getElementsByTagName('header')[0].classList.add('header_img');
        }
    }
</script>
