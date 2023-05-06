<template>
    <div class="h-screen border-r-4 bg-white" >
        <ul class="list-none capitalize pt-3 flex flex-col justify-center">
            <li class="tab">dashboard</li>
            <li class="tab">seller request</li>
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