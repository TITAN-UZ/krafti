<template>
  <div :class="rowClass">
    <template v-if="reviews.length">
      <template v-for="review in reviews">
        <course-review :key="review.id" :item="review" v-bind="$props" />
      </template>
    </template>
    <div v-else class="alert alert-info w-100 m-auto" style="max-width: 85%;">
      Отзывов пока нет
    </div>
  </div>
</template>

<script>
import CourseReview from './review'

export default {
  name: 'CourseReviews',
  components: {CourseReview},
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
