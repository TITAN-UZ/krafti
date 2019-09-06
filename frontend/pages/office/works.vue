<template>
  <div class="tabContainer">
    <b-alert variant="info" show v-if="!home.length && !works.length">
      Вы еще не отправляли свои работы
    </b-alert>
    <div v-else>
      <div v-if="home.length">
        <h2 class="worksList--title">Домашние работы</h2>
        <div class="row item__wrap worksList align-items-center">
          <div class="col-lg-3 col-md-4 col-6 m-width-80" v-for="item in home">
            <a :href="item.file" target="_blank" class="work__item">
              <div class="work__item">
                <img class="img-responsive" :src="item.file" alt="">
              </div>
            </a>
          </div>
          <!--<div class="col-lg-3 col-md-4 col-6 m-width-80"><a class="work__item consideration" href="">
            <div class="work__item&#45;&#45;img"><img class="conside__img img-responsive" src="~assets/images/content/consideration-2.jpg" alt="">
              <div class="ic__consider"></div>
            </div>
          </a>
          </div>-->
        </div>
      </div>
      <div v-if="works.length">
        <h2 class="worksList--title">Выполненные уроки</h2>
        <div class="row item__wrap worksList  align-items-center">
          <div class="col-lg-3 col-md-4 col-6 m-width-80" v-for="item in works">
            <a :href="item.file" target="_blank" class="work__item">
              <div class="work__item">
                <img class="img-responsive" :src="item.file" alt="">
              </div>
            </a>
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
                home: [],
                works: [],
            };
            const homeworks = await app.$axios.get('user/homeworks', {params: {limit: 0}});
            homeworks.data.rows.forEach(v => {
                if (v.section === 0) {
                    data.home.push(v)
                } else {
                    data.works.push(v)
                }
            });

            return data
        }
    }
</script>
