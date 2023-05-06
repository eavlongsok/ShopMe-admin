<template>
    <Header />
    <div class="grid grid-cols-10 gap-3">
        <Sidebar class="col-span-2"/>
        <CardRow class="col-span-8"/>
    </div>
</template>

<script>
    import Sidebar from './components/Sidebar.vue'
    import Header from './components/Header.vue'
    import ProfileBox from './components/ProfileBox.vue'
    import CardRow from './components/CardRow.vue'

    export default {
        components: {
            Sidebar, Header, ProfileBox, CardRow
        }
    }
</script>

<style>

</style>