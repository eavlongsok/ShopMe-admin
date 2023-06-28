<template>
    <ProductInfo v-if="infoPage" @backToMain="async (removed) => {
        infoPage = false
        if (removed) await this.getProducts()
    }" :product="_product" @changeStatus = "(banned_by) => {
        _product.banned_by = banned_by
    }"/>
    <div v-else>
        <h2 class="heading-2 mb-3">Removed Product List</h2>
        <select class="capitalize h-9 rounded outline-none hover:border-black border-[1px] border-white focus:border-[1px] focus:border-black mr-3 mt-5 focus:bg-gray-100 bg-white p-2" @change="changeCategory()">
            <option value=0 selected>all categories</option>
            <option v-for="category in categories" :value="category.category_id">{{ category.category_name }}</option>
        </select>

        <SearchBox :userType="0" @search="getBannedProducts"/>
        <small class="ml-3 text-sm block">Search for products by name or ID</small>

        <!-- Search result -->
        <p class="ml-3 text-lg mt-5" v-if="searched">Search result for: "{{query}}"</p>

        <div class="flex justify-center items-center h-[50vh]" v-if="searched && !loaded">
            <Loader :size="4" :thickness="0.4"/>
        </div>

        <h2 class="text-xl text-center mt-16 font-bold" v-if="searched && products.length === 0">No product was found</h2>

        <Table class="w-11/12 mt-3 mb-5" :fields="fields" v-else-if="searched && products.length > 0">
            <tbody>
                <tr v-for="(product, index) in products" @mouseover="displayArrow('arrow', index+1)" @mouseleave="hideArrow('arrow', index+1)" @click="infoPage = true; _product = product">
                    <td>{{ product.product_id }}</td>
                    <td><img :src="product.product_img" width="40" class="rounded-[50%] inline-block mr-3 border-2 aspect-square"/>{{ product.product_name }}</td>
                    <td>{{ product.category_name }}</td>
                    <td>{{ formatToCurrency(product.price) }}</td>
                    <td><img :src="product.store_logo" width="40" class="rounded-[50%] inline-block mr-3 border-2 aspect-square"/>{{ product.store_name }}</td>
                    <td>{{ product.banned_at }}</td>
                    <td class="hover-on-arrow w-24" title="More details"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow' + (index + 1)"/></td>
                </tr>
            </tbody>
        </Table>
    </div>
</template>

<script>
    import ProductInfo from './ProductInfo.vue';
    import Table from './Table.vue';
    import SearchBox from './SearchBox.vue';
    import Loader from './Loader.vue';

    export default {
        name: 'RemovedProducts',
        components: { Table, ProductInfo, SearchBox, Loader },
        data() {
            return {
                infoPage: false,
                chosenCategory: 0,
                _product: {},
                loaded: false,
                searched: false,
                query: '',
                fields: ['ID', 'Product Name', 'Category', 'Price', 'Store Name', 'Banned At', ' '],
                products: []
            }
        },
        props: ['categories'],
        methods: {
            displayArrow(ref, index) {
                this.$refs[ref + index][0].classList.remove('opacity-0')
            },
            hideArrow(ref, index) {
                this.$refs[ref + index][0].classList.add('opacity-0')
            },
            changeCategory() {
                this.chosenCategory = parseInt(event.target.value)
            },
            formatToCurrency(amount){
                const formatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                });
                return formatter.format(amount);
            },
            async getBannedProducts(query) {
                const page = 1;
                const limit = 20;
                this.query = query
                this.products = []
                this.loaded = false
                this.searched = true
                let params = new URLSearchParams();
                params.append('q', this.query)
                params.append('category_id', this.chosenCategory)

                try {
                    const response = await axios.get('/api/getBannedProducts', {
                        params,
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('admin_token'),
                        }
                    })

                    console.log(response.data)
                    this.products = response.data
                    this.loaded = true
                } catch(err) {
                    console.log(err.response.data)
                }
            },
        },
    }
</script>

<style scoped>

</style>