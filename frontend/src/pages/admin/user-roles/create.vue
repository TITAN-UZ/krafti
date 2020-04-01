<template>
  <modal-create v-model="record" :url="url" title="Новая группа">
    <template slot="fields">
      <form-user-role :record="record" />
    </template>
  </modal-create>
</template>

<script>
import FormUserRole from '../../../components/forms/user-roles'

export default {
  components: {FormUserRole},
  data() {
    return {
      url: 'admin/user-roles',
      record: {
        title: '',
        scope: [],
      },
    }
  },
}
</script>
