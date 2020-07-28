<template>
  <div>
    <app-table ref="table" :url="url" :fields="fields" :filters="filters" sort="id" dir="asc">
      <template slot="actions">
        <b-button variant="info" :to="{name: 'courses'}">
          <fa :icon="['fas', 'arrow-left']" /> Управление курсами
        </b-button>

        <b-button variant="secondary" class="ml-2" :to="{name: 'courses-templates-create'}">
          <fa :icon="['fas', 'plus']" /> Добавить шаблон
        </b-button>
      </template>

      <template v-slot:cell()="row">
        <span v-if="row.value === true" class="text-success">Да</span>
        <span v-else-if="row.value === false">Нет</span>
        <template v-else>{{ row.value }}</template>
      </template>
      <template v-slot:cell(actions)="row">
        <b-button
          size="sm"
          variant="outline-secondary"
          :to="{name: 'courses-templates-edit-id', params: {id: row.item.id}}"
        >
          <fa :icon="['fas', 'edit']" />
        </b-button>
        <b-button v-if="!row.item.courses_count" size="sm" variant="outline-danger" @click.prevent="onDelete(row.item)">
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
      url: 'admin/templates',
      fields: [
        {key: 'id', label: 'Id', sortable: true},
        {key: 'title', label: 'Название', sortable: true},
        {key: 'course_steps', label: 'Этапы'},
        {key: 'course_palette', label: 'Палитра'},
        {key: 'lesson_bonus', label: 'Лекции'},
        {key: 'course_homeworks', label: 'Домашки'},
        {key: 'course_bonus', label: 'Бонус'},
        // {key: 'courses_count', label: 'Курсы', sortable: true},
        {key: 'actions', label: 'Действия'},
      ],
      filters: {
        query: '',
      },
    }
  },
  methods: {
    onDelete(item) {
      this.$confirm('Вы уверены, что хотите удалить этот шаблон?', async () => {
        await this.$axios.delete(this.url, {params: {id: item.id}})
        this.$refs.table.refresh()
      })
    },
  },
}
</script>
