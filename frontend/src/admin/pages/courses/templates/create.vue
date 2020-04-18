<template>
  <modal-create v-model="record" :url="url" title="Новый шаблон курса">
    <template slot="fields">
      <form-template :record="record" />
    </template>
  </modal-create>
</template>

<script>
import FormTemplate from '../../../components/forms/template'

export default {
  components: {FormTemplate},
  data() {
    return {
      url: 'admin/templates',
      record: {
        title: '',
        course_palette: false,
        lesson_bonus: false,
        course_steps: false,
        course_homeworks: false,
        course_bonus: false,
      },
    }
  },
}
</script>
