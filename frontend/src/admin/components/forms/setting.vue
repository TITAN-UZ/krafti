<template>
  <div>
    <template v-if="record.created_at === null">
      <b-form-group label="Ключ настройки:" description="Латинские буквы, без пробелов и знаков препинания">
        <b-form-input v-model="record.key" autofocus required :disabled="record.created_at !== null" />
      </b-form-group>

      <b-form-group label="Тип настройки:" description="Выберите из списка">
        <b-form-select v-model="record.type" :options="types" />
      </b-form-group>
    </template>

    <b-form-group label="Название:">
      <b-form-input v-model="record.title" />
    </b-form-group>

    <b-form-group label="Значение:">
      <b-form-textarea v-if="record.type === 'text'" v-model="record.value" rows="3" max-rows="10" />
      <b-form-input v-else-if="record.type === 'url'" v-model="record.value" type="url" />
      <b-form-input v-else-if="record.type === 'phone'" v-model="record.value" type="tel" />
      <b-form-input v-else-if="record.type === 'email'" v-model="record.value" type="email" />
      <b-form-input v-else-if="record.type === 'number'" v-model="record.value" type="number" />
      <b-form-input v-else v-model="record.value" type="text" />
    </b-form-group>
  </div>
</template>

<script>
export default {
  name: 'FormSetting',
  props: {
    record: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      types: [
        {value: 'string', text: 'Строка'},
        {value: 'text', text: 'Текст'},
        {value: 'url', text: 'Ссылка'},
        {value: 'phone', text: 'Телефон'},
        {value: 'email', text: 'Почта'},
        {value: 'number', text: 'Число'},
      ],
    }
  },
}
</script>
