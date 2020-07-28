<template>
  <div>
    <b-form-group label="Логин">
      <b-form-input v-model.trim="record.email" type="email" autofocus required />
    </b-form-group>

    <b-form-group label="Пароль">
      <b-input-group>
        <b-form-input v-model.trim="record.password" :required="!record.id" />
        <b-input-group-append>
          <b-button tabindex="-1" variant="outline-secondary" @click.prevent="generatePassword">
            <fa :icon="['fas', 'key']" />
          </b-button>
        </b-input-group-append>
      </b-input-group>
    </b-form-group>

    <b-form-group label="ФИО">
      <b-form-input v-model.trim="record.fullname" required />
    </b-form-group>

    <b-form-group label="Группа">
      <b-form-select v-model="record.role_id" :options="roles" value-field="id" text-field="title" required />
    </b-form-group>

    <template v-if="record.favorite">
      <b-form-group label="Компания/должность">
        <b-form-input v-model.trim="record.company" required />
      </b-form-group>

      <b-form-group label="Короткое описание">
        <b-textarea v-model.trim="record.description" rows="2" required />
      </b-form-group>

      <b-form-group label="Длинное описание">
        <b-textarea v-model.trim="record.long_description" rows="4" />
      </b-form-group>
    </template>

    <b-form-group>
      <b-form-checkbox v-model="record.favorite">Выводить как автора</b-form-checkbox>
    </b-form-group>

    <b-form-group>
      <b-form-checkbox v-model.number="record.active">Активен</b-form-checkbox>
    </b-form-group>
  </div>
</template>

<script>
export default {
  name: 'FormUser',
  props: {
    record: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      roles: [],
    }
  },
  async created() {
    const {data: roles} = await this.$axios.get('admin/user-roles')
    this.roles = roles.rows
  },
  methods: {
    generatePassword(e, length = 8) {
      this.record.password = this.$password(length)
    },
  },
}
</script>
