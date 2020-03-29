<template>
  <div>
    <app-table ref="table" :url="url" :fields="fields" :filters="filters" :sort="sort" :dir="dir" :row-class="rowClass">
      <template slot="actions">
        <nuxt-link class="btn btn-secondary" :to="{name: 'admin-discounts-create'}">
          <fa :icon="['fas', 'plus']" />
          Добавить
        </nuxt-link>
      </template>

      <template v-slot:cell(discount)="row">
        {{ row.value | number }}
        <template v-if="row.item.percent">%</template>
        <template v-else>руб.</template>
      </template>
      <template v-slot:cell(date)="row">
        <template v-if="row.item.date_start && row.item.date_end">
          c {{ row.item.date_start | datetime }}<br />по {{ row.item.date_end | datetime }}
        </template>
        <template v-else-if="row.item.date_start"> c {{ row.item.date_start | datetime }} </template>
        <template v-else-if="row.item.date_end"> по {{ row.item.date_end | datetime }} </template>
        <template v-else>∞</template>
      </template>
      <template v-slot:cell(actions)="row">
        <nuxt-link class="btn btn-sm" :to="{name: 'admin-discounts-edit-id', params: {id: row.item.id}}">
          <fa :icon="['fas', 'edit']" />
        </nuxt-link>
        <button v-if="!row.item.used" class="btn btn-sm text-danger" @click="onDelete(row.item)">
          <fa :icon="['fas', 'times']" />
        </button>
      </template>
    </app-table>

    <nuxt-child />
  </div>
</template>

<script>
import {faEdit, faPlus, faTimes} from '@fortawesome/pro-solid-svg-icons'

export default {
  name: 'AdminPromos',
  data() {
    return {
      url: 'admin/promos',
      fields: [
        {key: 'id', label: 'Id', sortable: true},
        {key: 'code', label: 'Код', sortable: false},
        {key: 'discount', label: 'Скидка', sortable: false},
        {key: 'date', label: 'Время действия', sortable: false},
        {key: 'limit', label: 'Лимит', sortable: false},
        {key: 'orders_count', label: 'Заказы', sortable: true},
        {key: 'used', label: 'Использован', sortable: true},
        {key: 'actions', label: 'Действия'},
      ],
      filters: {
        query: '',
      },
      sort: 'id',
      dir: 'desc',
    }
  },
  created() {
    this.$fa.add(faTimes, faEdit, faPlus)
  },
  methods: {
    refresh() {
      this.$refs.table.refresh()
    },
    onEdit(item) {
      // console.log(item)
    },
    onDelete(item) {
      this.$message.confirm('Вы уверены, что хотите удалить эту запись?', () => {
        this.$axios.delete('admin/promos', {params: {id: item.id}}).then(() => {
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
      title: 'Админка / Промо-коды',
    }
  },
}
</script>
