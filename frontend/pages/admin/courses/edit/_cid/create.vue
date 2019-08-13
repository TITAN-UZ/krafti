<template>
  <div>
    <b-modal id="myNestedModal" title="Новый урок" @hidden="onHidden" hide-footer visible static size="lg">
      <b-form @submit.prevent="onSubmit">
        <b-form-group
          label="Укажите название:"
          label-for="input-title"
          description="Название должно быть уникальным">
          <b-form-input id="input-title" v-model="record.title" required/>
        </b-form-group>

        <b-form-group
          label="Описание курса:"
          label-for="input-description">
          <b-form-textarea id="input-description" no-resize rows="3" v-model="record.description"/>
        </b-form-group>

        <b-form-group
          label="Видеоурок"
          label-for="input-video"
          description="Выберите основное видео курса">
          <pick-video v-model="record.video_id"/>
        </b-form-group>

        <b-form-group
          label="Бонусное видео"
          label-for="input-bonus"
          description="Необязательное бонусное видео курса">
          <pick-video v-model="record.bonus_id"/>
        </b-form-group>

        <b-form-group
          label="Автор урока"
          label-for="input-author"
          description="Выберите кого-то из группы авторов">
          <pick-author v-model="record.author_id"/>
        </b-form-group>

        <b-form-checkbox v-model="record.active">Опубликован</b-form-checkbox>

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
  </div>
</template>

<script>
    import PickAuthor from '../../../../../components/pick-author'

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
                    file_id: null,
                    author_id: null,
                    active: false,
                    course_id: this.$route.params.cid,
                }
            }
        },
        components: {
            'pick-author': PickAuthor,
        },
        methods: {
            onHidden() {
                this.$router.push({name: 'admin-courses-edit-cid', params: this.$route.params})
            },
            onSubmit() {
                this.loading = true;
                this.$axios.put('admin/lessons', this.record)
                    .then(res => {
                        this.loading = false;
                        this.$root.$emit('app::lessons::update', res.data);
                        this.$router.replace({name: 'admin-courses-edit-cid', params: this.$route.params});
                    })
                    .catch(() => {
                        this.loading = false;
                    });
            },
        },
        created() {
            //console.log(this.$route)
        }
    }
</script>
