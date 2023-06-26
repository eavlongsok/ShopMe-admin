<template>
    <h2 class="heading-2 mt-10 mb-3 inline-block">Verification</h2>
    <span class="hover:font-bold hover:underline ml-3 text-sm cursor-pointer" @click="$emit('verifytab')">View All</span>

    <div v-if="loading" class="flex justify-center mt-14">
        <Loader :size="4" :thickness="0.4"/>
    </div>
    <Table class="w-11/12" :fields="fields" v-else-if="!loading && sellers.length != 0">
        <tbody>
            <tr v-for="(seller, index) in sellers" @mouseover="displayArrow('arrow', index+1)" @mouseleave="hideArrow('arrow', index+1)" @click="$emit('infopage', seller)">
                <td>{{index+1}}</td>
                <td><img :src="seller.img_url" width="40" class="rounded-[50%] aspect-square inline-block mr-3 border-2"/>{{seller.store_name}}</td>
                <td>{{ seller.first_name }} {{ seller.last_name }}</td>
                <td :ref="'email' + (index + 1)" @click="copyToClipBoard(index+1)">{{seller.email}}</td>
                <td>{{ seller.created_at }}</td>
                <td class="hover-on-arrow w-24" title="See More Details"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow' + (index + 1)"/></td>
            </tr>
        </tbody>
    </Table>
    <h2 class="text-2xl text-center mt-12 capitalize" v-else-if="sellers.length == 0 && !loading">No pending verifications!</h2>
</template>

<script>
    import Table from './Table.vue'
    import Loader from './Loader.vue'
    export default {
        name: 'VerificationSummary',
        data() {
            return {
                loading: true,
                fields: ['No.', 'Store Name', "Seller's Name", 'Email', 'Submission date', ' '],
                sellers: null
            }
        },
        components: {
            Table, Loader
        },
        methods: {
            copyToClipBoard(rowID) {
                var emailID = "email" + rowID
                var emailBox = (this.$refs[emailID])[0]
                var email = this.sellers[rowID-1].email
                navigator.clipboard.writeText(email)
                console.log(emailBox.innerText)
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
            async getPendingVerifications() {
                try {
                    let params = new URLSearchParams();
                    params.append('limit', 20);
                    params.append('page', 1);

                    const response = await axios.get('/api/verification', {params: params, headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('admin_token')
                    }});

                    this.sellers = response.data.data;
                    this.loading = false;
                }

                catch(err) {
                    console.log(err.response.data)
                }
            },
        },
        emits: ['verifytab', 'infopage'],
        async mounted() {
            await this.getPendingVerifications()
        }
    }
</script>

<style scoped>

    tr td:nth-child(4) {
        @apply hover:underline hover:font-bold hover:decoration-cyan-200 hover:text-cyan-200;
    }

    tr {
        @apply leading-loose;
    }
</style>