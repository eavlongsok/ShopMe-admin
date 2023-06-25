<template>
    <div class="mt-16">
        <Header :active="active"/>
        <div>
            <Sidebar class="fixed w-1/5 z-[1]" :active="active" @changetab="changeTab"/>
            <div class="w-[79%] ml-[21%] pt-5">
                <Home v-if="active === 1" @verifytab="changeTab(2)" @infopage="goToInfoPage"/>
                <Verification v-else-if="active === 2" :verifyInfoPage="verifyInfoPage" @reloadVerification="forceRerender" :key="verificationReloadKey" :seller_verification="seller_verification"/>
                <Ban v-else-if="active === 3" />
                <BannedUsers v-else-if="active === 4" />
                <Approve v-else-if="active === 5" />
                <ProductApproval v-else-if="active === 6" />
                <ProductManagement v-else-if="active === 7" />
                <RemovedProducts v-else-if="active === 8" />
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
    import Approve from './components/Approve.vue'
    import {ref} from 'vue';

    export default {
        data() {
            return {
                active: 1,
                verifyInfoPage: false,
                session_id: null,
                seller_verification: null
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
            ProductApproval,
            Approve
        },
        setup() {
            const verificationReloadKey = ref(0)
            const forceRerender = () => {
                verificationReloadKey.value += 1
            }
            return {
                verificationReloadKey, forceRerender
            }
        },
        methods: {
            changeTab(tabID) {
                this.active = tabID
                window.scrollTo(0,0)
            },

            getCookie(name) {
                var dc = document.cookie;
                var prefix = name + "=";
                var begin = dc.indexOf("; " + prefix);
                if (begin == -1) {
                    begin = dc.indexOf(prefix);
                    if (begin != 0) return null;
                }
                else
                {
                    begin += 2;
                    var end = document.cookie.indexOf(";", begin);
                    if (end == -1) {
                    end = dc.length;
                    }
                }
                return decodeURI(dc.substring(begin + prefix.length, end));
            },
            goToInfoPage(seller) {
                this.verifyInfoPage = true
                this.changeTab(2)
                this.seller_verification = seller
            }
        },
        mounted() {
            // console.log(this.getCookie('XSRF-TOKEN'))
        }
    }
</script>

<style>

</style>