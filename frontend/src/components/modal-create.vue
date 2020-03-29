<template>
  <b-modal
    ref="modal"
    :title="title"
    :size="size"
    hide-footer
    :no-close-on-esc="escNoClose"
    visible
    static
    @hidden="onHidden"
  >
    <template v-if="Object.keys($slots).includes('tabs')">
      <b-tabs v-model="tab" content-class="mt-2">
        <b-tab :title="tabTitle">
          <slot name="form">
            <b-form @submit.prevent="onSubmit">
              <slot name="fields" />
              <slot v-if="!commonButtons" name="buttons">
                <b-row no-gutters class="mt-2 justify-content-between">
                  <b-button variant="secondary" :disabled="loading" @click="onHide">
                    Отмена
                  </b-button>
                  <b-button :variant="submitVariant" type="submit" :disabled="loading">
                    <b-spinner v-if="loading" small />
                    Сохранить
                  </b-button>
                </b-row>
              </slot>
              <input v-else type="submit" class="d-none" />
            </b-form>
          </slot>
        </b-tab>
        <slot name="tabs" />
        <slot v-if="commonButtons" name="buttons">
          <b-row no-gutters class="mt-2 justify-content-between">
            <b-button variant="secondary" :disabled="loading" @click="onHide">
              Отмена
            </b-button>
            <b-button :variant="submitVariant" :disabled="loading" @click="onSubmit">
              <b-spinner v-if="loading" small />
              Сохранить
            </b-button>
          </b-row>
        </slot>
      </b-tabs>
    </template>
    <template v-else>
      <slot name="form">
        <b-form @submit.prevent="onSubmit">
          <slot name="fields" />
          <slot name="buttons">
            <b-row no-gutters class="mt-2 justify-content-between">
              <b-button variant="secondary" :disabled="loading" @click="$refs.modal.hide()">
                Отмена
              </b-button>
              <b-button :variant="submitVariant" type="submit" :disabled="loading">
                <b-spinner v-if="loading" small />
                Сохранить
              </b-button>
            </b-row>
          </slot>
        </b-form>
      </slot>
    </template>
  </b-modal>
</template>

<script>
export default {
  name: 'ModalCreate',
  props: {
    url: {
      type: String,
      required: true,
    },
    value: {
      type: Object,
      required: false,
      default() {
        return {}
      },
    },
    name: {
      type: String,
      required: false,
      default() {
        return this.url.split('/').join('-')
      },
    },
    title: {
      type: String,
      required: false,
      default: 'Новая запись',
    },
    size: {
      type: String,
      required: false,
      default: 'md',
    },
    tabTitle: {
      type: String,
      required: false,
      default: 'Основное',
    },
    currentTab: {
      type: Number,
      required: false,
      default: 0,
    },
    beforeSubmit: {
      type: Function,
      required: false,
      default(record) {
        return record
      },
    },
    submitVariant: {
      type: String,
      required: false,
      default: 'primary',
    },
    commonButtons: {
      type: Boolean,
      default: false,
    },
    escNoClose: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      loading: false,
    }
  },
  computed: {
    record: {
      get() {
        return this.value
      },
      set(newValue) {
        this.$emit('input', newValue)
      },
    },
    tab: {
      get() {
        return this.currentTab
      },
      set(newValue) {
        this.$emit('update:currentTab', newValue)
      },
    },
  },
  methods: {
    onHidden() {
      if (this.$listeners.onHidden) {
        this.$listeners.onHidden(this.name)
      } else {
        this.$router.go(-1)
      }
    },
    onHide() {
      this.$refs.modal.hide()
    },
    async onSubmit() {
      try {
        let record = JSON.parse(JSON.stringify(this.record))
        record = this.beforeSubmit(record)
        if (record === false) {
          return
        }
        this.loading = true
        const {data} = await this.$axios.put(this.url, record)
        if (this.$listeners.onSubmitted) {
          this.$listeners.onSubmitted(data)
        } else {
          this.$refs.modal.hide()
        }
        this.$root.$emit(`app::${this.name}::update`, data)
      } catch (err) {
      } finally {
        this.loading = false
      }
    },
  },
}
</script>
