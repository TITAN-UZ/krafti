<template>
  <modal-create v-model="record" :url="url" title="Новый курс" size="lg">
    <template slot="fields">
      <form-course :record="record" />
    </template>
  </modal-create>
</template>

<script>
import FormCourse from '../../components/forms/course'

export default {
  components: {FormCourse},
  data() {
    return {
      url: 'admin/courses',
      record: {
        title: '',
        tagline: '',
        description: '',
        category: '',
        price: {'3': '', '6': '', '12': ''},
        price6: '',
        price12: '',
        age: '',
        cover: {},
        video_id: null,
        bonus_id: null,
        template_id: 2,
        active: false,
        template: {},
      },
    }
  },
}
</script>
