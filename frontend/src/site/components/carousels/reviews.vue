<template>
  <div>
    <b-carousel ref="carousel" :interval="timeout * 1000" indicators>
      <div v-for="(slides, idx) in computedReviews" :key="idx" class="carousel-item">
        <div class="row">
          <course-review v-for="item in slides" :key="item.id" :item="item" :max-length="50" />
        </div>
      </div>
    </b-carousel>
    <div class="control-prev" @click="$refs.carousel.prev()">
      <fa :icon="['fas', 'chevron-left']" class="fa-2x" />
    </div>
    <div class="control-next" @click="$refs.carousel.next()">
      <fa :icon="['fas', 'chevron-right']" class="fa-2x" />
    </div>
  </div>
</template>

<script>
import CourseReview from '../course/review'

export default {
  name: 'ReviewCarousel',
  components: {CourseReview},
  props: {
    items: {
      type: Array,
      required: true,
    },
    timeout: {
      type: [Number, String],
      default: 5,
    },
  },
  computed: {
    computedReviews() {
      return this.items.reduce((resultArray, item, index) => {
        const chunkIndex = Math.floor(index / 3)
        if (!resultArray[chunkIndex]) {
          resultArray[chunkIndex] = []
        }
        resultArray[chunkIndex].push(item)

        return resultArray
      }, [])
    },
  },
}
</script>

<style scoped lang="scss">
div::v-deep {
  .carousel-item {
    padding: 0 30px;
  }
  .carousel-indicators {
    li {
      background-color: $primary;
    }
  }

  .control-next,
  .control-prev {
    position: absolute;
    top: 0;
    bottom: 0;
    z-index: 1;
    display: flex;
    align-items: center;
    width: 50px;
    transition: opacity 0.15s ease;
    opacity: 0.5;
    cursor: pointer;
    justify-content: center;
    svg {
      color: $primary;
    }
    &:hover {
      opacity: 1;
      background-color: #fafafa;
    }
  }
  .control-prev {
    left: -50px;
  }
  .control-next {
    right: -50px;
  }
}
</style>