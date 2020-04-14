<template>
  <modal-create v-model="record" :url="url" title="Новый пользователь">
    <template slot="fields">
      <form-user :record="record" />
    </template>
  </modal-create>
</template>

<script>
import FormUser from '../../components/forms/user'
export default {
  components: {FormUser},
  data() {
    return {
      url: 'admin/users',
      record: {
        email: '',
        password: '',
        fullname: '',
        instagram: '',
        dob: '',
        phone: '',
        company: '',
        description: '',
        active: true,
        role_id: 3,
        favorite: false,
      },
    }
  },
}
</script>
