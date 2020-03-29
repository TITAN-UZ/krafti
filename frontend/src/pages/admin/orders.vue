<template>
  <div>
    <app-table ref="table" :url="url" :fields="fields" :filters="filters" :sort="sort" :dir="dir" @onLoad="onLoad">
      <template slot="actions">
        <router-link class="btn btn-secondary" :to="{name: 'admin-orders-create'}">
          <fa icon="plus" /> Добавить
        </router-link>
      </template>

      <template v-slot:cell(user.photo)="row">
        <b-img-lazy v-if="row.value" :src="$image(row.value, '50x50', 'fit')" class="mr-2" />
      </template>
      <!--<template v-slot:cell(created_at)="row">
        {{ row.value | datetime }}
      </template>-->
      <template v-slot:cell(period)="row"> {{ row.value }} мес. </template>
      <template v-slot:cell(cost)="row">
        {{ row.value | number }} руб.
        <div v-if="row.item.discount" class="small text-muted">скидка {{ row.item.discount }} руб.</div>
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
        <template v-if="row.item.status === 1">
          <button class="btn btn-sm text-success" @click.prevent="changeStatus(row.item, 2)">
            <fa :icon="['fas', 'check-circle']" />
          </button>
          <button class="btn btn-sm text-danger" @click.prevent="onDelete(row.item)">
            <fa :icon="['fas', 'times']" />
          </button>
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
import {faPlus, faTimes, faCheckCircle} from '@fortawesome/pro-solid-svg-icons'

export default {
  name: 'AdminOrders',
  data() {
    return {
      url: 'admin/orders',
      fields: [
        {key: 'id', label: 'Id'},
        {key: 'user.photo', label: 'Фото'},
        {key: 'user.fullname', label: 'Пользователь'},
        {key: 'course.title', label: 'Курс'},
        // {key: 'created_at', label: 'Создан', sortable: true},
        {key: 'status', label: 'Статус'},
        {key: 'cost', label: 'Цена'},
        {key: 'period', label: 'Период'},
        {key: 'actions', label: 'Действия'},
      ],
      filters: {
        query: '',
        date: null,
        service: null,
        status: null,
        course_id: null,
      },
      sort: 'id',
      dir: 'desc',
      totalRows: 0,
      totalCost: 0,
    }
  },
  created() {
    this.$fa.add(faPlus, faTimes, faCheckCircle)
  },
  methods: {
    refresh() {
      this.$refs.table.refresh()
    },
    changeStatus(item, status) {
      this.$message.confirm('Этот заказ еще не был оплачен. Вы уверены, что хотите вручную активировать его?', () => {
        this.$axios.patch('admin/orders', {id: item.id, status}).then(() => {
          this.refresh()
        })
      })
    },
    onDelete(item) {
      this.$message.confirm('Вы уверены, что хотите <b>удалить</b> этот заказ?', () => {
        this.$axios.delete('admin/orders', {params: {id: item.id}}).then(() => {
          this.refresh()
        })
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

<style lang="scss">
#admin-orders {
  td {
    img {
      width: 50px;
      height: 50px;
      border-radius: 50%;
    }
  }
}
</style>
