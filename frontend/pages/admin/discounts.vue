<template>
  <div>
    <table-filter :filters="filters" :table="$options.name">
      <template slot="actions">
        <nuxt-link class="btn btn-secondary" :to="{name: 'admin-discounts-create'}">
          <fa :icon="['fas', 'plus']"/>
          Добавить
        </nuxt-link>
      </template>
    </table-filter>

    <b-table
      stacked="md"
      class="mt-3"
      :id="$options.name"
      :items="items"
      :fields="fields"
      :current-page="page"
      :per-page="limit"
      :sort-by.sync="sort"
      :sort-direction.sync="dir"
      :sort-desc="dir == 'desc'"
      :tbody-tr-class="rowClass"
      show-empty
      no-sort-reset
      no-local-sorting
      empty-text="Подходящих результатов не найдено"
      empty-filtered-text="Подходящих результатов не найдено">
      <template slot="cell(discount)" slot-scope="row">
        {{row.value | number}}
        <template v-if="row.item.percent">%</template>
        <template v-else>руб.</template>
      </template>
      <template slot="cell(date)" slot-scope="row">
        <template v-if="row.item.date_start && row.item.date_end">
          c {{row.item.date_start | datetime}}<br>по {{row.item.date_end | datetime}}
        </template>
        <template v-else-if="row.item.date_start">
          c {{row.item.date_start | datetime}}
        </template>
        <template v-else-if="row.item.date_end">
          по {{row.item.date_end | datetime}}
        </template>
        <template v-else>∞</template>
      </template>
      <template slot="cell(actions)" slot-scope="row">
        <nuxt-link class="btn btn-sm" :to="{name: 'admin-discounts-edit-id', params: {id: row.item.id}}">
          <fa :icon="['fas', 'edit']"/>
        </nuxt-link>
        <button class="btn btn-sm text-danger" @click="onDelete(row.item)" v-if="!row.item.used">
          <fa :icon="['fas', 'times']"/>
        </button>
      </template>
    </b-table>

    <table-footer :table="$options.name" :totalRows="totalRows" :limit="limit" :page.sync="page"></table-footer>

    <nuxt-child></nuxt-child>
  </div>
</template>

<script>
    import {faEdit, faPlus, faTimes} from '@fortawesome/pro-solid-svg-icons'

    export default {
        name: 'admin-promos',
        data() {
            return {
                video: false,
                items: (ctx) => {
                    return this.loadTable(ctx, this, 'admin/promos');
                },
                fields: [
                    {key: 'id', label: 'Id', sortable: true},
                    {key: 'code', label: 'Код', sortable: false},
                    {key: 'discount', label: 'Скидка', sortable: false},
                    {key: 'date', label: 'Время действия', sortable: false},
                    {key: 'limit', label: 'Лимит', sortable: false},
                    {key: 'used', label: 'Использован', sortable: true},
                    {key: 'actions', label: 'Действия'},
                ],
                page: 1,
                limit: 20,
                totalRows: 0,
                sort: 'id',
                dir: 'desc',
                filters: {
                    query: '',
                }
            }
        },
        methods: {
            refresh() {
                this.$root.$emit('bv::refresh::table', this.$options.name)
            },
            onEdit(item) {
                console.log(item)
            },
            onDelete(item) {
                this.$message.confirm('Вы уверены, что хотите удалить эту запись?', () => {
                    this.$axios.delete('admin/promos', {params: {id: item.id}})
                        .then(() => {
                            this.refresh();
                        })
                });
            },
            rowClass(item) {
                return item && !item.active ? 'text-muted' : '';
            },
        },
        created() {
            this.$fa.add(faTimes, faEdit, faPlus);

            this.$root.$on('app::' + this.$options.name + '::update', () => {
                this.refresh();
            });

            this.$root.$on('app::' + this.$options.name + '::change', () => {
                this.refresh();
            });

            this.$root.$on('app::' + this.$options.name + '::query', () => {
                this.page = 1;
            });
        },
        head() {
            return {
                title: 'Админка / Промо-коды'
            }
        }
    }
</script>
