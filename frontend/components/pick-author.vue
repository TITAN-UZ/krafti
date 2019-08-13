<template>
  <div>
    <vue-autosuggest
      ref="autosuggest"
      :suggestions="suggestions"
      :getSuggestionValue="getSuggestionValue"
      :inputProps="{class: 'form-control'}"
      @input="load"
      @selected="onSelected">
      <template slot-scope="{suggestion}">
        <img :src="suggestion.item.photo" v-if="suggestion.item.photo" class="avatar"/>
        {{suggestion.item.fullname}}
      </template>
    </vue-autosuggest>
  </div>

</template>

<script>
    export default {
        name: 'pick-author',
        props: {
            value: {
                type: Number,
                required: false,
                default: 0,
            }
        },
        data() {
            return {
                suggestions: [{
                    data: [],
                }],
            }
        },
        methods: {
            load(query) {
                this.$axios.get('admin/users', {params: {limit: 10, role: 'author', query: query || ''}})
                    .then(res => {
                        this.suggestions[0].data = res.data.rows;
                    })
                    .catch(() => {
                    })
            },
            onSelected: function (selected) {
                this.$emit('input', selected.item.id);
            },
            getSuggestionValue(suggestion) {
                return suggestion.item.fullname;
            },
            setValue(id) {
                this.$axios.get('admin/users', {params: {id: id, role: 'author'}})
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

<style lang="scss">
  #autosuggest {
    .avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      margin-right: 10px;
    }
  }
</style>
