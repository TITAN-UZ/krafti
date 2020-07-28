<template>
  <app-table
    ref="table"
    :url="url"
    :fields="fields"
    :filters="filters"
    :row-class="rowClass"
    sort="rank"
    dir="asc"
    @onLoad="onLoad"
  >
    <template slot="actions">
      <b-button variant="secondary" :to="{name: 'settings-sliders-edit-id-create', params: {id: $route.params.id}}">
        <fa :icon="['fas', 'plus']" /> Добавить
      </b-button>
    </template>

    <template v-slot:cell(image)="row">
      <b-button
        variant="outline-secondary"
        :to="{name: 'settings-sliders-edit-id-edit-sid', params: {id: row.item.slider_id, sid: row.item.id}}"
      >
        <b-img-lazy v-if="row.value" :src="$image(row.value, '200x100', 'fit')" height="50" />
      </b-button>
    </template>
    <template v-slot:cell(rank)="row">
      {{ row.value + 1 }}
    </template>
    <template v-slot:cell(actions)="row">
      <div class="text-nowrap text-right">
        <b-button
          size="sm"
          variant="outline-secondary"
          :to="{name: 'settings-sliders-edit-id-edit-sid', params: {id: row.item.slider_id, sid: row.item.id}}"
        >
          <fa :icon="['fas', 'edit']" />
        </b-button>
        <b-button v-if="row.item.rank > 0" size="sm" variant="outline-secondary" @click="moveUp(row.item.id)">
          <fa :icon="['fas', 'arrow-up']" />
        </b-button>
        <b-button
          v-if="row.item.rank < totalRows - 1"
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
export default {
  name: 'TableSlides',
  data() {
    return {
      url: 'admin/slider/items',
      fields: [
        // {key: 'id', label: 'Id'},
        {key: 'rank', label: ''},
        {key: 'image', label: 'Слайд'},
        {key: 'type', label: 'Тип'},
        {key: 'title', label: 'Название'},
        {key: 'actions', label: ''},
      ],
      filters: {
        query: '',
        slider_id: this.$route.params.id,
      },
      totalRows: 0,
    }
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
