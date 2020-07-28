<template>
  <div>
    <app-table ref="table" :url="url" :fields="fields" :filters="filters" :sort="sort" :dir="dir" :row-class="rowClass">
      <template slot="actions">
        <b-button variant="secondary" :to="{name: 'discounts-create'}">
          <fa :icon="['fas', 'plus']" />
          Добавить
        </b-button>
      </template>

      <template v-slot:cell(discount)="row">
        {{ row.value | number }}
        <template v-if="row.item.percent">%</template>
        <template v-else>руб.</template>
      </template>
      <template v-slot:cell(orders_count)="row">
        <div class="text-nowrap">
          <span v-b-tooltip="'Выполненные заказы'">{{ row.value | number }}</span>
          <sup v-if="row.item.used" v-b-tooltip="'Попытки'" class="text-muted">({{ row.item.used | number }})</sup>
        </div>
      </template>
      <template v-slot:cell(orders_cost)="row">
        <div class="text-nowrap">{{ row.value | number }} руб.</div>
      </template>
      <template v-slot:cell(date)="row">
        <template v-if="row.item.date_start && row.item.date_end">
          {{ row.item.date_start | date }} ~ {{ row.item.date_end | date }}
        </template>
        <template v-else-if="row.item.date_start"> c {{ row.item.date_start | date }} </template>
        <template v-else-if="row.item.date_end"> по {{ row.item.date_end | date }} </template>
        <template v-else>∞</template>
      </template>
      <template v-slot:cell(actions)="row">
        <b-button size="sm" variant="outline-secondary" :to="{name: 'discounts-edit-id', params: {id: row.item.id}}">
          <fa :icon="['fas', 'edit']" />
        </b-button>
        <b-button v-if="!row.item.orders_count" size="sm" variant="outline-danger" @click="onDelete(row.item)">
          <fa :icon="['fas', 'times']" />
        </b-button>
      </template>
    </app-table>

    <nuxt-child />
  </div>
</template>

<script>
export default {
  name: 'AdminPromos',
  data() {
    return {
      url: 'admin/promos',
      fields: [
        // {key: 'id', label: 'Id', sortable: true},
        {key: 'code', label: 'Код', sortable: false},
        {key: 'discount', label: 'Скидка', sortable: false},
        {key: 'date', label: 'Время действия', sortable: false},
        {key: 'orders_count', label: 'Заказы', sortable: true},
        {key: 'orders_cost', label: 'Сумма', sortable: true},
        {key: 'limit', label: 'Лимит', sortable: true},
        {key: 'actions', label: 'Действия'},
      ],
      filters: {
        query: '',
      },
      sort: 'id',
      dir: 'desc',
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
      title: 'Админка / Промо-коды',
    }
  },
}
</script>
