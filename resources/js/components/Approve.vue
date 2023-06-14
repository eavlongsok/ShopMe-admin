<template>
    <ApproveProductPage v-if="infoPage" :product="productClicked" @backToMain="infoPage = false"/>
    <div v-else>
        <h2 class="heading-2 mb-3">Approve Products</h2>
        <SearchBox action="/" @search="searched = true" class="inline-block"/>
        <small class="ml-3 text-sm block">Search for products by ID</small>

        <Table :fields="fields" class="w-11/12 mt-3 mb-5" v-if="searched">
            <tbody>
                <!-- {{ products }} -->
                <tr v-for="product in products" @mouseover="displayArrow()" @mouseleave="hideArrow()" @click="infoPage = true; productClicked=product">
                    <td>{{ product.product_id }}</td>
                    <td>{{ product.product_name }}</td>
                    <td>{{ product.category_name }}</td>
                    <td>{{ product.first_name }} {{ product.last_name }}</td>
                    <td>{{ product.quantity }}</td>
                    <td class="hover-on-arrow w-24" title="More details"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" ref="arrow"/></td>
                </tr>
            </tbody>
        </Table>
    </div>
</template>

<script>
    import ApproveProductPage from './ApproveProductPage.vue';
    import SearchBox from './SearchBox.vue';
    import Table from './Table.vue';

    export default {
        name: 'Approve',
        data() {
            return {
                infoPage: false,
                fields: ['ID', 'Product Name', 'Category', 'Store Name', 'Quantity', ' '],
                searched: false,
                products: [],
                productClicked: null
            }
        },
        components: { SearchBox, Table, ApproveProductPage },
        methods: {
            displayArrow() {
                this.$refs.arrow.classList.remove('opacity-0')
            },
            hideArrow() {
                this.$refs.arrow.classList.add('opacity-0')
            }
        },
        async mounted() {
            const response = await axios.get('/api/listingRequests/?offset=0&limit=10', {
                headers: {
                    "Authorization": "Bearer " + localStorage.getItem('admin_token')
                }
            })
            this.products = response.data
        }
    }
</script>

<style scoped>

</style>