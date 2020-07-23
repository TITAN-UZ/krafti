<template>
  <div>
    <app-table
      v-if="['settings', 'settings-create', 'settings-edit-key'].includes($route.name)"
      ref="table"
      :url="url"
      :fields="fields"
      :filters="filters"
      sort="key"
      dir="asc"
    >
      <template slot="actions">
        <!--<b-button variant="secondary" :to="{name: 'settings-create'}"><fa :icon="['fas', 'plus']" /> Добавить</b-button>-->
        <b-button variant="info" class="ml-2" :to="{name: 'settings-sliders'}">
          Управление слайдами <fa :icon="['fas', 'arrow-right']" />
        </b-button>
      </template>

      <template v-slot:cell(value)="row">
        {{ row.value | truncate(100) }}
      </template>
      <template v-slot:cell(actions)="row">
        <b-button size="sm" variant="outline-secondary" :to="{name: 'settings-edit-key', params: {key: row.item.key}}">
          <fa :icon="['fas', 'edit']" />
        </b-button>
        <!--<b-button size="sm" variant="outline-danger" @click.prevent="onDelete(row.item)">
          <fa :icon="['fas', 'times']" />
        </b-button>-->
      </template>
    </app-table>

    <nuxt-child />
  </div>
</template>

<script>
import {faPlus, faEdit, faTimes, faArrowRight} from '@fortawesome/pro-solid-svg-icons'

export default {
  data() {
    return {
      url: 'admin/settings',
      fields: [
        {key: 'key', label: 'Ключ', sortable: true},
        // {key: 'type', label: 'Тип', sortable: true},
        {key: 'title', label: 'Название'},
        {key: 'value', label: 'Значение'},
        // {key: 'created_at', label: 'Дата создания', sortable: true, formatter: this.$options.filters.datetime},
        {key: 'updated_at', label: 'Изменено', sortable: true, formatter: this.$options.filters.datetime},
        {key: 'actions', label: ''},
      ],
      filters: {
        query: '',
      },
    }
  },
  created() {
    this.$fa.add(faPlus, faEdit, faTimes, faArrowRight)
  },
  /* methods: {
    onDelete(item) {
      this.$confirm('Вы уверены, что хотите удалить эту настройку? Это может повредить работе сайта!', async () => {
        await this.$axios.delete(this.url, {params: {key: item.key}})
        this.$refs.table.refresh()
      })
    },
  }, */
}
</script>
