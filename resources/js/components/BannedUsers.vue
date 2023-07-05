<template>
    <div v-if="profilePage === false">
        <h2 class="heading-2 mb-3">Ban List</h2>
        <div class="ml-4 mt-5 text-md">
            <span :class="{'bg-blue-700 text-white': userType === 1}" class="border-2 border-gray-700 rounded-2xl border-opacity-75 cursor-pointer font-bold p-2" @click="userType = 1; fetchData()">Buyers</span>
            <span :class="{'bg-blue-700 text-white': userType === 2}" class="border-2 border-gray-700 rounded-2xl border-opacity-75 cursor-pointer font-bold p-2 ml-3" @click="userType = 2; fetchData()">Sellers</span>
        </div>
        <template v-if="userType === 1 && buyers.length != 0">
            <Table :fields="buyerFields" class="w-11/12 mt-10">
                <tbody>
                    <tr v-for="(buyer, index) in buyers" @mouseover="displayArrow('arrow-', index+1)" @mouseleave="hideArrow('arrow-', index+1)" @click="_buyer = buyer; profilePage = true">
                        <td>{{ buyer.buyer_id }}</td>
                        <td class="text-start indent-10"><img :src="buyer.img_url == null ? '/profilepic.png' : buyer.img_url" width="40"  class="rounded-[50%] aspect-square inline-block mr-3 border-2"/>{{ buyer.first_name }} {{ buyer.last_name }}</td>
                        <td>{{ buyer.created_at }}</td>
                        <td>{{ buyer.banned_at }}</td>
                        <!-- <td><button class="bg-green-800 rounded-xl p-2 border-2 border-black hover:bg-green-900 py-0" @click="profilePage = true">Show</button></td> -->
                        <!-- <td><button class="bg-red-700 rounded-xl p-2 border-2 border-black hover:bg-red-900 py-0">Unban</button></td> -->
                        <td class="hover-on-arrow w-24" title="See More Details" @click="profilePage = true"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow-' + (index + 1)"/></td>
                    </tr>
                </tbody>
            </Table>
            <Pagination :totalPages = "Math.ceil(total / limit)" :page="page" @changePage="changePage"/>
        </template>
            <div v-else-if="userType === 1 && !loaded" class="h-full flex justify-center mt-44">
                <Loader :size="5" :thickness="0.5" />
            </div>
            <h1 v-else-if="userType === 1 && loaded && buyers.length === 0" class="capitalize h-full flex justify-center mt-44 text-3xl font-bold">no one is currently being banned</h1>

            <template v-if="userType === 2 && sellers.length != 0">
                <Table :fields="sellerFields" class="w-11/12 mt-10">
                    <tbody>
                        <tr v-for="(seller, index) in sellers" @mouseover="displayArrow('arrow--', index+1)" @mouseleave="hideArrow('arrow--', index+1)" @click="_seller = seller; profilePage = true">
                            <td>{{ seller.seller_id }}</td>
                            <td class="text-start indent-10"><img :src="seller.img_url == null ? '/profilepic.png' : seller.img_url" width="40"  class="rounded-[50%] aspect-square inline-block mr-3 border-2"/>{{ seller.seller_first_name }} {{ seller.seller_last_name }}</td>
                            <td>{{ seller.created_at }}</td>
                            <td>{{ seller.banned_at }}</td>
                            <!-- <td><button class="bg-green-800 rounded-xl p-2 border-2 border-black hover:bg-green-900 py-0" @click="profilePage = true">Show</button></td> -->
                            <!-- <td><button class="bg-red-700 rounded-xl p-2 border-2 border-black hover:bg-red-900 py-0">Unban</button></td> -->
                            <td class="hover-on-arrow w-24" title="See More Details" @click="profilePage = true"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow--' + (index + 1)"/></td>
                        </tr>
                    </tbody>
                </Table>
                <Pagination :totalPages = "Math.ceil(total / limit)" :page="page" @changePage="changePage"/>
            </template>
        <div v-else-if="userType === 2 && !loaded" class="h-full flex justify-center mt-44">
                <Loader :size="5" :thickness="0.5" />
        </div>
        <h1 v-else-if="userType === 2 && loaded && sellers.length === 0" class="capitalize h-full flex justify-center mt-44 text-3xl font-bold">no one is currently being banned</h1>
    </div>
    <Profile v-else @backToMain="profilePage = false" @toggleBan="toggleBan()" :userType="userType" :user="userType === 1 ? _buyer : _seller"/>
</template>

<script>
    import Profile from './Profile.vue';
    import Table from './Table.vue';
    import Loader from './Loader.vue';
    import Pagination from './Pagination.vue'
    export default {
        name: 'BannedUsers',
        components: {
            Table,
            Profile,
            Loader,
            Pagination
        },
        data() {
            return {
                _buyer: {},
                _seller: {},
                userType: 1,
                profilePage: false,
                loaded: false,
                buyerFields: ['ID', 'name', 'created at', 'ban date', ' '],
                sellerFields: ['ID', 'store name', 'name', 'registration date', 'ban date', ' '],
                buyers: [],
                sellers: [],
                page: 1,
                limit: 20,
                total: 0,
            }
        },
        methods: {
            toggleBan() {
                if (this.userType === 1) {
                    if (this._buyer.status === 0) {
                        this._buyer.status = 1
                    }
                    else {
                        this._buyer.status = 0
                    }
                }
                else {
                    if (this._seller.status === 0) {
                        this._seller.status = 1
                    }
                    else {
                        this._seller.status = 0
                    }
                }
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

            async changePage(pageNumber) {
                if (pageNumber === '...') return
                if (pageNumber === '+') {
                    if (this.page == Math.ceil(this.total / this.limit)) return
                    this.page = this.page + 1
                }
                else if (pageNumber === '-') {
                    if (this.page == 1) return
                    this.page = this.page - 1
                }
                else this.page = parseInt(pageNumber)

                await this.fetchData()
            },

            async fetchData() {
                this.loaded = false
                const route = "api/search/" + (this.userType === 1 ? 'buyer' : 'seller');
                let params = new URLSearchParams();
                params.append('q', '');
                params.append('page', this.page);
                params.append('limit', this.limit);
                params.append('status', 0);
                try {
                    const response = await axios.get(route, {
                        params: params,
                        headers: {
                            Authorization: 'Bearer ' + localStorage.getItem('admin_token')
                        }
                    });

                    console.log(response.data)
                    if (this.userType === 1) {
                        this.buyers = response.data.users;
                    }
                    else {
                        this.sellers = response.data.users;
                    }
                    this.loaded = true
                    this.total = response.data.total
                }
                catch(err) {
                    console.log(err.response.data)
                }
            }
        },
        async mounted() {
            try {
                await this.fetchData();
                // console.log(this.buyers)
            }
            catch(err) {
                console.log(err)
            }
        }
    }
</script>

<style scoped>

</style>