<template>
    <ApprovalInfo v-if="infoPage" @backToMain="infoPage = false" :product="_product"/>
    <div v-else>
        <h2 class="heading-2 mb-3">Product Approval</h2>
        <div class="ml-4 mt-5 text-md">
            <span :class="{'bg-blue-700 text-white': minitab === 1}" class="border-2 border-gray-700 rounded-2xl border-opacity-75 cursor-pointer font-bold p-2" @click="changeTab(1)">Recent</span>
            <span :class="{'bg-blue-700 text-white': minitab === 2}" class="border-2 border-gray-700 rounded-2xl border-opacity-75 cursor-pointer font-bold p-2 ml-3" @click="changeTab(2)">Search</span>
        </div>
        <div v-if="minitab === 1" class="mt-5 ml-3">
            <p class="capitalize text-xl font-bold text-gray-700">recently approved products</p>
            <select class="capitalize h-9 rounded outline-none hover:border-black hover:border-[1px] focus:border-[1px] focus:border-black mr-3 mt-5 focus:bg-gray-100 bg-white p-2" @change="changeCategory(true)">
                <option value=0 selected>all categories</option>
                <option v-for="category in categories" :value="category.category_id">{{ category.category_name }}</option>
            </select>

            <div class="flex justify-center items-center h-[50vh]" v-if="!loaded">
                <Loader :size="4" :thickness="0.4"/>
            </div>

            <h2 class="text-xl text-center mt-16 font-bold" v-else-if="loaded && products.length === 0">No product was found</h2>

            <Table class="w-11/12 mt-3 mb-5" :fields="fields" v-else-if="loaded && products.length > 0">
                <tbody>
                    <tr v-for="(product, index) in products" @mouseover="displayArrow('arrow', index+1)" @mouseleave="hideArrow('arrow', index+1)" @click="infoPage = true; _product = product">
                        <td>{{ index + 1 }}</td>
                        <td>{{ product.product_id }}</td>
                        <td>{{ product.product_name }}</td>
                        <td>{{ product.category_name }}</td>
                        <!-- class="text-start indent-24" -->
                        <td><img :src="product.store_logo" width="40"  class="rounded-[50%] inline-block mr-3 border-2 aspect-square"/>{{ product.store_name }}</td>
                        <td>{{ product.approved_at }}</td>
                        <td class="hover-on-arrow w-24" title="More details"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow' + (index + 1)"/></td>
                    </tr>
                </tbody>
            </Table>
        </div>
        <div v-else-if="minitab === 2" class="mt-5 ml-3">
            <select class="capitalize h-9 rounded outline-none hover:border-black hover:border-[1px] focus:border-[1px] focus:border-black mr-3 focus:bg-gray-100 p-2 bg-white" @change="changeCategory(searched)">
                <option value=0 selected>all categories</option>
                <option v-for="category in categories" :value="category.category_id">{{ category.category_name }}</option>
            </select>
            <SearchBox :userType="2" @search="getProducts" />
            <small class="text-sm block">Search for products by name or ID</small>

            <p class="text-lg mt-5" v-if="searched">Search result for: "{{query}}"</p>

            <div class="flex justify-center items-center h-[50vh]" v-if="searched && !loaded">
                <Loader :size="4" :thickness="0.4"/>
            </div>

            <div v-if="loaded && products.length === 0" class="flex justify-center items-center h-[50vh]">
                <h2 class="text-xl font-bold">No product was found</h2>
            </div>

            <Table class="w-11/12 mt-3 mb-5" :fields="fields" v-else-if="loaded && products.length > 0">
                <tbody>
                    <tr v-for="(product, index) in products" @mouseover="displayArrow('arrow', index+1)" @mouseleave="hideArrow('arrow', index+1)" @click="infoPage = true; _product = product">
                        <td>{{ index + 1 }}</td>
                        <td>{{ product.product_id }}</td>
                        <td>{{ product.product_name }}</td>
                        <td>{{ product.category_name }}</td>
                        <td>{{ product.store_name }}</td>
                        <td>{{ product.approved_at }}</td>
                        <td class="hover-on-arrow w-24" title="More details"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow' + (index + 1)"/></td>
                    </tr>
                </tbody>
            </Table>
        </div>
    </div>
</template>

<script>
    import SearchBox from './SearchBox.vue';
    import Table from './Table.vue';
    import ApprovalInfo from './ApprovalInfo.vue';
    import Loader from './Loader.vue';
    export default {
        name: "ProductApproval",
        data() {
            return {
                minitab: 1,
                categories: {},
                loaded: false,
                searched: false,
                infoPage: false,
                query: '',
                chosenCategory: 0,
                fields: ['No', 'ID', 'Product Name', 'Category', 'Store Name', 'Approval Time', ' '],
                products: [],
                _product: {}
            }
        },
        components: {
            SearchBox, Table, ApprovalInfo, Loader
        },
        methods: {
            displayArrow(ref, index) {
                this.$refs[ref + index][0].classList.remove('opacity-0')
            },
            hideArrow(ref, index) {
                this.$refs[ref + index][0].classList.add('opacity-0')
            },
            async changeCategory(search) {
                this.chosenCategory = parseInt(event.target.value)
                if (search)
                    await this.getProducts('');
            },
            async changeTab(tabNumber) {
                this.minitab = tabNumber;
                this.chosenCategory = 0;
                this.loaded = false;
                this.products = [];
                this.searched = false;
                this.query = '';

                if (tabNumber === 1) {
                    await this.getProducts('')
                }
            },
            async getCategories() {
                try {
                    const response = await axios.get('/api/getCategories', {
                        headers:{
                            'Authorization': 'Bearer ' + localStorage.getItem('admin_token'),
                        }
                    })
                    this.categories = response.data
                } catch (error) {
                    console.log(error)
                }
            },
            async getProducts(query) {
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
                    const response = await axios.get('/api/getProducts', {
                        params,
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('admin_token'),
                        }
                    })

                    this.products = response.data
                    this.loaded = true
                } catch(err) {
                    console.log(err.response.data)
                }
            }
        },
        async mounted() {
            await this.getCategories()
            await this.getProducts('')
        }
    }
</script>

<style scoped>

</style>