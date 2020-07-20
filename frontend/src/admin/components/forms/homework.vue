<template>
  <div>
    <a :href="$image(record.file)" target="_blank" rel="noreferrer">
      <b-img :src="$image(record.file, '500x500', 'fit')" :rounded="true" fluid-grow />
    </a>

    <b-alert variant="info" :show="true" class="mt-3">
      <div v-if="record.course">{{ record.course.title }}</div>
      <div v-if="record.lesson">{{ record.lesson.title }}</div>
    </b-alert>

    <b-form-group class="mt-3" label="Отзыв:" description="Напишите свой отзыв о работе">
      <b-form-textarea v-model="record.comment" rows="5" :required="record.approved" />
    </b-form-group>

    <b-form-checkbox v-model="record.approved" class="mb-3">
      Работа проверена
    </b-form-checkbox>
  </div>
</template>

<script>
export default {
  name: 'FormHomework',
  props: {
    record: {
      type: Object,
      required: true,
    },
  },
}
</script>
