<template>
  <div class="wrapper">
    <div class="wrapper__content">
      <section class="teacher__content">
        <div class="container">
          <div class="row">
            <div class="col-md-8 offset-md-2">
              <div class="row">
                <div class="col-12">
                  <div class="teacher__detail--wrap">
                    <div class="teacher__info">
                      <div class="teacher__info--photo">
                        <img class="teacher-photo rounded-circle" :src="record.photo" alt="">
                        <span class="label__shape"></span>
                      </div>
                      <h2 class="teacher__info--name">{{record.fullname}}</h2>
                      <div class="teacher__info--position">{{record.company}}</div>
                    </div>
                    <div class="teacher__text">
                      {{record.description}}
                    </div>
                    <div class="teacher__gallery">
                      <gallery-lightbox :object-id="record.id" object-name="User"/>
                      <!--
                        <div class="left__block mr-1">
                          <img class="img-responsive" src="~assets/images/content/teacher/img.jpg" alt="">
                        </div>
                        <div class="right__block">
                          <img class="mb-15 img-responsive" src="~assets/images/content/teacher/img-2.jpg" alt="">
                          <img class="img-responsive" src="~assets/images/content/teacher/img-3.jpg" alt="">
                        </div>
                      -->
                    </div>
                    <div class="teacher__text">
                      {{record.long_description}}
                    </div>
                  </div>
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
    import GalleryLightbox from '../../components/gallery-lightbox'

    export default {
        auth: false,
        validate({params}) {
            return /^\d+$/.test(params.id)
        },
        components: {GalleryLightbox},
        async asyncData({app, params, error}) {
            try {
                const [record] = await Promise.all([
                    app.$axios.get('web/authors', {params: params}),
                ]);

                return {
                    record: record.data,
                }
            } catch (e) {
                return error({statusCode: 404, message: 'Страница не найдена'})
            }
        },
        head() {
            return {
                title: 'Крафти / Наша команда / Преподаватель ' + this.$route.params.id,
            }
        },
        created() {
            this.$app.header_image.set(false)
        }
    }
</script>
