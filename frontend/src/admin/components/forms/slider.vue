<template>
  <div>
    <b-form-group label="Название:">
      <b-form-input v-model="record.title" />
    </b-form-group>

    <b-form-group label="Таймаут:" description="Количество секунд между слайдами">
      <b-form-input v-model="record.timeout" type="number" />
    </b-form-group>
  </div>
</template>

<script>
export default {
  name: 'FormSlider',
  props: {
    record: {
      type: Object,
      required: true,
    },
  },
}
</script>
