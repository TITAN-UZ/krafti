<template>
  <b-modal id="myModal" :title="record.title" @hidden="onHidden" hide-footer visible static size="xl">
    <b-tabs>
      <b-tab title="Настройки">
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
            <upload-cover v-model="cover" :label="record.cover"/>
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
            <b-form-textarea id="input-description" no-resize rows="3" v-model="record.description"/>
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
              Сохранить
            </b-button>
          </b-row>
        </b-form>
      </b-tab>
      <b-tab title="Уроки" active>
        <table-filter :filters="filters" :table="$options.name">
          <template slot="actions">
            <router-link class="btn btn-secondary" :to="$route.params.cid + '/create'">
              <fa icon="plus"/>
              Добавить
            </router-link>

              <b-form-select v-model="filters.section" style="width:120px;" class="ml-auto" v-if="filters.section !== undefined">
                <option :value="null">Все этапы</option>
                <option :value="1">Этап 1</option>
                <option :value="2">Этап 2</option>
                <option :value="3">Этап 3</option>
                <option :value="0">Бонус</option>
              </b-form-select>
          </template>
        </table-filter>

        <b-table stacked="md" class="mt-3"
                 :id="$options.name"
                 :items="items"
                 :fields="fields"
                 :current-page="page"
                 :per-page="limit"
                 :sort-by.sync="sort"
                 :tbody-tr-class="rowClass"
                 show-empty
                 no-sort-reset
                 no-local-sorting
                 empty-text="Подходящих результатов не найдено"
                 empty-filtered-text="Подходящих результатов не найдено">
          <template slot="section" slot-scope="row">
            <div v-if="row.value == 0">
              Бонус
            </div>
            <div v-else>
              Этап: <strong>{{row.value}}</strong>
            </div>
          </template>
          <template slot="video" slot-scope="row">
            <a :href="row.value['1920x1080']" target="_blank" v-if="row.value['100x75'] && row.value['1920x1080']">
              <img :src="row.value['100x75']" class="mr-2"/>
            </a>
          </template>
          <template slot="title" slot-scope="row">
            {{row.item.rank + 1}}. <strong>{{row.value}}</strong>
          </template>
          <template slot="actions" slot-scope="row">
            <router-link class="btn btn-sm" :to="$route.params.cid + '/edit/' + row.item.id">
              <fa icon="edit"/>
            </router-link>
            <a class="btn btn-sm text-danger" @click.prevent="onDelete(row.item)">
              <fa icon="times"/>
            </a>
            <a class="btn btn-sm" @click.prevent="moveUp(row.item.id)" v-if="row.item.rank > 0 && filters.section">
              <fa :icon="['fas', 'arrow-up']"/>
            </a>
            <a class="btn btn-sm" @click.prevent="moveDown(row.item.id)" v-if="row.item.rank < (totalRows - 1)  && filters.section">
              <fa :icon="['fas', 'arrow-down']"/>
            </a>
          </template>
        </b-table>

        <table-footer :table="$options.name" :totalRows="totalRows" :limit="limit" :page.sync="page"></table-footer>
      </b-tab>
    </b-tabs>
    <nuxt-child></nuxt-child>
  </b-modal>
</template>

<script>
    import {faArrowDown, faArrowUp} from '@fortawesome/pro-solid-svg-icons'

    export default {
        name: 'admin-lessons',
        validate({params}) {
            return /^\d+$/.test(params.cid)
        },
        data() {
            return {
                loading: false,
                sections: [0, 0, 0],
                items: (ctx) => {
                    this.sections = [0, 0, 0];
                    return this.loadTable(ctx, this, 'admin/lessons');
                },
                fields: [
                    {key: 'section', label: 'Этап', sortable: false},
                    {key: 'video', label: 'Превью', sortable: false},
                    {key: 'title', label: 'Название', sortable: false},
                    {key: 'actions', label: 'Действия'},
                ],
                cover: {},
                page: 1,
                limit: 10,
                totalRows: 0,
                sort: 'rank',
                dir: 'asc',
                filters: {
                    query: '',
                    section: null,
                    course_id: this.$route.params.cid,
                }
            }
        },
        methods: {
            onHidden() {
                this.$router.push({name: 'admin-courses'})
            },
            onSubmit() {
                this.loading = true;
                let record = JSON.parse(JSON.stringify(this.record));
                record.cover = this.cover;
                this.$axios.patch('admin/courses', record)
                    .then(res => {
                        this.loading = false;
                        this.$root.$emit('bv::hide::modal', 'myModal');
                        this.$root.$emit('app::admin-courses::update', res.data);
                    })
                    .catch(() => {
                        this.loading = false;
                    });
            },
            refresh() {
                this.$root.$emit('bv::refresh::table', this.$options.name)
            },
            rowClass(item) {
                return item && !item.active ? 'text-muted' : '';
            },
            moveUp(id) {
                this.$axios.post('admin/lessons', {action: 'move_up', id: id})
                    .then(() => {
                        this.refresh();
                    })
            },
            moveDown(id) {
                this.$axios.post('admin/lessons', {action: 'move_down', id: id})
                    .then(() => {
                        this.refresh();
                    })
            },
            onDelete(item) {
                this.$message.confirm('Вы уверены, что хотите удалить эту запись?', () => {
                    this.$axios.delete('admin/lessons', {params: {id: item.id}})
                        .then(() => {
                            this.refresh();
                        })
                });
            },
        },
        asyncData({app, params}) {
            return app.$axios.get('admin/courses', {params: {id: params.cid}})
                .then((res) => {
                    return {
                        record: res.data,
                    }
                }).catch(() => {
                })
        },
        created() {
            this.$fa.add(faArrowUp, faArrowDown);

            this.$root.$on('app::' + this.$options.name + '::update', () => {
                this.refresh();
            });
        }
    }
</script>
