<template>
  <div>
    <b-form-group label-cols-lg="3" label-align-lg="right" label="Укажите название:" description="Название должно быть уникальным">
      <b-form-input id="input-title" v-model="record.title" required />
    </b-form-group>

    <b-form-group label-cols-lg="3" label-align-lg="right" label="Укажите слоган:">
      <b-form-input id="input-tagline" v-model="record.tagline" />
    </b-form-group>

    <b-form-group label-cols-lg="3" label-align-lg="right" label="Обложка" description="Загрузите файл с обложкой">
      <upload-image v-if="!record.id" v-model="record.cover" />
      <upload-image v-else v-model="record.new_cover" :label="record.cover" />
    </b-form-group>

    <b-form-group label-cols-lg="3" label-align-lg="right" label="Видео превью" description="Выберите видео с рекламой курса">
      <pick-video v-model="record.video_id" />
    </b-form-group>

    <b-form-group label-cols-lg="3" label-align-lg="right" label="Описание курса:">
      <b-form-textarea id="input-description" v-model="record.description" rows="3" />
    </b-form-group>

    <b-form-group label-cols-lg="3" label-align-lg="right" label="Стоимость курса:">
      <div class="d-flex flex-wrap flex-md-nowrap justify-content-md-between">
        <b-form-group description="Цена за 3 месяца" class="flex-grow-1 flex-md-grow-0">
          <b-form-input id="input-price-3" v-model="record.price['3']" type="number" placeholder="2990" required />
        </b-form-group>
        <b-form-group description="Цена за 6 месяцев" class="flex-grow-1 flex-md-grow-0">
          <b-form-input id="input-price-6" v-model="record.price['6']" type="number" placeholder="3990" required />
        </b-form-group>
        <b-form-group description="Цена за 1 год" class="flex-grow-1 flex-md-grow-0">
          <b-form-input id="input-price-12" v-model="record.price['12']" type="number" placeholder="5990" required />
        </b-form-group>
      </div>
    </b-form-group>

    <b-form-group label-cols-lg="3" label-align-lg="right" label="Категория:">
      <b-form-select v-model="record.category" required>
        <option :value="null" disabled selected>Выберите из списка</option>
        <option value="Рисование">Рисование</option>
        <option value="Спорт">Спорт</option>
        <option value="Музыка">Музыка</option>
        <option value="Кулинария">Кулинария</option>
      </b-form-select>
    </b-form-group>

    <b-form-group label-cols-lg="3" label-align-lg="right" label="Возраст:" description="Укажите возраст от и до, через дефис">
      <b-form-input id="input-age" v-model="record.age" placeholder="4-8" required />
    </b-form-group>

    <b-form-group label-cols-lg="3" label-align-lg="right" label="Сертификат" description="Загрузите шаблон сертификата">
      <upload-image v-if="!record.id" v-model="record.diploma" />
      <upload-image v-else v-model="record.new_diploma" :label="record.diploma" />
    </b-form-group>

    <b-form-checkbox v-model="record.active" class="offset-lg-3">Опубликован</b-form-checkbox>
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
}
</script>
