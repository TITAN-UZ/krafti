<template>
  <div>
    <b-row v-if="diplomas.total">
      <b-col v-for="diploma in diplomas.rows" :key="diploma.id" md="6" class="d-flex align-items-center mt-5">
        <div class="">
          <h2 class="title">{{ diploma.course.title }}</h2>
          <div v-if="diploma.child" :key="diploma.id" class="mb-2">{{ diploma.child.name }}</div>
          <a :href="$image(diploma.file)" target="_blank" rel="noreferrer">
            <fa :icon="['fas', 'download']" /> Скачать диплом
          </a>
        </div>
        <div class="ml-auto">
          <a :href="$image(diploma.file)" target="_blank" rel="noreferrer">
            <img src="~assets/images/general/kraftik.png" alt="" />
          </a>
        </div>
      </b-col>
    </b-row>
    <b-alert v-else variant="info" :show="true" class="w-100 m-auto" style="max-width: 85%;">
      Вы еще не получали дипломы
    </b-alert>
  </div>
</template>

<script>
export default {
  async asyncData({app}) {
    const {data: diplomas} = await app.$axios.get('user/diplomas', {params: {limit: 0}})
    return {diplomas}
  },
  data() {
    return {
      diplomas: {},
    }
  },
  head() {
    return {
      title: 'Крафти / Личный кабинет / Дипломы',
    }
  },
}
</script>

<style scoped lang="scss">
div::v-deep {
  .row {
    margin-top: -3rem;
  }
  .title {
    font-size: 24px;
    line-height: 29px;
  }
  a {
    color: #c3c3c3;
    &:hover {
      color: #999;
    }
  }
}
</style>
