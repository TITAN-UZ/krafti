<template>
  <div class="row diplomsList flex-wrap">
    <template v-if="diplomas.length > 0">
      <div v-for="diploma in diplomas" :key="diploma.id" class="col-lg-6 col-12">
        <div class="diploam__item d-flex align-content-center justify-content-between">
          <div class="left__block d-flex justify-content-start align-items-end">
            <div class="diploam__info">
              <h2 class="diploam__info--title">{{ diploma.course.title }}</h2>
              <div v-if="diploma.child" :key="diploma.id" class="mb-2">{{ diploma.child.name }}</div>
              <a class="diploam__info--link" :href="diploma.file" target="_blank" rel="noreferrer">Скачать диплом</a>
            </div>
          </div>
          <div class="right-block">
            <div class="diploam__item--img">
              <img src="~assets/images/general/kraftik.png" alt="" />
            </div>
          </div>
        </div>
      </div>
    </template>
    <b-alert v-else variant="info" :show="true" class="w-100 m-auto" style="max-width: 85%;">
      Вы еще не получали дипломы
    </b-alert>
  </div>
</template>

<script>
export default {
  async asyncData({app}) {
    const diplomas = await app.$axios.get('user/diplomas', {params: {limit: 0}})

    return {
      diplomas: diplomas.data.rows,
    }
  },
  head() {
    return {
      title: 'Крафти / Личный кабинет / Дипломы',
    }
  },
}
</script>
