<template>
    <div>
        <table-filter :filters="filters" :table="$options.name">
            <template slot="actions">
                <router-link class="btn btn-secondary" to="users/create">
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
                 :sort-direction.sync="dir"
                 :sort-desc="dir == 'desc'"
                 :tbody-tr-class="rowClass"
                 show-empty
                 no-sort-reset
                 no-local-sorting
                 empty-text="Подходящих результатов не найдено"
                 empty-filtered-text="Подходящих результатов не найдено">
            <template slot="cell(photo)" slot-scope="row">
                <img v-if="row.value" :src="[$settings.image_url, row.item.photo_id, '50x50'].join('/')" class="mr-2"/>
            </template>
            <template slot="cell(email)" slot-scope="row">
                <strong>{{row.value}}</strong>
            </template>
            <template slot="cell(actions)" slot-scope="row">
                <router-link class="btn btn-sm" :to="'users/edit/' + row.item.id">
                    <fa icon="edit"/>
                </router-link>
                <a href="#" class="btn btn-sm text-danger" @click.prevent="onDelete(row.item)">
                    <fa icon="times"/>
                </a>
            </template>
        </b-table>

        <table-footer :table="$options.name" :totalRows="totalRows" :limit="limit" :page.sync="page"
                      forms="пользователь|пользователя|пользователей"></table-footer>

        <nuxt-child></nuxt-child>
    </div>
</template>

<script>
    import {faEdit, faKey, faPlus, faSync, faTimes} from '@fortawesome/pro-solid-svg-icons'

    export default {
        name: 'admin-users',
        data() {
            return {
                items: (ctx) => {
                    return this.loadTable(ctx, this, 'admin/users');
                },
                loading: false,
                tag: [],
                fields: [
                    {key: 'id', label: 'Id', sortable: true},
                    {key: 'photo', label: 'Фото', sortable: false},
                    {key: 'email', label: 'Email', sortable: true},
                    {key: 'fullname', label: 'ФИО', sortable: true},
                    {key: 'role_id', label: 'Группа', formatter: 'renderRole'},
                    {key: 'referrals_count', label: 'Рефералы', sortable: true},
                    {key: 'actions', label: 'Действия'},
                ],
                page: 1,
                limit: 20,
                totalRows: 0,
                sort: 'id',
                dir: 'desc',
                filters: {
                    query: '',
                    role_id: null,
                    active: null,
                    confirmed: null,
                },
                roles: {},
            }
        },
        methods: {
            refresh() {
                this.$root.$emit('bv::refresh::table', this.$options.name)
            },
            onDelete(item) {
                this.$message.confirm('Вы уверены, что хотите удалить эту запись?', () => {
                    this.$axios.delete('admin/users', {params: {id: item.id}})
                        .then(() => {
                            this.refresh();
                        })
                });
            },
            rowClass(item) {
                return item && !item.active ? 'text-muted' : '';
            },
            renderRole(id) {
                return this.roles[id]
                    ? this.roles[id]['title']
                    : '';
            }
        },
        created() {
            this.$fa.add(faTimes, faPlus, faEdit, faSync, faKey);

            this.$root.$on('app::' + this.$options.name + '::update', () => {
                this.refresh();
            });

            this.$root.$on('app::' + this.$options.name + '::query', () => {
                this.page = 1;
            });

            this.$axios.get('admin/user-roles', {params: {limit: 0}}).then(res => {
                res.data.rows.forEach(v => {
                    this.roles[v.id] = v;
                });
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
    #admin-users {
        td {
            img {
                width: 50px;
                height: 50px;
                border-radius: 50%;
            }
        }
    }
</style>
