<template>
    <div class="w-1/5 bg-blue-400 font-carlito h-screen" >
        <h1 class="font-bold text-center text-4xl pt-6">ShopMe Admin</h1>
        <ul class="list-none capitalize">
            <li class="pl-20 pt-3 text-2xl">dashboard</li>
            <li class="pl-20 pt-3 text-2xl">seller request</li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: 'Sidebar',
        props: ['active'],
        methods: [
            function changeTab(tabID) {
                this.active = tabID;
            }
        ]
    }
</script>

<style scoped>

</style>