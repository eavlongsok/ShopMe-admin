<template>
    <h2 class="heading-2 mb-3">seller verification</h2>
    <Table class="w-11/12 mt-10" :fields="fields">
        <tbody>
            <tr v-for="(seller, index) in sellers">
                <td>{{ index + 1 }}</td>
                <td>{{ seller.store_name }}</td>
                <td><img src="bingchilling.jpeg" width="40"  class="rounded-full inline-block mr-3 border-2"/>{{ seller.name }}</td>
                <td :ref="'email' + (index + 1)" @click="copyToClipBoard(index+1)">{{ seller.email }}</td>
                <td>{{ seller.submission_date }}</td>
            </tr>
        </tbody>
    </Table>
</template>

<script>
    import Table from './Table.vue';
    export default {
        data() {
            return {
                fields: ['No.', 'Store Name', "Seller's Name", 'Email', 'Submission date'],
                sellers: [
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                    {store_name: 'Shopme', name: 'Eav Long Sok', email: 'esok@paragoniu.edu.kh', submission_date: '19/03/2012'},
                ]
            }
        }, components: {
            Table
        },
        methods: {
            copyToClipBoard(rowID) {
                var emailID = "email" + rowID

                var emailBox = (this.$refs[emailID])[0]
                var email = emailBox.innerText
                navigator.clipboard.writeText(email)
                emailBox.innerText = "Copied!"
                setTimeout(() => {
                    emailBox.innerText = email
                }, 170)
            }
        }
    }
</script>

<style scoped>

</style>