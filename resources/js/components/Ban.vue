<template>
    <div v-if="profilePage === false">
        <h2 class="heading-2 mb-3">Ban user</h2>
        <SearchBox :userType="userType" action="/" @search="userType === 1? searched_buyer = true : searched_seller = true"/>
        <small class="ml-3 text-sm">Search for user by name or ID</small>
        <br/>
        <div class="ml-3 mt-2">
            <input type="radio" name="userType" id="buyer" value=1 @click="userType = 1" :checked="userType === 1"/>
            <label for="buyer" class="mr-4">Buyer</label>
            <input type="radio" name="userType" id="seller" value=2/ @click="userType = 2" :checked="userType === 2">
            <label for="seller">Seller</label>
        </div>
        <div class="w-full mt-6" v-if="userType === 1 && searched_buyer && buyers.length !== 0">
            <span class="ml-3 text-lg">Search result for: "Eav Long Sok"</span>
            <Table class="w-11/12 mt-3 mb-5" :fields="buyerFields">
                <tbody>
                    <tr v-for="(buyer, index) in buyers" @mouseover="displayArrow('arrow-', index+1)" @mouseleave="hideArrow('arrow-', index+1)">
                        <td>{{ index + 1 }}</td>
                        <td @click="profilePage = true"><img src="bingchilling.jpeg" width="40"  class="rounded-full inline-block mr-3 border-2"/>{{ buyer.name }}</td>
                        <td :ref="'email' + (index + 1)" @click="copyToClipBoard(index+1)">{{ buyer.email }}</td>
                        <td>{{ buyer.created_at }}</td>
                        <!-- <td><button class="bg-green-800 rounded-xl p-2 border-2 border-black hover:bg-green-900 py-0" @click="profilePage = true">Show</button></td> -->
                        <td class="hover-on-arrow w-24" title="See More Details" @click="profilePage = true"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow-' + (index + 1)"/></td>
                    </tr>
                </tbody>
            </Table>
        </div>

        <h2 class="text-xl text-center mt-16" v-else-if="userType === 1 && searched_buyer && buyers.length === 0">No buyer was found!</h2>


        <div class="w-full mt-6" v-if="userType === 2 && searched_seller && sellers.length !== 0">
            <span class="ml-3 text-lg">Search result for: "Eav Long Sok"</span>
            <Table class="w-11/12 mt-3 mb-5" :fields="sellerFields">
                <tbody>
                    <tr v-for="(seller, index) in sellers" @mouseover="displayArrow('arrow--', index+1)" @mouseleave="hideArrow('arrow--', index+1)">
                        <td>{{ index + 1 }}</td>
                        <td><img src="bingchilling.jpeg" width="40"  class="rounded-full inline-block mr-3 border-2"/>{{ seller.store_name }}</td>
                        <td>{{ seller.name }}</td>
                        <td :ref="'email' + (index + 1)" @click="copyToClipBoard(index+1)">{{ seller.email }}</td>
                        <td>{{ seller.created_at }}</td>
                        <td class="hover-on-arrow w-24" title="See More Details" @click="profilePage = true"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow--' + (index + 1)"/></td>
                    </tr>
                </tbody>
            </Table>
        </div>

        <h2 class="text-xl text-center mt-10" v-else-if="userType === 2 && searched_seller && sellers.length === 0">No seller was found!</h2>
    </div>

    <Profile v-else-if="profilePage === true" @backToMain="profilePage = false" @toggleBan="toggleBan()" :userType="userType" :user="userType === 1 ? buyer : seller"/>
</template>

<script>
import Profile from './Profile.vue';
import SearchBox from './SearchBox.vue';
import Table from './Table.vue';

    export default {
    data() {
        return {
            userType: 1,
            profilePage: false,

            searched_buyer: false,
            searched_seller: false,

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

            buyerFields: ['No.', 'Buyer\'s Name', "Email", 'Created At', ' '],
            sellerFields: ['No.', 'Store Name', "Seller's Name", 'Email', 'Created At', ' '],
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
                banned: false
            }
        }
    },
    components: { SearchBox, Table, Profile },
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
        }
    }
}
</script>

<style scoped>

</style>