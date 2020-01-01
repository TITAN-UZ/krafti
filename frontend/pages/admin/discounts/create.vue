<template>
  <b-modal id="myModal" title="Новый промокод" @hidden="onHidden" hide-footer visible static>
    <b-form @submit.prevent="onSubmit">

      <b-form-group
        label="Промокод:"
        label-for="input-code"
        description="Код должен быть уникален">
        <b-form-input id="input-code" v-model="record.code" autofocus required/>
      </b-form-group>

      <b-form-group
        label="Скидка:"
        label-for="input-discount"
        description="">
        <b-input-group :append="record.percent ? '%' : 'руб.'" class="mb-2">
          <b-form-input type="number" id="input-discount" v-model="record.discount" required/>
        </b-input-group>
        <b-form-checkbox v-model="record.percent">Скидка в процентах</b-form-checkbox>
      </b-form-group>

      <b-form-group
        label="Курсы:"
        label-for="input-courses"
        description="Выберите курсы, для которых работает этот код. Если не выбрано ничего, то скидка применится ко всем курсам">
        <b-form-checkbox-group v-model="record.courses" :options="courses" stacked/>
      </b-form-group>

      <b-form-group
        label="Количество использований:"
        label-for="input-limit"
        description="">
        <b-form-input type="number" id="input-limit" v-model="record.limit"/>
      </b-form-group>

      <b-form-group
        label="Время действия:"
        label-for="input-date"
        description="С какого и по какое число работает промокод">
        <div class="row">
          <div class="col-6">
            <date-picker format="DD.MM.YY HH:mm:ss" v-model="record.date_start" :range="false"/>
          </div>
          <div class="col-6">
            <date-picker format="DD.MM.YY HH:mm:ss" v-model="record.date_end" :range="false"/>
          </div>
        </div>
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
</template>

<script>
  export default {
    data() {
      const formatDate = {
        value2date: value => {
          return value ? this.$moment(new Date(value), 'DD.MM.YY HH:mm:ss').toDate() : null;
        },
        date2value: date => {
          return date ? this.$moment(date).format('YYYY-MM-DD HH:mm:ss') : null;
        }
      };
      return {
        formatDate,
        loading: false,
        record: {
          code: '',
          discount: null,
          percent: true,
          title: null,
          date_start: null,
          date_end: null,
        },
        networks: [],
      }
    },
    methods: {
      onHidden() {
        this.$router.push({name: 'admin-discounts'})
      },
      onSubmit() {
        this.loading = true;
        this.$axios.put('admin/promos', this.record)
          .then(res => {
            this.loading = false;
            this.$root.$emit('bv::hide::modal', 'myModal');
            this.$root.$emit('app::admin-promos::update', res.data);
          })
          .catch(() => {
            this.loading = false;
          });
      },
    },
    async asyncData({app}) {
      try {
        const {data: courses} = await app.$axios.get('admin/courses', {params: {limit: 0, combo: true}});

        courses.rows.map((v, idx, arr) => {
          arr[idx] = {value: v.id, text: v.title}
        });

        return {
          courses: courses.rows
        }
      } catch (e) {
        error({statusCode: e.statusCode, message: e.data})
      }
    },
  }
</script>
