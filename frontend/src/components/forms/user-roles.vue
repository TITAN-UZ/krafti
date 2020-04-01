<template>
  <div>
    <b-form-group label="Укажите название группы:" description="Название должно быть уникальным">
      <b-form-input id="input-title" v-model="record.title" required />
    </b-form-group>

    <b-form-group label="Укажите разрешения:">
      <input-tags v-model="record.scope" />
    </b-form-group>
  </div>
</template>

<script>
export default {
  name: 'FormUserRole',
  props: {
    record: {
      type: Object,
      required: true,
    },
  },
}
</script>
