<template>
  <b-modal id="myModal" title="Новый заказ" hide-footer visible static @hidden="onHidden">
    <b-form @submit.prevent="onSubmit">
      <b-form-group label="Выберите покупателя:" label-for="input-user">
        <pick-user id="input-user" v-model="record.user_id" :disabled="loading" :role-id="[3]" required />
      </b-form-group>

      <b-form-group label="Выберите курс:" label-for="input-course">
        <b-form-select
          v-model="record.course_id"
          value-field="id"
          text-field="title"
          :options="courses"
          :disabled="loading"
          required
          @change="onChange"
        >
          <template slot="first">
            <option :value="null" disabled selected>--</option>
          </template>
        </b-form-select>
      </b-form-group>

      <b-form-group label="Выберите стоимость:" label-for="input-price">
        <b-form-select v-model="record.period" :disabled="loading || !Object.keys(price).length">
          <option v-for="(cost, period) in price" :key="period" :value="period">
            {{ cost | number }} руб. за {{ period }} {{ period | noun('месяц|месяца|месяцев') }}
          </option>
        </b-form-select>
      </b-form-group>

      <b-row no-gutters class="mt-2 justify-content-between">
        <b-button variant="secondary" :disabled="loading" @click="$root.$emit('bv::hide::modal', 'myModal')">
          Отмена
        </b-button>
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
  asyncData({app}) {
    return app.$axios.get('admin/courses').then((res) => {
      return {courses: res.data.rows}
    })
  },
  data() {
    return {
      loading: false,
      price: {},
      courses: [],
      record: {
        user_id: null,
        course_id: null,
        // service: 'internal',
        period: 3,
        // cost: null,
        // discount: 0,
        // status: 1,
      },
    }
  },
  methods: {
    onHidden() {
      this.$router.push({name: 'admin-orders'})
    },
    onChange(val) {
      this.courses.forEach((v) => {
        if (v.id === val) {
          this.price = v.price
        }
      })
    },
    onSubmit() {
      this.loading = true
      this.$axios
        .put('admin/orders', this.record)
        .then((res) => {
          this.loading = false
          this.$root.$emit('bv::hide::modal', 'myModal')
          this.$root.$emit('app::admin-orders::update', res.data)
        })
        .catch(() => {
          this.loading = false
        })
    },
  },
}
</script>
