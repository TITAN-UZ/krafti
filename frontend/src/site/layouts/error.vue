<template>
  <div class="wrapper">
    <div class="wrapper__content">
      <section class="container__940 tabs">
        <h3 v-if="error.statusCode === 404">
          Страница не найдена
        </h3>
        <h3 v-else-if="error.statusCode === 401">
          Требуется авторизация
        </h3>
        <h3 v-else>Ошибка {{ error.statusCode }}: {{ error.message }}</h3>
      </section>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    error: {
      type: Object,
      default() {
        return {}
      },
    },
  },
  created() {
    console.error(this.error)
  },
}
</script>
