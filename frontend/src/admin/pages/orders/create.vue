<template>
  <modal-create v-model="record" :url="url" title="Новый заказ">
    <template slot="fields">
      <form-order :record="record" :price.sync="price" />
    </template>
  </modal-create>
</template>

<script>
import FormOrder from '../../components/forms/order'
export default {
  components: {FormOrder},
  data() {
    return {
      url: 'admin/orders',
      record: {
        user_id: null,
        course_id: null,
        period: null,
        paid_till: null,
        status: 2,
      },
      price: {},
    }
  },
}
</script>
