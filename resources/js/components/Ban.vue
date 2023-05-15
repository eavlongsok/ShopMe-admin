<template>
    <div v-if="profilePage === false">
        <h2 class="heading-2 mb-3">Ban user</h2>
        <SearchBox :userType="userType" action="/"/>
        <small class="ml-3 text-sm">Search for user by name or ID</small>
        <br/>
        <div class="ml-3 mt-2">
            <input type="radio" name="userType" id="buyer" value=1 @click="userType = 1" checked/>
            <label for="buyer" class="mr-4">Buyer</label>
            <input type="radio" name="userType" id="seller" value=2/ @click="userType = 2">
            <label for="seller">Seller</label>
        </div>
        <SearchResult :userType="userType" />

        <div class="w-full mt-6" v-if="userType === 1 && searched && buyers.length !== 0">
            <span class="ml-3 text-lg">Search result for: "Eav Long Sok"</span>
            <Table class="w-11/12 mt-3 mb-5" :fields="buyerFields">
                <tbody>
                    <tr v-for="(buyer, index) in buyers">
                        <td>{{ index + 1 }}</td>
                        <td><img src="bingchilling.jpeg" width="40"  class="rounded-full inline-block mr-3 border-2"/>{{ buyer.name }}</td>
                        <td :ref="'email' + (index + 1)" @click="copyToClipBoard(index+1)">{{ buyer.email }}</td>
                        <td>{{ buyer.created_at }}</td>
                        <td><button class="bg-green-800 rounded-xl p-2 border-2 border-black hover:bg-green-900 py-0" @click="profilePage = true">Show</button></td>
                    </tr>
                </tbody>
            </Table>
        </div>

        <h2 class="text-xl text-center mt-10" v-else-if="userType === 1 && searched && buyers.length === 0">No buyer was found!</h2>


        <div class="w-full mt-6" v-if="userType === 2 && searched && sellers.length !== 0">
            <span class="ml-3 text-lg">Search result for: "Eav Long Sok"</span>
            <Table class="w-11/12 mt-3 mb-5" :fields="sellerFields">
                <tbody>
                    <tr v-for="(seller, index) in sellers">
                        <td>{{ index + 1 }}</td>
                        <td><img src="bingchilling.jpeg" width="40"  class="rounded-full inline-block mr-3 border-2"/>{{ seller.store_name }}</td>
                        <td>{{ seller.name }}</td>
                        <td :ref="'email' + (index + 1)" @click="copyToClipBoard(index+1)">{{ seller.email }}</td>
                        <td>{{ seller.created_at }}</td>
                        <td><button class="bg-green-800 rounded-xl p-2 border-2 border-black hover:bg-green-900 py-0" @click="profilePage = true">Show</button></td>
                    </tr>
                </tbody>
            </Table>
        </div>

        <h2 class="text-xl text-center mt-10" v-else-if="userType === 2 && searched && sellers.length === 0">No seller was found!</h2>
    </div>

    <Profile v-else-if="profilePage === true" @backToMain="profilePage = false" @toggleBan="toggleBan()" :userType="uesrType" :user="userType === 1 ? buyer : seller"/>
</template>

<script>
import Profile from './Profile.vue';
import SearchBox from './SearchBox.vue';
import SearchResult from './SearchResult.vue';
import Table from './Table.vue';

    export default {
    data() {
        return {
            userType: 1,
            profilePage: false,

            searched: true,

            // buyers: [],

            buyers: [
                {name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', created_at: '27/10/2020'},
                {name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', created_at: '27/10/2020'},
                {name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', created_at: '27/10/2020'},
                {name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', created_at: '27/10/2020'},
                {name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', created_at: '27/10/2020'},
                {name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', created_at: '27/10/2020'},
                {name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', created_at: '27/10/2020'},
                {name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', created_at: '27/10/2020'}
            ],

            // sellers: [],

            sellers: [
                {store_name: 'ShopMe', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh',         created_at: '27/10/2020'},
                {store_name: 'ShopMe', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh',         created_at: '27/10/2020'},
                {store_name: 'ShopMe', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh',         created_at: '27/10/2020'},
                {store_name: 'ShopMe', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh',         created_at: '27/10/2020'},
                {store_name: 'ShopMe', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh',         created_at: '27/10/2020'},
                {store_name: 'ShopMe', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh',         created_at: '27/10/2020'},
                {store_name: 'ShopMe', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh',         created_at: '27/10/2020'},
                {store_name: 'ShopMe', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh',         created_at: '27/10/2020'},
                {store_name: 'ShopMe', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh',         created_at: '27/10/2020'},
                {store_name: 'ShopMe', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh',         created_at: '27/10/2020'},
            ],

            buyerFields: ['No.', 'Buyer\'s Name', "Email", 'Created At', 'Details'],
            sellerFields: ['No.', 'Store Name', "Seller's Name", 'Email', 'Created At', 'Details'],
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
                banned: false
            },
            seller: {}
        }
    },
    components: { SearchBox, SearchResult, Table, Profile },
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
        toggleBan() {
            if (this.userType === 1) {
                this.buyer.banned = !this.buyer.banned
            }
            else this.seller.banned = !this.seller.banned
        }
    }
}
</script>

<style scoped>

</style>