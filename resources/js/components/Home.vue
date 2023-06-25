<template>
    <div class="mb-36">
        <DashBoard/>
        <VerificationSummary @verifytab="$emit('verifytab')" @infopage="reEmitInfoPage"/>
    </div>
</template>

<script>
    import DashBoard from './DashBoard.vue';
    import VerificationSummary from './VerificationSummary.vue';
    export default {
        name: "Home",
        components: {
            DashBoard, VerificationSummary
        },
        emits: ['verifytab', 'infopage'],
        methods: {
            reEmitInfoPage(seller) {
                this.$emit('infopage', seller)
            }
        }
    }
</script>

<style scoped>

</style>