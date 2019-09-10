<template>
  <div>
    <table-filter :filters="filters" :table="$options.name">
      <template slot="actions">
        <router-link class="btn btn-secondary" to="courses/create">
          <fa icon="plus"/>
          Добавить
        </router-link>
      </template>
    </table-filter>

    <b-table stacked="md" class="mt-5"
             :id="$options.name"
             :items="items"
             :fields="fields"
             :current-page="page"
             :per-page="limit"
             :sort-by.sync="sort"
             :tbody-tr-class="rowClass"
             show-empty
             no-sort-reset
             no-local-sorting
             empty-text="Подходящих результатов не найдено"
             empty-filtered-text="Подходящих результатов не найдено">
      <template slot="cell(title)" slot-scope="row">
        <strong>{{row.value}}</strong>
      </template>
      <template slot="cell(cover)" slot-scope="row">
        <a :href="row.value" target="_blank" v-if="row.value">
          <img v-if="row.value" :src="[$settings.image_url, row.item.cover_id, '100x50'].join('/')" class="mr-2" width="100"/>
        </a>
      </template>
      <template slot="cell(actions)" slot-scope="row">
        <router-link class="btn btn-sm" :to="'courses/edit/' + row.item.id">
          <fa icon="edit"/>
        </router-link>
        <a href="#" class="btn btn-sm text-danger" @click.prevent="onDelete(row.item)">
          <fa icon="times"/>
        </a>
      </template>
    </b-table>

    <table-footer :table="$options.name" :totalRows="totalRows" :limit="limit" :page.sync="page"></table-footer>

    <nuxt-child></nuxt-child>
  </div>
</template>

<script>
    import {faEdit, faPlus, faSync, faTimes} from '@fortawesome/pro-solid-svg-icons'

    export default {
        name: 'admin-courses',
        data() {
            return {
                items: (ctx) => {
                    return this.loadTable(ctx, this, 'admin/courses');
                },
                fields: [
                    {key: 'id', label: 'Id', sortable: true},
                    {key: 'cover', label: 'Обложка', sortable: false},
                    {key: 'title', label: 'Название', sortable: false},
                    {key: 'lessons_count', label: 'Уроки', sortable: true},
                    {key: 'age', label: 'Возраст', sortable: true},
                    {key: 'category', label: 'Категория', sortable: true},
                    {key: 'actions', label: 'Действия'},
                ],
                page: 1,
                limit: 20,
                totalRows: 0,
                sort: 'id',
                filters: {
                    query: '',
                }
            }
        },

        methods: {
            refresh() {
                this.$root.$emit('bv::refresh::table', this.$options.name)
            },
            onDelete(item) {
                this.$message.confirm('Вы уверены, что хотите удалить эту запись?', () => {
                    this.$axios.delete('admin/courses', {params: {id: item.id}})
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
            this.$fa.add(faSync, faEdit, faPlus, faTimes);

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
                title: 'Админка / Курсы'
            }
        }
    }
</script>
