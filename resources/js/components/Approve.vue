<template>
    <ApproveProductPage v-if="infoPage" :product="_product" @backToMain="infoPage = false" @toggleApprove="toggleApprove"/>
    <div v-else class="mb-10">
        <h2 class="heading-2 mb-3">Approve Products</h2>
        <SearchBox :userType="0" @search="getListingRequests"/>
        <small class="ml-3 text-sm block">Search for products by ID</small>

        <div class="flex justify-center items-center h-[60vh]" v-if="loading">
            <Loader :size="4" :thickness="0.4"/>
        </div>
        <template v-else-if="!loading && products.length != 0 && !loading">
            <Table :fields="fields" class="w-11/12 mt-3 mb-5">
                <tbody>
                    <tr v-for="(product, index) in products" @mouseover="displayArrow('arrow', index)" @mouseleave="hideArrow('arrow', index)" @click="infoPage = true; _product=product">
                        <td>{{ product.product_id }}</td>
                        <td class="text-start indent-10">{{ product.product_name }}</td>
                        <td>{{ product.category_name }}</td>
                        <td>{{ product.store_name }}</td>
                        <td>{{ product.quantity }}</td>
                        <td>{{ product.is_approved ? 'Yes' : 'No' }}</td>
                        <td class="hover-on-arrow w-24" title="More details"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="('arrow' + index)"/></td>
                    </tr>
                </tbody>
            </Table>
            <Pagination :totalPages = "Math.ceil(total / limit)" :page="page" @changePage="changePage"/>
        </template>

        <h2 class="text-xl text-center mt-36" v-else-if="products.length === 0 && searched">No product was found!</h2>
    </div>
</template>

<script>
    import ApproveProductPage from './ApproveProductPage.vue';
    import SearchBox from './SearchBox.vue';
    import Table from './Table.vue';
    import Loader from './Loader.vue'
    import Pagination from './Pagination.vue'

    export default {
        name: 'Approve',
        data() {
            return {
                infoPage: false,
                fields: ['ID', 'Product Name', 'Category', 'Store Name', 'Quantity', 'Approved', ' '],
                loading: false,
                products: [],
                searched: false,
                _product: {},
                page: 1,
                limit: 20,
                total: 0,
            }
        },
        components: { SearchBox, Table, ApproveProductPage, Loader, Pagination },
        methods: {
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
            toggleApprove(productID) {
                this.products.forEach(product => {
                    if (product.product_id == productID) {
                        product.is_approved = !product.is_approved
                    }
                })
            },
            async changePage(pageNumber) {
                if (pageNumber === '...') return
                if (pageNumber === '+') {
                    if (this.page == this.total) return
                    this.page = this.page + 1
                }
                else if (pageNumber === '-') {
                    if (this.page == 1) return
                    this.page = this.page - 1
                }
                else this.page = parseInt(pageNumber)

                await this.getListingRequests('')
            },
            async getListingRequests(query) {
                this.searched = true
                this.loading = true
                this.products = []
                let params = new URLSearchParams()
                params.append('page', this.page)
                params.append('limit', 15)
                params.append('q', query)

                try {
                    const response = await axios.get('/api/listingRequests/', {
                        params,
                        headers: {
                            "Authorization": "Bearer " + localStorage.getItem('admin_token')
                        }
                    })
                    console.log(response.data)
                    this.products = response.data.products
                    this.total = response.data.total
                } catch(error) {
                    console.log(error.response)
                }
                this.loading = false
            }
        },
    }
</script>

<style scoped>

</style>