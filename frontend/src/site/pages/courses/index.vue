<template>
  <div class="wrapper">
    <header-bg image="courses" />
    <div class="wrapper__content">
      <section class="courses-list">
        <div class="container">
          <div class="row">
            <div class="col-md-8 offset-md-2">
              <h2 class="section__title">Курсы</h2>
              <div class="row mob_container">
                <div class="col-12 tab__wrap--scroll">
                  <b-tabs>
                    <b-tab v-for="(records, category) in courses" :key="category" :title="category">
                      <courses-list :courses="records" />
                    </b-tab>
                  </b-tabs>
                </div>
              </div>
            </div>
          </div>
          <gallery-masonry class="mt-5" />
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import CoursesList from '../../components/courses-list'
import HeaderBg from '../../components/header-bg'
import GalleryMasonry from '../../components/gallery-masonry'

export default {
  auth: false,
  components: {GalleryMasonry, CoursesList, HeaderBg},
  async asyncData({app}) {
    const courses = {}
    const {data: res} = await app.$axios('web/courses', {params: {limit: 0}})
    res.rows.forEach((item) => {
      if (!courses[item.category]) {
        courses[item.category] = []
      }
      courses[item.category].push(item)
    })
    return {courses}
  },
  data() {
    return {
      courses: {},
    }
  },
  scrollToTop: true,
  created() {
    this.$app.header_image.set(true)
  },
  head() {
    return {
      title: 'Крафти / Курсы',
    }
  },
}
</script>
