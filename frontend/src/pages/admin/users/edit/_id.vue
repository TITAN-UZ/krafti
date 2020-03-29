<template>
  <b-modal
    id="myModal"
    ref="modalWindow"
    :title="record.fullname"
    size="lg"
    hide-footer
    visible
    static
    @hidden="onHidden"
  >
    <b-tabs>
      <b-tab title="Настройки">
        <b-form-group v-if="record.role_id < 3" label="" :disabled="loading" :label-cols-md="3">
          <upload-photo v-model="record.photo" :size="150" :user-id="record.id" class="mb-3" />
        </b-form-group>

        <b-form ref="modalForm" @submit.prevent="onSubmit">
          <b-form-group label="Почта" :disabled="loading" :label-cols-md="3">
            <b-form-input v-model.trim="record.email" type="email" autofocus required />
          </b-form-group>

          <b-form-group label="Пароль" :disabled="loading" :label-cols-md="3">
            <b-input-group>
              <b-form-input v-model.trim="record.password" />
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

          <b-form-checkbox v-model="record.active" class="offset-md-3">Активен</b-form-checkbox>
          <b-form-checkbox v-if="record.role_id < 3" v-model="record.favorite" class="offset-md-3 mt-2"
            >Выводить как автора</b-form-checkbox
          >

          <b-row no-gutters class="mt-2 justify-content-between">
            <b-button variant="secondary" :disabled="loading" @click="$refs.modalWindow.hide()">Отмена </b-button>
            <b-button variant="primary" type="submit" :disabled="loading">
              <b-spinner v-if="loading" small />
              Обновить
            </b-button>
          </b-row>
        </b-form>
      </b-tab>
      <b-tab v-if="record.role_id < 3" title="Галерея">
        <gallery-manager :object-id="record.id" object-name="User" />
      </b-tab>
    </b-tabs>
  </b-modal>
</template>

<script>
export default {
  async asyncData({app, params}) {
    const [record, roles] = await Promise.all([
      app.$axios.get('admin/users', {params: {id: params.id}}),
      app.$axios.get('admin/user-roles'),
    ])
    record.data.password = ''
    return {
      roles: roles.data.rows,
      record: record.data,
    }
  },
  data() {
    return {
      loading: false,
    }
  },
  validate({params}) {
    return /^\d+$/.test(params.id)
  },
  methods: {
    onHidden() {
      this.$router.push({name: 'admin-users'})
    },
    onSubmit() {
      this.loading = true
      this.$axios
        .patch('admin/users', this.record)
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
