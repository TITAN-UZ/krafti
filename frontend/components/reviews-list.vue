<template>
  <div :class="rowClass">
    <div v-if="reviews.length">
      <div :class="itemClass" v-for="review in reviews">
        <div class="review__item d-flex flex-column justify-content-center align-items-center">
          <div class="review__item--photo">
            <img class="rounded-circle" :src="review.user.photo" v-if="review.user.photo">
            <fa :icon="['fad', 'user-circle']"
                style="--fa-primary-color: #fff"
                class="user-placeholder" v-else/>
          </div>
          <h2 class="review__item--name">{{review.user.fullname}}</h2>
          <div class="review__item--position">{{review.user.company}}</div>
          <div class="review__item--text">{{review.text}}</div>
        </div>
      </div>
    </div>
    <div class="alert alert-info w-100 m-auto" style="max-width: 85%;"  v-else>
      Отзывов пока нет
    </div>
  </div>
</template>

<script>
    import {faUserCircle} from '@fortawesome/pro-duotone-svg-icons'

    export default {
        name: 'reviews-list',
        props: {
            reviews: {
                type: Array,
                required: true,
                default() {
                    return []
                }
            },
            rowClass: {
                type: String,
                required: false,
                default: 'row reviews__wrap',
            },
            itemClass: {
                type: String,
                required: false,
                default: 'col-12 col-lg-4 m-width-80',
            }
        },
        created() {
            this.$fa.add(faUserCircle)
        }
    }
</script>

<style lang="scss">
  .user-placeholder {

    font-size: 500px;
    max-width: 180px;
    max-height: 180px;
  }
</style>
