<template>
  <div class="gallery-lightbox">
    <div class="gallery-lightbox-items">
      <div class="swiper-container">
        <div class="swiper-wrapper">
          <a v-for="item in items" :key="item.id" :href="[$settings.image_url, item.id, '0x1000'].join('/')" class="swiper-slide">
            <img :src="[$settings.image_url, item.id, '200x0'].join('/')" />
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Swiper from 'swiper'

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
  },
  data() {
    return {
      items: [],
      options: {
        history: false,
        shareEl: false,
        zoomEl: false,
        bgOpacity: 0.7,
      },
      swiper: null,
      gallery: null,
    }
  },
  mounted() {
    this.loadFiles()
  },
  methods: {
    loadFiles() {
      this.$axios.get('web/gallery', {params: {object_id: this.objectId, object_name: this.objectName}}).then((res) => {
        this.items = res.data.rows
        this.setTimeout(() => {
          this.initGallery('.swiper-wrapper')
          this.initSwiper('.swiper-container')
        }, 200)
      })
    },
    initSwiper(selector) {
      this.swiper = new Swiper(selector, {
        spaceBetween: 10,
        loop: true,
        loopedSlides: this.items.length,
        preventClicks: true,
        simulateTouch: false,
        slidesPerView: 'auto',
        autoplay: {
          delay: 3000,
          disableOnInteraction: false,
        },
      })
    },
    initGallery(selector) {
      require(['lightgallery.js'], () => {
        const el = document.querySelector(selector)
        window.lightGallery(el, {
          selector: 'a',
          download: false,
          onAfterOpen: this.onOpen,
          onCloseAfter: this.onClose,
        })
        this.gallery = window.lgData[el.getAttribute('lg-uid')]
        if (this.swiper) {
          el.addEventListener('onBeforeOpen', () => {
            this.swiper.autoplay.stop()
          })
          el.addEventListener('onCloseAfter', () => {
            this.swiper.autoplay.start()
          })
        }
      })
    },
  },
}
</script>

<style lang="scss">
.gallery-lightbox {
  .swiper-wrapper {
    align-items: center;
  }

  .swiper-slide {
    width: 200px;

    img {
      width: 200px;
      border-radius: 10px;
      padding: 5px;
    }
  }
}
</style>
