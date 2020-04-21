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
                        <img class="teacher-photo rounded-circle" :src="record.photo" alt="" />
                        <!--<span class="label__shape"></span>-->
                      </div>
                      <h2 class="teacher__info--name">{{ record.fullname }}</h2>
                      <div class="teacher__info--position">{{ record.company }}</div>
                    </div>
                    <div v-if="record.description" class="teacher__text" v-html="$md.render(record.description)" />
                    <div class="teacher__gallery">
                      <gallery-lightbox :object-id="record.id" object-name="User" :per-page="perPage" />
                    </div>
                    <div
                      v-if="record.long_description"
                      class="teacher__text"
                      v-html="$md.render(record.long_description)"
                    ></div>
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
      const {data: record} = await app.$axios.get('web/authors', {params})
      return {record}
    } catch (e) {
      return error({statusCode: e.status, message: e.data})
    }
  },
  data() {
    return {
      record: {},
      perPage: [
        [100, 1],
        [376, 2],
      ],
    }
  },
  mounted() {
    this.$app.header_image.set(false)
  },
  head() {
    return {
      title: 'Крафти / Наша команда / ' + this.record.fullname,
      meta: [
        {hid: 'og:title', property: 'og:title', content: this.record.fullname},
        {hid: 'og:description', property: 'og:description', content: this.record.description},
        {hid: 'og:image', property: 'og:image', content: this.record.photo},
      ],
    }
  },
}
</script>
