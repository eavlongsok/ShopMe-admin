<template>
    <div class=" z-10 w-screen h-16 bg-blue-500 flex flex-row items-center fixed top-0 left-0">
        <!-- <h1 class="font-bold text-4xl pl-10 inline-block text-white">ShopMe Admin</h1> -->
        <div class="w-1/5">
            <img src="logo.svg" class="m-auto"/>
        </div>

        <form class="pl-2 ml-1">
            <input type="text" name="query" class="h-9 w-64 focus:outline-none rounded focus:bg-gray-200 placeholder-gray-900 search" placeholder="Search"/>
            <button type="submit" class="bg-green-500 ml-5 w-20 h-10 text-md tracking-wider rounded-2xl text-gray-800 hover:bg-green-600 hover:text-gray-900 font-semibold">Search</button>
        </form>

        <div class="ml-auto mr-6">
            <ProfileBox/>
        </div>
    </div>
</template>

<script>
    import ProfileBox from './ProfileBox.vue'

    export default {
        name: 'Header',
        components: {
            ProfileBox
        },
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