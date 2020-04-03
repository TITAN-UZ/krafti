<template>
  <div :class="rowClass">
    <template v-if="reviews.length">
      <div v-for="review in reviews" :key="review.id" :class="itemClass">
        <div class="review__item d-flex flex-column justify-content-center align-items-center">
          <user-avatar :user="review.user" :size="100" />
          <h2 class="review__item--name text-center">{{ review.user.fullname }}</h2>
          <!--<div class="review__item&#45;&#45;position">{{ review.user.company }}</div>-->
          <div class="review__item--text">
            <template v-if="review.text.length > maxLength && !expanded.includes(review.id)">
              {{ review.text | truncate(maxLength, {mark: ''}) }}
              <a class="font-weight-bold text-primary" @click.prevent="expanded.push(review.id)">...</a>
            </template>
            <template v-else>
              {{ review.text }}
            </template>
          </div>
        </div>
      </div>
    </template>
    <div v-else class="alert alert-info w-100 m-auto" style="max-width: 85%;">
      Отзывов пока нет
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserReviews',
  props: {
    reviews: {
      type: Array,
      required: true,
    },
    rowClass: {
      type: String,
      default: 'row reviews__wrap',
    },
    itemClass: {
      type: String,
      default: 'col-12 col-lg-4',
    },
    maxLength: {
      type: Number,
      default: 100,
    },
  },
  data() {
    return {
      expanded: [],
    }
  },
}
</script>
