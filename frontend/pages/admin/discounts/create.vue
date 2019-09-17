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

      <!--<b-form-group
        label="Название:"
        label-for="input-title"
        description="Внутреннее название промокода">
        <b-form-input id="input-title" v-model="record.title"/>
      </b-form-group>-->

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
        <!--<date-picker id="input-date" v-model="record.date"/>-->
        <div class="row">
          <div class="col-6">
            <vue2-datepicker
              type="datetime"
              format="DD.MM.YY HH:mm:ss"
              lang="ru"
              input-class="form-control"
              v-model="record.date_start"
              placeholder="работает с"
              :value-type="formatDate"
            />
          </div>
          <div class="col-6">
            <vue2-datepicker
              type="datetime"
              format="DD.MM.YY HH:mm:ss"
              lang="ru"
              input-class="form-control"
              v-model="record.date_end"
              placeholder="работает по"
              :value-type="formatDate"
            />
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
    }
</script>
