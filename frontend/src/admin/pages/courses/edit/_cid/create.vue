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
        active: false,
        extra: false,
        free: false,
      },
    }
  },
}
</script>
