<template>
  <div>
    <chart-orders />

    <app-table ref="table" :url="url" :fields="fields" :filters="filters" :sort="sort" :dir="dir" @onLoad="onLoad">
      <template slot="actions">
        <b-button variant="secondary" :to="{name: 'orders-create'}"><fa :icon="['fas', 'plus']" /> Добавить</b-button>
      </template>

      <template v-slot:cell(user)="row">
        <user-avatar :user="row.value" :truncate="200" :show-name="true" />
      </template>
      <template v-slot:cell(period)="row"> {{ row.value }} мес. </template>
      <template v-slot:cell(cost)="row">
        {{ row.value | number }} руб.
        <div
          v-if="row.item.discount && row.item.discount !== '0'"
          v-b-tooltip="row.item.discount_type ? discount_types[row.item.discount_type] : null"
          class="small text-muted"
        >
          - {{ row.item.discount }}
          <template v-if="row.item.discount.slice(-1) !== '%'">руб.</template>
        </div>
      </template>
      <template v-slot:cell(status)="row">
        <div v-if="row.value == 1">
          <span>Новый</span>
          <div v-if="row.item.created_at" class="small text-muted">{{ row.item.created_at | datetime }}</div>
        </div>
        <div v-else-if="row.value == 2">
          <span class="text-success">Оплачен</span>
          <span v-if="row.item.paid_till" class="small">до {{ row.item.paid_till | date }}</span>
          <div v-if="row.item.paid_at" class="small">{{ row.item.paid_at | datetime }}</div>
        </div>
      </template>
      <template v-slot:cell(actions)="row">
        <b-button size="sm" variant="outline-secondary" :to="{name: 'orders-edit-id', params: {id: row.item.id}}">
          <fa :icon="['fas', 'edit']" />
        </b-button>
        <template v-if="row.item.manual || row.item.status === 1">
          <b-button size="sm" variant="outline-danger" @click.prevent="onDelete(row.item)">
            <fa :icon="['fas', 'times']" />
          </b-button>
        </template>
      </template>

      <template slot="pagination-data">
        <b>{{ totalRows | number }}</b> {{ totalRows | noun('заказ|заказа|заказов') }},

        <b>{{ totalCost | number }}</b> {{ totalCost | noun('рубль|рубля|рублей') }}
      </template>
    </app-table>

    <nuxt-child />
  </div>
</template>

<script>
import {faPlus, faTimes, faEdit} from '@fortawesome/pro-solid-svg-icons'
import ChartOrders from '../components/charts/orders'

export default {
  name: 'AdminOrders',
  components: {ChartOrders},
  data() {
    return {
      url: 'admin/orders',
      fields: [
        {key: 'id', label: 'Id'},
        {key: 'user', label: 'Пользователь'},
        {key: 'course.title', label: 'Курс'},
        {key: 'status', label: 'Статус'},
        {key: 'cost', label: 'Цена'},
        {key: 'period', label: 'Срок'},
        {key: 'actions', label: ''},
      ],
      filters: {
        query: '',
        date: null,
        service: null,
        status: null,
        course_id: null,
      },
      discount_types: {
        promo: 'Промокод',
        order: 'Повторный заказ',
        referrer: 'Реферальный код',
      },
      sort: 'id',
      dir: 'desc',
      totalRows: 0,
      totalCost: 0,
    }
  },
  created() {
    this.$fa.add(faPlus, faTimes, faEdit)
  },
  methods: {
    onDelete(item) {
      this.$confirm('Вы уверены, что хотите удалить этот заказ?', async () => {
        await this.$axios.delete(this.url, {params: {id: item.id}})
        this.$refs.table.refresh()
      })
    },
    onLoad(items) {
      this.totalCost = items.total_cost
      this.totalRows = items.total
      return items
    },
  },
  head() {
    return {
      title: 'Админка / Заказы',
    }
  },
}
</script>
