<template>
    <div class=" z-10 w-screen h-16 bg-blue-500 flex flex-row items-center fixed top-0 left-0">
        <!-- <h1 class="font-bold text-4xl pl-10 inline-block text-white">ShopMe Admin</h1> -->
        <img src="logo.svg" class="ml-12" />
        <input class="h-9 w-64 focus:outline-none rounded pl-2 ml-32 focus:bg-gray-100 placeholder-gray-900 search" type="text" placeholder="Search"/>
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