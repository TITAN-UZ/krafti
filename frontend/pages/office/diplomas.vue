<template>
  <div class="row diplomsList flex-wrap">
    <div class="col-lg-6 col-12"  v-for="diploma in diplomas">
      <div class="diploam__item d-flex align-content-center justify-content-between">
        <div class="left__block d-flex justify-content-start align-items-end">
          <div class="diploam__info">
            <h2 class="diploam__info--title">
              {{diploma.course.title}}
              <span v-if="diploma.child">, {{diploma.child.name}}</span>
            </h2>
            <a class="diploam__info--link" :href="diploma.file" target="_blank">Скачать диплом</a>
          </div>
        </div>
        <div class="right-block">
          <div class="diploam__item--img">
            <img src="~assets/images/general/kraftik.png" alt="">
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        async asyncData({app}) {
            const diplomas = await app.$axios.get('user/diplomas', {params: {limit: 0}});

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
