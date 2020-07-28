<template lang="html">
  <div>
    <app-table ref="table" :url="url" :fields="fields" :filters="filters">
      <template slot="actions">
        <b-button variant="info" :to="{name: 'users'}">
          <fa :icon="['fas', 'arrow-left']" /> Управление пользователями
        </b-button>
        <b-button variant="secondary" class="ml-2" :to="{name: 'users-roles-create'}">
          <fa :icon="['fas', 'plus']" /> Добавить группу
        </b-button>
      </template>

      <template v-slot:cell(title)="row">
        <strong>{{ row.value }}</strong>
      </template>
      <template v-slot:cell(actions)="row">
        <b-button size="sm" variant="outline-secondary" :to="{name: 'users-roles-edit-id', params: {id: row.item.id}}">
          <fa :icon="['fas', 'edit']" />
        </b-button>
        <b-button size="sm" variant="outline-danger" @click.prevent="onDelete(row.item)">
          <fa :icon="['fas', 'times']" />
        </b-button>
      </template>
    </app-table>

    <nuxt-child />
  </div>
</template>

<script>
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
  methods: {
    renderScope(item) {
      return item ? item.join(', ') : ''
    },
    onDelete(item) {
      this.$confirm('Вы уверены, что хотите удалить эту запись?', async () => {
        await this.$axios.delete(this.url, {params: {id: item.id}})
        this.$refs.table.refresh()
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
