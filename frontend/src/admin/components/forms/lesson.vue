<template>
  <div>
    <b-row>
      <b-col md="6">
        <b-form-group label="Укажите название:" description="Название должно быть уникальным">
          <b-form-input v-model="record.title" required />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group label="Автор урока" description="Выберите кого-то из авторов или администраторов">
          <pick-user v-model="record.author_id" :filter="{role_id: [1, 2]}" />
        </b-form-group>
      </b-col>
    </b-row>

    <b-form-group label="Описание урока:">
      <b-form-textarea v-model="record.description" rows="3" />
    </b-form-group>

    <b-form-group v-if="record.course.template.course_steps" label="Этап" description="Номер этапа занятий">
      <b-form-select v-model="record.section" required>
        <option :value="null" disabled selected>Выберите из списка</option>
        <option :value="1">Этап 1</option>
        <option :value="2">Этап 2</option>
        <option :value="3">Этап 3</option>
        <option :value="0">Бонус</option>
      </b-form-select>
    </b-form-group>

    <template v-if="record.course.template.lesson_bonus">
      <b-row>
        <b-col md="6">
          <b-form-group label="Видеоурок" description="Выберите основное видео курса">
            <pick-video v-model="record.video_id" :required="true" />
          </b-form-group>
        </b-col>
        <b-col md="6">
          <b-form-group label="Мини-лекция" description="Необязательное бонусное видео">
            <pick-video v-model="record.bonus_id" />
          </b-form-group>
        </b-col>
      </b-row>
    </template>
    <b-form-group v-else label="Видеоурок" description="Выберите основное видео курса">
      <pick-video v-model="record.video_id" :required="true" />
    </b-form-group>

    <b-row>
      <b-col md="8">
        <b-form-group label="Что понадобится:" description="Набор товаров в произвольной форме, через enter">
          <input-tags v-model="record.products" separator="" />
        </b-form-group>
      </b-col>
      <b-col md="4">
        <b-form-group label="Материалы урока" description="Загрузите *.zip или *.pdf файл">
          <upload-file v-model="record.new_file" :label="record.file" />
        </b-form-group>
      </b-col>
    </b-row>

    <b-form-group>
      <b-row>
        <b-form-checkbox v-model="record.active" class="col-md-4">Опубликован</b-form-checkbox>
        <b-form-checkbox v-model="record.extra" class="col-md-4">Дополнительный материал</b-form-checkbox>
        <b-form-checkbox v-model="record.free" class="col-md-4">Доступен бесплатно</b-form-checkbox>
      </b-row>
    </b-form-group>
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
