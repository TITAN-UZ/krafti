<template>
  <div>
    <app-table ref="table" :url="url" :fields="fields" :filters="filters" :row-class="rowClass">
      <template slot="actions">
        <nuxt-link class="btn btn-secondary" :to="{name: 'courses-create'}"><fa icon="plus" /> Добавить</nuxt-link>
      </template>

      <template v-slot:cell(title)="row">
        <strong>{{ row.value }}</strong>
      </template>
      <template v-slot:cell(cover)="row">
        <nuxt-link class="btn btn-sm btn-secondary" :to="{name: 'courses-edit-cid', params: {cid: row.item.id}}">
          <b-img-lazy v-if="row.value" :src="$image(row.value, '200x100', 'fit')" height="50" />
        </nuxt-link>
      </template>
      <template v-slot:cell(actions)="row">
        <nuxt-link class="btn btn-sm btn-secondary" :to="{name: 'courses-edit-cid', params: {cid: row.item.id}}">
          <fa :icon="['fas', 'edit']" />
        </nuxt-link>
        <b-button size="sm" variant="danger" @click.prevent="onDelete(row.item)">
          <fa :icon="['fas', 'times']" />
        </b-button>
      </template>
    </app-table>

    <nuxt-child />
  </div>
</template>

<script>
import {faEdit, faPlus, faTimes} from '@fortawesome/pro-solid-svg-icons'

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
    this.$fa.add(faEdit, faPlus, faTimes)
  },
  methods: {
    refresh() {
      this.$refs.table.refresh()
    },
    onDelete(item) {
      this.$confirm('Вы уверены, что хотите удалить эту запись?', async () => {
        await this.$axios.delete(this.url, {params: {id: item.id}})
        this.refresh()
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
