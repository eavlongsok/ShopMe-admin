<template>
    <div v-if="profilePage === false">
        <div>
            <h2 class="heading-2 mb-3">Ban user</h2>
            <SearchBox @search="search" :userType="userType"/>
            <Loader :size="2.8" :thickness="0.3" v-if="userType == 1 && searched_buyer && !loaded" class="ml-7"/>
            <Loader v-if="userType == 2 && searched_seller && !loaded" class="ml-7"/>
        </div>
        <small class="ml-3 text-sm block">Search for user by name or ID</small>
        <div class="ml-3 mt-3">
            <input type="radio" name="userType" id="buyer" value=1 @click="userType = 1" :checked="userType === 1"/>
            <label for="buyer" class="mr-4">Buyer</label>
            <input type="radio" name="userType" id="seller" value=2/ @click="userType = 2" :checked="userType === 2">
            <label for="seller">Seller</label>
        </div>

        <div class="w-full mt-6" v-if="userType === 1 && searched_buyer && buyers.length !== 0">
            <span class="ml-3 text-lg">Search result for: "{{queryForBuyer}}"</span>
            <Table class="w-11/12 mt-3 mb-5" :fields="buyerFields">
                <tbody>
                    <tr v-for="(buyer, index) in buyers" @mouseover="displayArrow('arrow-', index+1)" @mouseleave="hideArrow('arrow-', index+1) " @click="_buyer = buyer">
                        <td @click="profilePage = true">{{ index + 1 }}</td>
                        <td @click="profilePage = true"><img src="bingchilling.jpeg" width="40"  class="rounded-[50%] aspect-square inline-block mr-3 border-2"/>{{ buyer.first_name }} {{ buyer.last_name }}</td>
                        <td :ref="'email' + (index + 1)" @click="copyToClipBoard(index+1)">{{ buyer.email }}</td>
                        <td @click="profilePage = true">{{ buyer.created_at }}</td>
                        <!-- <td><button class="bg-green-800 rounded-xl p-2 border-2 border-black hover:bg-green-900 py-0" @click="profilePage = true">Show</button></td> -->
                        <td class="hover-on-arrow w-24" title="See More Details" @click="profilePage = true"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow-' + (index + 1)"/></td>
                    </tr>
                </tbody>
            </Table>
        </div>

        <h2 class="text-xl text-center mt-16" v-else-if="userType === 1 && searched_buyer && buyers.length === 0 && loaded">No buyer was found!</h2>


        <div class="w-full mt-6" v-if="userType === 2 && searched_seller && sellers.length !== 0">
            <span class="ml-3 text-lg">Search result for: "{{queryForSeller}}"</span>
            <Table class="w-11/12 mt-3 mb-5" :fields="sellerFields">
                <tbody>
                    <tr v-for="(seller, index) in sellers" @mouseover="displayArrow('arrow--', index+1)" @mouseleave="hideArrow('arrow--', index+1)" @click="_seller = seller">
                        <td>{{ index + 1 }}</td>
                        <td @click="profilePage = true"><img src="bingchilling.jpeg" width="40"  class="rounded-[50%] aspect-square inline-block mr-3 border-2"/>{{ seller.store_name === null ? '##N/A##' : seller.store_name  }}</td>
                        <td @click="profilePage = true">{{ seller.seller_first_name }} {{ seller.seller_last_name}}</td>
                        <td :ref="'email' + (index + 1)" @click="copyToClipBoard(index+1)">{{ seller.email }}</td>
                        <td>{{ seller.created_at }}</td>
                        <td class="hover-on-arrow w-24" title="See More Details" @click="profilePage = true"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow--' + (index + 1)"/></td>
                    </tr>
                </tbody>
            </Table>
        </div>

        <h2 class="text-xl text-center mt-10" v-else-if="userType === 2 && searched_seller && sellers.length === 0 && loaded">No seller was found!</h2>
    </div>

    <Profile v-else-if="profilePage === true" @backToMain="profilePage = false" @toggleBan="toggleBan" :userType="userType" :user="userType === 1 ? _buyer : _seller"/>
</template>

<script>
import Profile from './Profile.vue';
import SearchBox from './SearchBox.vue';
import Table from './Table.vue';
import Loader from './Loader.vue';

    export default {
    data() {
        return {
            queryForBuyer: "",
            queryForSeller: "",
            userType: 1,
            profilePage: false,
            loaded: false,

            searched_buyer: false,
            searched_seller: false,

            // buyers: [],

            buyers: [],

            // sellers: [],

            sellers: [],

            buyerFields: ['No.', 'Buyer\'s Name', "Email", 'Created At', ' '],
            sellerFields: ['No.', 'Store Name', "Seller's Name", 'Email', 'Created At', ' '],
            _buyer: {},
            _seller: {},
        }
    },
    components: { SearchBox, Table, Profile, Loader },
    methods: {
        copyToClipBoard(rowID) {
            var emailID = "email" + rowID
            var emailBox = (this.$refs[emailID])[0]
            var email = emailBox.innerText
            navigator.clipboard.writeText(email)
            emailBox.innerText = "Copied!"
            setTimeout(() => {
                emailBox.innerText = email
            }, 200)
        },
        toggleBan(userType, userID) {
            if (userType == 1)
                this.buyers.forEach(buyer => {
                    if (buyer.buyer_id == userID)
                        if (buyer.status == 1)
                            buyer.status = 0
                        else
                            buyer.status = 1
                })
            else
                this.sellers.forEach(seller => {
                    if (seller.seller_id == userID)
                        if (seller.status == 1)
                            seller.status = 0
                        else
                            seller.status = 1
                })
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
        async search(query) {
            this.loaded = false

            if (this.userType == 1) {
                this.queryForBuyer = query;
                this.searched_buyer = true
                this.buyers = []
            }

            else {
                this.queryForSeller = query;
                this.searched_seller = true
                this.sellers = []
            }
            let params = new URLSearchParams();
            if (this.userType == 1) params.append('q', this.queryForBuyer)
            else params.append('q', this.queryForSeller)
            params.append('limit', 30)
            params.append('offset', 0)
            params.append('status', 1)

            let route = '/api/search/' + (this.userType === 1 ? 'buyer' : 'seller')

            try {
                const response = await axios.get(route, {
                    params: params,
                    headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('admin_token'),
                    }
                })

                console.log(response.data)

                if (this.userType === 1) {
                    this.buyers = response.data
                }
                else {
                    this.sellers = response.data
                }

                this.loaded = true
            }
            catch(err) {
                console.log(err.response.data)
                this.loaded = true
            }

        },
    },
    async mounted() {
        // const response = await this.fetchData()
    }
}
</script>

<style scoped>

</style>