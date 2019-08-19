<template>
  <div>
    <b-modal id="myModal" title="Новая группа" @hidden="onHidden" hide-footer visible static>
      <b-form @submit.prevent="onSubmit">
        <b-form-group
          label="Укажите название группы:"
          label-for="input-title"
          description="Название должно быть уникальным">
          <b-form-input id="input-title" v-model="record.title" required/>
        </b-form-group>

        <b-form-group label="Укажите разрешения:" label-for="input-scope">
          <tags v-model="record.scope" placeholder="" :add-tags-on-comma="true"/>
        </b-form-group>

        <b-row no-gutters class="mt-2 justify-content-between">
          <b-button variant="secondary" @click="$root.$emit('bv::hide::modal', 'myModal')" :disabled="this.loading">
            Отмена
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
                    title: '',
                    scope: [],
                },
            }
        },
        methods: {
            onHidden() {
                this.$router.push({name: 'admin-user-roles'})
            },
            onSubmit() {
                this.loading = true;
                this.$axios.put('admin/user-roles', this.record)
                    .then(res => {
                        this.loading = false;
                        this.$root.$emit('bv::hide::modal', 'myModal');
                        this.$root.$emit('app::admin-user-roles::update', res.data);
                    })
                    .catch(() => {
                        this.loading = false;
                    });
            },
        },
    }
</script>
