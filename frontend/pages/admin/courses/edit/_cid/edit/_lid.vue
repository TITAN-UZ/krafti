<template>
  <b-modal id="myNestedModal" :title="record.title" @hidden="onHidden" hide-footer visible static size="lg">
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
        <div v-if="record.file && !upload_new">
          Файл уже загружен. Вы можете <a :href="record.file" class="btn btn-outline-secondary" target="_self">скачать его</a>
          или
          <b-button variant="outline-secondary" @click="upload_new = true">заменить</b-button>
        </div>
        <upload-file v-model="file" v-else/>
      </b-form-group>

      <b-form-checkbox class="offset-lg-3" v-model="record.active">Опубликован</b-form-checkbox>
      <b-form-checkbox class="offset-lg-3 mt-2" v-model="record.extra">Дополнительный материал</b-form-checkbox>

      <b-row no-gutters class="mt-4 justify-content-between">
        <b-button variant="secondary" @click="$root.$emit('bv::hide::modal', 'myNestedModal')"
                  :disabled="this.loading">Отмена
        </b-button>
        <b-button variant="primary" type="submit" :disabled="this.loading">
          <b-spinner small v-if="loading"/>
          Сохранить
        </b-button>
      </b-row>
    </b-form>
  </b-modal>
</template>

<script>
    export default {
        validate({params}) {
            return /^\d+$/.test(params.cid) && /^\d+$/.test(params.lid)
        },
        data() {
            return {
                loading: false,
                upload_new: false,
                file: {},
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
                this.$axios.patch('admin/lessons', record)
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
        asyncData({app, params}) {
            return app.$axios.get('admin/lessons', {params: {id: params.lid}})
                .then((res) => {
                    return {
                        record: res.data,
                    }
                }).catch(() => {
                })
        },
    }
</script>
