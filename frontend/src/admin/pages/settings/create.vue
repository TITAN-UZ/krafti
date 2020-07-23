<template>
  <modal-create v-model="record" :url="url" title="Новая настройка">
    <template slot="fields">
      <form-setting :record="record" />
    </template>
  </modal-create>
</template>

<script>
import FormSetting from '../../components/forms/setting'

export default {
  components: {FormSetting},
  data() {
    return {
      url: 'admin/settings',
      record: {
        key: null,
        type: 'string',
        value: null,
        title: null,
        created_at: null,
      },
    }
  },
}
</script>
