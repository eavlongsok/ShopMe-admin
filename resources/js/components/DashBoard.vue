<template>
    <h2 class="heading-2 mb-3">Dashboard</h2>
    <div class="flex justify-center items-center h-[130px]" v-if="!loaded">
        <Loader :size="4" :thickness="0.4"/>
    </div>
    <div class="grid lg:grid-cols-4 2xl:grid-cols-5 3xl:grid-cols-6 justify-evenly gap-5 capitalize" v-else>
        <Card title="Total Users" :value="totalCountAllTime" color="red" />
        <Card title="New Users (30 days)" :value="totalCount30Days" color="green" />
        <Card title="New Buyers (30 days)" :value="buyerCount30Days" color="blue" />
        <Card title="New Sellers (30 days)" :value="sellerCount30Days" color="magenta" />
        <Card title="Total products" :value="totalCount30Days" color="red" />
        <Card title="Listing Request (30 days)" :value="productRequested30Days" color="magenta" />
        <Card title="Approved Products (30 days)" :value="productApproved30Days" color="blue" />

        <!-- <Card title="title 8" value="80" color="green" /> -->
    </div>
</template>

<script>
    import Card from './Card.vue'
    import Loader from './Loader.vue'
    export default {
        name: 'DashBoard',
        data() {
            return {
                loaded: false,
                buyerCount30Days: 0,
                sellerCount30Days: 0,
                totalCount30Days: 0,
                totalCountAllTime: 0,
                productRequested30Days: 0,
                productApproved30Days: 0,
                totalProductCount: 0,
            }
        },
        components: {
            Card, Loader
        },
        methods: {
            async getRegisteredUsersLast30Days() {
                try {
                    const response = await axios.get('/api/info/monthlyaccounts', {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('admin_token')
                        }
                    })
                    this.buyerCount30Days = response.data.buyerCount30Days
                    this.sellerCount30Days = response.data.sellerCount30Days
                    this.totalCount30Days = response.data.totalCount30Days
                    this.totalCountAllTime = response.data.totalCountAllTime

                }
                catch(err) {
                    console.log(err.response)
                }
            },
            async getProductsLast30Days() {
                try {
                    const response = await axios.get('/api/info/monthlyproducts', {
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('admin_token')
                        }
                    })
                    this.productRequested30Days = response.data.productRequested30Days
                    this.productApproved30Days = response.data.productApproved30Days
                    this.totalProductCount = response.data.totalProductCount

                }
                catch(err) {
                    console.log(err.response)
                }
            }
        },
        async mounted() {
            await this.getRegisteredUsersLast30Days();
            this.loaded = true
        }
    }
</script>

<style scoped>

</style>