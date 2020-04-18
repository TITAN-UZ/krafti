<template>
  <modal-update v-model="record" :url="url" :title="record.title">
    <template slot="fields">
      <form-template :record="record" />
    </template>
  </modal-update>
</template>

<script>
import FormTemplate from '../../../../components/forms/template'

export default {
  components: {FormTemplate},
  async asyncData({app, params, error}) {
    try {
      const {data: record} = await app.$axios.get('admin/templates', {params})
      return {record}
    } catch (e) {
      error({statusCode: e.statusCode, message: e.data})
    }
  },
  data() {
    return {
      url: 'admin/templates',
      record: {},
    }
  },
}
</script>
