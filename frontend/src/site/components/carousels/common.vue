<template>
  <b-carousel ref="carousel" :interval="interval" :indicators="items.length > 1" :style="`height: ${height}px`">
    <b-carousel-slide v-for="item in items" :key="item.id" :caption="item.title" :text="item.description">
      <template v-slot:img>
        <div
          :class="{slide: true, pointer: item.type === 'image' && item.link}"
          :style="`background-image: url(${$image(item.image, '0x1200', 'resize')})`"
          @click="onClick(item)"
        >
          <template v-if="item.type === 'video'">
            <vimeo :ref="`slide-${item.id}`" :video="item.video.remote_key" @onHidden="onHidden" />
            <div class="play-button main" @click="playVideo(item.id)">
              <fa :icon="['fad', 'circle']" />
              <fa :icon="['fas', 'play']" />
            </div>
          </template>
        </div>
      </template>
    </b-carousel-slide>
  </b-carousel>
</template>

<script>
export default {
  name: 'Carousel',
  props: {
    items: {
      type: Array,
      required: true,
    },
    timeout: {
      type: [Number, String],
      default: 5,
    },
    height: {
      type: [Number, String],
      default: 600,
    },
  },
  data() {
    return {
      pause: false,
    }
  },
  computed: {
    interval() {
      return this.pause ? 0 : this.timeout * 1000
    },
  },
  methods: {
    playVideo(id) {
      this.pause = true
      this.$refs[`slide-${id}`].shift().show()
    },
    onClick(item) {
      if (item.type === 'image' && item.link) {
        if (/:\/\//.test(item.link)) {
          window.open(item.link)
        } else {
          this.$router.push(item.link)
        }
      }
    },
    onHidden() {
      this.pause = false
    },
  },
}
</script>

<style scoped lang="scss">
div::v-deep {
  background-color: #efefef;
  .carousel-item {
    .slide {
      height: 600px;
      background-repeat: no-repeat;
      background-position: center;
      background-size: cover;
      .icon {
        position: absolute;
        display: block;
        font-size: 20px;
        width: 100px;
        height: 100px;
        top: calc(50% - 50px);
        left: calc(50% - 50px);
        color: $primary;
        opacity: 1;
        cursor: pointer;
        &:hover {
          opacity: 0.8;
        }
      }
      &.pointer {
        cursor: pointer;
      }
    }
    //.carousel-caption {}
  }
  .carousel-indicators {
    li {
      border-radius: 50%;
      width: 10px;
      height: 10px;
    }
  }
}
</style>
