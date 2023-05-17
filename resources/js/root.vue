<template>
    <Login v-if="loggedIn === false"/>
    <div class="mt-16" v-else-if="loggedIn === true">
        <Header :active="active" @logout="loggedIn = false"/>
        <div class="">
            <Sidebar class="fixed w-1/5 z-[1]" :active="active" @changetab="changeTab"/>
            <div class="w-[79%] ml-[21%] pt-5">
                <Home v-if="active === 1" @verifytab="changeTab"/>
                <Verification v-else-if="active === 2"/>
                <Ban v-else-if="active === 3" />
                <BannedUsers v-else-if="active === 4" />
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

    export default {
        data() {
            return {
                active: 1,
                loggedIn: true
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
            Ban
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