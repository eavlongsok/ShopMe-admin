<template>
    <DashBoard/>
    <VerificationSummary @verifytab="emitTabEvent" @infopage="$emit('infopage')"/>
</template>

<script>
    import DashBoard from './DashBoard.vue';
    import VerificationSummary from './VerificationSummary.vue';
    export default {
        name: "Home",
        components: {
            DashBoard, VerificationSummary
        },
        methods: {
            emitTabEvent(tabID) {
                this.$emit('verifytab', tabID)
            }
        },
        emits: ['verifytab']
    }
</script>

<style scoped>

</style>