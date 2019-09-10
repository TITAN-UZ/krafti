<template>
  <client-only>
    <div :class="cssClass" :style="bg">
      <slot name="content"></slot>
    </div>
  </client-only>
</template>


<script>
    export default {
        name: 'header-bg',
        data() {
            return {
                bg: null
            }
        },
        props: {
            image: {
                type: String,
                required: true,
            },
            cssClass: {
                type: String,
                default: 'wrapper__bg'
            }
        },
        methods: {
            getBg() {
                let image = require('../assets/images/background/' + this.image + '.jpg');
                let url = image.src;
                if (!process.client) {
                    return {'background-image': 'url(' + url + ')'};
                }
                const width = window.innerWidth;
                const height = window.innerHeight;
                if (height > width) {
                    try {
                        image = require('../assets/images/background/' + this.image + '-mob.jpg');
                    } catch (e) {
                    }
                }
                const images = image.images;
                for (let i in images) {
                    if (images.hasOwnProperty(i)) {
                        if (images[i].width > width) {
                            url = images[i].path;
                        } else {
                            break;
                        }
                    }
                }

                return {'background-image': 'url(' + url + ')'};
            }
        },
        created() {
            if (process.browser) {
                const that = this;
                window.onresize = function () {
                    that.bg = that.getBg();
                };
                this.bg = this.getBg();
            }
        },
        mounted() {
            this.$app.header_image.set(true);
        }
    }
</script>
