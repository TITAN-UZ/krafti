<template>
  <b-modal id="myModal" :title="record.title" hide-footer visible static size="xl" @hidden="onHidden">
    <b-tabs>
      <b-tab title="Настройки">
        <b-form @submit.prevent="onSubmit">
          <b-form-group
            label-cols-lg="3"
            label-align-lg="right"
            label="Укажите название:"
            label-for="input-title"
            description="Название должно быть уникальным"
          >
            <b-form-input id="input-title" v-model="record.title" required />
          </b-form-group>

          <b-form-group label-cols-lg="3" label-align-lg="right" label="Укажите слоган:" label-for="input-tagline">
            <b-form-input id="input-tagline" v-model="record.tagline" />
          </b-form-group>

          <b-form-group
            label-cols-lg="3"
            label-align-lg="right"
            label="Обложка"
            label-for="input-cover"
            description="Загрузите файл с обложкой"
          >
            <upload-cover v-model="cover" :label="record.cover" />
          </b-form-group>

          <b-form-group
            label-cols-lg="3"
            label-align-lg="right"
            label="Видео превью"
            label-for="input-video"
            description="Выберите видео с рекламой курса"
          >
            <pick-video v-model="record.video_id" />
          </b-form-group>

          <b-form-group label-cols-lg="3" label-align-lg="right" label="Описание курса:" label-for="input-description">
            <b-form-textarea id="input-description" v-model="record.description" rows="3" />
          </b-form-group>

          <b-form-group label-cols-lg="3" label-align-lg="right" label="Стоимость курса:" label-for="input-price">
            <div class="d-flex flex-wrap flex-md-nowrap justify-content-md-between">
              <b-form-group description="Цена за 3 месяца" class="flex-grow-1 flex-md-grow-0">
                <b-form-input
                  id="input-price-3"
                  v-model="record.price['3']"
                  v-mask="'###?#?#'"
                  type="number"
                  placeholder="2990"
                  required
                />
              </b-form-group>
              <b-form-group description="Цена за 6 месяцев" class="flex-grow-1 flex-md-grow-0">
                <b-form-input
                  id="input-price-6"
                  v-model="record.price['6']"
                  v-mask="'###?#?#'"
                  type="number"
                  placeholder="3990"
                  required
                />
              </b-form-group>
              <b-form-group description="Цена за 1 год" class="flex-grow-1 flex-md-grow-0">
                <b-form-input
                  id="input-price-12"
                  v-model="record.price['12']"
                  v-mask="'###?#?#'"
                  type="number"
                  placeholder="5990"
                  required
                />
              </b-form-group>
            </div>
          </b-form-group>

          <b-form-group label-cols-lg="3" label-align-lg="right" label="Категория:" label-for="input-category">
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
            description="Укажите возраст от и до, через дефис"
          >
            <b-form-input id="input-age" v-model="record.age" placeholder="4-8" required />
          </b-form-group>

          <b-form-group
            label-cols-lg="3"
            label-align-lg="right"
            label="Шаблон диплома"
            label-for="input-cover"
            description="Загрузите файл с шаблоном"
          >
            <upload-cover v-model="diploma" :label="record.diploma" />
          </b-form-group>

          <b-form-checkbox v-model="record.active" class="offset-lg-3">Опубликован</b-form-checkbox>

          <b-row no-gutters class="mt-4 justify-content-between">
            <b-button variant="secondary" :disabled="loading" @click="$root.$emit('bv::hide::modal', 'myModal')">
              Отмена
            </b-button>
            <b-button variant="primary" type="submit" :disabled="loading">
              <b-spinner v-if="loading" small />
              Сохранить
            </b-button>
          </b-row>
        </b-form>
      </b-tab>
      <b-tab title="Уроки" active>
        <table-filter :filters="filters" :table="$options.name">
          <template slot="actions">
            <router-link class="btn btn-secondary" :to="$route.params.cid + '/create'">
              <fa icon="plus" />
              Добавить
            </router-link>

            <b-form-select
              v-if="filters.section !== undefined"
              v-model="filters.section"
              style="width:120px;"
              class="ml-auto"
            >
              <option :value="null">Все этапы</option>
              <option :value="1">Этап 1</option>
              <option :value="2">Этап 2</option>
              <option :value="3">Этап 3</option>
              <option :value="0">Бонус</option>
            </b-form-select>
          </template>
        </table-filter>

        <b-table
          :id="$options.name"
          stacked="md"
          class="mt-3"
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
          empty-filtered-text="Подходящих результатов не найдено"
        >
          <template slot="cell(section)" slot-scope="row">
            <div v-if="row.value == 0">
              Бонус
            </div>
            <div v-else>
              Этап: <strong>{{ row.value }}</strong>
            </div>
          </template>
          <template slot="cell(video)" slot-scope="row">
            <a :href="row.value[Object.keys(row.value).pop()]" target="_blank" rel="noreferrer">
              <img :src="row.value[Object.keys(row.value).shift()]" class="mr-2" />
            </a>
          </template>
          <template slot="cell(title)" slot-scope="row">
            {{ row.item.rank + 1 }}. <strong>{{ row.value }}</strong>
          </template>
          <template slot="cell(actions)" slot-scope="row">
            <router-link class="btn btn-sm" :to="$route.params.cid + '/edit/' + row.item.id">
              <fa icon="edit" />
            </router-link>
            <a class="btn btn-sm text-danger" @click.prevent="onDelete(row.item)">
              <fa icon="times" />
            </a>
            <a v-if="row.item.rank > 0 && filters.section" class="btn btn-sm" @click.prevent="moveUp(row.item.id)">
              <fa :icon="['fas', 'arrow-up']" />
            </a>
            <a
              v-if="row.item.rank < totalRows - 1 && filters.section"
              class="btn btn-sm"
              @click.prevent="moveDown(row.item.id)"
            >
              <fa :icon="['fas', 'arrow-down']" />
            </a>
          </template>
        </b-table>

        <table-footer :table="$options.name" :total-rows="totalRows" :limit="limit" :page.sync="page"></table-footer>
      </b-tab>
      <b-tab title="Галерея">
        <gallery-manager :object-id="record.id" object-name="Course" />
      </b-tab>
    </b-tabs>
    <nuxt-child></nuxt-child>
  </b-modal>
</template>

<script>
import {faArrowDown, faArrowUp} from '@fortawesome/pro-solid-svg-icons'

export default {
  name: 'AdminLessons',
  validate({params}) {
    return /^\d+$/.test(params.cid)
  },
  asyncData({app, params}) {
    return app.$axios
      .get('admin/courses', {params: {id: params.cid}})
      .then((res) => {
        return {
          record: res.data,
        }
      })
      .catch(() => {})
  },
  data() {
    return {
      loading: false,
      sections: [0, 0, 0],
      items: (ctx) => {
        this.sections = [0, 0, 0]
        return this.loadTable(ctx, this, 'admin/lessons')
      },
      fields: [
        {key: 'section', label: 'Этап', sortable: false},
        {key: 'video', label: 'Превью', sortable: false},
        {key: 'title', label: 'Название', sortable: false},
        {key: 'actions', label: 'Действия'},
      ],
      cover: {},
      diploma: {},
      page: 1,
      limit: 10,
      totalRows: 0,
      sort: 'rank',
      dir: 'asc',
      filters: {
        query: '',
        section: null,
        course_id: this.$route.params.cid,
      },
    }
  },
  created() {
    this.$fa.add(faArrowUp, faArrowDown)

    this.$root.$on('app::' + this.$options.name + '::update', () => {
      this.refresh()
    })
  },
  methods: {
    onHidden() {
      this.$router.push({name: 'admin-courses'})
    },
    onSubmit() {
      this.loading = true
      const record = JSON.parse(JSON.stringify(this.record))
      record.cover = this.cover
      record.diploma = this.diploma
      this.$axios
        .patch('admin/courses', record)
        .then((res) => {
          this.loading = false
          this.$root.$emit('bv::hide::modal', 'myModal')
          this.$root.$emit('app::admin-courses::update', res.data)
        })
        .catch(() => {
          this.loading = false
        })
    },
    refresh() {
      this.$root.$emit('bv::refresh::table', this.$options.name)
    },
    rowClass(item) {
      return item && !item.active ? 'text-muted' : ''
    },
    moveUp(id) {
      this.$axios.post('admin/lessons', {action: 'move_up', id}).then(() => {
        this.refresh()
      })
    },
    moveDown(id) {
      this.$axios.post('admin/lessons', {action: 'move_down', id}).then(() => {
        this.refresh()
      })
    },
    onDelete(item) {
      this.$message.confirm('Вы уверены, что хотите удалить эту запись?', () => {
        this.$axios.delete('admin/lessons', {params: {id: item.id}}).then(() => {
          this.refresh()
        })
      })
    },
  },
}
</script>
