<template>
  <div>
    <app-table :url="url" :fields="fields" :filters="filters" :sort="sort" :dir="dir">
      <template slot="actions">
        <a href="https://vimeo.com/manage/videos" target="_blank" rel="noreferrer" class="btn btn-secondary">
          <fa :icon="['fas', 'external-link']" />
          Перейти на Vimeo
        </a>
      </template>

      <template v-slot:cell(preview)="row">
        <vimeo :video="row.item.remote_key" style="cursor: pointer">
          <img slot="button" :src="row.value[Object.keys(row.value).shift()]" class="mr-2" />
        </vimeo>
      </template>
      <template v-slot:cell(title)="row">
        <strong>{{ row.value }}</strong>
      </template>
      <template v-slot:cell(duration)="row">
        {{ row.value | duration }}
      </template>
      <template v-slot:cell(row-details)="row">
        {{ row.item.description }}
      </template>
      <template v-slot:cell(actions)="row">
        <a :href="`https://vimeo.com/manage/${row.item.remote_key}/general`" class="btn btn-sm" target="_blank" rel="noreferrer">
          <fa :icon="['fas', 'external-link']" />
        </a>
        <b-button v-if="row.item.description" size="sm" @click="row.toggleDetails">
          <fa :icon="['fas', 'align-right']" />
        </b-button>
      </template>
    </app-table>

    <b-alert variant="warning" class="mt-3 mb-0" :show="true">
      <strong>Внимание!</strong> Вся работа с видео проводится на
      <a href="https://vimeo.com/manage/videos" target="_blank" rel="noreferrer"><strong>Vimeo</strong></a>
      , изменения выгружаются на сайт примерно раз в час.
    </b-alert>
  </div>
</template>

<script>
import {faAlignRight, faExternalLink, faInfoCircle} from '@fortawesome/pro-solid-svg-icons'
import Vimeo from '../../components/vimeo'

export default {
  name: 'AdminVideos',
  components: {Vimeo},
  data() {
    return {
      url: 'admin/videos',
      fields: [
        {key: 'id', label: 'Id', sortable: true},
        {key: 'preview', label: 'Превью', sortable: false},
        {key: 'title', label: 'Название', sortable: false},
        {key: 'width', label: 'Высота', sortable: true},
        {key: 'height', label: 'Ширина', sortable: true},
        {key: 'duration', label: 'Время', sortable: true},
        {key: 'actions', label: 'Действия'},
      ],
      sort: 'id',
      dir: 'desc',
      filters: {
        query: '',
      },
    }
  },
  created() {
    this.$fa.add(faInfoCircle, faExternalLink, faAlignRight)
  },
  head() {
    return {
      title: 'Админка / Видео',
    }
  },
}
</script>
