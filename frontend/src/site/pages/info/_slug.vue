<template>
  <div class="wrapper">
    <div class="wrapper__content">
      <section class="contacts__content">
        <div class="container markdown" v-html="text" />
      </section>
    </div>
  </div>
</template>

<script>
export default {
  auth: false,
  async asyncData({params, error}) {
    try {
      const text = await require('../../assets/docs/' + params.slug + '.md').default
      return {text}
    } catch (e) {
      return error({statusCode: e.status, message: e.data})
    }
  },
  /* asyncData({params, error, app}) {
    const text = app.$app.settings(`page_${params.slug}`)
    if (!text) {
      return error({statusCode: 404, message: 'Страница не найдена'})
    }
    return {text: app.$md.render(text)}
  }, */
}
</script>
