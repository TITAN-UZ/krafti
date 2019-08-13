<template>
    <nuxt-child/>
</template>

<script>
    export default {
        middleware: 'token',
    }
</script>