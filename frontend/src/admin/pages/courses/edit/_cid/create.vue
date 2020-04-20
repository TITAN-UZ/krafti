<template>
  <modal-create v-model="record" :url="url" title="Новый урок" size="lg">
    <template slot="fields">
      <form-lesson :record="record" />
    </template>
  </modal-create>
</template>

<script>
import FormLesson from '../../../../components/forms/lesson'

export default {
  components: {FormLesson},
  async asyncData({app, params, error}) {
    try {
      const {data: course} = await app.$axios.get('admin/courses', {params: {id: params.cid}})
      return {course}
    } catch (e) {
      error({statusCode: e.statusCode, message: e.data})
    }
  },
  data() {
    return {
      url: 'admin/lessons',
      record: {
        title: '',
        description: '',
        products: [],
        video_id: null,
        bonus_id: null,
        file: {},
        author_id: null,
        course_id: this.$route.params.cid,
        section: 1,
        active: false,
        extra: false,
        free: false,
      },
      course: {},
    }
  },
  created() {
    this.record.course = this.course
  },
}
</script>
