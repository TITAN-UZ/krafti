<template>
  <div>
    <app-table ref="table" :url="url" :fields="fields" :filters="filters">
      <template slot="actions">
        <b-button variant="info" :to="{name: 'settings'}">
          <fa :icon="['fas', 'arrow-left']" /> Управление настройками
        </b-button>
      </template>

      <template v-slot:cell(actions)="row">
        <b-button
          size="sm"
          variant="outline-secondary"
          :to="{name: 'settings-sliders-edit-id', params: {id: row.item.id}}"
        >
          <fa :icon="['fas', 'edit']" />
        </b-button>
      </template>
    </app-table>

    <nuxt-child />
  </div>
</template>

<script>
import {faArrowLeft, faEdit} from '@fortawesome/pro-solid-svg-icons'

export default {
  data() {
    return {
      url: 'admin/sliders',
      fields: [
        {key: 'id', label: 'Id'},
        {key: 'title', label: 'Название'},
        {key: 'timeout', label: 'Таймаут'},
        {key: 'items_count', label: 'Слайды'},
        {key: 'actions', label: ''},
      ],
      filters: {
        query: '',
      },
    }
  },
  created() {
    this.$fa.add(faArrowLeft, faEdit)
  },
}
</script>
