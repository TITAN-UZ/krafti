<template>
  <div v-if="items.length" class="gallery-swiper container">
    <div class="swiper-container gallery-top">
      <div class="swiper-wrapper">
        <div v-for="item in items" :key="item.id" class="swiper-slide" :style="'background-image:url(' + item.file + ')'"></div>
        <!--<div class="swiper-slide" v-for="item in items" :key="item.id">
          <img :src="item.file" class="main-image" :style="{'max-height': 'calc(' + height + 'px - 20% - 25px'}"/>
        </div>-->
      </div>
      <button class="btn btn-secondary swiper-button-next">
        <fa :icon="['fal', 'chevron-right']" size="3x" />
      </button>
      <button class="btn btn-secondary swiper-button-prev">
        <fa :icon="['fal', 'chevron-left']" size="3x" />
      </button>
    </div>

    <div class="swiper-container gallery-thumbs">
      <div class="swiper-wrapper">
        <div v-for="item in items" :key="item.id" class="swiper-slide" :style="'background-image:url(' + item.file + ')'"></div>
      </div>
    </div>
  </div>
</template>

<script>
import Swiper from 'swiper'
import {faChevronLeft, faChevronRight} from '@fortawesome/pro-light-svg-icons'

export default {
  name: 'GallerySwiper',
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
    time: {
      type: Number,
      required: false,
      default: 5,
    },
    thumbs: {
      type: String,
      required: false,
      default: '4',
    },
  },
  data() {
    return {
      items: [],
      swiper: null,
    }
  },
  created() {
    this.$fa.add(faChevronLeft, faChevronRight)
  },
  mounted() {
    this.loadFiles()
  },
  methods: {
    loadFiles() {
      this.$axios.get('web/gallery', {params: {object_id: this.objectId, object_name: this.objectName}}).then((res) => {
        this.items = res.data.rows
        if (res.data.total > 0) {
          this.setTimeout(() => {
            this.initGallery()
          }, 200)
        }
      })
    },
    initGallery() {
      this.swiper = new Swiper('.gallery-top', {
        spaceBetween: 10,
        loop: true,
        loopedSlides: this.items.length, // looped slides should be the same
        // centeredSlides: true,
        preventClicks: true,
        simulateTouch: false,
        slidesPerView: 1,
        autoplay: {
          delay: this.time * 1000,
          disableOnInteraction: false,
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev',
        },
        breakpoints: {},
        thumbs: {
          swiper: {
            el: '.gallery-thumbs',
            preventClicks: true,
            // simulateTouch: false,
            freeMode: true,
            slidesPerView: this.items.length > 8 ? 8 : this.items.length,
            spaceBetween: 20,
            // loop: true,
            // loopedSlides: 5,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            breakpoints: {
              // when window width is <= 320px
              320: {
                slidesPerView: this.items.length > 2 ? 2 : this.items.length,
                spaceBetween: 2,
              },
              // when window width is <= 480px
              480: {
                slidesPerView: this.items.length > 4 ? 4 : this.items.length,
                spaceBetween: 6,
              },
              // when window width is <= 640px
              640: {
                slidesPerView: this.items.length > 6 ? 6 : this.items.length,
                spaceBetween: 10,
              },
            },
          },
        },
      })
    },
  },
}
</script>

<style lang="scss">
.gallery-swiper {
  height: 1024px;
  margin: 0;
  padding: 0;

  .swiper-container {
    width: 100%;
    height: 300px;
    margin-left: auto;
    margin-right: auto;
  }

  .gallery-top {
    height: 80%;
    width: 100%;

    .swiper-slide {
      border-radius: 10px;
      background-size: cover;
      background-repeat: no-repeat;
      background-position: top;
    }
  }

  .gallery-thumbs {
    height: 20%;
    box-sizing: border-box;
    padding: 10px 0;

    .swiper-slide {
      height: 100%;
      opacity: 0.4;
      border-radius: 5px;
      background-size: cover;
      background-position: top;
      //max-width: 100%;

      &-thumb-active {
        opacity: 1;
      }
    }
  }

  /*.swiper-button-next, .swiper-button-prev {
      position: absolute;
      top: 50%;
      //width: 48px;
      //height: 48px;
      margin-top: -32px;
      z-index: 10;
      cursor: pointer;
      text-align: center;
      opacity: .5;

      svg {
        color: white;
      }

      &:hover {
        opacity: 1;
      }
    }*/

  .swiper-button-next {
    right: 1px;
    left: auto;
  }

  .swiper-button-prev {
    left: 1px;
    right: auto;
  }

  .swiper-button-disabled {
    opacity: 0.35;
    cursor: auto;
    pointer-events: none;
  }

  @media (max-width: 1280px) {
    height: 800px;
  }

  @media (max-width: 720px) {
    height: 600px;
  }

  @media (max-width: 480px) {
    height: 400px;
  }
}
</style>
