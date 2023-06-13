<template>
    <h2 class="heading-2 mb-3">Dashboard</h2>
    <div class="grid lg:grid-cols-4 2xl:grid-cols-5 3xl:grid-cols-6 justify-evenly gap-5 capitalize">
        <Card title="title 1" value="10" color="red" />
        <Card title="title 2" value="20" color="blue" />
        <Card title="title 3" value="30" color="magenta" />
        <Card title="title 4" value="40" color="green" />
        <Card title="title 5" value="50" color="red" />
        <Card title="title 6" value="60" color="blue" />
        <Card title="title 7" value="70" color="magenta" />
        <Card title="title 8" value="80" color="green" />
    </div>
</template>

<script>
    import Card from './Card.vue'
    export default {
        name: 'DashBoard',
        components: {
            Card
        }
    }
</script>

<style scoped>

</style>