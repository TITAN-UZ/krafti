<template>
  <div :id="`comment-${comment.id}`" class="message__item">
    <div>
      <div :class="computedClass">
        <div class="wrap mr-2">
          <user-avatar :user="comment.user" :size="50" />
        </div>
        <div class="media-body">
          <div class="media-body-top d-flex align-items-center justify-content-between">
            <h4 class="message__item--title mt-0">{{ comment.user.fullname }}</h4>
            <span class="days_ago">{{ comment.created_at | dateago }}</span>
          </div>
          <div class="media-body-bottom d-flex align-items-center justify-content-between">
            <div class="message__item--info">{{ comment.text }}</div>
          </div>
        </div>
      </div>
      <slot name="actions" :comment="comment" />
    </div>

    <!-- Reply form -->
    <slot name="form" :comment="comment" />

    <!-- Recursive comments -->
    <div v-if="comment.children.length" class="comment-children ml-md-5 ml-2">
      <comments-item v-for="item in comment.children" :key="item.id" :comment="item" v-bind="$attrs" v-on="$listeners">
        <slot v-for="slot in Object.keys($slots)" :slot="slot" :name="slot" />
        <template v-for="slot in Object.keys($scopedSlots)" v-slot:[slot]="scope">
          <slot :name="slot" v-bind="scope" />
        </template>
      </comments-item>
    </div>
  </div>
</template>

<script>
import {faStar} from '@fortawesome/pro-light-svg-icons'
import {faRedo, faTimes, faStar as faReview, faUserCircle} from '@fortawesome/pro-solid-svg-icons'

export default {
  name: 'CommentsItem',
  props: {
    comment: {
      type: Object,
      required: true,
    },
  },
  computed: {
    computedClass() {
      return {
        'd-flex': true,
        'comment-body': true,
        owner: this.$auth.loggedIn && this.$auth.user.id === this.comment.user.id,
        review: this.$auth.loggedIn && this.$auth.hasScope('comments') && this.comment.review,
        deleted: this.comment.deleted,
      }
    },
  },
  created() {
    this.$fa.add(faTimes, faRedo, faStar, faReview, faUserCircle)
  },
}
</script>
