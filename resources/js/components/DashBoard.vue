<template>
    <h2 class="heading-2 mb-3">Dashboard</h2>
    <div class="flex flex-row justify-evenly flex-wrap">
        <Card title="title 1" value="10" color="red" />
        <Card title="title 2" value="20" color="blue" />
        <Card title="title 3" value="30" color="magenta" />
        <Card title="title 4" value="40" color="green" />
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