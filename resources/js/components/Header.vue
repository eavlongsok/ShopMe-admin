<template>
    <div class="w-screen h-16 bg-blue-500 flex flex-row items-center">
        <!-- <h1 class="font-bold text-4xl pl-10 inline-block text-white">ShopMe Admin</h1> -->
    <img src="logo.svg" class="ml-12" />
    <input class="h-9 w-64 focus:outline-none rounded pl-2 ml-32 focus:bg-gray-100 placeholder-gray-900 search" type="text" placeholder="Search"/>
    <ProfileBox class="ml-auto mr-6"/>
    </div>
</template>

<script>
    import ProfileBox from './ProfileBox.vue'
    export default {
        name: 'Header',
        components: {
            ProfileBox
        }
    }
</script>

<style scoped>

</style>