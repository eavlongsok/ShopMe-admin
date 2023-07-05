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

            <template v-else-if="loaded && products.length > 0">
                <Table class="w-11/12 mt-3 mb-5" :fields="fields">
                    <tbody>
                        <tr v-for="(product, index) in products" @mouseover="displayArrow('arrow', index+1)" @mouseleave="hideArrow('arrow', index+1)" @click="infoPage = true; _product = product">
                            <td>{{ product.product_id }}</td>
                            <td class="text-start indent-10"><img :src="product.product_img" width="40" class="rounded-[50%] inline-block mr-3 border-2 aspect-square"/>{{ product.product_name }}</td>
                            <td>{{ product.category_name }}</td>
                            <td>{{ product.store_name }}</td>
                            <td>{{ product.approved_at }}</td>
                            <td class="hover-on-arrow w-24" title="More details"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow' + (index + 1)"/></td>
                        </tr>
                    </tbody>
                </Table>
                <Pagination :totalPages = "Math.ceil(total / limit)" :page="page" @changePage="changePage" class="mb-10"/>
            </template>
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

            <template v-else-if="loaded && products.length > 0">
                <Table class="w-11/12 mt-3 mb-5" :fields="fields">
                    <tbody>
                        <tr v-for="(product, index) in products" @mouseover="displayArrow('arrow', index+1)" @mouseleave="hideArrow('arrow', index+1)" @click="infoPage = true; _product = product">
                            <td>{{ product.product_id }}</td>
                            <!-- <td>{{ product.product_name }}</td> -->
                            <td class="text-start indent-10"><img :src="product.product_img" width="40" class="rounded-[50%] inline-block mr-3 border-2 aspect-square"/>{{ product.product_name }}</td>
                            <td>{{ product.category_name }}</td>
                            <td>{{ product.store_name }}</td>
                            <td>{{ product.approved_at }}</td>
                            <td class="hover-on-arrow w-24" title="More details"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow' + (index + 1)"/></td>
                        </tr>
                    </tbody>
                </Table>
                <Pagination :totalPages = "Math.ceil(total / limit)" :page="page" @changePage="changePage"/>
            </template>
        </div>
    </div>
</template>

<script>
    import SearchBox from './SearchBox.vue';
    import Table from './Table.vue';
    import ApprovalInfo from './ApprovalInfo.vue';
    import Loader from './Loader.vue';
    import Pagination from './Pagination.vue';
    export default {
        name: "ProductApproval",
        data() {
            return {
                minitab: 1,
                loaded: false,
                searched: false,
                infoPage: false,
                query: '',
                chosenCategory: 0,
                fields: ['ID', 'Product Name', 'Category', 'Store Name', 'Approval Time', ' '],
                products: [],
                _product: {},
                page: 1,
                limit: 20,
                total: 0,
            }
        },
        components: {
            SearchBox, Table, ApprovalInfo, Loader, Pagination
        },
        props: ['categories'],
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
                    await this.getProducts(this.query);
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

                await this.getProducts('')
            },
            async getCategories() {
                try {
                    const response = await axios.get('/api/getCategories', {
                        headers:{
                            'Authorization': 'Bearer ' + localStorage.getItem('admin_token'),
                        }
                    })
                    this.categories = response.data.categories
                } catch (error) {
                    console.log(error.response)
                }
            },
            async getProducts(query) {
                this.query = query
                this.products = []
                this.loaded = false
                this.searched = true
                let params = new URLSearchParams();
                params.append('q', this.query)
                params.append('category_id', this.chosenCategory)
                params.append('page', this.page)
                params.append('limit', this.limit)

                try {
                    const response = await axios.get('/api/getProducts', {
                        params,
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('admin_token'),
                        }
                    })

                    console.log(response.data)
                    this.products = response.data.products
                    this.total = response.data.total
                    this.loaded = true
                } catch(err) {
                    console.log(err.response.data)
                }
            }
        },
        async mounted() {
            await this.getProducts('')
        }
    }
</script>

<style scoped>

</style>