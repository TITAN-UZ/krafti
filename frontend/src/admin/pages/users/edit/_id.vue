<template>
  <modal-update v-model="record" :url="url" :title="record.fullname" :current-tab.sync="tab" :size="tab ? 'lg' : 'md'">
    <template slot="fields">
      <form-user :record="record" />
    </template>
    <template v-if="record.favorite" slot="tabs">
      <b-tab title="Галерея">
        <gallery-manager :object-id="record.id" object-name="User" />
      </b-tab>
    </template>
  </modal-update>
</template>

<script>
import FormUser from '../../../components/forms/user'

export default {
  components: {FormUser},
  async asyncData({app, params, error}) {
    try {
      const {data: record} = await app.$axios.get('admin/users', {params})
      return {record}
    } catch (e) {
      error({statusCode: e.statusCode, message: e.data})
    }
  },
  data() {
    return {
      url: 'admin/users',
      record: {},
      tab: 0,
    }
  },
  validate({params}) {
    return /^\d+$/.test(params.id)
  },
}
</script>
