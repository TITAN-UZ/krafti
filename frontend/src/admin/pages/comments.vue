<template>
  <div>
    <app-table ref="table" :url="url" :fields="fields" :filters="filters" :row-class="rowClass">
      <template v-slot:cell(user)="row">
        <user-avatar :user="row.value" :truncate="200" :show-name="true" />
      </template>
      <template v-slot:cell(text)="row">
        <div>{{ row.value }}</div>
        <small v-html="renderLesson(row.item)" />
      </template>
      <template v-slot:cell(created_at)="row">
        <small>{{ row.value | datetime }}</small>
      </template>
      <template v-slot:cell(actions)="row">
        <b-button v-if="row.item.deleted" size="sm" variant="outline-success" @click.prevent="onRestore(row.item)">
          <fa :icon="['fas', 'check']" />
        </b-button>
        <b-button v-else size="sm" variant="outline-danger" @click.prevent="onDelete(row.item)">
          <fa :icon="['fas', 'times']" />
        </b-button>
      </template>
    </app-table>

    <nuxt-child />
  </div>
</template>

<script>
export default {
  name: 'AdminComments',
  data() {
    return {
      url: 'admin/comments',
      fields: [
        // {key: 'id', label: 'Id', sortable: true},
        {key: 'user', label: 'Пользователь'},
        {key: 'text', label: 'Комментарий'},
        {key: 'created_at', label: 'Написано'},
        {key: 'actions', label: ''},
      ],
      filters: {
        query: '',
      },
    }
  },
  methods: {
    onDelete(item) {
      this.$axios.patch('admin/comments', {id: item.id, deleted: true}).then(() => {
        this.$refs.table.refresh()
      })
    },
    onRestore(item) {
      this.$axios.patch('admin/comments', {id: item.id, deleted: false}).then(() => {
        this.$refs.table.refresh()
      })
    },
    rowClass(item) {
      return item && item.deleted ? 'text-muted' : ''
    },
    renderLesson(item) {
      if (!item) {
        return
      }
      let title = item.lesson.title + ' / ' + item.lesson.course.title
      if (!item.deleted) {
        title = `<a href="/courses/${item.lesson.course.id}/lesson/${item.lesson.id}#comment-${item.id}" target="_blank" rel="noreferrer">${title}</a>`
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
