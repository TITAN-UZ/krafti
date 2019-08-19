<template>
  <div>
    <b-modal id="myModal" title="Новый пользователь" @hidden="onHidden" ref="modalWindow" hide-footer visible static>
      <b-form ref="modalForm" @submit.prevent="onSubmit">

        <b-form-group label="Логин" :disabled="loading" :label-cols-md="2">
          <b-form-input v-model.trim="record.email" type="email" autofocus required/>
        </b-form-group>

        <b-form-group label="Пароль" :disabled="loading" :label-cols-md="2">
          <b-input-group>
            <b-form-input v-model.trim="record.password" required/>
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

        <b-form-checkbox class="offset-md-2" v-model.number="record.active">Активен</b-form-checkbox>

        <b-row no-gutters class="mt-2 justify-content-between">
          <b-button variant="secondary" @click="$refs.modalWindow.hide()" :disabled="this.loading">Отмена
          </b-button>
          <b-button variant="primary" type="submit" :disabled="this.loading">
            <b-spinner small v-if="loading"/>
            Сохранить
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
                record: {
                    email: '',
                    password: '',
                    fullname: '',
                    instagram: '',
                    dob: '',
                    phone: '',
                    company: '',
                    description: '',
                    active: true,
                    role_id: 3,
                },
            }
        },
        asyncData({app, params}) {
            return app.$axios.get('admin/user-roles')
                .then((res) => {
                    return {roles: res.data.rows}
                }).catch(() => {
                })
        },
        methods: {
            onHidden() {
                this.$router.push({name: 'admin-users'})
            },
            onSubmit() {
                this.loading = true;
                this.$axios.put('admin/users', this.record)
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
    }
</script>
