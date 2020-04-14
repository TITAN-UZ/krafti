<template>
  <modal-create v-model="record" :url="url" title="Новый промокод">
    <template slot="fields">
      <form-promo :record="record" />
    </template>
  </modal-create>
</template>

<script>
import FormPromo from '../../components/forms/promo'

export default {
  components: {FormPromo},
  data() {
    return {
      url: 'admin/promos',
      record: {
        code: '',
        discount: null,
        percent: true,
        title: null,
        date_start: null,
        date_end: null,
      },
    }
  },
}
</script>
