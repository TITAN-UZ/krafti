<template>
  <modal-update v-model="record" :url="url" :title="record.title">
    <template slot="fields">
      <form-user-role :record="record" />
    </template>
  </modal-update>
</template>

<script>
import FormUserRole from '../../../components/forms/user-roles'

export default {
  components: {FormUserRole},
  async asyncData({app, params, error}) {
    try {
      const {data: record} = await app.$axios.get('admin/user-roles', {params})
      return {record}
    } catch (e) {
      error({statusCode: e.statusCode, message: e.data})
    }
  },
  data() {
    return {
      url: 'admin/user-roles',
      record: {},
    }
  },
  validate({params}) {
    return /^\d+$/.test(params.id)
  },
}
</script>
