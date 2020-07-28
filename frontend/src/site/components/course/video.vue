<template>
  <div>
    <div v-if="record.video" class="course-video">
      <div class="video-link" :style="{'background-image': background}" />
      <span class="play-button course" @click.prevent="$refs.mainVideo.show()">
        <fa :icon="['fad', 'circle']" />
        <fa :icon="['fas', 'play']" />
      </span>
      <vimeo ref="mainVideo" :video="record.video.remote_key" />
    </div>
    <div v-else class="course-video">
      <div class="video-link disabled" :style="{'background-image': background}" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'CourseVideo',
  props: {
    record: {
      type: Object,
      required: true,
    },
  },
  computed: {
    background() {
      return this.record.cover ? `url(${this.$image(this.record.cover)})` : false
    },
  },
}
</script>
