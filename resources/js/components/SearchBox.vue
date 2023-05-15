<template>
    <form class="pl-2 ml-1" :action = "action">
            <input type="text" name="query" class="h-9 w-72 focus:outline-none rounded focus:bg-gray-100 placeholder-gray-900 search" placeholder="Search"/>
            <button type="submit" class="bg-green-500 ml-5 w-20 h-10 text-md tracking-wide rounded-2xl text-gray-800 hover:bg-green-600 hover:text-gray-900 font-semibold" @click="$emit('search')">Search</button>
    </form>
</template>

<script>
    export default {
        name: 'SearchBox',
        props: ['action', 'userType'],
        emits: ['search']
    }
</script>

<style scoped>
    .search {
        background-image: url('/searchicon.svg');
        background-repeat: no-repeat;
        background-size: 25px 25px;
        background-position: 2% 50%;
        padding-left: 35px;
    }
</style>