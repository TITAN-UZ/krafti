<template>
  <div>
    <b-form-group class="mb-3" label="Имя / Фамилия">
      <b-input v-model="record.fullname" trim required />
    </b-form-group>

    <b-form-group class="mb-3" label="Дата рождения">
      <input-date v-model="record.dob" :required="true" :hide-buttons="true" />
    </b-form-group>

    <b-form-group class="mb-3" label="Адрес электронной почты">
      <b-input v-model="record.email" type="email" trim required autocomplete="new-password" />
    </b-form-group>

    <b-form-group class="mb-3" label="Пароль">
      <b-input v-model="record.password" type="password" trim autocomplete="new-password" />
    </b-form-group>

    <b-form-group class="mb-3" label="Номер телефона">
      <input-phone v-model="record.phone" max-length="13" />
    </b-form-group>

    <b-form-group class="mb-3" label="Аккаунт Instagram">
      <b-input v-model="record.instagram" trim placeholder="@" />
    </b-form-group>

    <!--<b-form-group class="mb-3" label="Компания">
      <b-input v-model="record.company" trim/>
    </b-form-group>

    <b-form-group class="mb-3" label="О себе">
      <b-textarea v-model="record.description" rows="5" trim no-resize/>
    </b-form-group>-->

    <b-form-group
      class="mb-3"
      label="Ваш реферальный код"
      description="Если ваш друг пройдёт по вашей ссылке при регистрации, он получит скидку на первую покупку, а вы - крафтики!"
    >
      <b-input-group>
        <b-form-input :value="record.promo" readonly style="background: transparent" />
        <b-input-group-append>
          <b-button variant="outline-primary" @click="copyLink">
            <fa :icon="['fad', 'copy']" />
          </b-button>
        </b-input-group-append>
      </b-input-group>
    </b-form-group>

    <div v-if="record.referrer.id" class="alert alert-warning">
      Вы зарегистрировались у нас на сайте по ссылке пользователя
      <strong>{{ record.referrer.fullname }}</strong> и являетесь его рефералом.
    </div>
    <b-form-group
      v-else
      class="mb-3"
      label="Код вашего друга"
      description="Если вы зарегистрировались по приглашению друга, но не прошли по его ссылке, то можно указать его реферальный код вручную"
    >
      <b-input v-model="record.referrer_code" trim placeholder="" />
    </b-form-group>

    <div class="children-form mt-5 mb-5">
      <h4 class="text-muted">Ваши дети</h4>
      <small class="text-muted">
        После прохождения курсов мы генерируем дипломы для указанных имён.
      </small>

      <div v-if="record.children.length" class="row mt-3 mb-3 justify-content-center">
        <b-badge
          v-for="(childItem, idx) in record.children"
          :key="idx"
          pill
          variant="primary"
          class="ml-3 mb-2 child-badge"
          @click="deleteChild(idx)"
        >
          <fa v-if="childItem.gender > 0" :icon="['fad', 'female']" class="mr-2" />
          <fa v-else :icon="['fad', 'male']" class="mr-2" />
          {{ childItem.name }}, {{ childItem.dob | years }}
          {{ childItem.dob | years | noun('год|года|лет') }}
          <fa :icon="['fal', 'times']" class="ml-2" />
        </b-badge>
      </div>

      <form v-if="childrenForm" @submit.prevent="onChildSubmit">
        <b-form-group label-cols-md="4" label="Имя" label-align="right">
          <b-form-input v-model="child.name" autofocus :required="true" />
        </b-form-group>
        <b-form-group label-cols-md="4" label="Дата рождения" label-align="right">
          <input-date v-model="child.dob" :hide-buttons="true" :required="true" />
        </b-form-group>
        <b-form-group label-cols-md="4" label="Пол ребенка" label-align="right">
          <b-form-radio-group v-model="child.gender" stacked :required="true">
            <b-form-radio value="0">Мальчик</b-form-radio>
            <b-form-radio value="1">Девочка</b-form-radio>
          </b-form-radio-group>
        </b-form-group>

        <div class="d-flex justify-content-between">
          <b-button variant="default" class="d-flex align-items-center" type="submit">
            <fa :icon="['fad', 'check-circle']" class="mr-2" /> Ок
          </b-button>
          <b-button variant="default" class="d-flex align-items-center" @click="closeChildForm">
            Отмена <fa :icon="['fad', 'times-circle']" class="ml-2" />
          </b-button>
        </div>
      </form>

      <div v-if="record.children.length < childrenMax" class="form-group">
        <b-button v-if="!childrenForm" variant="default" class="d-flex align-items-center" @click="openChildForm">
          <fa :icon="['fad', 'plus-circle']" class="mr-2" />
          <template v-if="!record.children.length">Добавить ребёнка</template>
          <template v-else>Добавить еще одного ребёнка</template>
        </b-button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FormProfile',
  props: {
    value: {
      type: Boolean,
      required: false,
      default: false,
    },
    record: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      childrenMax: process.env.CHILDREN_MAX,
      child: {},
    }
  },
  computed: {
    childrenForm: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
  },
  methods: {
    copyLink() {
      const link = process.env.SITE_URL + 'p/' + this.record.promo
      this.$copyText(link).then(() => {
        this.$notify.success({message: 'Ссылка успешно скопирована в буфер обмена!'})
      })
    },
    openChildForm() {
      this.childrenForm = true
      this.child = {
        name: null,
        gender: null,
        dob: null,
      }
    },
    closeChildForm() {
      this.childrenForm = false
    },
    onChildSubmit() {
      this.record.children.push(this.child)
      this.closeChildForm()
    },
    deleteChild(idx) {
      this.record.children.splice(idx, 1)
    },
  },
}
</script>
