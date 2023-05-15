<template>
    <h2 class="heading-2 mb-3">Ban List</h2>
    <div class="ml-4 mt-5 text-md">
        <span :class="{'bg-blue-700 text-white': active === 1}" class="border-2 border-gray-700 rounded-2xl border-opacity-75 cursor-pointer font-bold p-2" @click="active = 1">Buyers</span>
        <span :class="{'bg-blue-700 text-white': active === 2}" class="border-2 border-gray-700 rounded-2xl border-opacity-75 cursor-pointer font-bold p-2 ml-3" @click="active = 2">Sellers</span>
    </div>
        <Table v-if="active === 1" :fields="buyerFields" class="w-11/12 mt-10">
            <tbody>
                <tr v-for="(buyer, index) in buyers">
                    <td>{{ index + 1 }}</td>
                    <td>{{ buyer.name }}</td>
                    <td>{{ buyer.created_at }}</td>
                    <td>{{ buyer.ban_date }}</td>
                    <td><button class="bg-green-800 rounded-xl p-2 border-2 border-black hover:bg-green-900 py-0">Show</button></td>
                    <td><button class="bg-red-700 rounded-xl p-2 border-2 border-black hover:bg-red-900 py-0">Unban</button></td>
                </tr>
            </tbody>
        </Table>
        <Table v-else-if="active === 2" :fields="sellerFields" class="w-11/12 mt-10">
            <tbody>
                <tr v-for="(seller, index) in sellers">
                    <td>{{ index + 1 }}</td>
                    <td>{{ seller.store_name }}</td>
                    <td>{{ seller.name }}</td>
                    <td>{{ seller.registration_date }}</td>
                    <td>{{ seller.ban_date }}</td>
                    <td><button class="bg-green-800 rounded-xl p-2 border-2 border-black hover:bg-green-900 py-0">Show</button></td>
                    <td><button class="bg-red-700 rounded-xl p-2 border-2 border-black hover:bg-red-900 py-0">Unban</button></td>
                </tr>
            </tbody>
    </Table>
</template>

<script>
    import Table from './Table.vue';
    export default {
        name: 'BannedUsers',
        components: {
            Table
        },
        data() {
            return {
                active: 1,
                buyerFields: ['No.', 'name', 'created at', 'ban date', 'details', 'unban'],
                sellerFields: ['No.', 'store name', 'name', 'registration date', 'ban date', 'details', 'unban'],
                buyers: [
                    {name: 'Eav Long Sok', created_at: '25/01/2015', ban_date: '30/02/2017'},
                    {name: 'Eav Long Sok', created_at: '25/01/2015', ban_date: '30/02/2017'},
                    {name: 'Eav Long Sok', created_at: '25/01/2015', ban_date: '30/02/2017'},
                    {name: 'Eav Long Sok', created_at: '25/01/2015', ban_date: '30/02/2017'},
                    {name: 'Eav Long Sok', created_at: '25/01/2015', ban_date: '30/02/2017'},
                    {name: 'Eav Long Sok', created_at: '25/01/2015', ban_date: '30/02/2017'}
                ],
                sellers: [
                    {store_name:'ShopMe', name: 'Eav Long Sok', registration_date: '25/01/2015', ban_date: '30/02/2017'},
                    {store_name:'ShopMe', name: 'Eav Long Sok', registration_date: '25/01/2015', ban_date: '30/02/2017'},
                    {store_name:'ShopMe', name: 'Eav Long Sok', registration_date: '25/01/2015', ban_date: '30/02/2017'},
                    {store_name:'ShopMe', name: 'Eav Long Sok', registration_date: '25/01/2015', ban_date: '30/02/2017'},
                    {store_name:'ShopMe', name: 'Eav Long Sok', registration_date: '25/01/2015', ban_date: '30/02/2017'},
                    {store_name:'ShopMe', name: 'Eav Long Sok', registration_date: '25/01/2015', ban_date: '30/02/2017'}
                ]
            }
        }
    }
</script>

<style scoped>

</style>