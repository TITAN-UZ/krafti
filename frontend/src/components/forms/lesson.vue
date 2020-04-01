<template>
  <div>
    <b-form-group label-cols-lg="3" label-align-lg="right" label="Укажите название:" description="Название должно быть уникальным">
      <b-form-input v-model="record.title" required />
    </b-form-group>

    <b-form-group label-cols-lg="3" label-align-lg="right" label="Описание урока:">
      <b-form-textarea v-model="record.description" rows="3" />
    </b-form-group>

    <b-form-group label-cols-lg="3" label-align-lg="right" label="Этап" description="Номер этапа занятий">
      <b-form-select v-model="record.section" required>
        <option :value="null" disabled selected>Выберите из списка</option>
        <option :value="1">Этап 1</option>
        <option :value="2">Этап 2</option>
        <option :value="3">Этап 3</option>
        <option :value="0">Бонус</option>
      </b-form-select>
    </b-form-group>

    <b-form-group label-cols-lg="3" label-align-lg="right" label="Видеоурок" description="Выберите основное видео курса">
      <pick-video v-model="record.video_id" required />
    </b-form-group>

    <b-form-group label-cols-lg="3" label-align-lg="right" label="Мини-лекция" description="Необязательное бонусное видео курса">
      <pick-video v-model="record.bonus_id" />
    </b-form-group>

    <b-form-group label-cols-lg="3" label-align-lg="right" label="Автор урока" description="Выберите кого-то из авторов или администраторов">
      <pick-user v-model="record.author_id" :role-id="[1, 2]" />
    </b-form-group>

    <b-form-group label-cols-lg="3" label-align-lg="right" label="Что понадобится:" description="Набор товаров в произвольной форме, через запятую">
      <input-tags v-model="record.products" separator="" />
    </b-form-group>

    <b-form-group label-cols-lg="3" label-align-lg="right" label="Материалы урока" description="Загрузите *.zip или *.pdf файл">
      <upload-file v-if="!record.id" v-model="record.file" />
      <upload-file v-else v-model="record.new_file" :label="record.file" />
    </b-form-group>

    <b-form-checkbox v-model="record.active" class="offset-lg-3">Опубликован</b-form-checkbox>
    <b-form-checkbox v-model="record.extra" class="offset-lg-3 mt-2">Дополнительный материал</b-form-checkbox>
    <b-form-checkbox v-model="record.free" class="offset-lg-3 mt-2">Доступен бесплатно</b-form-checkbox>
  </div>
</template>

<script>
export default {
  name: 'FormLesson',
  props: {
    record: {
      type: Object,
      required: true,
    },
  },
}
</script>
