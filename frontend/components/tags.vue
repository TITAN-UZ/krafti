<template>
  <vue-tags
    placeholder=""
    v-model="myValue"
    :addTagsOnComma="true"
    :typeahead="true"
    :onlyExistingTags="existingTags.length > 0"
    :existingTags="existingTags"
    @tag-added="tagAdded"></vue-tags>
</template>

<script>
    import VueTags from '@voerro/vue-tagsinput'

    export default {
        data() {
            return {
                loaded: false,
            }
        },
        props: {
            value: {
                type: Array,
                default: () => {
                    return [];
                }
            },
            existingTags: {
                type: Array,
                default: () => {
                    return [];
                }
            },
            /*onlyExistingTags: {
                type: Boolean,
                default: false
            },*/
        },
        components: {
            'vue-tags': VueTags
        },
        methods: {
            tagAdded(e) {
                this.$emit('tag-added', e);
            }
        },
        computed: {
            myValue: {
                get() {
                    if (!this.loaded) {
                        let value = [];
                        this.value.forEach(k => {
                            if (/^\d+$/.test(k) && this.existingTags.length) {
                                for (let i in this.existingTags) {
                                    if (this.existingTags.hasOwnProperty(i) && this.existingTags[i].key == k) {
                                        value.push(this.existingTags[i]);
                                    }
                                }
                            } else {
                                value.push({key: '', value: k});
                            }
                        });
                        this.loaded = true;

                        return value;
                    } else {
                        return this.value;
                    }
                },
                set(newValue) {
                    this.$emit('input', newValue);
                }
            }
        },
    }
</script>
