<template>
  <div>
    <app-table ref="table" :url="url" :fields="fields" :filters="filters" :row-class="rowClass">
      <template slot="actions">
        <router-link class="btn btn-secondary" :to="{name: 'admin-courses-create'}">
          <fa icon="plus" /> Добавить
        </router-link>
      </template>

      <template v-slot:cell(title)="row">
        <strong>{{ row.value }}</strong>
      </template>
      <template v-slot:cell(cover)="row">
        <a v-if="row.value" :href="$image(row.value)" target="_blank" rel="noreferrer">
          <img v-if="row.value" :src="$image(row.value, '100x50', 'fit')" alt="" />
        </a>
      </template>
      <template v-slot:cell(actions)="row">
        <router-link class="btn btn-sm" :to="{name: 'admin-courses-edit-cid', params: {cid: row.item.id}}">
          <fa :icon="['fas', 'edit']" />
        </router-link>
        <b-button size="sm" variant="outline-danger" @click.prevent="onDelete(row.item)">
          <fa :icon="['fas', 'times']" />
        </b-button>
      </template>
    </app-table>

    <nuxt-child />
  </div>
</template>

<script>
import {faEdit, faPlus, faSync, faTimes} from '@fortawesome/pro-solid-svg-icons'

export default {
  data() {
    return {
      url: 'admin/courses',
      fields: [
        {key: 'id', label: 'Id', sortable: true},
        {key: 'cover', label: 'Обложка', sortable: false},
        {key: 'title', label: 'Название', sortable: false},
        {key: 'lessons_count', label: 'Уроки', sortable: true},
        {key: 'age', label: 'Возраст', sortable: true},
        {key: 'category', label: 'Категория', sortable: true},
        {key: 'actions', label: 'Действия'},
      ],
      filters: {
        query: '',
      },
    }
  },
  created() {
    this.$fa.add(faSync, faEdit, faPlus, faTimes)
  },
  methods: {
    refresh() {
      this.$refs.table.refresh()
    },
    onDelete(item) {
      this.$message.confirm('Вы уверены, что хотите удалить эту запись?', () => {
        this.$axios.delete(this.url, {params: {id: item.id}}).then(() => {
          this.refresh()
        })
      })
    },
    rowClass(item) {
      return item && !item.active ? 'text-muted' : ''
    },
  },
  head() {
    return {
      title: 'Админка / Курсы',
    }
  },
}
</script>
