<template>
  <b-modal id="myModal" :title="record.title" hide-footer visible static @hidden="onHidden">
    <b-form @submit.prevent="onSubmit">
      <div id="homeworks-image">
        <a :href="record.file" target="_blank">
          <img :src="[$settings.image_url, record.file_id, '500x500'].join('/')" />
        </a>
      </div>

      <b-form-group class="mt-3" label="Отзыв:" label-for="input-comment" description="Напишите свой отзыв о работе">
        <b-form-textarea id="input-comment" v-model="record.comment" rows="5" :required="record.approved" />
      </b-form-group>

      <b-form-checkbox v-model="record.approved" class="mb-3">Работа проверена</b-form-checkbox>

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
    const res = await app.$axios.get('admin/homeworks', {params: {id: params.id}})
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
      this.$router.push({name: 'admin-homeworks'})
    },
    onSubmit() {
      this.loading = true
      const record = {
        id: this.record.id,
        comment: this.record.comment,
        approved: this.record.approved,
      }
      this.$axios
        .patch('admin/homeworks', record)
        .then((res) => {
          this.loading = false
          this.$root.$emit('bv::hide::modal', 'myModal')
          this.$root.$emit('app::admin-homeworks::update', res.data)
        })
        .catch(() => {
          this.loading = false
        })
    },
  },
}
</script>

<style lang="scss">
#homeworks-image {
  img {
    display: block;
    margin: auto;
    max-width: 100%;
    border-radius: 20px;
  }
}
</style>
