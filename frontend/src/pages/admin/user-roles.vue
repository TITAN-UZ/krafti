<template lang="html">
  <div>
    <app-table ref="table" :url="url" :fields="fields" :filters="filters">
      <template slot="actions">
        <router-link class="btn btn-secondary" to="user-roles/create"> <fa icon="plus" /> Добавить </router-link>
      </template>

      <template v-slot:cell(title)="row">
        <strong>{{ row.value }}</strong>
      </template>
      <template v-slot:cell(actions)="row">
        <router-link class="btn btn-sm" :to="{name: 'admin-user-roles-edit-id', params: {id: row.item.id}}">
          <fa :icon="['fas', 'edit']" />
        </router-link>
        <b-button size="sm" variant="outline-danger" @click.prevent="onDelete(row.item)">
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
      this.$message.confirm('Вы уверены, что хотите удалить эту запись?', () => {
        this.$axios.delete(this.url, {params: {id: item.id}}).then(() => {
          this.refresh()
        })
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
