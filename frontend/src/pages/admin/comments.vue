<template>
  <div>
    <table-filter :filters="filters" :table="$options.name" />

    <b-table
      :id="$options.name"
      stacked="md"
      class="mt-5"
      :items="items"
      :fields="fields"
      :current-page="page"
      :per-page="limit"
      :sort-by.sync="sort"
      :sort-direction.sync="dir"
      :sort-desc="dir == 'desc'"
      :tbody-tr-class="rowClass"
      show-empty
      no-sort-reset
      no-local-sorting
      empty-text="Подходящих результатов не найдено"
      empty-filtered-text="Подходящих результатов не найдено"
    >
      <template slot="cell(user.photo)" slot-scope="row">
        <img v-if="row.value" :src="[$settings.image_url, row.item.user.photo_id, '50x50'].join('/')" class="mr-2" />
      </template>
      <template slot="cell(lesson)" slot-scope="row">
        <span v-html="row.value"></span>
      </template>
      <template slot="cell(created_at)" slot-scope="row">
        {{ row.value | dateago }}
      </template>
      <template slot="cell(actions)" slot-scope="row">
        <button v-if="row.item.deleted === true" class="btn btn-sm text-success" @click.prevent="onRestore(row.item)">
          <fa :icon="['fas', 'check']" />
        </button>
        <button v-else class="btn btn-sm text-danger" @click.prevent="onDelete(row.item)">
          <fa :icon="['fas', 'times']" />
        </button>
      </template>
    </b-table>

    <table-footer
      :table="$options.name"
      :total-rows="totalRows"
      :limit="limit"
      :page.sync="page"
      forms="пользователь|пользователя|пользователей"
    />
  </div>
</template>

<script>
import {faTimes, faCheck} from '@fortawesome/pro-solid-svg-icons'

export default {
  name: 'AdminComments',
  data() {
    return {
      items: (ctx) => {
        return this.loadTable(ctx, this, 'admin/comments')
      },
      loading: false,
      tag: [],
      fields: [
        {key: 'id', label: 'Id', sortable: true},
        {key: 'user.photo', label: 'Фото', sortable: false},
        {key: 'user.fullname', label: 'Пользователь', sortable: true},
        {key: 'text', label: 'Комментарий'},
        {key: 'created_at', label: 'Написано', sortable: true},
        {key: 'lesson', label: 'Урок', formatter: this.renderLesson},
        {key: 'actions', label: 'Действия'},
      ],
      page: 1,
      limit: 20,
      totalRows: 0,
      sort: 'created_at',
      dir: 'desc',
      filters: {
        query: '',
        role_id: null,
        active: null,
        confirmed: null,
      },
      roles: {},
    }
  },
  created() {
    this.$fa.add(faTimes, faCheck)

    this.$root.$on('app::' + this.$options.name + '::update', () => {
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
    onDelete(item) {
      this.$axios.patch('admin/comments', {id: item.id, deleted: true}).then(() => {
        // item.deleted = true
        this.refresh()
      })
    },
    onRestore(item) {
      this.$axios.patch('admin/comments', {id: item.id, deleted: false}).then(() => {
        // item.deleted = false
        this.refresh()
      })
    },
    rowClass(item) {
      // review
      return item && item.deleted ? 'text-muted' : ''
    },
    renderLesson(value, field, item) {
      let title = item.lesson.title + ' / ' + item.course.title
      if (!item.deleted) {
        title =
          '<a href="/courses/' +
          item.course.id +
          '/lesson/' +
          item.lesson.id +
          '#comment-' +
          item.id +
          '" target="_blank" rel="noreferrer">' +
          title +
          '</a>'
        // console.log(title)
      }

      return title
    },
  },
  head() {
    return {
      title: 'Админка / Комментарии',
    }
  },
}
</script>

<style lang="scss">
#admin-comments {
  td {
    img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
    }
  }
}
</style>
