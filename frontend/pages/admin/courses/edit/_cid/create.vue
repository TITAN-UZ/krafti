<template>
  <b-modal id="myNestedModal" title="Новый урок" @hidden="onHidden" hide-footer visible static size="lg">
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
        label="Описание урока:"
        label-for="input-description">
        <b-form-textarea id="input-description" rows="3" v-model="record.description"/>
      </b-form-group>

      <b-form-group
        label-cols-lg="3"
        label-align-lg="right"
        label="Этап"
        label-for="input-section"
        description="Номер этапа занятий">
        <b-form-select v-model="record.section" required>
          <option :value="null" disabled selected>Выберите из списка</option>
          <option :value="1">Этап 1</option>
          <option :value="2">Этап 2</option>
          <option :value="3">Этап 3</option>
          <option :value="0">Бонус</option>
        </b-form-select>
      </b-form-group>

      <b-form-group
        label-cols-lg="3"
        label-align-lg="right"
        label="Видеоурок"
        label-for="input-video"
        description="Выберите основное видео курса">
        <pick-video v-model="record.video_id" required/>
      </b-form-group>

      <b-form-group
        label-cols-lg="3"
        label-align-lg="right"
        label="Мини-лекция"
        label-for="input-bonus"
        description="Необязательное бонусное видео курса">
        <pick-video v-model="record.bonus_id"/>
      </b-form-group>

      <b-form-group
        label-cols-lg="3"
        label-align-lg="right"
        label="Автор урока"
        label-for="input-author"
        description="Выберите кого-то из авторов или администраторов">
        <pick-user v-model="record.author_id" :role_id="[1,2]"/>
      </b-form-group>

      <b-form-group
        label-cols-lg="3"
        label-align-lg="right"
        label="Что понадобится:"
        label-for="input-scope"
        description="Набор товаров в произвольной форме, через запятую">
        <tags v-model="record.products"/>
      </b-form-group>

      <b-form-group
        label-cols-lg="3"
        label-align-lg="right"
        label="Материалы урока"
        label-for="input-author"
        description="Загрузите *.zip или *.pdf файл">
        <upload-file v-model="file"/>
      </b-form-group>

      <b-form-checkbox class="offset-lg-3" v-model="record.active">Опубликован</b-form-checkbox>
      <b-form-checkbox class="offset-lg-3 mt-2" v-model="record.extra">Дополнительный материал</b-form-checkbox>

      <b-row no-gutters class="mt-4 justify-content-between">
        <b-button variant="secondary" @click="$root.$emit('bv::hide::modal', 'myNestedModal')"
                  :disabled="this.loading">Отмена
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
                    description: '',
                    products: [],
                    video_id: null,
                    bonus_id: null,
                    file: {},
                    author_id: null,
                    active: false,
                    course_id: this.$route.params.cid,
                }
            }
        },
        methods: {
            onHidden() {
                this.$router.push({name: 'admin-courses-edit-cid', params: this.$route.params})
            },
            onSubmit() {
                this.loading = true;
                let record = JSON.parse(JSON.stringify(this.record));
                record.products = record.products.map(v => {
                    return v.value
                });
                if (this.file) {
                    record.file = this.file;
                }
                this.$axios.put('admin/lessons', record)
                    .then(res => {
                        this.loading = false;
                        this.$root.$emit('app::admin-lessons::update', res.data);
                        this.$router.replace({name: 'admin-courses-edit-cid', params: this.$route.params});
                    })
                    .catch(() => {
                        this.loading = false;
                    });
            },
        },
    }
</script>
