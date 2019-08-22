<template>
  <client-only>
    <div class="wrapper__bg" :style="bg"></div>
    <!--<div class="wrapper__bg">
      <img :src="require('../assets/images/background/' + image)"
           :srcset="require('../assets/images/background/' + image).srcSet"/>
    </div>-->
  </client-only>
</template>


<script>
    export default {
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
        },
        methods: {
            getBg() {
                const image = require('../assets/images/background/' + this.image + '.jpg');
                let url = image.src;
                if (process.browser) {
                    const width = window.innerWidth;
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
            document.getElementsByTagName('header')[0].classList.add('header_img')
        }
    }
</script>
