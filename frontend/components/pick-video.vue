<template>
  <div>
    <vue-autosuggest
      ref="autosuggest"
      :suggestions="suggestions"
      :getSuggestionValue="getSuggestionValue"
      :inputProps="{class: 'form-control', required: required}"
      @input="load"
      @selected="onSelected">
      <template slot-scope="{suggestion}">
        <img :src="suggestion.item.preview['100x75']" v-if="suggestion.item.preview['100x75']"/>
        {{suggestion.item.title}}
      </template>
    </vue-autosuggest>
    <img :src="img" v-if="img" class="mt-2"/>
  </div>

</template>

<script>
    export default {
        name: 'pick-video',
        props: {
            required: Boolean,
            value: {
                type: Number,
                required: false,
                default: 0,
            },
        },
        data() {
            return {
                suggestions: [{
                    data: [],
                }],
                img: '',
            }
        },
        methods: {
            load(query) {
                this.$axios.get('admin/videos', {params: {limit: 10, query: query || ''}})
                    .then(res => {
                        this.suggestions[0].data = res.data.rows;
                    })
                    .catch(() => {
                    })
            },
            onSelected: function (selected) {
                if (selected && selected.item) {
                    this.$emit('input', selected.item.id);
                    this.img = selected.item.preview && selected.item.preview['295x166']
                        ? selected.item.preview['295x166']
                        : '';
                } else {
                    this.$emit('input', null);
                    this.img = '';
                }
            },
            getSuggestionValue(suggestion) {
                return suggestion.item.title;
            },
            setValue(id) {
                this.$axios.get('admin/videos', {params: {id: id}})
                    .then(res => {
                        this.suggestions[0].data = [res.data];

                        const {listeners, setCurrentIndex, setChangeItem} = this.$refs.autosuggest;
                        setCurrentIndex(0);
                        setChangeItem({item: res.data}, true);
                        listeners.selected(true)
                    })
                    .catch(() => {
                    })
            }
        },
        created() {
            if (this.value > 0) {
                this.setValue(this.value);
            } else {
                this.load();
            }
        }
    }
</script>
