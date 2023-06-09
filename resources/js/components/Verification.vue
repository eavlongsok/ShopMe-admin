<template>
    <VerificationInfo v-if="infoPage === true" :seller="_seller" @backToMain="infoPage = false" @reloadVerification="handleReload"/>
    <div v-else-if="infoPage === false">
        <h2 class="heading-2 mb-3">seller verification</h2>
        <div class="flex justify-center mt-24" v-if="loading == true">
            <Loader :size="5" :thickness="0.4"/>
        </div>
        <template v-if="!loading && sellers.length != 0">
            <Table class="w-11/12 mt-10 mb-5" :fields="fields">
                <tbody>
                    <tr v-for="(seller, index) in sellers" @mouseover="displayArrow('arrow', index+1)" @mouseleave="hideArrow('arrow', index+1)" @click="infoPage = true; _seller=seller;">
                        <td>{{ index + 1 }}</td>
                        <!-- class="text-start indent-24" -->
                        <td><img :src="seller.img_url" width="40" class="rounded-[50%] inline-block mr-3 border-2 aspect-square"/>{{ seller.store_name }}</td>
                        <td>{{ seller.first_name }} {{ seller.last_name }}</td>
                        <td :ref="'email' + (index + 1)" @click="copyToClipBoard(index+1)">{{ seller.email }}</td>
                        <!-- created_at is the date of submission -->
                        <td>{{ seller.created_at }}</td>
                        <td class="hover-on-arrow w-24" title="See More Details"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow' + (index + 1)"/></td>
                    </tr>
                </tbody>
            </Table>
            <Pagination :totalPages = "Math.ceil(total / limit)" :page="page" @changePage="changePage"/>
        </template>
        <h2 class="text-2xl text-center mt-24" v-else-if="sellers.length == 0 && !loading">No pending verification!</h2>
    </div>
</template>

<script>
    import Table from './Table.vue';
    import VerificationInfo from './VerificationInfo.vue';
    import Loader from './Loader.vue';
    import Pagination from './Pagination.vue'
    export default {
        data() {
            return {
                infoPage: false,
                loading: true,
                _seller: null,
                fields: ['No.', 'Store Name', "Seller's Name", 'Email', 'Submission date', ' '],
                sellers: [],
                page: 1,
                limit: 20,
                total: 0,
            }
        }, components: {
            Table,
            VerificationInfo,
            Loader,
            Pagination
        },
        props: ['verifyInfoPage', 'key', 'seller_verification'],
        emits: ['reloadVerification'],

        methods: {
            handleReload() {
                this.$emit('reloadVerification');
                this.infoPage=false
            },
            copyToClipBoard(rowID) {
                var emailID = "email" + rowID
                var emailBox = (this.$refs[emailID])[0]
                var email = this.sellers[rowID-1].email
                navigator.clipboard.writeText(email)
                this.sellers[rowID-1].email = "Copied!"
                setTimeout(() => {
                    this.sellers[rowID-1].email = email
                }, 200)
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

                await this.getPendingVerifications()
            },
            async getPendingVerifications() {
                this.loading = true
                try {
                    let params = new URLSearchParams();
                    params.append('limit', this.limit);
                    params.append('page', this.page);

                    const response = await axios.get('/api/verification', {params: params, headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('admin_token')
                    }});

                    this.sellers = response.data.sellers;
                    this.total = response.data.total;
                }

                catch(err) {
                    console.log(err.response.data)
                }
                this.loading = false
            },
        },
        async mounted() {
            if (this.seller_verification != null) {
                this._seller = this.seller_verification
            }
            if (this.verifyInfoPage) {
                this.infoPage = true
            }
            await this.getPendingVerifications()
        }
    }
</script>

<style scoped>

</style>