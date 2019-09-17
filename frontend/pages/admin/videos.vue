<template>
  <div>
    <table-filter :filters="filters" :table="$options.name">
      <template slot="actions">
        <a href="https://vimeo.com/manage/videos" target="_blank" rel="noreferrer" class="btn btn-secondary">
          <fa :icon="['fas', 'external-link']"/>
          Перейти на Vimeo
        </a>
      </template>
    </table-filter>

    <b-table stacked="md" class="mt-3"
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
      <template slot="cell(preview)" slot-scope="row">
        <vimeo :video="row.item.remote_key" style="cursor: pointer">
          <img :src="row.value[Object.keys(row.value).shift()]" class="mr-2" slot="button"/>
        </vimeo>
      </template>
      <template slot="cell(title)" slot-scope="row">
        <strong>{{row.value}}</strong>
      </template>
      <template slot="cell(duration)" slot-scope="row">
        {{row.value | duration}}
      </template>
      <template slot="cell(row-details)" slot-scope="row">
        {{row.item.description}}
      </template>
      <template slot="cell(actions)" slot-scope="row">
        <a :href="'https://vimeo.com/manage/' + row.item.remote_key + '/general'" class="btn btn-sm" target="_blank" rel="noreferrer">
          <fa :icon="['fas', 'external-link']"/>
        </a>

        <button class="btn btn-sm" @click="row.toggleDetails" v-if="row.item.description">
          <fa :icon="['fas', 'align-right']"/>
        </button>
      </template>
    </b-table>

    <b-alert variant="warning" class="mt-3 mb-0" fade show>
      <strong>Внимание!</strong> Вся работа с видео проводится на
      <a href="https://vimeo.com/manage/videos" target="_blank" rel="noreferrer"><strong>Vimeo</strong></a>.
      Изменения выгружаются на сайт примерно раз в час.
    </b-alert>

    <table-footer :table="$options.name" :totalRows="totalRows" :limit="limit" :page.sync="page"></table-footer>

    <nuxt-child></nuxt-child>
  </div>
</template>

<script>
    import {faAlignRight, faExternalLink, faInfoCircle} from '@fortawesome/pro-solid-svg-icons'
    import Vimeo from '../../components/vimeo'

    export default {
        name: 'admin-videos',
        data() {
            return {
                video: false,
                items: (ctx) => {
                    return this.loadTable(ctx, this, 'admin/videos');
                },
                fields: [
                    {key: 'id', label: 'Id', sortable: true},
                    {key: 'preview', label: 'Превью', sortable: false},
                    {key: 'title', label: 'Название', sortable: false},
                    {key: 'width', label: 'Высота', sortable: true},
                    {key: 'height', label: 'Ширина', sortable: true},
                    {key: 'duration', label: 'Время', sortable: true},
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
        components: {Vimeo},
        methods: {
            refresh() {
                this.$root.$emit('bv::refresh::table', this.$options.name)
            },
            renderScope(value) {
                return value.join(', ');
            },
            /*onDelete(item) {
                this.$message.confirm('Вы уверены, что хотите удалить эту запись?', () => {
                    this.$axios.delete('admin/videos', {params: {id: item.id}})
                        .then(() => {
                            this.refresh();
                        })
                });
            },*/
        },
        created() {
            this.$fa.add(faInfoCircle, faExternalLink, faAlignRight);

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
                title: 'Админка / Видео'
            }
        }
    }
</script>
