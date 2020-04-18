<template>
  <div>
    <app-table :url="url" :fields="fields" :filters="filters" :sort="sort" :dir="dir" table-class="mt-4">
      <template slot="actions">
        <b-button variant="info" :to="{name: 'courses'}">
          <fa :icon="['fas', 'arrow-left']" /> Управление курсами
        </b-button>
        <a href="https://vimeo.com/manage/videos" target="_blank" rel="noreferrer" class="btn btn-secondary ml-2">
          Перейти на Vimeo
          <fa :icon="['fas', 'external-link']" />
        </a>
      </template>

      <template slot="header">
        <b-alert variant="warning" class="mt-3 mb-0" :show="true">
          Вся работа с видео проводится на <strong>Vimeo</strong>, изменения выгружаются на сайт примерно раз в час.
        </b-alert>
      </template>

      <template v-slot:cell(preview)="row">
        <vimeo :video="row.item.remote_key" style="cursor: pointer">
          <div slot="button" class="btn btn-sm btn-outline-secondary">
            <img :src="row.value[Object.keys(row.value).shift()]" />
          </div>
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
        <a
          :href="`https://vimeo.com/manage/${row.item.remote_key}/general`"
          class="btn btn-sm btn-outline-secondary"
          target="_blank"
          rel="noreferrer"
        >
          <fa :icon="['fas', 'external-link']" />
        </a>
        <b-button v-if="row.item.description" size="sm" @click="row.toggleDetails">
          <fa :icon="['fas', 'align-right']" />
        </b-button>
      </template>
    </app-table>
  </div>
</template>

<script>
import {faAlignRight, faExternalLink, faInfoCircle, faArrowLeft} from '@fortawesome/pro-solid-svg-icons'

export default {
  name: 'AdminVideos',
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
    this.$fa.add(faInfoCircle, faExternalLink, faAlignRight, faArrowLeft)
  },
  head() {
    return {
      title: 'Админка / Видео',
    }
  },
}
</script>
