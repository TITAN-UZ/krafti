<template>
  <div class="buy__wrap">
    <b-button v-if="!record.lessons_count" variant="default" class="btn__play">
      Готовится к публикации
    </b-button>

    <template v-if="record.bought">
      <b-button
        v-if="currentLesson"
        variant="default"
        class="btn__play"
        :to="{name: 'courses-cid-index-lesson-lid', params: {cid: record.id, lid: currentLesson.id}}"
      >
        <template v-if="currentLesson.section > 1 || currentLesson.rank > 0">Продолжить просмотр</template>
        <template v-else>Начать просмотр</template>
      </b-button>
    </template>

    <template v-else>
      <b-button variant="default" class="btn__buy" :to="{name: 'courses-cid-index-buy', params: $route.params}">
        Купить от {{ lowestPrice | price(record.discount) | number }} руб.
      </b-button>
      <template v-if="record.free_lesson">
        <div class="flex-grow-1 text-center p-2">или</div>
        <b-button
          variant="default"
          class="btn__play"
          :to="{name: 'courses-cid-index-free', params: {cid: record.id, id: record.free_lesson.id}}"
        >
          Попробовать бесплатно!
        </b-button>
      </template>
    </template>
  </div>
</template>

<script>
export default {
  name: 'CourseButton',
  props: {
    record: {
      type: Object,
      required: true,
    },
    lessons: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      progress: this.record.progress,
      pSection: this.record.progress.section,
      pRank: this.record.progress.rank,
    }
  },
  computed: {
    currentLesson() {
      const filtered = this.lessons.filter((item) => {
        return item.section === this.progress.section && item.rank === this.progress.rank
      })
      if (filtered.length) {
        return filtered[0]
      }

      return this.lessons[0]
    },
    lowestPrice() {
      return this.record.price[Object.keys(this.record.price)[0]]
    },
  },
}
</script>
