<template>
  <div>
    <client-only>
      <div v-masonry item-selector=".masonry-item" fit-width="false" class="masonry-container">
        <div v-for="(item, index) in gridImages" :key="index" v-masonry-tile class="masonry-item">
          <b-img :src="item" :rounded="true" @click="image = index" />
        </div>
      </div>

      <vue-gallery :images="fullImages" :index="image" @close="image = null" />

      <div class="text-center">
        <b-btn v-if="(page - 1) * perPage < total" variant="primary" @click="getImages">
          <b-spinner v-if="loading" small /> Еще
        </b-btn>
      </div>
    </client-only>
  </div>
</template>

<script>
export default {
  name: 'GalleryMasonry',
  props: {
    imgWidth: {
      type: [Number, String],
      default: 160,
    },
    perPage: {
      type: [Number, String],
      default: 20,
    },
  },
  data() {
    return {
      loading: false,
      images: [],
      page: 1,
      total: 0,
      image: 0,
    }
  },
  computed: {
    gridImages() {
      return this.images.map((v) => {
        return this.$image(v.file, `${this.imgWidth}x0`, 'fit')
      })
    },
    fullImages() {
      return this.images.map((v) => {
        return this.$image(v.file)
      })
    },
  },
  created() {
    this.getImages()
  },
  methods: {
    async getImages() {
      this.loading = true
      try {
        const params = {page: this.page, limit: this.perPage}
        const {data: res} = await this.$axios.get('web/gallery', {params})
        this.total = res.total
        this.images = this.images.concat(res.rows)
        this.page++
        this.$redrawVueMasonry()
      } catch (e) {
        this.images = []
      } finally {
        this.loading = false
      }
    },
  },
}
</script>

<style scoped lang="scss">
.masonry-container {
  margin: auto;
  .masonry-item {
    margin-left: 10px;
    margin-bottom: 10px;
  }
}
.blueimp-gallery {
  background-color: rgba(#000, 0.8);
}
</style>
