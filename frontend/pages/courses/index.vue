<template>
  <div class="wrapper">
    <div class="wrapper__bg" :style="style_bg"></div>
    <div class="wrapper__content">
      <section class="courses-list">
        <div class="container">
          <div class="row">
            <div class="col-md-8 offset-md-2">
              <div class="row">
                <div class="col-12">
                  <h2 class="section__title">Курсы</h2>
                </div>
              </div>
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
    import bg from '../../assets/images/general/headline_favorites.jpg'

    export default {
        auth: false,
        data() {
            return {
                style_bg: {'background-image': 'url(' + bg + ')'},
            }
        },
        components: {
            'courses-list': CoursesList,
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
        mounted() {
            document.getElementsByTagName('header')[0].classList.add('header_img')
        }
    }
</script>
