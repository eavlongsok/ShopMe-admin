<template>
    <VerificationInfo v-if="infoPage === true" :seller="seller" @backToMain="infoPage = false"/>
    <div v-else-if="infoPage === false">
        <h2 class="heading-2 mb-3">seller verification</h2>
        <Table class="w-11/12 mt-10 mb-5" :fields="fields">
            <tbody>
                <tr v-for="(seller, index) in sellers" @mouseover="displayArrow('arrow', index+1)" @mouseleave="hideArrow('arrow', index+1)" @click="infoPage = true">
                    <td>{{ index + 1 }}</td>
                    <td><img src="bingchilling.jpeg" width="40"  class="rounded-full inline-block mr-3 border-2"/>{{ seller.store_name }}</td>
                    <td>{{ seller.name }}</td>
                    <td :ref="'email' + (index + 1)" @click="copyToClipBoard(index+1)">{{ seller.email }}</td>
                    <td>{{ seller.submission_date }}</td>
                    <td class="hover-on-arrow w-24" title="See More Details"><img src="forward-arrow.png" width="16" class="inline-block opacity-0" :ref="'arrow' + (index + 1)"/></td>
                </tr>
            </tbody>
        </Table>
    </div>
</template>

<script>
    import Table from './Table.vue';
    import VerificationInfo from './VerificationInfo.vue';
    export default {
        data() {
            return {
                infoPage: false,
                seller: {
                    ID: 1,
                    store_name: 'Shopme',
                    name: 'Eav Long Sok',
                    id_card: 'some id card info',
                    email: 'esok@paragoniu.edu.kh',
                    address: 'some address',
                    submission_date: '19/03/2012',
                    business_info: 'some business info'
                },
                fields: ['No.', 'Store Name', "Seller's Name", 'Email', 'Submission date', ' '],
                sellers: [
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                ]
            }
        }, components: {
            Table,
            VerificationInfo
        },
        props: ['verifyInfoPage'],
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
            }
        },
        mounted() {
            if (this.verifyInfoPage) {
                this.infoPage = true
            }
        }
    }
</script>

<style scoped>

</style>