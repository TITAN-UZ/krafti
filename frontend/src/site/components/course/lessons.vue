<template>
  <div>
    <div class="row lessons__list align-items-start">
      <div v-for="item in lessons" :key="item.id" :class="wrapperClass">
        <slot v-bind="{open: isLessonOpen, link: lessonLink, thumb: lessonThumb, item}">
          <div class="lesson__item--video">
            <nuxt-link v-if="isLessonOpen(item)" :to="lessonLink(item)" class="video">
              <b-img-lazy :src="lessonThumb(item)" class="img-responsive lesson__video--thumb" alt="" />
            </nuxt-link>
            <div v-else class="disabled">
              <b-img-lazy :src="lessonThumb(item)" class="img-responsive bonus__lesson--thumb" alt="" />
            </div>
          </div>
          <div class="lesson__item--info d-flex align-items-center justify-content-center">
            {{ item.title }}
          </div>
        </slot>
      </div>
    </div>

    <slot name="homework" />
  </div>
</template>

<script>
export default {
  name: 'CourseLessons',
  props: {
    record: {
      type: Object,
      required: true,
    },
    lessons: {
      type: Array,
      required: true,
    },
    minThumbWidth: {
      type: [String, Number],
      default: null,
    },
    wrapperClass: {
      type: [Object, String],
      default() {
        return (
          'lesson__item col-12 col-lg-4 col-md-6 ' +
          'd-flex flex-lg-column justify-content-lg-center align-content-center'
        )
      },
    },
  },
  computed: {
    progress() {
      return this.$store.getters['courses/progress'](this.record.id)
    },
  },
  methods: {
    lessonLink(item) {
      return {name: 'courses-cid-index-lesson-lid', params: {cid: this.record.id, lid: item.id}}
    },
    lessonThumb(item) {
      if (!item.video || !item.video.preview) {
        return null
      }
      const thumbs = item.video.preview
      if (this.minThumbWidth) {
        for (const i in thumbs) {
          if (Object.prototype.hasOwnProperty.call(thumbs, i)) {
            const tmp = i.split('x')
            if (this.minThumbWidth <= tmp[0]) {
              return thumbs[i]
            }
          }
        }
      }

      return thumbs[Object.keys(thumbs)[0]]
    },
    isLessonOpen(item) {
      // return !item.locked
      const progress = this.progress

      if (item.extra || item.free || (!progress.section && !progress.rank)) {
        return true
      }

      return progress.section > item.section || (progress.section === item.section && progress.rank >= item.rank)
    },
  },
}
</script>

<style scoped lang="scss">
.lesson__item--video {
  min-height: 75px;
  @media (min-width: 576px) {
    min-height: 166px;
  }
  .disabled::before {
    width: 30px;
  }
}
</style>
