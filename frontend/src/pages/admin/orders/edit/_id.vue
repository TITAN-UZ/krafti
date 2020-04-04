<template>
  <modal-update v-model="record" :url="url" :title="`Заказ № ${record.id}`">
    <template slot="fields">
      <form-order :record="record" :price.sync="price" />
    </template>
  </modal-update>
</template>

<script>
import FormOrder from '../../../../components/forms/order'
export default {
  components: {FormOrder},
  validate({params}) {
    return /^\d+$/.test(params.id)
  },
  async asyncData({app, params, error}) {
    try {
      const {data: record} = await app.$axios.get('admin/orders', {params})
      const {data: course} = await app.$axios.get('admin/courses', {params: {id: record.course_id}})
      return {
        record,
        price: course.price,
      }
    } catch (e) {
      error({statusCode: e.statusCode, message: e.data})
    }
  },
  data() {
    return {
      url: 'admin/orders',
      record: {},
      price: {},
    }
  },
}
</script>
