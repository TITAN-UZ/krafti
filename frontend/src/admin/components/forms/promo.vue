<template>
  <div>
    <b-form-group label="Промокод:" description="Код должен быть уникален">
      <b-form-input v-model="record.code" autofocus required />
    </b-form-group>

    <b-form-group label="Скидка:">
      <b-input-group :append="record.percent ? '%' : 'руб.'" class="mb-2">
        <b-form-input v-model="record.discount" type="number" required />
      </b-input-group>
      <b-form-checkbox v-model="record.percent">Скидка в процентах</b-form-checkbox>
    </b-form-group>

    <b-form-group label="Курсы:" description="Если курсы не выбраны, скидка применится ко всем курсам">
      <b-form-checkbox-group v-model="record.courses" :options="courses" stacked />
    </b-form-group>

    <b-form-group label="Количество использований:">
      <b-form-input v-model="record.limit" type="number" />
    </b-form-group>

    <b-form-group label="Время действия:" description="С какого и по какое число работает промокод">
      <div class="row">
        <div class="col-6">
          <input-date v-model="record.date_start" type="datetime" />
        </div>
        <div class="col-6">
          <input-date v-model="record.date_end" type="datetime" />
        </div>
      </div>
    </b-form-group>
  </div>
</template>

<script>
export default {
  name: 'FormPromo',
  props: {
    record: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      courses: [],
    }
  },
  async created() {
    const {data: courses} = await this.$axios.get('admin/courses', {params: {limit: 0, combo: true}})
    courses.rows.map((v, idx, arr) => {
      arr[idx] = {value: v.id, text: v.title}
    })
    this.courses = courses.rows
  },
}
</script>
