<template>
  <div>
    <b-form-group label="Заголовок:">
      <b-form-input v-model="record.title" />
    </b-form-group>

    <b-form-group label="Изображение слайда:">
      <upload-image v-if="!record.id" v-model="record.image" :max-height="3000" :max-width="6000" />
      <upload-image v-else v-model="record.new_image" :label="record.image" :max-height="3000" :max-width="6000" />
    </b-form-group>

    <b-form-group label="Тип:">
      <b-form-select v-model="record.type" :options="options" />
    </b-form-group>

    <b-form-group v-if="record.type === 'video'" label="Видео файл:" description="Выберите видео из списка">
      <pick-video v-model="record.video_id" :required="true" />
    </b-form-group>
    <b-form-group v-else label="Ссылка" description="Куда отправлять юзера при клике на слайд, можно не заполнять">
      <b-form-input v-model="record.link" />
    </b-form-group>

    <b-form-group label="Текст:" description="Текст на слайде, можно не заполнять">
      <b-form-textarea v-model="record.description" />
    </b-form-group>

    <b-form-group>
      <b-form-checkbox v-model="record.active">Опубликован</b-form-checkbox>
    </b-form-group>
  </div>
</template>

<script>
export default {
  name: 'FormSlide',
  props: {
    record: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      options: [
        {value: 'image', text: 'Картинка'},
        {value: 'video', text: 'Видео'},
      ],
    }
  },
}
</script>
