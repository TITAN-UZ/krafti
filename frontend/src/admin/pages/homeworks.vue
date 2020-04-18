<template>
  <div>
    <app-table ref="table" :url="url" :fields="fields" :filters="filters">
      <template v-slot:cell(user)="row">
        <user-avatar :user="row.value" :truncate="200" :show-name="true" />
      </template>
      <template v-slot:cell(created_at)="row">
        <small>{{ row.value | datetime }}</small>
      </template>
      <template v-slot:cell(course)="row">
        <div>{{ row.item.course.title }}</div>
        <div v-if="row.item.lesson" class="small text-muted">{{ row.item.lesson.title }}</div>
        <div v-else class="small text-muted">Домашняя работа этапа {{ row.item.section }}</div>
      </template>
      <template v-slot:cell(file)="row">
        <b-button size="sm" variant="outline-secondary" :to="{name: 'homeworks-edit-id', params: {id: row.item.id}}">
          <b-img-lazy v-if="row.value" :src="$image(row.value, '200x100', 'fit')" height="50" />
        </b-button>
      </template>

      <template slot="row-details" slot-scope="row">
        <blockquote>{{ row.item.comment }}</blockquote>
      </template>
      <template v-slot:cell(actions)="row">
        <b-button size="sm" variant="outline-secondary" :to="{name: 'homeworks-edit-id', params: {id: row.item.id}}">
          <fa :icon="['fas', 'edit']" />
        </b-button>
        <button v-if="row.item.comment" class="btn btn-sm btn-outline-secondary" @click="row.toggleDetails">
          <fa :icon="['fas', 'align-right']" />
        </button>
      </template>
    </app-table>

    <nuxt-child />
  </div>
</template>

<script>
import {faAlignRight, faEdit} from '@fortawesome/pro-solid-svg-icons'

export default {
  name: 'AdminHomeworks',
  data() {
    return {
      url: 'admin/homeworks',
      fields: [
        // {key: 'id', label: 'Id'},
        {key: 'user', label: 'Пользователь'},
        {key: 'course', label: 'Курс'},
        {key: 'file', label: 'Изображение'},
        {key: 'created_at', label: 'Отправлена'},
        {key: 'actions', label: ''},
      ],
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
  },
  methods: {
    refresh() {
      this.$refs.table.update()
    },
  },
  head() {
    return {
      title: 'Админка / Домашние работы',
    }
  },
}
</script>
