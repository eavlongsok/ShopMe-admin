<template>
    <div class="h-screen border-r-4 bg-white fixed w-1/5 z-[1] overflow-y-scroll" >
        <h1 class="text-3xl text-center pt-3 font-semibold text-cyan-800 leading-loose">Admin</h1>
        <ul class="list-none capitalize pt-3 flex flex-col justify-center mb-[33%]">
            <li @click="emitTabEvent(1)" class="tab" :class="{'bg-blue-500 text-gray-100': active === 1}">dashboard</li>
            <li @click="emitTabEvent(2)" class="tab" :class="{'bg-blue-500 text-gray-100': active === 2}">verification</li>
            <li @click="emitTabEvent(3)" class="tab" :class="{'bg-blue-500 text-gray-100': active === 3}">ban</li>
            <li @click="emitTabEvent(4)" class="tab" :class="{'bg-blue-500 text-gray-100': active === 4}">banned users</li>
            <li @click="emitTabEvent(5)" class="tab" :class="{'bg-blue-500 text-gray-100': active === 5}">approve products</li>
            <li @click="emitTabEvent(6)" class="tab" :class="{'bg-blue-500 text-gray-100': active === 6}">approved products</li>
            <li @click="emitTabEvent(7)" class="tab" :class="{'bg-blue-500 text-gray-100': active === 7}">Product Management</li>
            <li @click="emitTabEvent(8)" class="tab" :class="{'bg-blue-500 text-gray-100': active === 8}">Removed products</li>
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
        @apply pl-10 text-2xl leading-loose border-b-4 border-gray-300 flex;
    }

    .tab:first-child {
        @apply border-t-4 border-gray-300;
    }

    .tab:hover {
        @apply bg-blue-600 cursor-pointer text-gray-100;
    }
</style>