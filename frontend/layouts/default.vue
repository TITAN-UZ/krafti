<template>
  <div>
    <page-header ref="header"/>
    <nuxt/>
    <page-footer ref="footer"/>
  </div>
</template>

<script>
    import header from '../components/header'
    import footer from '../components/footer'

    export default {
        name: 'default',
        components: {
            'page-header': header,
            'page-footer': footer,
        },
        created() {
            //console.log(this.$router.options.routes)
            //console.log(this)
        },
    }
</script>
