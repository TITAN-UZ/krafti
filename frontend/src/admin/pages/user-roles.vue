<template lang="html">
  <div>
    <app-table ref="table" :url="url" :fields="fields" :filters="filters">
      <template slot="actions">
        <nuxt-link class="btn btn-secondary" to="user-roles/create"> <fa icon="plus" /> Добавить </nuxt-link>
      </template>

      <template v-slot:cell(title)="row">
        <strong>{{ row.value }}</strong>
      </template>
      <template v-slot:cell(actions)="row">
        <nuxt-link class="btn btn-sm btn-secondary" :to="{name: 'user-roles-edit-id', params: {id: row.item.id}}">
          <fa :icon="['fas', 'edit']" />
        </nuxt-link>
        <b-button size="sm" variant="danger" @click.prevent="onDelete(row.item)">
          <fa :icon="['fas', 'times']" />
        </b-button>
      </template>
    </app-table>

    <nuxt-child />
  </div>
</template>

<script>
import {faEdit, faPlus, faTimes} from '@fortawesome/pro-solid-svg-icons'

export default {
  name: 'AdminUserRoles',
  data() {
    return {
      url: 'admin/user-roles',
      fields: [
        {key: 'id', label: 'Id'},
        {key: 'title', label: 'Название'},
        {key: 'scope', label: 'Разрешения', formatter: this.renderScope},
        {key: 'actions', label: 'Действия'},
      ],
      filters: {
        query: '',
      },
    }
  },
  created() {
    this.$fa.add(faEdit, faPlus, faTimes)
  },
  methods: {
    refresh() {
      this.$refs.table.refresh()
    },
    renderScope(item) {
      return item ? item.join(', ') : ''
    },
    onDelete(item) {
      this.$confirm('Вы уверены, что хотите удалить эту запись?', async () => {
        await this.$axios.delete(this.url, {params: {id: item.id}})
        this.refresh()
      })
    },
  },
  head() {
    return {
      title: 'Админка / Группы пользователей',
    }
  },
}
</script>
