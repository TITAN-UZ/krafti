<template>
  <b-row no-gutters class="b-table-pagination justify-content-center justify-content-md-start mt-5 align-items-center">
    <b-pagination
      v-if="totalRows > limit"
      v-model="currentPage"
      class="m-0"
      :total-rows="totalRows"
      :per-page="limit"
      :limit="pageLimit"
    />

    <b-button class="ml-2" variant="primary" @click.prevent="refresh">
      <b-spinner v-if="busy" small />
      <fa v-else :icon="['fas', 'sync']" />
    </b-button>

    <div class="col-12 col-md-auto mt-2 mt-md-0 ml-md-2 text-center">
      <slot name="pagination-data">
        <b>{{ totalRows | number }}</b> {{ totalRows | noun(forms) }}
      </slot>
    </div>
  </b-row>
</template>

<script>
export default {
  name: 'TableFooter',
  props: {
    table: {
      type: String,
      required: true,
    },
    page: {
      type: Number,
      required: true,
    },
    limit: {
      type: Number,
      required: true,
    },
    totalRows: {
      type: Number,
      required: true,
    },
    forms: {
      type: String,
      default: 'запись|записи|записей',
    },
    pageLimit: {
      type: Number,
      default: 7,
    },
    busy: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
  computed: {
    currentPage: {
      get() {
        return this.page
      },
      set(newValue) {
        this.$emit('update:page', newValue)
      },
    },
  },
  methods: {
    refresh() {
      this.$root.$emit('bv::refresh::table', this.table)
    },
  },
}
</script>
