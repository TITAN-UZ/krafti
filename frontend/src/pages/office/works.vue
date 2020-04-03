<template>
  <div class="tabContainer">
    <b-alert v-if="!homeworks.length && !lessonworks.length" variant="info" :show="true">
      Вы еще не отправляли свои работы
    </b-alert>
    <div v-else>
      <div v-if="homeworks.length">
        <h2 class="worksList--title">Домашние работы</h2>
        <div class="row item__wrap worksList align-items-center flex-wrap">
          <div v-for="item in homeworks" :key="item.id" class="col-lg-3 col-md-4 col-6 m-width-80">
            <div class="mt-2 text-center text-muted">{{ item.course.title }}, этап {{ item.section }}</div>
            <a :href="item.file" target="_blank" rel="noreferrer" class="work__item">
              <div class="work__item">
                <img class="img-responsive" :src="$image({id: item.file_id}, '300x300', 'fit')" alt="" />
              </div>
            </a>
            <div v-if="item.comment" class="mt-2 text-center"><strong>Отзыв от Krafti:</strong><br />{{ item.comment }}</div>
          </div>
          <!--<div class="col-lg-3 col-md-4 col-6 m-width-80"><a class="work__item consideration" href="">
            <div class="work__item&#45;&#45;img"><img class="conside__img img-responsive" src="~assets/images/content/consideration-2.jpg" alt="">
              <div class="ic__consider"></div>
            </div>
          </a>
          </div>-->
        </div>
      </div>
      <div v-if="lessonworks.length">
        <h2 class="worksList--title">Выполненные уроки</h2>
        <div class="row item__wrap worksList align-items-center flex-wrap">
          <div v-for="item in lessonworks" :key="item.id" class="col-lg-3 col-md-4 col-6 m-width-80">
            <div class="mt-2 text-center text-muted">{{ item.course.title }} / {{ item.lesson.title }} {{ item.comment }}</div>
            <a :href="item.file" target="_blank" rel="noreferrer" class="work__item">
              <div class="work__item">
                <img class="img-responsive" :src="$image({id: item.file_id}, '300x300', 'fit')" alt="" />
              </div>
            </a>
            <div v-if="item.comment" class="mt-2 text-center"><strong>Отзыв от Krafti:</strong> {{ item.comment }}</div>
          </div>
          <!--<div class="col-lg-3 col-md-4 col-6 m-width-80">
            <a class="work__item" href="">
              <img class="img-responsive" src="~assets/images/content/consideration.jpg" alt=""
            </a>
          </div>-->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  async asyncData({app}) {
    const data = {
      homeworks: [],
      lessonworks: [],
    }
    const homeworks = await app.$axios.get('user/homeworks', {params: {limit: 0}})
    homeworks.data.rows.forEach((v) => {
      if (v.section === 0) {
        data.lessonworks.push(v)
      } else {
        data.homeworks.push(v)
      }
    })

    return data
  },
  head() {
    return {
      title: 'Крафти / Личный кабинет / Мои работы',
    }
  },
}
</script>
