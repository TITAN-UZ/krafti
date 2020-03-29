<template>
  <b-modal id="myModal" :title="record.title" hide-footer visible static @hidden="onHidden">
    <b-form @submit.prevent="onSubmit">
      <b-form-group
        label="Укажите название группы:"
        label-for="input-title"
        description="Название должно быть уникальным"
      >
        <b-form-input id="input-title" v-model="record.title" required />
      </b-form-group>

      <b-form-group label="Укажите разрешения:" label-for="input-scope">
        <input-tags v-model="record.scope" />
      </b-form-group>

      <b-row no-gutters class="mt-2 justify-content-between">
        <b-button variant="secondary" :disabled="loading" @click="$root.$emit('bv::hide::modal', 'myModal')">
          Отмена
        </b-button>
        <b-button variant="primary" type="submit" :disabled="loading">
          <b-spinner v-if="loading" small />
          Обновить
        </b-button>
      </b-row>
    </b-form>
  </b-modal>
</template>

<script>
export default {
  async asyncData({app, params}) {
    const res = await app.$axios.get('admin/user-roles', {params: {id: params.id}})
    return {record: res.data}
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
      this.$router.push({name: 'admin-user-roles'})
    },
    onSubmit() {
      this.loading = true
      this.$axios
        .patch('admin/user-roles', this.record)
        .then((res) => {
          this.loading = false
          this.$root.$emit('bv::hide::modal', 'myModal')
          this.$root.$emit('app::admin-user-roles::update', res.data)
        })
        .catch(() => {
          this.loading = false
        })
    },
  },
}
</script>
