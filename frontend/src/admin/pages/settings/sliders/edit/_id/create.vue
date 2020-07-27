<template>
  <modal-create v-model="record" :url="url" title="Новый слайд">
    <template slot="fields">
      <form-slide :record="record" />
    </template>
  </modal-create>
</template>

<script>
import FormSlide from '../../../../../components/forms/slide'

export default {
  components: {FormSlide},
  data() {
    return {
      url: 'admin/slider/items',
      record: {
        slider_id: this.$route.params.id,
        image_id: null,
        video_id: null,
        type: 'image',
        link: null,
        title: null,
        description: null,
        active: true,
      },
    }
  },
}
</script>
