<template>
    <b-row no-gutters class="justify-content-center justify-content-md-start mt-5">

        <b-pagination class="m-0" :total-rows="totalRows" :per-page="limit" :limit="pageLimit" v-model="currentPage"
                      v-if="totalRows > limit"/>

        <b-button class="ml-2 d-none d-md-block" @click.prevent="refresh">
            <fa icon="sync"/>
        </b-button>

        <div class="btn">
          <b>{{totalRows | number}}</b> {{totalRows | noun(forms)}}<span v-if="totalCost">, на сумму <b>{{totalCost | number}}</b> руб.</span>
        </div>

    </b-row>
</template>

<script>
    import {library} from '@fortawesome/fontawesome-svg-core'
    import {faSync} from '@fortawesome/pro-solid-svg-icons'

    library.add(faSync);

    export default {
        name: 'table-footer',
        data() {
            return {
                //page: this.page
            };
        },
        methods: {
            refresh() {
                this.$root.$emit('bv::refresh::table', this.table)
            }
        },
        computed: {
            currentPage: {
                get() {
                    return this.page
                },
                set(newValue) {
                    this.$emit('update:page', newValue);
                }
            }
        },
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
            totalCost: {
                type: Number,
                required: false,
                default: null,
            },
            forms: {
                type: String,
                default: 'запись|записи|записей',
            },
            pageLimit: {
                type: Number,
                default: 7,
            },
        }
    }
</script>
