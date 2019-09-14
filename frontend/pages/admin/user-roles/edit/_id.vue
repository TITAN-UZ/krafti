<template>
  <b-modal id="myModal" :title="record.title" @hidden="onHidden" hide-footer visible static>
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
          Обновить
        </b-button>
      </b-row>
    </b-form>
  </b-modal>
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
                this.$router.push({name: 'admin-user-roles'})
            },
            onSubmit() {
                this.loading = true;
                let record = JSON.parse(JSON.stringify(this.record));
                record.scope = record.scope.map(v => {
                    return v.value;
                });
                this.$axios.patch('admin/user-roles', record)
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
        async asyncData({app, params}) {
            const res = await app.$axios.get('admin/user-roles', {params: {id: params.id}});
            return {record: res.data};
        }/*
        asyncData({app, params}) {
            return app.$axios.get('admin/user-roles', {params: {id: params.id}})
                .then((res) => {
                    res.data.scope = res.data.scope.map(v => {
                        return {key: '', value: v}
                    });

                    return {record: res.data};
                }).catch(() => {
                })
        },*/
    }
</script>
