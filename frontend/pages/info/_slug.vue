<template>
  <div class="wrapper">
    <div class="wrapper__content">
      <section class="contacts__content">
        <div class="container markdown" v-html="text"></div>
      </section>
    </div>
  </div>

</template>

<script>
    export default {
        auth: false,
        async asyncData({params, error}) {
            try {
                let text = await require('../../assets/docs/' + params.slug + '.md').default;
                //text = text.replace(/<a href="(.*?)">(.*?)<\/a>/, '<nuxt :to="$1">$2</nuxt>')
                return {
                    text: text
                }
            } catch (e) {
                return error({statusCode: 404, message: 'Страница не найдена'})
            }
        },
    }
</script>

<style lang="scss">
  .markdown {
    h2, h4 {
      text-align: center;
      margin: 20px 0;
    }

    ul, ol {
      margin: -20px 0 20px 30px;
      list-style-type: disc;
    }

    blockquote {
      padding-left: 20px;
    }
  }
</style>
