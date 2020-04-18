<template>
  <div>
    <b-row>
      <b-col md="6">
        <b-form-group label="Укажите название:" description="Название должно быть уникальным">
          <b-form-input v-model="record.title" required />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group label="Выберите шаблон:" description="Шаблон определяет параметры курса на сайте">
          <b-form-select
            v-model="record.template_id"
            :options="templates"
            text-field="title"
            value-field="id"
            required
          />
        </b-form-group>
      </b-col>
    </b-row>

    <b-form-group label="Укажите слоган:">
      <b-form-input v-model="record.tagline" />
    </b-form-group>

    <b-form-group label="Описание курса:">
      <b-form-textarea v-model="record.description" rows="3" />
    </b-form-group>

    <b-row class="align-items-start">
      <b-col md="6">
        <b-form-group label="Обложка" description="Загрузите файл с обложкой">
          <upload-image v-if="!record.id" v-model="record.cover" />
          <upload-image v-else v-model="record.new_cover" :label="record.cover" />
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group label="Видео превью" description="Выберите видео с рекламой курса">
          <pick-video v-model="record.video_id" />
        </b-form-group>
      </b-col>
    </b-row>

    <b-form-group label="Стоимость курса:">
      <div class="d-flex flex-wrap flex-md-nowrap justify-content-md-between">
        <b-form-group description="Цена за 3 месяца" class="flex-grow-1 flex-md-grow-0">
          <b-form-input v-model="record.price['3']" type="number" placeholder="2990" required />
        </b-form-group>
        <b-form-group description="Цена за 6 месяцев" class="flex-grow-1 flex-md-grow-0">
          <b-form-input v-model="record.price['6']" type="number" placeholder="3990" required />
        </b-form-group>
        <b-form-group description="Цена за 1 год" class="flex-grow-1 flex-md-grow-0">
          <b-form-input v-model="record.price['12']" type="number" placeholder="5990" required />
        </b-form-group>
      </div>
    </b-form-group>

    <b-row>
      <b-col md="6">
        <b-form-group label="Категория:">
          <b-form-select v-model="record.category" required>
            <option :value="null" disabled selected>Выберите из списка</option>
            <option value="Рисование">Рисование</option>
            <option value="Спорт">Спорт</option>
            <option value="Музыка">Музыка</option>
            <option value="Кулинария">Кулинария</option>
          </b-form-select>
        </b-form-group>
      </b-col>
      <b-col md="6">
        <b-form-group label="Возраст:" description="Укажите возраст от и до, через дефис">
          <b-form-input v-model="record.age" placeholder="4-8" required />
        </b-form-group>
      </b-col>
    </b-row>

    <b-form-group label="Сертификат" description="Загрузите шаблон сертификата">
      <upload-image v-if="!record.id" v-model="record.diploma" />
      <upload-image v-else v-model="record.new_diploma" :label="record.diploma" />
    </b-form-group>

    <b-form-checkbox v-model="record.active">Опубликован</b-form-checkbox>
  </div>
</template>

<script>
export default {
  name: 'FormCourse',
  props: {
    record: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      templates: [],
    }
  },
  watch: {
    'record.template_id'(val) {
      this.record.template = this.templates.filter((item) => item.id === val)[0]
    },
  },
  async created() {
    const {data: res} = await this.$axios.get('admin/templates', {params: {combo: 1}})
    this.templates = res.rows
  },
}
</script>
