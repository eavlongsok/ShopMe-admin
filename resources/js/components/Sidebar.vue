<template>
    <div class="h-screen border-r-4 bg-white" >
        <ul class="list-none capitalize pt-3 flex flex-col justify-center">
            <li @click="emitTabEvent(1)" class="tab" :class="{'bg-blue-500 text-gray-100': active === 1}">dashboard</li>
            <li @click="emitTabEvent(2)" class="tab" :class="{'bg-blue-500 text-gray-100': active === 2}">verification</li>
        </ul>
    </div>
</template>

<script>
export default {
    name: 'Sidebar',
    props: ['active'],
    methods: {
        emitTabEvent(tabID) {
            this.$emit("changetab", tabID)
        }
    }
}
</script>

<style scoped>
    .tab {
        @apply pl-10 text-2xl leading-loose border-b-4;
    }

    .tab:hover {
        @apply bg-blue-600 cursor-pointer text-gray-100;
    }
</style>