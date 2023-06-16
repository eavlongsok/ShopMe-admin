<template>
    <div class="inline-block align-middle loader"></div>
</template>

<script>
    export default {
        name: 'Loader',
        props: {
            size: {
                type: Number,
                default: 2.8
            }, thickness: {
                type: Number,
                default: 0.3
            }
        },
        computed: {
            computedSize() {
                return this.size + 'rem';
            },
            computedThickness() {
                return this.thickness + 'rem solid';
            }
        }
    }
</script>

<style scoped>
    .loader {
        /* 2.8 default */
        height: v-bind(computedSize);
        width: v-bind(computedSize);
        /* 0.3 default */
        border: v-bind('computedThickness');
        border-color: rgb(59 130 246) transparent rgb(59 130 246) transparent;
        border-radius: 50%;
        animation: loading 1.2s ease infinite;
    }

    @keyframes loading {
        to {
            transform: rotate(360deg);
        }
    }
</style>