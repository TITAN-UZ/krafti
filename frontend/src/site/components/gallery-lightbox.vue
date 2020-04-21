<template>
  <div>
    <b-carousel v-model="slide" class="p-2 rounded" v-bind="carousel" :interval="interval">
      <b-carousel-slide v-for="(rows, section) in slides" :key="section">
        <template v-slot:img>
          <div class="d-flex justify-content-around">
            <b-img
              v-for="(item, idx) in rows"
              :key="idx"
              :src="$image(item.file, '200x200', 'fit')"
              :rounded="true"
              style="cursor: pointer"
              @click="onSlideClick(idx, section)"
            />
          </div>
        </template>
      </b-carousel-slide>
    </b-carousel>

    <vue-gallery :images="images" :index="image" @close="image = null" @onslide="onImageSlide" />
  </div>
</template>

<script>
export default {
  name: 'GalleryLightbox',
  props: {
    objectId: {
      type: Number,
      required: false,
      default: 0,
    },
    objectName: {
      type: String,
      required: false,
      default: '',
    },
    perPage: {
      type: Array,
      default() {
        return [
          [200, 1],
          [376, 2],
          [768, 3],
          [992, 4],
        ]
      },
    },
  },
  data() {
    return {
      url: 'web/gallery',
      items: [],
      image: null,
      slide: 0,
      width: 960,
      carousel: {
        background: '#f5f5f5',
        interval: 5000,
      },
    }
  },
  computed: {
    limit() {
      return this.perPage.filter((item) => this.width >= item[0]).pop()[1]
    },
    slides() {
      const items = JSON.parse(JSON.stringify(this.items))
      const images = []
      while (items.length) {
        images.push(items.splice(0, this.limit))
      }
      return images
    },
    images() {
      return this.items.map((item) => {
        return this.$image(item.file, '1000x1000', 'resize')
      })
    },
    interval() {
      return this.image !== null ? 0 : this.carousel.interval
    },
  },
  created() {
    this.loadFiles()
  },
  mounted() {
    this.width = window.innerWidth
    window.onresize = () => {
      this.width = window.innerWidth
    }
  },
  methods: {
    onSlideClick(idx, section) {
      this.image = (idx + 1) * (section + 1) - 1
    },
    onImageSlide(item) {
      this.slide = Math.floor(item.index / this.limit)
    },
    async loadFiles() {
      const params = {object_id: this.objectId, object_name: this.objectName}
      const {data: res} = await this.$axios.get(this.url, {params})
      this.items = res.rows
    },
  },
}
</script>

<style scoped lang="scss">
.blueimp-gallery {
  background-color: rgba(#000, 0.8);
}
</style>
