<template>
  <div class="wrapper">
    <header-bg image="courses"/>
    <div class="wrapper__content">
      <section class="courses-list">
        <div class="container">
          <div class="row">
            <div class="col-md-8 offset-md-2">
              <h2 class="section__title">Курсы</h2>
              <div class="row mob_container">
                <div class="col-12 tab__wrap--scroll">
                  <b-tabs>
                    <b-tab v-for="(records, category) in courses" :title="category" :key="category">
                      <courses-list :courses="records"/>
                    </b-tab>
                  </b-tabs>
                </div>
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
    import HeaderBg from '../../components/header-bg'

    export default {
        auth: false,
        data() {
            return {}
        },
        components: {
            'courses-list': CoursesList,
            'header-bg': HeaderBg,
        },
        scrollToTop: false,
        asyncData({app}) {
            let data = {
                courses: {},
            };
            return app.$axios('web/courses', {params: {limit: 0}})
                .then(res => {
                    res.data.rows.forEach(item => {
                        if (!data.courses[item.category]) {
                            data.courses[item.category] = [];
                        }
                        data.courses[item.category].push(item);
                    });

                    return data;
                })
        },
        head() {
            return {
                title: 'Крафти / Курсы',
            }
        },
        created() {
            this.$app.header_image.set(true)
        }
    }
</script>
