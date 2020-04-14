<template>
  <div class="wrapper">
    <header-bg image="reviews" />
    <div class="wrapper__content">
      <section class="container__940 tabs">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <section class="feedbacks tab__wrap--scroll">
                <div class="container">
                  <h2 class="section__title">Отзывы</h2>
                </div>
                <div class="container__940">
                  <user-reviews :reviews="reviews" />
                </div>
              </section>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
import HeaderBg from '../components/header-bg'
import UserReviews from '../components/user/reviews'

export default {
  auth: false,
  components: {HeaderBg, UserReviews},
  async asyncData({app}) {
    const reviews = await app.$axios.get('web/reviews', {params: {limit: 100}})

    return {reviews: reviews.data.rows}
  },
  head() {
    return {
      title: 'Крафти / Отзывы',
    }
  },
}
</script>
