<template>
    <div class="mt-16">
        <Header />
        <div class="grid grid-cols-10 gap-3">
            <Sidebar class="col-span-2" :active="active" @changetab="changeTab"/>
            <div class="col-span-8">
                <DashBoard v-if="active === 1"/>
                <Verification v-else-if="active === 2"/>
            </div>
        </div>
    </div>
</template>

<script>
    import Sidebar from './components/Sidebar.vue'
    import Header from './components/Header.vue'
    import ProfileBox from './components/ProfileBox.vue'
    import DashBoard from './components/DashBoard.vue'
    import Verification from './components/Verification.vue'

    export default {
        data() {
            return {
                active: 1
            }
        },
        components: {
            Sidebar, Header, ProfileBox, DashBoard, Verification
        },
        methods: {
            changeTab(tabID) {
                this.active = tabID
            }
        }
    }
</script>

<style>

</style>