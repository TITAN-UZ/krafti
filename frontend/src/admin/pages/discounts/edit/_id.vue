<template>
  <modal-update v-model="record" :url="url" :title="record.code">
    <template slot="fields">
      <form-promo :record="record" />
    </template>
  </modal-update>
</template>

<script>
import FormPromo from '../../../components/forms/promo'

export default {
  components: {FormPromo},
  async asyncData({app, params, error}) {
    try {
      const {data: record} = await app.$axios.get('admin/promos', {params})
      return {record}
    } catch (e) {
      error({statusCode: e.statusCode, message: e.data})
    }
  },
  validate({params}) {
    return /^\d+$/.test(params.id)
  },
  data() {
    return {
      url: 'admin/promos',
      record: {},
    }
  },
}
</script>
