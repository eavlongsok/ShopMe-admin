<template>
    <ProductInfo v-if="infoPage === true" @backToMain="infoPage = false" :product="product" @changeStatus = "product.status ^= 1"/>
    <div v-else>
        <h2 class="heading-2 mb-3">remove product</h2>
        <SearchBox :userType="userType" action="/"/>
        <small class="ml-3 text-sm">Search for products by name or ID</small>
        <br/>

        <!-- Search result -->

        <Table class="w-11/12 mt-3 mb-5" :fields="fields" v-if="searched">
            <tbody>
                <tr v-for="(product, index) in products" @mouseover="displayArrow('arrow', index+1)" @mouseleave="hideArrow('arrow', index+1)" @click="infoPage = true">
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
        name: 'RemoveProduct',
        data() {
            return {
                searched: true,
                infoPage: false,
                fields: ['No.', 'Product Name', 'Price', 'Seller', 'Date Added', ' '],
                products: [
                    {name: 'iPhone 12 Pro Max', price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                    {name: 'iPhone 12 Pro Max', price: 1200, seller: 'Eav Long Sok', date_added: '27/10/2020'},
                ],
                product: {
                    id: 1,
                    name: 'iPhone 12 Pro Max',
                    category: 'Electronics',
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
            }
        }
    }
</script>

<style scoped>

</style>