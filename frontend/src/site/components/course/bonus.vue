<template>
  <div v-if="bonus !== null" class="bonus__lesson">
    <div class="bonus__lesson--wrap d-flex justify-content-center align-items-center flex-column">
      <div class="bonus__lesson--thumb">
        <span class="ic__star-gold" />
      </div>
      <div class="bonus__lesson--title">
        <span :class="{'mr-2': true, 'ic__locked--gray': progress.section !== 0}" />
        Бонусный урок
      </div>
      <div class="bonus__lesson--text text-center">
        <p>
          <strong>{{ bonus.title }}</strong>
        </p>
        {{ bonus.description }}
      </div>

      <div v-if="bonus.video" class="bonus__lesson--video">
        <div v-if="progress.section !== 0" class="disabled">
          <b-img-lazy class="img-responsive bonus__lesson--thumb" :src="bonus.video.preview['295x166']" height="166" />
        </div>
        <nuxt-link v-else :to="link" class="video">
          <b-img-lazy class="img-responsive bonus__lesson--thumb" :src="bonus.video.preview['295x166']" height="166" />
        </nuxt-link>
      </div>
      <div v-if="progress.section != 0" class="bonus__btn">
        <nuxt-link class="btn btn-default btn__buy" :to="{name: 'courses-cid-index-buy-bonus', cid: record.id}">
          <span class="ic__star mr-2" />
          Купить за {{ bonus_cost | number }}
          {{ bonus_cost | noun('крафтик|крафтика|крафтиков') }}
        </nuxt-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CourseBonus',
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
      bonus_cost: process.env.COINS_BUY_BONUS,
    }
  },
  computed: {
    progress() {
      return this.$store.getters['courses/progress'](this.record.id)
    },
    bonus() {
      const filtered = this.lessons.filter((item) => item.section === 0)
      if (filtered.length) {
        return filtered[0]
      }
      return null
    },
    link() {
      return {name: 'courses-cid-index-lesson-lid', params: {cid: this.record.id, lid: this.bonus.id}}
    },
  },
}
</script>

<style scoped lang="scss">
.bonus__lesson--video {
  .disabled::before {
    width: 30px !important;
  }
}
</style>
