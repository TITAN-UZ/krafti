<template>
  <b-modal
    id="myModal"
    ref="modalWindow"
    title="Новый пользователь"
    size="lg"
    hide-footer
    visible
    static
    @hidden="onHidden"
  >
    <b-form ref="modalForm" @submit.prevent="onSubmit">
      <b-form-group label="Логин" :disabled="loading" :label-cols-md="3">
        <b-form-input v-model.trim="record.email" type="email" autofocus required />
      </b-form-group>

      <b-form-group label="Пароль" :disabled="loading" :label-cols-md="3">
        <b-input-group>
          <b-form-input v-model.trim="record.password" required />
          <b-input-group-append>
            <b-button tabindex="-1" variant="outline-secondary" @click.prevent="generatePassword">
              <fa icon="key" />
            </b-button>
          </b-input-group-append>
        </b-input-group>
      </b-form-group>

      <b-form-group label="ФИО" :disabled="loading" :label-cols-md="3">
        <b-form-input v-model.trim="record.fullname" required />
      </b-form-group>

      <b-form-group label="Группа" :disabled="loading" :label-cols-md="3">
        <b-form-select v-model="record.role_id" :options="roles" value-field="id" text-field="title" required />
      </b-form-group>

      <b-form-group label="Компания/должность" :disabled="loading" :label-cols-md="3">
        <b-form-input v-model.trim="record.company" />
      </b-form-group>

      <b-form-group label="Короткое описание" :disabled="loading" :label-cols-md="3">
        <b-textarea v-model.trim="record.description" rows="2" no-resize />
      </b-form-group>

      <b-form-group label="Длинное описание" :disabled="loading" :label-cols-md="3">
        <b-textarea v-model.trim="record.long_description" rows="4" no-resize />
      </b-form-group>

      <b-form-checkbox v-model.number="record.active" class="offset-md-3">Активен</b-form-checkbox>
      <b-form-checkbox v-if="record.role_id < 3" v-model="record.favorite" class="offset-md-3 mt-2"
        >Выводить как автора</b-form-checkbox
      >

      <b-row no-gutters class="mt-2 justify-content-between">
        <b-button variant="secondary" :disabled="loading" @click="$refs.modalWindow.hide()">Отмена </b-button>
        <b-button variant="primary" type="submit" :disabled="loading">
          <b-spinner v-if="loading" small />
          Сохранить
        </b-button>
      </b-row>
    </b-form>
  </b-modal>
</template>

<script>
export default {
  asyncData({app, params}) {
    return app.$axios
      .get('admin/user-roles')
      .then((res) => {
        return {roles: res.data.rows}
      })
      .catch(() => {})
  },
  data() {
    return {
      loading: false,
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
  methods: {
    onHidden() {
      this.$router.push({name: 'admin-users'})
    },
    onSubmit() {
      this.loading = true
      this.$axios
        .put('admin/users', this.record)
        .then((res) => {
          this.loading = false
          this.$refs.modalWindow.hide()
          this.$root.$emit('app::admin-users::update', res.data)
        })
        .catch(() => {
          this.loading = false
        })
    },
    generatePassword(e, length = 8) {
      this.record.password = this.$password(length)
    },
  },
}
</script>
