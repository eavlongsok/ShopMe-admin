<template>
    <h2 class="heading-2 mb-3">Product Approval</h2>
    <div class="ml-4 mt-5 text-md">
        <span :class="{'bg-blue-700 text-white': minitab === 1}" class="border-2 border-gray-700 rounded-2xl border-opacity-75 cursor-pointer font-bold p-2" @click="minitab = 1; chosenCategory = 0; searched = false">Recent</span>
        <span :class="{'bg-blue-700 text-white': minitab === 2}" class="border-2 border-gray-700 rounded-2xl border-opacity-75 cursor-pointer font-bold p-2 ml-3" @click="minitab = 2; chosenCategory = 0">Search</span>
    </div>
    <div v-if="minitab === 1" class="mt-5 ml-3">
        <p class="capitalize text-xl font-bold text-gray-700">recently approved products</p>
        <select class="capitalize h-9 rounded outline-none hover:border-black hover:border-[1px] focus:border-[1px] text-center focus:border-black mr-3 mt-5 focus:bg-gray-100" @change="changeCategory()">
            <option value=0 selected>all categories</option>
            <option value=1>shirt</option>
            <option value=2>pants</option>
            <option value=3>shoes</option>
            <option value=4>table</option>
            <option value=5>chair</option>
            <option value=6>bed</option>
            <option value=7>PC</option>
            <option value=8>Camera</option>
            <option value=9>Phone</option>
            <option value=10>Toy</option>
        </select>

        <h2 class="text-xl text-center mt-16 font-bold" v-if="filteredProducts.length === 0">No product was found</h2>

        <Table class="w-11/12 mt-3 mb-5" :fields="fields" v-else-if="filteredProducts.length > 0">
            <tbody>
                <tr v-for="(product, index) in filteredProducts" @mouseover="displayArrow('arrow', index+1)" @mouseleave="hideArrow('arrow', index+1)" @click="infoPage = true">
                    <td>{{ index + 1 }}</td>
                    <td>{{ product.id }}</td>
                    <td>{{ product.name }}</td>
                    <td>{{ product.category }}</td>
                    <td>{{ product.seller }}</td>
                    <td>{{ product.approved_at }}</td>
                    <td class="hover-on-arrow w-24" title="More details"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow' + (index + 1)"/></td>
                </tr>
            </tbody>
        </Table>
    </div>
    <div v-else-if="minitab === 2" class="mt-5 ml-3">
        <select class="capitalize h-9 rounded outline-none hover:border-black hover:border-[1px] focus:border-[1px] text-center focus:border-black mr-3 focus:bg-gray-100" @change= "changeCategory()">
                <option value=0 selected>all categories</option>
                <option value=1>shirt</option>
                <option value=2>pants</option>
                <option value=3>shoes</option>
                <option value=4>table</option>
                <option value=5>chair</option>
                <option value=6>bed</option>
                <option value=7>PC</option>
                <option value=8>Camera</option>
                <option value=9>Phone</option>
                <option value=10>Toy</option>
        </select>
        <SearchBox action="/" @search="searched = true" class="inline-block"/>
        <small class="ml-3 text-sm block">Search for products by name or ID</small>

        <p class="ml-3 text-lg mt-5" v-if="searched">Search result for: "iPhone 12 Pro Max"</p>

        <h2 class="text-xl text-center mt-16 font-bold" v-if="searched && filteredProducts.length === 0">No product was found</h2>

        <Table class="w-11/12 mt-3 mb-5" :fields="fields" v-else-if="searched && filteredProducts.length > 0">
            <tbody>
                <tr v-for="(product, index) in filteredProducts" @mouseover="displayArrow('arrow', index+1)" @mouseleave="hideArrow('arrow', index+1)" @click="infoPage = true">
                    <td>{{ index + 1 }}</td>
                    <td>{{ product.id }}</td>
                    <td>{{ product.name }}</td>
                    <td>{{ product.category }}</td>
                    <td>{{ product.seller }}</td>
                    <td>{{ product.approved_at }}</td>
                    <td class="hover-on-arrow w-24" title="More details"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow' + (index + 1)"/></td>
                </tr>
            </tbody>
        </Table>
    </div>
</template>

<script>
    import SearchBox from './SearchBox.vue';
    import Table from './Table.vue';
    export default {
        name: "ProductApproval",
        data() {
            return {
                minitab: 1,
                searched: false,
                chosenCategory: 0,
                fields: ['No', 'ID', 'Product Name', 'Category', 'Store Name', 'Approval Time', ' '],
                products: [
                    {id: 1, name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'ShopMe', approved_at: '27/10/2020'},
                    {id: 1, name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'ShopMe', approved_at: '27/10/2020'},
                    {id: 1, name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'ShopMe', approved_at: '27/10/2020'},
                    {id: 1, name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'ShopMe', approved_at: '27/10/2020'},
                    {id: 1, name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'ShopMe', approved_at: '27/10/2020'},
                    {id: 1, name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'ShopMe', approved_at: '27/10/2020'},
                    {id: 1, name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'ShopMe', approved_at: '27/10/2020'},
                    {id: 1, name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'ShopMe', approved_at: '27/10/2020'},
                    {id: 1, name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'ShopMe', approved_at: '27/10/2020'},
                ]
            }
        },
        components: {
            SearchBox, Table
        },
        methods: {
            displayArrow(ref, index) {
                this.$refs[ref + index][0].classList.remove('opacity-0')
            },
            hideArrow(ref, index) {
                this.$refs[ref + index][0].classList.add('opacity-0')
            },
            changeCategory() {
                this.chosenCategory = parseInt(event.target.value)
            }
        },
        computed: {
            filteredProducts() {
                if (this.chosenCategory === 0) {
                    return this.products
                } else {
                    return this.products.filter(product => product.category_id === this.chosenCategory)
                }
            }
        }
    }
</script>

<style scoped>

</style>