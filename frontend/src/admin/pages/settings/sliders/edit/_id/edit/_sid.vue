<template>
  <modal-update v-model="record" :url="url" :title="record.title">
    <template slot="fields">
      <form-slide :record="record" />
    </template>
  </modal-update>
</template>

<script>
import FormSlide from '../../../../../../components/forms/slide'

export default {
  components: {FormSlide},
  async asyncData({app, params, error}) {
    try {
      const {data: record} = await app.$axios.get('admin/slider/items', {params: {id: params.sid}})
      return {record}
    } catch (e) {
      error({statusCode: e.statusCode, message: e.data})
    }
  },
  data() {
    return {
      url: 'admin/slider/items',
      record: {},
    }
  },
}
</script>
