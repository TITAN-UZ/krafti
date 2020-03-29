<template>
  <div>
    <table-filter :filters="filters" :table="$options.name" />

    <b-table
      :id="$options.name"
      stacked="md"
      class="mt-3"
      :items="items"
      :fields="fields"
      :current-page="page"
      :per-page="limit"
      :sort-by.sync="sort"
      :sort-direction.sync="dir"
      :sort-desc="dir == 'desc'"
      show-empty
      no-sort-reset
      no-local-sorting
      empty-text="Подходящих результатов не найдено"
      empty-filtered-text="Подходящих результатов не найдено"
    >
      <template slot="cell(user)" slot-scope="row">
        <div class="user-cell">
          <img v-if="row.value.photo_id" :src="[$settings.image_url, row.value.photo_id, '50x50'].join('/')" />
          <span v-else></span>
          {{ row.value.fullname }}
        </div>
      </template>
      <template slot="cell(created_at)" slot-scope="row">
        {{ row.value | datetime }}
      </template>
      <template slot="cell(course)" slot-scope="row">
        <div>{{ row.item.course.title }}</div>
        <div v-if="row.item.lesson" class="small text-muted">{{ row.item.lesson.title }}</div>
        <div v-else class="small text-muted">Домашняя работа этапа {{ row.item.section }}</div>
      </template>
      <template slot="cell(file_id)" slot-scope="row">
        <nuxt-link class="btn btn-sm" :to="{name: 'admin-homeworks-edit-id', params: {id: row.item.id}}">
          <img v-if="row.value" :src="[$settings.image_url, row.value, '100x50'].join('/')" />
        </nuxt-link>
      </template>
      <template slot="row-details" slot-scope="row">
        <blockquote>{{ row.item.comment }}</blockquote>
      </template>
      <template slot="cell(actions)" slot-scope="row">
        <nuxt-link class="btn btn-sm" :to="{name: 'admin-homeworks-edit-id', params: {id: row.item.id}}">
          <fa :icon="['fas', 'edit']" />
        </nuxt-link>
        <button v-if="row.item.comment" class="btn btn-sm" @click="row.toggleDetails">
          <fa :icon="['fas', 'align-right']" />
        </button>
      </template>
    </b-table>

    <table-footer :table="$options.name" :total-rows="totalRows" :limit="limit" :page.sync="page"></table-footer>

    <nuxt-child></nuxt-child>
  </div>
</template>

<script>
import {faAlignRight, faEdit} from '@fortawesome/pro-solid-svg-icons'

export default {
  name: 'AdminHomeworks',
  data() {
    return {
      video: false,
      items: (ctx) => {
        return this.loadTable(ctx, this, 'admin/homeworks')
      },
      fields: [
        {key: 'id', label: 'Id', sortable: true},
        {key: 'user', label: 'Пользователь', sortable: false},
        {key: 'course', label: 'Курс', sortable: false},
        {key: 'file_id', label: 'Изображение', sortable: false},
        {key: 'created_at', label: 'Отправлена', sortable: true},
        {key: 'actions', label: 'Действия'},
      ],
      page: 1,
      limit: 20,
      totalRows: 0,
      sort: 'id',
      dir: 'desc',
      filters: {
        query: '',
        date: null,
        course_id: null,
        work_type: null,
      },
    }
  },
  created() {
    this.$fa.add(faAlignRight, faEdit)

    this.$root.$on('app::' + this.$options.name + '::update', () => {
      this.refresh()
    })

    this.$root.$on('app::' + this.$options.name + '::change', () => {
      this.refresh()
    })

    this.$root.$on('app::' + this.$options.name + '::query', () => {
      this.page = 1
    })
  },
  methods: {
    refresh() {
      this.$root.$emit('bv::refresh::table', this.$options.name)
    },
    onEdit(item) {
      // console.log(item)
    },
  },
  head() {
    return {
      title: 'Админка / Домашние работы',
    }
  },
}
</script>
