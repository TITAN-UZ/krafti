<template>
  <div>
    <table-filter :filters="filters" :table="$options.name"/>

    <b-table
      stacked="md"
      class="mt-5"
      :id="$options.name"
      :items="items"
      :fields="fields"
      :current-page="page"
      :per-page="limit"
      :sort-by.sync="sort"
      :sort-direction.sync="dir"
      :sort-desc="dir == 'desc'"
      show-empty
      no-sort-reset
      no-local-sorting
      empty-text="Подходящих результатов не найдено"
      empty-filtered-text="Подходящих результатов не найдено">
      <template slot="cell(user.photo_id)" slot-scope="row">
        <img v-if="row.value" :src="[$settings.image_url, row.value, '50x50'].join('/')" class="mr-2"/>
      </template>
      <template slot="cell(created_at)" slot-scope="row">
        {{row.value | datetime}}
      </template>
      <template slot="cell(period)" slot-scope="row">
        {{row.value}} мес.
      </template>
      <template slot="cell(cost)" slot-scope="row">
        {{row.value | number}} руб.
        <div v-if="row.item.discount" class="small text-muted">скидка {{row.item.discount}} руб.</div>
      </template>
      <template slot="cell(status)" slot-scope="row">
        <div v-if="row.value == 1">
          <span>Новый</span>
          <div class="small text-muted" v-if="row.item.created_at">{{row.item.created_at | datetime}}</div>
        </div>
        <div v-else-if="row.value == 2">
          <span class="text-success">Оплачен</span>
          <span class="small" v-if="row.item.paid_till">до {{row.item.paid_till | date}}</span>
          <div class="small" v-if="row.item.paid_at">{{row.item.paid_at | datetime}}</div>
        </div>
      </template>
      <template slot="cell(actions)" slot-scope="row" v-if="row.item.status === 1">
        <button class="btn btn-sm text-success" @click.prevent="changeStatus(row.item, 2)">
          <fa :icon="['fas', 'check-circle']"/>
        </button>
        <button class="btn btn-sm text-danger" @click.prevent="onDelete(row.item)">
          <fa :icon="['fas', 'times']"/>
        </button>
      </template>
    </b-table>

    <table-footer
      :table="$options.name"
      :totalRows="totalRows"
      :totalCost="totalCost"
      :limit="limit"
      :page.sync="page"
      forms="заказ|заказа|заказов"/>
  </div>
</template>

<script>
    import {faTimes, faCheckCircle} from '@fortawesome/pro-solid-svg-icons'

    export default {
        name: 'admin-orders',
        data() {
            return {
                items: (ctx) => {
                    return this.loadTable(ctx, this, 'admin/orders');
                },
                loading: false,
                tag: [],
                fields: [
                    {key: 'id', label: 'Id', sortable: true},
                    {key: 'user.photo_id', label: 'Фото', sortable: false},
                    {key: 'user.fullname', label: 'Пользователь', sortable: true},
                    {key: 'course.title', label: 'Курс'},
                    //{key: 'created_at', label: 'Создан', sortable: true},
                    {key: 'status', label: 'Статус', sortable: true},
                    {key: 'cost', label: 'Цена', sortable: true},
                    {key: 'period', label: 'Период', sortable: true},
                    //{key: 'lesson', label: 'Урок', formatter: this.renderLesson},
                    {key: 'actions', label: 'Действия'},
                ],
                page: 1,
                limit: 20,
                totalRows: 0,
                totalCost: 0,
                sort: 'created_at',
                dir: 'desc',
                filters: {
                    query: '',
                    date: null,
                    service: null,
                    status: null,
                    course_id: null,
                },
                roles: {},
            }
        },
        methods: {
            refresh() {
                this.$root.$emit('bv::refresh::table', this.$options.name)
            },
            /*onDelete(item) {
                this.$axios.patch('admin/comments', {id: item.id, deleted: true})
                    .then(() => {
                        //item.deleted = true
                        this.refresh()
                    })
            },*/
            changeStatus(item, status) {
                this.$message.confirm('Этот заказ еще не был оплачен. Вы уверены, что хотите вручную активировать его?', () => {
                    this.$axios.patch('admin/orders', {id: item.id, status: status})
                        .then(() => {
                            this.refresh()
                        })
                });
            },
            onDelete(item) {
                this.$message.confirm('Вы уверены, что хотите <b>удалить</b> этот заказ?', () => {
                    this.$axios.delete('admin/orders', {params: {id: item.id}})
                        .then(() => {
                            this.refresh()
                        })
                });
            },
        },
        created() {
            this.$fa.add(faTimes, faCheckCircle);

            this.$root.$on('app::' + this.$options.name + '::update', () => {
                this.refresh();
            });

            this.$root.$on('app::' + this.$options.name + '::query', () => {
                this.page = 1;
            });
        },
        head() {
            return {
                title: 'Админка / Пользователи'
            }
        }
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

      .small {
        font-size: 80%;
      }
    }
  }
</style>
