<template>
    <table class="table-auto ml-3 text-lg">
        <thead class="capitalize">
            <tr>
                <td v-for="item in fields">{{ item }}</td>
            </tr>
        </thead>
        <slot></slot>
    </table>
</template>

<script>
    export default {
        name: 'Table',
        props: ['fields']
    }
</script>

<style scoped>
    :slotted(tr), tr {
        border: 1px solid black;
        @apply border-l-0 border-r-0 text-center;
    }

    :slotted(td), td {
        @apply py-2;
    }

    :slotted(tr) {
        @apply cursor-pointer hover:font-bold;
    }

    :slotted(tr:nth-child(even)) {
        @apply bg-cyan-700 text-white;
    }

    :slotted(tr:nth-child(odd)) {
        @apply bg-cyan-600 text-gray-100;
    }

    thead > tr:first-child {
        @apply bg-cyan-900 text-white;
    }
</style>