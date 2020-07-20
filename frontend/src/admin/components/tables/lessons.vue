<template>
  <app-table
    ref="table"
    :url="`${url}?course_id=${record.id}`"
    :fields="fields"
    :filters="filters"
    :limit="10"
    :row-class="rowClass"
    name="admin-lessons"
    @onLoad="onLoad"
  >
    <template slot="actions">
      <b-button variant="secondary" :to="{name: 'courses-edit-cid-create', params: {cid: record.id}}">
        <fa :icon="['fas', 'plus']" /> Добавить
      </b-button>

      <b-form-select
        v-if="record.template && record.template.course_steps"
        v-model="filters.section"
        style="width:120px;"
        class="ml-auto"
      >
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
      <b-button
        variant="secondary"
        :to="{name: 'courses-edit-cid-edit-lid', params: {cid: row.item.course_id, lid: row.item.id}}"
      >
        <b-img-lazy :src="row.value[Object.keys(row.value).shift()]" height="75" />
      </b-button>
    </template>
    <template v-slot:cell(title)="row"> {{ row.item.rank + 1 }}. {{ row.value }} </template>
    <template v-slot:cell(actions)="row">
      <div class="text-nowrap text-right">
        <b-button
          size="sm"
          variant="outline-secondary"
          :to="{name: 'courses-edit-cid-edit-lid', params: {cid: row.item.course_id, lid: row.item.id}}"
        >
          <fa :icon="['fas', 'edit']" />
        </b-button>
        <b-button
          v-if="row.item.rank > 0 && showArrows"
          size="sm"
          variant="outline-secondary"
          @click="moveUp(row.item.id)"
        >
          <fa :icon="['fas', 'arrow-up']" />
        </b-button>
        <b-button
          v-if="row.item.rank < totalRows - 1 && showArrows"
          size="sm"
          variant="outline-secondary"
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
import {faArrowDown, faArrowUp, faPlus, faTimes} from '@fortawesome/pro-solid-svg-icons'

export default {
  name: 'TableLessons',
  props: {
    record: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      url: 'admin/lessons',
      sort: 'rank',
      dir: 'asc',
      filters: {
        query: '',
        section: null,
        course_id: this.$route.params.cid,
      },
      totalRows: 0,
    }
  },
  computed: {
    fields() {
      const fields = []
      if (this.record.template && this.record.template.course_steps) {
        fields.push({key: 'section', label: 'Этап'})
      }
      fields.push({key: 'title', label: 'Название'})
      fields.push({key: 'video.preview', label: 'Превью'})
      fields.push({key: 'actions', label: 'Действия'})

      return fields
    },
    showArrows() {
      return this.filters.section || !this.record.template || !this.record.template.course_steps
    },
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
      await this.$axios.post(this.url, {action: 'move_up', id})
      this.refresh()
    },
    async moveDown(id) {
      await this.$axios.post(this.url, {action: 'move_down', id})
      this.refresh()
    },
    onDelete(item) {
      this.$confirm('Вы уверены, что хотите удалить эту запись?', async () => {
        await this.$axios.delete(this.url, {params: {id: item.id}})
        this.refresh()
      })
    },
    onLoad(items) {
      this.totalRows = items.total
      return items
    },
  },
}
</script>
