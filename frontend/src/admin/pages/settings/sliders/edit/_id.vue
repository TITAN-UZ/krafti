<template>
  <modal-update v-model="record" :url="url" :title="record.title" :size="tab > 0 ? 'lg' : 'md'" :current-tab.sync="tab">
    <template slot="fields">
      <form-slider :record="record" />
    </template>
    <template slot="tabs">
      <b-tab title="Слайды">
        <table-slides :record="record" />
      </b-tab>

      <nuxt-child />
    </template>
  </modal-update>
</template>

<script>
import FormSlider from '../../../../components/forms/slider'
import TableSlides from '../../../../components/tables/slides'

export default {
  components: {TableSlides, FormSlider},
  validate({params}) {
    return /^\d+$/.test(params.id)
  },
  async asyncData({app, params, error}) {
    try {
      const {data: record} = await app.$axios.get('admin/sliders', {params})
      return {record}
    } catch (e) {
      error({statusCode: e.statusCode, message: e.data})
    }
  },
  data() {
    return {
      url: 'admin/sliders',
      record: {},
      tab: 1,
    }
  },
}
</script>
