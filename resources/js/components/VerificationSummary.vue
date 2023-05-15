<template>
    <h2 class="heading-2 mt-10 mb-3 inline-block">Verification</h2>
    <span class="hover:font-bold hover:underline ml-3 text-sm cursor-pointer" @click="emitTabEvent(2)">View All</span>

    <Table class="w-11/12 shadow-xl" :fields="fields">
        <tbody>
            <tr>
                <td>1</td>
                <td>ShopMe</td>
                <td>Eav Long Sok</td>
                <td ref="email1" @click="copyToClipBoard(1)">esok@paragoniu.edu.kh</td>
                <td>19/03/2012</td>
            </tr>
            <tr>
                <td>2</td>
                <td>ShopMe</td>
                <td>Eav Long Sok</td>
                <td ref="email2" @click="copyToClipBoard(2)">esok@paragoniu.edu.kh</td>
                <td>19/03/2012</td>
            </tr>
            <tr>
                <td>3</td>
                <td>ShopMe</td>
                <td>Eav Long Sok</td>
                <td ref="email3" @click="copyToClipBoard(3)">esok@paragoniu.edu.kh</td>
                <td>19/03/2012</td>
            </tr>
            <tr>
                <td>4</td>
                <td>ShopMe</td>
                <td>Eav Long Sok</td>
                <td ref="email4" @click="copyToClipBoard(4)">esok@paragoniu.edu.kh</td>
                <td>19/03/2012</td>
            </tr>
            <tr>
                <td>5</td>
                <td>ShopMe</td>
                <td>Eav Long Sok</td>
                <td ref="email5" @click="copyToClipBoard(5)">esok@paragoniu.edu.kh</td>
                <td>19/03/2012</td>
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
                fields: ['No.', 'Store Name', "Seller's Name", 'Email', 'Submission date']
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