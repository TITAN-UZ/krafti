<template>
  <modal-update v-model="record" :url="url" :title="record.user.fullname">
    <template slot="fields">
      <form-homework :record="record" />
    </template>
  </modal-update>
</template>

<script>
import FormHomework from '../../../../components/forms/homework'

export default {
  components: {FormHomework},
  async asyncData({app, params, error}) {
    try {
      const {data: record} = await app.$axios.get('admin/homeworks', {params})
      return {record}
    } catch (e) {
      error({statusCode: e.statusCode, message: e.data})
    }
  },
  data() {
    return {
      url: 'admin/homeworks',
      record: {},
    }
  },
  validate({params}) {
    return /^\d+$/.test(params.id)
  },
}
</script>
