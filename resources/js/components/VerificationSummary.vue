<template>
    <h2 class="heading-2 mt-10 mb-3 inline-block">Verification</h2>
    <span class="hover:font-bold hover:underline ml-3 text-sm cursor-pointer" @click="emitTabEvent(2)">View All</span>

    <Table class="w-11/12 shadow-xl" :fields="fields">
        <tbody>
            <tr v-for="(seller, index) in sellers" @mouseover="displayArrow('arrow', index+1)" @mouseleave="hideArrow('arrow', index+1)">
                <td>1</td>
                <td>{{seller.store_name}}</td>
                <td><img src="bingchilling.jpeg" width="40"  class="rounded-full inline-block mr-3 border-2"/>{{ seller.seller_name }}</td>
                <td ref="email1" @click="copyToClipBoard(1)">{{seller.email}}</td>
                <td>{{ seller.submission_date }}</td>
                <td class="hover-on-arrow w-24" title="See More Details"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow' + (index + 1)"/></td>
            </tr>
        </tbody>
    </Table>
</template>

<script>
    import Table from './Table.vue'
    export default {
        name: 'VerificationSummary',
        data() {
            return {
                fields: ['No.', 'Store Name', "Seller's Name", 'Email', 'Submission date', ' '],
                sellers: [
                    {store_name: 'ShopMe', seller_name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/1012'},
                    {store_name: 'ShopMe', seller_name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/1012'},
                    {store_name: 'ShopMe', seller_name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/1012'},
                    {store_name: 'ShopMe', seller_name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/1012'},
                    {store_name: 'ShopMe', seller_name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/1012'},
                    {store_name: 'ShopMe', seller_name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/1012'},
                    {store_name: 'ShopMe', seller_name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/1012'},
                    {store_name: 'ShopMe', seller_name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/1012'},
                    {store_name: 'ShopMe', seller_name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/1012'},
                ]
            }
        },
        components: {
            Table
        },
        methods: {
            emitTabEvent(tabID) {
                this.$emit("verifytab", tabID)
            },
            copyToClipBoard(rowID) {
                var emailID = "email" + rowID
                var emailBox = this.$refs[emailID]
                var email = emailBox.innerText
                navigator.clipboard.writeText(email)
                emailBox.innerText = "Copied!"
                setTimeout(() => {
                    emailBox.innerText = email
                }, 170)
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
            }
        },
        emits: ['verifytab']
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