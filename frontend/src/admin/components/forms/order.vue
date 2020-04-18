<template>
  <div>
    <b-form-group label="Выберите покупателя:" description="Начните набор для появления подсказки">
      <pick-user v-model="record.user_id" required />
    </b-form-group>

    <b-form-group label="Статус заказа:">
      <b-form-select v-model="record.status" :options="statuses" />
    </b-form-group>

    <b-form-group label="Выберите курс:">
      <b-form-select v-model="record.course_id" value-field="id" text-field="title" :options="courses">
        <template slot="first">
          <option :value="null" disabled>--</option>
        </template>
      </b-form-select>
    </b-form-group>

    <b-form-group label="Выберите период и стоимость:">
      <b-form-select v-model="record.period" :disabled="!Object.keys(myPrice).length">
        <option v-for="(cost, period) in myPrice" :key="period" :value="period">
          {{ cost | number }} руб. за {{ period }} {{ period | noun('месяц|месяца|месяцев') }}
        </option>
      </b-form-select>
    </b-form-group>

    <b-form-group label="Курс оплачен по:">
      <input-date v-model="record.paid_till" :disabled="record.course_id === null" />
    </b-form-group>
  </div>
</template>

<script>
export default {
  name: 'FormOrder',
  props: {
    record: {
      type: Object,
      required: true,
    },
    price: {
      type: Object,
      default() {
        return {}
      },
    },
  },
  data() {
    return {
      courses: [],
      statuses: [
        {text: 'Новый', value: 1},
        {text: 'Оплачен', value: 2},
      ],
    }
  },
  computed: {
    myPrice: {
      get() {
        return this.price
      },
      set(newValue) {
        this.$emit('update:price', newValue)
      },
    },
  },
  watch: {
    'record.course_id'(newValue) {
      const filtered = this.courses.filter((item) => item.id === newValue)
      this.myPrice = filtered[0].price
      this.record.period = Object.keys(filtered[0].price)[0]
    },
    'record.period'(newValue) {
      this.record.paid_till = this.$moment()
        .add(newValue, 'month')
        .format('YYYY-MM-DD')
    },
  },
  async created() {
    try {
      const {data: res} = await this.$axios.get('admin/courses', {params: {combo: true}})
      this.courses = res.rows
    } catch (e) {}
  },
}
</script>
