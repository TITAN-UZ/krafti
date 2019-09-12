<template>
  <div class="wrapper">
    <header-bg image="team"/>
    <div class="wrapper__content">
      <section class="teachers">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <div class="row">
                <div class="col-12">
                  <h2 class="section__title">Наша команда</h2>
                </div>
              </div>
              <div class="container__940">
                <authors-list :authors="authors"/>
              </div>
              <!--<div class="row">
                <div class="col-12 tab__wrap&#45;&#45;scroll">
                  <b-tabs>
                    <b-tab title="Рисование" active>
                      <div class="row">
                        <div class="col-lg-5 col-12 left__block">
                          <div class="teachers__list">
                            <div class="media teacher__item">
                              <div class="wrap mr-3">
                                <img class="teacher__item&#45;&#45;photo rounded-circle"
                                     src="~assets/images/content/teacher.png" alt="..."><span
                                class="label__shape"></span>
                              </div>
                              <div class="media-body">
                                <b-link to="/teachers/1">
                                  <h4 class="teacher__item&#45;&#45;title mt-0">Алиса Матюшкина</h4>
                                </b-link>
                                <h5 class="teacher__item&#45;&#45;position">Идейный вдохновитель</h5>
                                <div class="teacher__item&#45;&#45;info">On the other hand, we
                                  denounce with righteous indignation and dislike men
                                  who are so beguiled and demoralized by the charms of
                                  pleasure of the moment, so ...
                                </div>
                                <div class="teacher__item&#45;&#45;more">
                                  <b-link class="more_link" to="/teachers/1">Подробнее</b-link>
                                </div>
                              </div>
                            </div>
                            <div class="media teacher__item">
                              <div class="wrap mr-3">
                                <img class="teacher__item&#45;&#45;photo rounded-circle"
                                     src="~assets/images/content/teacher.png" alt="..."><span
                                class="label__shape"></span>
                              </div>
                              <div class="media-body">
                                <b-link to="/teachers/2">
                                  <h4 class="teacher__item&#45;&#45;title mt-0">Алиса Матюшкина</h4>
                                </b-link>
                                <h5 class="teacher__item&#45;&#45;position">Идейный вдохновитель</h5>
                                <div class="teacher__item&#45;&#45;info">On the other hand, we
                                  denounce with righteous indignation and dislike men
                                  who are so beguiled and demoralized by the charms of
                                  pleasure of the moment, so ...
                                </div>
                                <div class="teacher__item&#45;&#45;more">
                                  <b-link class="more_link" to="/teachers/2">Подробнее</b-link>
                                </div>
                              </div>
                            </div>
                            <div class="media teacher__item">
                              <div class="wrap mr-3">
                                <img class="teacher__item&#45;&#45;photo rounded-circle"
                                     src="~assets/images/content/teacher.png" alt="..."><span
                                class="label__shape"></span>
                              </div>
                              <div class="media-body">
                                <b-link to="/teachers/3">
                                  <h4 class="teacher__item&#45;&#45;title mt-0">Алиса Матюшкина</h4>
                                </b-link>
                                <h5 class="teacher__item&#45;&#45;position">Идейный вдохновитель</h5>
                                <div class="teacher__item&#45;&#45;info">On the other hand, we
                                  denounce with righteous indignation and dislike men
                                  who are so beguiled and demoralized by the charms of
                                  pleasure of the moment, so ...
                                </div>
                                <div class="teacher__item&#45;&#45;more">
                                  <b-link class="more_link" to="/teachers/3">Подробнее</b-link>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-7 right__block d-none d-lg-block">
                          <div class="tab__img">
                            <img class="img-responsive" alt=""
                                 src="~assets/images/general/tab-teachers.png">
                          </div>
                        </div>
                      </div>
                    </b-tab>
                    <b-tab title="Спорт">
                      ...
                    </b-tab>
                    <b-tab title="Музыка">
                      ...
                    </b-tab>
                    <b-tab title="Кулинария">
                      ...
                    </b-tab>
                  </b-tabs>
                </div>
              </div>-->
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
    import HeaderBg from '../../components/header-bg'
    import AuthorsList from '../../components/authors-list';

    export default {
        auth: false,
        components: {AuthorsList, HeaderBg},
        asyncData({app}) {
            return app.$axios.get('web/authors', {params: {limit: 30}})
                .then(res => {
                    return {authors: res.data.rows}
                })
        },
        head() {
            return {
                title: 'Крафти / Наша команда',
            }
        },
    }
</script>
