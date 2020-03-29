<template>
  <modal-update v-model="record" :url="url" :title="record.title" size="lg">
    <template slot="fields">
      <form-lesson :record="record" />
    </template>
  </modal-update>
</template>

<script>
import FormLesson from '../../../../../../components/forms/lesson'

export default {
  components: {FormLesson},
  validate({params}) {
    return /^\d+$/.test(params.cid) && /^\d+$/.test(params.lid)
  },
  async asyncData({app, params, error}) {
    try {
      const {data: record} = await app.$axios.get('admin/lessons', {params: {id: params.lid}})
      return {record}
    } catch (e) {
      error({statusCode: e.statusCode, message: e.data})
    }
  },
  data() {
    return {
      url: 'admin/lessons',
      record: {},
    }
  },
}
</script>
