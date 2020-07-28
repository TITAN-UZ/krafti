<template>
  <div>
    <app-table
      v-if="!/(templates|videos)/.test($route.name)"
      ref="table"
      :url="url"
      :fields="fields"
      :filters="filters"
      :row-class="rowClass"
    >
      <template slot="actions">
        <b-button variant="secondary" :to="{name: 'courses-create'}">
          <fa :icon="['fas', 'plus']" />
          <span class="d-none d-md-inline">Добавить курс</span>
          <span class="d-md-none">Добавить</span>
        </b-button>
        <b-button variant="info" class="ml-2" :to="{name: 'courses-templates'}">
          <fa :icon="['fas', 'columns']" />
          <span class="d-none d-md-inline">Управление шаблонами</span>
          <span class="d-md-none">Шаблоны</span>
        </b-button>
        <b-button variant="info" class="ml-2" :to="{name: 'courses-videos'}">
          <fa :icon="['fas', 'video']" />
          <span class="d-none d-md-inline">Просмотр видео</span>
          <span class="d-md-none">Видео</span>
        </b-button>
      </template>

      <template v-slot:cell(title)="row">
        <strong>{{ row.value }}</strong>
      </template>
      <template v-slot:cell(cover)="row">
        <b-button size="sm" variant="outline-secondary" :to="{name: 'courses-edit-cid', params: {cid: row.item.id}}">
          <b-img-lazy v-if="row.value" :src="$image(row.value, '200x100', 'fit')" height="50" />
        </b-button>
      </template>
      <template v-slot:cell(actions)="row">
        <b-button size="sm" variant="outline-secondary" :to="{name: 'courses-edit-cid', params: {cid: row.item.id}}">
          <fa :icon="['fas', 'edit']" />
        </b-button>
        <b-button size="sm" variant="outline-danger" @click.prevent="onDelete(row.item)">
          <fa :icon="['fas', 'times']" />
        </b-button>
      </template>
    </app-table>

    <nuxt-child />
  </div>
</template>

<script>
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
  methods: {
    onDelete(item) {
      this.$confirm('Вы уверены, что хотите удалить эту запись?', async () => {
        await this.$axios.delete(this.url, {params: {id: item.id}})
        this.$refs.table.refresh()
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
