<template>
  <modal-update v-model="record" :url="url" :title="record.title" size="xl">
    <template slot="fields">
      <form-course :record="record" />
    </template>
    <template slot="tabs">
      <b-tab title="Уроки" active>
        <table-lessons :course-id="record.id" />
      </b-tab>
      <b-tab title="Галерея">
        <gallery-manager :object-id="record.id" object-name="Course" />
      </b-tab>

      <nuxt-child />
    </template>
  </modal-update>
</template>

<script>
import FormCourse from '../../../../components/forms/course'
import TableLessons from '../../../../components/tables/lessons'

export default {
  components: {TableLessons, FormCourse},
  validate({params}) {
    return /^\d+$/.test(params.cid)
  },
  async asyncData({app, params, error}) {
    try {
      const {data: record} = await app.$axios.get('admin/courses', {params: {id: params.cid}})
      return {record}
    } catch (e) {
      error({statusCode: e.statusCode, message: e.data})
    }
  },
  data() {
    return {
      url: 'admin/courses',
      record: {},
    }
  },
}
</script>
