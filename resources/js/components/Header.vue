<template>
    <div class="z-10 w-screen h-16 bg-blue-500 flex flex-row items-center fixed top-0 left-0">
        <!-- <h1 class="font-bold text-4xl pl-10 inline-block text-white">ShopMe Admin</h1> -->
        <div class="w-1/5">
            <img src="logo.svg" class="m-auto cursor-pointer" @click="reload()"/>
        </div>

        <div class="ml-auto mr-6">
            <ProfileBox @adminprofile="$emit('adminprofile')"/>
        </div>
    </div>
</template>

<script>
    import ProfileBox from './ProfileBox.vue'

    export default {
        name: 'Header',
        components: {
            ProfileBox,
        },
        emits: ['adminprofile'],
        methods: {
            reload() {
                window.location.reload()
            }
        }
    }
</script>

<style scoped>
</style>