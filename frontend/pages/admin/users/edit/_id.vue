<template>
  <div>
    <b-modal id="myModal" :title="record.fullname" @hidden="onHidden" ref="modalWindow" hide-footer visible static>
      <b-form ref="modalForm" @submit.prevent="onSubmit">
        <b-form-group label="Почта" :disabled="loading" :label-cols-md="2">
          <b-form-input v-model.trim="record.email" type="email" autofocus required/>
        </b-form-group>

        <b-form-group label="Пароль" :disabled="loading" :label-cols-md="2">
          <b-input-group>
            <b-form-input v-model.trim="record.password"/>
            <b-input-group-append>
              <b-button @click.prevent="generatePassword" tabindex="-1">
                <fa icon="key"/>
              </b-button>
            </b-input-group-append>
          </b-input-group>
        </b-form-group>

        <b-form-group label="ФИО" :disabled="loading" :label-cols-md="2">
          <b-form-input v-model.trim="record.fullname" required/>
        </b-form-group>

        <b-form-group label="Группа" :disabled="loading" :label-cols-md="2">
          <b-form-select v-model="record.role_id" :options="roles" value-field="id" text-field="title" required/>
        </b-form-group>

        <b-form-group label="Компания" :disabled="loading" :label-cols-md="2">
          <b-form-input v-model.trim="record.company"/>
        </b-form-group>

        <b-form-group label="О себе" :disabled="loading" :label-cols-md="2">
          <b-textarea v-model.trim="record.description" rows="3" no-resize/>
        </b-form-group>

        <b-form-checkbox v-model="record.active">Активен</b-form-checkbox>

        <b-row no-gutters class="mt-2 justify-content-between">
          <b-button variant="secondary" @click="$refs.modalWindow.hide()" :disabled="this.loading">Отмена
          </b-button>
          <b-button variant="primary" type="submit" :disabled="this.loading">
            <b-spinner small v-if="loading"/>
            Обновить
          </b-button>
        </b-row>

      </b-form>
    </b-modal>
  </div>
</template>

<script>
    export default {
        data() {
            return {
                loading: false,
            }
        },
        validate({params}) {
            return /^\d+$/.test(params.id)
        },
        methods: {
            onHidden() {
                this.$router.push({name: 'admin-users'})
            },
            onSubmit() {
                this.loading = true;
                this.$axios.patch('admin/users', this.record)
                    .then(res => {
                        this.loading = false;
                        this.$refs.modalWindow.hide();
                        this.$root.$emit('app::admin-users::update', res.data);
                    })
                    .catch(() => {
                        this.loading = false;
                    });
            },
            generatePassword(e, length = 8) {
                this.record.password = this.$password(length);
            },
        },
        async asyncData({app, params}) {
            const [record, roles] = await Promise.all([
                app.$axios.get('admin/users', {params: {id: params.id}}),
                app.$axios.get('admin/user-roles'),
            ]);
            record.data.password = '';
            return {
                roles: roles.data.rows,
                record: record.data,
            };
        }
    }
</script>
