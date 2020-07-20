<template>
  <div :class="itemClass">
    <div class="review__item d-flex flex-column justify-content-center align-items-center">
      <user-avatar :user="item.user" :size="100" />
      <h2 class="review__item--name text-center">{{ item.user.fullname }}</h2>
      <!--<div class="review__item&#45;&#45;position">{{ item.user.company }}</div>-->
      <div class="review__item--text">
        <template v-if="item.text.length > maxLength && !expanded">
          {{ item.text | truncate(maxLength, {mark: ''}) }}
          <a class="font-weight-bold text-primary" @click.prevent="expanded = true">...</a>
        </template>
        <template v-else>
          {{ item.text }}
        </template>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CourseReview',
  props: {
    item: {
      type: Object,
      required: true,
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
      expanded: false,
    }
  },
}
</script>
