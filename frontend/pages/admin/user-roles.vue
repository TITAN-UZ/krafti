<template lang="html">
    <div>
        <table-filter :filters="filters" :table="$options.name">
            <template slot="actions">
                <router-link class="btn btn-secondary" to="user-roles/create">
                    <fa icon="plus"/> Добавить
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
                 show-empty
                 no-sort-reset
                 no-local-sorting
                 empty-text="Подходящих результатов не найдено"
                 empty-filtered-text="Подходящих результатов не найдено">
            <template slot="cell(title)" slot-scope="row">
                <strong>{{row.value}}</strong>
            </template>
            <template slot="cell(actions)" slot-scope="row">
                <router-link class="btn btn-sm" :to="'user-roles/edit/' + row.item.id">
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
        name: 'admin-user-roles',
        data() {
            return {
                items: (ctx) => {
                    return this.loadTable(ctx, this, 'admin/user-roles');
                },
                fields: [
                    {key: 'id', label: 'Id', sortable: true},
                    {key: 'title', label: 'Название', sortable: true},
                    {key: 'scope', label: 'Разрешения', formatter: 'renderScope'},
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
            renderScope(value) {
                return value
                    ? value.join(', ')
                    : ''
            },
            onDelete(item) {
                this.$message.confirm('Вы уверены, что хотите удалить эту запись?', () => {
                    this.$axios.delete('admin/user-roles', {params: {id: item.id}})
                        .then(() => {
                            this.refresh();
                        })
                });
            }
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
                title: 'Админка / Группы пользователей'
            }
        }
    }
</script>
