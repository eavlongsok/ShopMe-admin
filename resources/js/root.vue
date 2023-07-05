<template>
    <div class="mt-16">
        <Header @adminprofile="active = 0"/>
        <div>
            <Sidebar class="fixed w-1/5 z-[1]" :active="active" @changetab="changeTab"/>
            <div class="w-[79%] ml-[21%] pt-5">
                <Home v-if="active === 1" @verifytab="changeTab(2)" @infopage="goToInfoPage"/>
                <Verification v-else-if="active === 2" :verifyInfoPage="verifyInfoPage" @reloadVerification="forceRerender" :key="verificationReloadKey" :seller_verification="seller_verification"/>
                <Ban v-else-if="active === 3" />
                <BannedUsers v-else-if="active === 4" />
                <Approve v-else-if="active === 5" />
                <ProductApproval v-else-if="active === 6" :categories="categories"/>
                <ProductManagement v-else-if="active === 7" :categories="categories"/>
                <BannedProducts v-else-if="active === 8" :categories="categories" />
                <AdminProfile v-else-if="active === 0" @changetab="changeTab"/>
                <EditAdminProfile v-else-if="active === -1" @changetab="changeTab"/>
            </div>
        </div>
    </div>
</template>

<script>
    import Sidebar from './components/Sidebar.vue'
    import Header from './components/Header.vue'
    import Home from './components/Home.vue'
    import Verification from './components/Verification.vue'
    import BannedUsers from './components/BannedUsers.vue'
    import Login from './components/Login.vue'
    import Ban from './components/Ban.vue'
    import BannedProducts from './components/BannedProducts.vue'
    import ProductManagement from './components/ProductManagement.vue'
    import ProductApproval from './components/ProductApproval.vue'
    import Approve from './components/Approve.vue'
    import AdminProfile from './components/AdminProfile.vue'
    import EditAdminProfile from './components/EditAdminProfile.vue'
    import {ref} from 'vue';

    export default {
        data() {
            return {
                active: 1,
                verifyInfoPage: false,
                session_id: null,
                seller_verification: null,
                categories: []
            }
        },
        components: {
            Sidebar,
            Header,
            Home,
            Verification,
            BannedUsers,
            Login,
            Ban,
            ProductManagement,
            BannedProducts,
            ProductManagement,
            ProductApproval,
            Approve,
            AdminProfile,
            EditAdminProfile
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
                if (tabID !== 2) {
                    this.verifyInfoPage = false
                    this.seller_verification = null
                }
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
            },
            async getCategories() {
                try {
                    const response = await axios.get('/api/getCategories', {
                        headers:{
                            'Authorization': 'Bearer ' + localStorage.getItem('admin_token'),
                        }
                    })
                    this.categories = response.data.categories
                } catch (error) {
                    console.log(error)
                }
            },
        },
        async mounted() {
            await this.getCategories()
        }
    }
</script>

<style>

</style>