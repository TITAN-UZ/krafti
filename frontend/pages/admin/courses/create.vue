<template>
  <b-modal id="myModal" title="Новый курс" @hidden="onHidden" size="lg" hide-footer visible static>

    <b-form @submit.prevent="onSubmit">
      <b-form-group
        label-cols-lg="3"
        label-align-lg="right"
        label="Укажите название:"
        label-for="input-title"
        description="Название должно быть уникальным">
        <b-form-input id="input-title" v-model="record.title" required/>
      </b-form-group>

      <b-form-group
        label-cols-lg="3"
        label-align-lg="right"
        label="Укажите слоган:"
        label-for="input-tagline">
        <b-form-input id="input-tagline" v-model="record.tagline"/>
      </b-form-group>

      <b-form-group
        label-cols-lg="3"
        label-align-lg="right"
        label="Обложка"
        label-for="input-cover"
        description="Загрузите файл с обложкой">
        <upload-cover v-model="record.cover"/>
      </b-form-group>

      <b-form-group
        label-cols-lg="3"
        label-align-lg="right"
        label="Видео превью"
        label-for="input-video"
        description="Выберите видео с рекламой курса">
        <pick-video v-model="record.video_id"/>
      </b-form-group>

      <b-form-group
        label-cols-lg="3"
        label-align-lg="right"
        label="Описание курса:"
        label-for="input-description">
        <b-form-textarea id="input-description" rows="3" v-model="record.description"/>
      </b-form-group>

      <b-form-group
        label-cols-lg="3"
        label-align-lg="right"
        label="Стоимость курса:"
        label-for="input-price">
        <div class="d-flex flex-wrap flex-md-nowrap justify-content-md-between">
          <b-form-group description="Цена за 3 месяца" class="flex-grow-1 flex-md-grow-0">
            <b-form-input id="input-price-3" type="number" placeholder="2990" v-model="record.price['3']"
                          required v-mask="'###?#?#'"/>
          </b-form-group>
          <b-form-group description="Цена за 6 месяцев" class="flex-grow-1 flex-md-grow-0">
            <b-form-input id="input-price-6" type="number" placeholder="3990" v-model="record.price['6']"
                          required v-mask="'###?#?#'"/>
          </b-form-group>
          <b-form-group description="Цена за 1 год" class="flex-grow-1 flex-md-grow-0">
            <b-form-input id="input-price-12" type="number" placeholder="5990" v-model="record.price['12']"
                          required v-mask="'###?#?#'"/>
          </b-form-group>
        </div>
      </b-form-group>

      <b-form-group
        label-cols-lg="3"
        label-align-lg="right"
        label="Категория:"
        label-for="input-category">
        <b-form-select v-model="record.category" required>
          <option :value="null" disabled selected>Выберите из списка</option>
          <option value="Рисование">Рисование</option>
          <option value="Спорт">Спорт</option>
          <option value="Музыка">Музыка</option>
          <option value="Кулинария">Кулинария</option>
        </b-form-select>
      </b-form-group>

      <b-form-group
        label-cols-lg="3"
        label-align-lg="right"
        label="Возраст:"
        label-for="input-age"
        description="Укажите возраст от и до, через дефис">
        <b-form-input id="input-age" placeholder="4-8" v-model="record.age" required/>
      </b-form-group>

      <b-form-checkbox class="offset-lg-3" v-model="record.active">Опубликован</b-form-checkbox>

      <b-row no-gutters class="mt-4 justify-content-between">
        <b-button variant="secondary" @click="$root.$emit('bv::hide::modal', 'myModal')" :disabled="this.loading">
          Отмена
        </b-button>
        <b-button variant="primary" type="submit" :disabled="this.loading">
          <b-spinner small v-if="loading"/>
          Создать
        </b-button>
      </b-row>
    </b-form>
  </b-modal>
</template>

<script>
    export default {
        data() {
            return {
                loading: false,
                record: {
                    title: '',
                    tagline: '',
                    description: '',
                    category: '',
                    price: {'3': '', '6': '', '12': ''},
                    price6: '',
                    price12: '',
                    age: '',
                    cover: {},
                    video_id: null,
                    bonus_id: null,
                    active: false,
                },
            }
        },
        methods: {
            onHidden() {
                this.$router.push({name: 'admin-courses'})
            },
            onSubmit() {
                this.loading = true;
                this.$axios.put('admin/courses', this.record)
                    .then(res => {
                        this.loading = false;
                        this.$root.$emit('app::admin-courses::update', res.data);
                        //this.$root.$emit('bv::hide::modal', 'myModal');
                        this.$router.replace('edit/' + res.data.id);
                    })
                    .catch(() => {
                        this.loading = false;
                    });
            },
        },
        created() {
            this.loaded = true;
        }
    }
</script>
