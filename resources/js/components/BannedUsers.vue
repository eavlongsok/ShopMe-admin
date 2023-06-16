<template>
    <div v-if="profilePage === false">
        <h2 class="heading-2 mb-3">Ban List</h2>
        <div class="ml-4 mt-5 text-md">
            <span :class="{'bg-blue-700 text-white': userType === 1}" class="border-2 border-gray-700 rounded-2xl border-opacity-75 cursor-pointer font-bold p-2" @click="userType = 1; fetchData()">Buyers</span>
            <span :class="{'bg-blue-700 text-white': userType === 2}" class="border-2 border-gray-700 rounded-2xl border-opacity-75 cursor-pointer font-bold p-2 ml-3" @click="userType = 2; fetchData()">Sellers</span>
        </div>
            <Table v-if="userType === 1 && buyers.length != 0" :fields="buyerFields" class="w-11/12 mt-10">
                <tbody>
                    <tr v-for="(buyer, index) in buyers" @mouseover="displayArrow('arrow-', index+1)" @mouseleave="hideArrow('arrow-', index+1)" @click="profilePage = true">
                        <td>{{ index + 1 }}</td>
                        <td><img src="bingchilling.jpeg" width="40"  class="rounded-full inline-block mr-3 border-2"/>{{ buyer.first_name }} {{ buyer.last_name }}</td>
                        <td>{{ buyer.created_at }}</td>
                        <td>{{ buyer.banned_at }}</td>
                        <!-- <td><button class="bg-green-800 rounded-xl p-2 border-2 border-black hover:bg-green-900 py-0" @click="profilePage = true">Show</button></td> -->
                        <!-- <td><button class="bg-red-700 rounded-xl p-2 border-2 border-black hover:bg-red-900 py-0">Unban</button></td> -->
                        <td class="hover-on-arrow w-24" title="See More Details" @click="profilePage = true"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow-' + (index + 1)"/></td>
                    </tr>
                </tbody>
            </Table>
            <div v-else-if="userType === 1 && !loaded" class="h-full flex justify-center mt-44">
                <Loader :size="5" :thickness="0.5" />
            </div>
            <h1 v-else-if="userType === 1 && loaded && buyers.length === 0" class="capitalize h-full flex justify-center mt-44 text-3xl font-bold">no one is currently being banned</h1>

            <Table v-if="userType === 2 && sellers.length != 0" :fields="sellerFields" class="w-11/12 mt-10">
                <tbody>
                    <tr v-for="(seller, index) in sellers" @mouseover="displayArrow('arrow--', index+1)" @mouseleave="hideArrow('arrow--', index+1)" @click="profilePage = true">
                        <td>{{ index + 1 }}</td>
                        <td><img src="bingchilling.jpeg" width="40"  class="rounded-full inline-block mr-3 border-2"/>{{ buyer.name }}</td>
                        <td>{{ seller.name }}</td>
                        <td>{{ seller.registration_date }}</td>
                        <td>{{ seller.ban_date }}</td>
                        <!-- <td><button class="bg-green-800 rounded-xl p-2 border-2 border-black hover:bg-green-900 py-0" @click="profilePage = true">Show</button></td> -->
                        <!-- <td><button class="bg-red-700 rounded-xl p-2 border-2 border-black hover:bg-red-900 py-0">Unban</button></td> -->
                        <td class="hover-on-arrow w-24" title="See More Details" @click="profilePage = true"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow--' + (index + 1)"/></td>
                    </tr>
                </tbody>
        </Table>
        <div v-else-if="userType === 2 && !loaded" class="h-full flex justify-center mt-44">
                <Loader :size="5" :thickness="0.5" />
        </div>
        <h1 v-else-if="userType === 2 && loaded && sellers.length === 0" class="capitalize h-full flex justify-center mt-44 text-3xl font-bold">no one is currently being banned</h1>
    </div>
    <Profile v-else @backToMain="profilePage = false" @toggleBan="toggleBan()" :userType="userType" :user="userType === 1 ? buyer : seller"/>
</template>

<script>
    import Profile from './Profile.vue';
    import Table from './Table.vue';
    import Loader from './Loader.vue';
    export default {
        name: 'BannedUsers',
        components: {
            Table,
            Profile,
            Loader
        },
        data() {
            return {
                userType: 1,
                profilePage: false,
                loaded: false,
                buyerFields: ['No.', 'name', 'created at', 'ban date', ' '],
                sellerFields: ['No.', 'store name', 'name', 'registration date', 'ban date', ' '],
                buyers: [],
                sellers: [],
                buyer:
                {
                    ID: 2022,
                    name: 'Eav Long Sok',
                    email: 'esok@paragoniu.edu.kh',
                    dob: '01/01/2000',
                    created_at: '27/10/2020',
                    addresses: [
                        {user_id: 2022, region_id: 1, street_number: 101, house_number: 202, description: 'My address number 1'},
                        {user_id: 2022, region_id: 1, street_number: 101, house_number: 202, description: 'My address number 2'},
                        {user_id: 2022, region_id: 1, street_number: 101, house_number: 202, description: 'My address number 3'},
                        {user_id: 2022, region_id: 1, street_number: 101, house_number: 202, description: 'My address number 4'},
                        {user_id: 2022, region_id: 1, street_number: 101, house_number: 202, description: 'My address number 5'},
                    ],
                    orders: 'some orders',
                    banned: true
                },
                seller: {
                    ID: 2022,
                    store_name: 'ShopMe',
                    name: 'Eav Long Sok',
                    email: 'esok@paragoniu.edu.kh',
                    dob: '01/01/2000',
                    created_at: '27/10/2020',
                    verified_at: '07/11/2022',
                    verified_by: 'Yi Long Ma',
                    verifier_ID: 2202,
                    business_address: {user_id: 2022, region_id: 1, street_number: 101, house_number: 202},
                    business_info: {info: 'some information'},
                    description: 'My address description',
                    recent_sales: [
                        {
                            product_id: 10,
                            product_name: 'Phone case',
                            quantity: 5,
                            total_price: 50,
                            discount_percentage: 0
                        },
                        {
                            product_id: 10,
                            product_name: 'Phone case',
                            quantity: 5,
                            total_price: 50,
                            discount_percentage: 0
                        },
                        {
                            product_id: 10,
                            product_name: 'Phone case',
                            quantity: 5,
                            total_price: 50,
                            discount_percentage: 0
                        }
                    ],
                    banned: true
                }
            }
        },
        methods: {
            toggleBan() {
                if (this.userType === 1) {
                    this.buyer.banned = !this.buyer.banned
                }
                else this.seller.banned = !this.seller.banned
            },
            displayArrow(arrowName, rowID) {
                var arrowID = arrowName + rowID
                var arrow = (this.$refs[arrowID])[0]
                arrow.style.opacity = 1;
            },

            hideArrow(arrowName, rowID) {
                var arrowID = arrowName + rowID
                var arrow = (this.$refs[arrowID])[0]
                arrow.style.opacity = 0;
            },

            async fetchData() {
                this.loaded = false
                const route = "api/search/" + (this.userType === 1 ? 'buyer' : 'seller');
                let params = new URLSearchParams();
                params.append('q', '');
                params.append('page', 1);
                params.append('limit', 30);
                params.append('status', 0);
                try {
                    const response = await axios.get(route, {
                        params: params,
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('token')
                        }
                    });

                    // console.log(response.data);
                    if (this.userType === 1) {
                        this.buyers = response.data;
                    }
                    else {
                        this.sellers = response.data;
                    }
                    this.loaded = true
                }
                catch(err) {
                    console.log(err.response.data)
                }
            }
        },
        async mounted() {
            try {
                await this.fetchData();
            }
            catch(err) {
                console.log(err)
            }
        }
    }
</script>

<style scoped>

</style>