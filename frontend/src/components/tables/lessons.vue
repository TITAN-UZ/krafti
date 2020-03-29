<template>
  <app-table
    ref="table"
    :url="`${url}?course_id=${courseId}`"
    :fields="fields"
    :filters="filters"
    :limit="10"
    :row-class="rowClass"
    @onLoad="onLoad"
  >
    <template slot="actions">
      <router-link class="btn btn-secondary" :to="{name: 'admin-courses-edit-cid-create', params: {cid: courseId}}">
        <fa :icon="['fas', 'plus']" /> Добавить
      </router-link>

      <b-form-select v-model="filters.section" style="width:120px;" class="ml-auto">
        <option :value="null">Все этапы</option>
        <option :value="1">Этап 1</option>
        <option :value="2">Этап 2</option>
        <option :value="3">Этап 3</option>
        <option :value="0">Бонус</option>
      </b-form-select>
    </template>

    <template v-slot:cell(section)="row">
      <div v-if="row.value == 0">Бонус</div>
      <div v-else class="text-nowrap">
        Этап: <strong>{{ row.value }}</strong>
      </div>
    </template>
    <template v-slot:cell(video.preview)="row">
      <a :href="row.value[Object.keys(row.value).pop()]" target="_blank" rel="noreferrer">
        <img :src="row.value[Object.keys(row.value).shift()]" class="mr-2" />
      </a>
    </template>
    <template v-slot:cell(title)="row"> {{ row.item.rank + 1 }}. {{ row.value }} </template>
    <template v-slot:cell(actions)="row">
      <div class="text-nowrap text-right">
        <router-link class="btn btn-sm" :to="$route.params.cid + '/edit/' + row.item.id">
          <fa :icon="['fas', 'edit']" />
        </router-link>
        <b-button v-if="row.item.rank > 0 && filters.section" size="sm" variant="default" @click="moveUp(row.item.id)">
          <fa :icon="['fas', 'arrow-up']" />
        </b-button>
        <b-button
          v-if="row.item.rank < totalRows - 1 && filters.section"
          size="sm"
          variant="default"
          @click="moveDown(row.item.id)"
        >
          <fa :icon="['fas', 'arrow-down']" />
        </b-button>
        <b-button size="sm" variant="outline-danger" @click="onDelete(row.item)">
          <fa :icon="['fas', 'times']" />
        </b-button>
      </div>
    </template>
  </app-table>
</template>

<script>
import {faPlus, faArrowUp, faArrowDown, faTimes} from '@fortawesome/pro-solid-svg-icons'

export default {
  name: 'TableLessons',
  props: {
    courseId: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      url: 'admin/lessons',
      sort: 'rank',
      dir: 'asc',
      fields: [
        {key: 'section', label: 'Этап', sortable: false},
        {key: 'video.preview', label: 'Превью', sortable: false},
        {key: 'title', label: 'Название', sortable: false},
        {key: 'actions', label: 'Действия'},
      ],
      filters: {
        query: '',
        section: null,
        course_id: this.$route.params.cid,
      },
      totalRows: 0,
    }
  },
  created() {
    this.$fa.add(faPlus, faArrowUp, faArrowDown, faTimes)
  },
  methods: {
    refresh() {
      this.$refs.table.refresh()
    },
    rowClass(item) {
      if (!item) {
        return ''
      }

      const cls = []
      if (!item.active) {
        cls.push('text-muted')
      }
      if (item.free) {
        cls.push('text-success')
      }

      return cls.join(' ')
    },
    async moveUp(id) {
      await this.$axios.post('admin/lessons', {action: 'move_up', id})
      this.refresh()
    },
    async moveDown(id) {
      await this.$axios.post('admin/lessons', {action: 'move_down', id})
      this.refresh()
    },
    onDelete(item) {
      this.$message.confirm('Вы уверены, что хотите удалить эту запись?', () => {
        this.$axios.delete('admin/lessons', {params: {id: item.id}}).then(() => {
          this.refresh()
        })
      })
    },
    onLoad(items) {
      this.totalRows = items.total
      return items
    },
  },
}
</script>
