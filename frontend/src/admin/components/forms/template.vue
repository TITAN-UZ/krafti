<template>
  <div>
    <b-form-group label="Название:">
      <b-form-input v-model="record.title" autofocus required />
    </b-form-group>

    <b-row class="pt-2">
      <b-form-group class="col-6">
        <b-form-checkbox v-model="record.course_palette">Включить палитру</b-form-checkbox>
      </b-form-group>

      <b-form-group class="col-6">
        <b-form-checkbox v-model="record.lesson_bonus">Уроки с лекциями</b-form-checkbox>
      </b-form-group>

      <b-form-group class="col-6">
        <b-form-checkbox v-model="record.course_steps">Разбивка на этапы</b-form-checkbox>
      </b-form-group>

      <b-form-group class="col-6">
        <b-form-checkbox v-model="record.course_homeworks">Домашние работы</b-form-checkbox>
      </b-form-group>

      <b-form-group class="col-6">
        <b-form-checkbox v-model="record.course_bonus">Бонусный урок</b-form-checkbox>
      </b-form-group>
    </b-row>
  </div>
</template>

<script>
export default {
  name: 'FormTemplate',
  props: {
    record: {
      type: Object,
      required: true,
    },
  },
}
</script>
