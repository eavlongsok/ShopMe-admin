<template>
    <Login v-if="loggedIn === false"/>
    <div class="mt-16" v-else-if="loggedIn === true">
        <Header :active="active" @logout="loggedIn = false"/>
        <div class="">
            <Sidebar class="fixed w-1/5 z-[1]" :active="active" @changetab="changeTab"/>
            <div class="w-[79%] ml-[21%] pt-5">
                <Home v-if="active === 1" @verifytab="changeTab" @infopage="changeTab(2); verifyInfoPage = true"/>
                <Verification v-else-if="active === 2" :verifyInfoPage="verifyInfoPage"/>
                <Ban v-else-if="active === 3" />
                <BannedUsers v-else-if="active === 4" />
                <ProductManagement v-else-if="active === 5" />
                <RemovedProducts v-else-if="active === 6" />
                <ProductApproval v-else-if="active === 7" />
            </div>
        </div>
    </div>
</template>

<script>
    import Sidebar from './components/Sidebar.vue'
    import Header from './components/Header.vue'
    import ProfileBox from './components/ProfileBox.vue'
    import Home from './components/Home.vue'
    import Verification from './components/Verification.vue'
    import BannedUsers from './components/BannedUsers.vue'
    import Login from './components/Login.vue'
    import Ban from './components/Ban.vue'
    import RemovedProducts from './components/RemovedProducts.vue'
    import ProductManagement from './components/ProductManagement.vue'
    import ProductApproval from './components/ProductApproval.vue'

    export default {
        data() {
            return {
                active: 1,
                loggedIn: true,
                verifyInfoPage: true
            }
        },
        components: {
            Sidebar,
            Header,
            ProfileBox,
            Home,
            Verification,
            BannedUsers,
            Login,
            Ban,
            ProductManagement,
            RemovedProducts,
            ProductManagement,
            ProductApproval
        },
        methods: {
            changeTab(tabID) {
                this.active = tabID
                window.scrollTo(0,0)
            }
        }
    }
</script>

<style>

</style>