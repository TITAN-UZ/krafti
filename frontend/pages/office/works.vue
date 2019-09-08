<template>
  <div class="tabContainer">
    <b-alert variant="info" show v-if="!homeworks.length && !lessonworks.length">
      Вы еще не отправляли свои работы
    </b-alert>
    <div v-else>
      <div v-if="homeworks.length">
        <h2 class="worksList--title">Домашние работы</h2>
        <div class="row item__wrap worksList align-items-center">
          <div class="col-lg-3 col-md-4 col-6 m-width-80" v-for="item in homeworks">
            <a :href="item.file" target="_blank" class="work__item">
              <div class="work__item">
                <img class="img-responsive" :src="item.file" alt="">
              </div>
            </a>
            <div class="mt-2 text-center text-muted">
              {{item.course.title}}, этап {{item.section}}
            </div>
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
        <div class="row item__wrap worksList  align-items-center">
          <div class="col-lg-3 col-md-4 col-6 m-width-80" v-for="item in lessonworks">
            <a :href="item.file" target="_blank" class="work__item">
              <div class="work__item">
                <img class="img-responsive" :src="item.file" alt="">
              </div>
            </a>
            <div class="mt-2 text-center text-muted">
              {{item.course.title}}, этап {{item.lesson.section}}, урок {{item.lesson.rank + 1}}
            </div>
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
            let data = {
                homeworks: [],
                lessonworks: [],
            };
            const homeworks = await app.$axios.get('user/homeworks', {params: {limit: 0}});
            homeworks.data.rows.forEach(v => {
                if (v.section === 0) {
                    data.lessonworks.push(v)
                } else {
                    data.homeworks.push(v)
                }
            });

            return data
        },
        head() {
            return {
                title: 'Крафти / Личный кабинет / Мои работы',
            }
        },
    }
</script>
