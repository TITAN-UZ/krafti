<template>
    <div>
        <nuxt-child></nuxt-child>
    </div>
</template>

<script>
    export default {
        middleware: 'token',
        head () {
            return {
                title: 'Крафти / Курсы / Курс №' + this.$route.params.cid + ' / Видео',
            }
        },
    }
</script>