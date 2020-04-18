<template>
  <div :class="cssClass" :style="bg">
    <slot name="content" />
  </div>
</template>

<script>
export default {
  name: 'HeaderBg',
  props: {
    image: {
      type: String,
      required: true,
    },
    cssClass: {
      type: String,
      default: 'wrapper__bg',
    },
  },
  data() {
    return {
      bg: null,
    }
  },
  mounted() {
    if (process.browser) {
      const that = this
      window.onresize = function() {
        that.bg = that.getBg()
      }
      this.bg = this.getBg()
    }

    this.$app.header_image.set(true)
  },
  methods: {
    getBg() {
      let image = require('../assets/images/background/' + this.image + '.jpg')
      let url = image.src
      if (!process.client) {
        return {'background-image': 'url(' + url + ')'}
      }

      const width = window.innerWidth
      const height = window.innerHeight
      if (width <= 500 && height > width) {
        try {
          image = require('../assets/images/background/' + this.image + '-mob.jpg')
        } catch (e) {}
      }
      const images = image.images
      for (const i in images) {
        if (Object.prototype.hasOwnProperty.call(images, i)) {
          if (images[i].width > width) {
            url = images[i].path
          } else {
            break
          }
        }
      }

      return {'background-image': 'url(' + url + ')'}
    },
  },
}
</script>
