<template>
    <ProductInfo v-if="infoPage === true" @backToMain="infoPage = false" :product="product" @changeStatus = "product.status ^= 1"/>
    <div v-else>
        <h2 class="heading-2 mb-3">product management</h2>
        <select class="capitalize h-9 rounded outline-none hover:border-black hover:border-[1px] focus:border-[1px] text-center focus:border-black mr-3 ml-3 focus:bg-gray-100" @change= "changeCategory()">
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
        <SearchBox @search="searched = true" class="inline-block"/>
        <small class="ml-3 text-sm block">Search for products by name or ID</small>

        <!-- Search result -->
        <p class="ml-3 text-lg mt-5" v-if="searched">Search result for: "iPhone 12 Pro Max"</p>

        <h2 class="text-xl text-center mt-16 font-bold" v-if="searched && filteredProducts.length === 0">No product was found</h2>

        <Table class="w-11/12 mt-3 mb-5" :fields="fields" v-else-if="searched && filteredProducts.length > 0">
            <tbody>
                <tr v-for="(product, index) in filteredProducts" @mouseover="displayArrow('arrow', index+1)" @mouseleave="hideArrow('arrow', index+1)" @click="infoPage = true">
                    <td>{{ index + 1 }}</td>
                    <td>{{ product.name }}</td>
                    <td>{{ product.price }}</td>
                    <td>{{ product.seller }}</td>
                    <td>{{ product.date_added }}</td>
                    <td class="hover-on-arrow w-24" title="More details"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow' + (index + 1)"/></td>
                </tr>
            </tbody>
        </Table>
    </div>
</template>

<script>
    import ProductInfo from './ProductInfo.vue';
    import SearchBox from './SearchBox.vue';
    import Table from './Table.vue';

    export default {
        name: 'ProductManagement',
        data() {
            return {
                searched: false,
                infoPage: false,
                chosenCategory: 0,
                fields: ['No.', 'Product Name', 'Price', 'Seller', 'Date Added', ' '],
                products: [
                    {name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', category: 'Phone', category_id: 9, price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},

                ],
                product: {
                    id: 1,
                    name: 'iPhone 12 Pro Max',
                    category_id: 9,
                    category: 'Phone',
                    price: 1200,
                    seller: 'Eav Long Sok',
                    amount_sold: 100,
                    date_added: '27/10/2020',
                    status: 1
                }
            }
        },
        components: {
            SearchBox,
            Table,
            ProductInfo
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