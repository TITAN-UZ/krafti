<template>
  <modal-update v-model="record" :url="url" :title="`Ключ настройки: ${record.key}`">
    <template slot="fields">
      <form-setting :record="record" />
    </template>
  </modal-update>
</template>

<script>
import FormSetting from '../../../components/forms/setting'

export default {
  components: {FormSetting},
  validate({params}) {
    return /^[\w-]+$/.test(params.key)
  },
  async asyncData({app, params, error}) {
    try {
      const {data: record} = await app.$axios.get('admin/settings', {params})
      return {record}
    } catch (e) {
      error({statusCode: e.statusCode, message: e.data})
    }
  },
  data() {
    return {
      url: 'admin/settings',
      record: {},
    }
  },
}
</script>
