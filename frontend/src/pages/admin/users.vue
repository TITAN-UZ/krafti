<template>
  <div>
    <app-table ref="table" :url="url" :fields="fields" :filters="filters" :row-class="rowClass" :sort="sort" :dir="dir">
      <template slot="actions">
        <router-link class="btn btn-secondary" :to="{name: 'admin-users-create'}">
          <fa icon="plus" />
          Добавить
        </router-link>
      </template>

      <template v-slot:cell(fullname)="row">
        <user-avatar :user="row.item" :truncate="150" :add="row.item.role.title" :show-name="true" />
      </template>
      <template v-slot:cell(email)="row">
        <div v-if="row.value">{{ row.value.toLowerCase() }}</div>
        <div v-if="row.item.instagram">
          <a :href="`https://www.instagram.com/${row.item.instagram}/`" target="_blank"> @{{ row.item.instagram.toLowerCase() }} </a>
        </div>
      </template>
      <template v-slot:cell(actions)="row">
        <router-link class="btn btn-sm" :to="'users/edit/' + row.item.id">
          <fa icon="edit" />
        </router-link>
        <b-button v-if="row.item.active" size="sm" variant="outline-warning" @click.prevent="onDisable(row.item)">
          <fa :icon="['fas', 'power-off']" />
        </b-button>
        <b-button v-else size="sm" variant="outline-success" @click.prevent="onEnable(row.item)">
          <fa :icon="['fas', 'play']" />
        </b-button>
        <b-button v-if="!row.item.orders_count" size="sm" variant="outline-danger" @click.prevent="onDelete(row.item)">
          <fa :icon="['fas', 'times']" />
        </b-button>
      </template>
    </app-table>

    <nuxt-child />
  </div>
</template>

<script>
import {faEdit, faPlus, faPowerOff, faPlay, faTimes} from '@fortawesome/pro-solid-svg-icons'

export default {
  name: 'AdminUsers',
  data() {
    return {
      url: 'admin/users',
      fields: [
        {key: 'id', label: 'Id', sortable: true},
        {key: 'fullname', label: 'ФИО', sortable: true},
        {key: 'email', label: 'Связь'},
        // {key: 'role.title', label: 'Группа'},
        {key: 'orders_count', label: 'Покупки', sortable: true},
        {key: 'referrals_count', label: 'Рефералы', sortable: true},
        {key: 'actions', label: 'Действия'},
      ],
      filters: {
        query: '',
        role_id: null,
        active: null,
        confirmed: null,
      },
      sort: 'id',
      dir: 'desc',
    }
  },
  created() {
    this.$fa.add(faPlus, faEdit, faPowerOff, faPlay, faTimes)
  },
  methods: {
    refresh() {
      this.$refs.table.refresh()
    },
    rowClass(item) {
      return item && !item.active ? 'text-muted' : ''
    },
    async onDisable(item) {
      try {
        await this.$axios.patch(this.url, {id: item.id, active: false})
        this.refresh()
      } catch (e) {}
    },
    async onEnable(item) {
      try {
        await this.$axios.patch(this.url, {id: item.id, active: true})
        this.refresh()
      } catch (e) {}
    },
    onDelete(item) {
      this.$message.confirm('Вы уверены, что хотите удалить эту запись?', () => {
        this.$axios.delete('admin/users', {params: {id: item.id}}).then(() => {
          this.refresh()
        })
      })
    },
  },
  head() {
    return {
      title: 'Админка / Пользователи',
    }
  },
}
</script>
